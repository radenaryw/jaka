<?php
// =============================================
// DJAKA COFFEE — REST API
// Endpoint: api.php?action=xxx
// =============================================
require_once 'db_connect.php';

$action = $_GET['action'] ?? '';
$input  = json_decode(file_get_contents('php://input'), true) ?? [];

switch ($action) {

  // ── AUTH ──
  case 'login_customer':
    $email = $input['email'] ?? '';
    $db = getDB();
    // Create user if not exists
    $stmt = $db->prepare("INSERT INTO users (email, name, role) VALUES (?, ?, 'customer') ON DUPLICATE KEY UPDATE email=email");
    $name = explode('@', $email)[0];
    $stmt->bind_param('ss', $email, $name);
    $stmt->execute();
    $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    echo json_encode(['status'=>'ok','user'=>$user]);
    break;

  // ── MENU ──
  case 'get_menus':
    $db = getDB();
    $result = $db->query("
      SELECT m.*, 
        SUM(CASE WHEN s.size='S' THEN s.quantity ELSE 0 END) as stock_s,
        SUM(CASE WHEN s.size='M' THEN s.quantity ELSE 0 END) as stock_m,
        SUM(CASE WHEN s.size='L' THEN s.quantity ELSE 0 END) as stock_l,
        SUM(CASE WHEN s.size='single' THEN s.quantity ELSE 0 END) as stock_single
      FROM menus m
      LEFT JOIN stock s ON m.id=s.menu_id
      WHERE m.active=1
      GROUP BY m.id
      ORDER BY m.category, m.id
    ");
    $menus = [];
    while ($row = $result->fetch_assoc()) $menus[] = $row;
    echo json_encode(['status'=>'ok','menus'=>$menus]);
    break;

  // ── STOCK ──
  case 'get_stock':
    $db = getDB();
    $result = $db->query("SELECT menu_id, size, quantity FROM stock");
    $stock = [];
    while ($row = $result->fetch_assoc()) {
      $stock[$row['menu_id']][$row['size']] = (int)$row['quantity'];
    }
    echo json_encode(['status'=>'ok','stock'=>$stock]);
    break;

  case 'update_stock':
    $db = getDB();
    $menuId = $input['menu_id'] ?? '';
    $size   = $input['size'] ?? '';
    $delta  = (int)($input['delta'] ?? 0);
    $stmt = $db->prepare("UPDATE stock SET quantity = GREATEST(0, quantity + ?) WHERE menu_id=? AND size=?");
    $stmt->bind_param('iss', $delta, $menuId, $size);
    $stmt->execute();
    // Return new qty
    $stmt2 = $db->prepare("SELECT quantity FROM stock WHERE menu_id=? AND size=?");
    $stmt2->bind_param('ss', $menuId, $size);
    $stmt2->execute();
    $row = $stmt2->get_result()->fetch_assoc();
    echo json_encode(['status'=>'ok','quantity'=>(int)$row['quantity']]);
    break;

  case 'restock':
    $db = getDB();
    $menuId = $input['menu_id'] ?? '';
    $amount = (int)($input['amount'] ?? 10);
    $stmt = $db->prepare("UPDATE stock SET quantity = quantity + ? WHERE menu_id=?");
    $stmt->bind_param('is', $amount, $menuId);
    $stmt->execute();
    echo json_encode(['status'=>'ok']);
    break;

  // ── ORDERS ──
  case 'create_order':
    $db = getDB();
    $user    = $input['user'] ?? '';
    $items   = $input['items'] ?? [];
    $total   = (int)($input['total'] ?? 0);
    $payMethod = $input['pay_method'] ?? 'cash';
    $tableNo = $input['table_no'] ?? null;

    // Generate unique 3-digit order num
    do {
      $orderNum = rand(100,999);
      $r = $db->query("SELECT id FROM orders WHERE order_num=$orderNum");
    } while ($r->num_rows > 0);

    // Insert order
    $stmt = $db->prepare("INSERT INTO orders (order_num,user_name,total,pay_method,table_no) VALUES(?,?,?,?,?)");
    $stmt->bind_param('isiss', $orderNum, $user, $total, $payMethod, $tableNo);
    $stmt->execute();

    // Insert items & reduce stock
    foreach ($items as $item) {
      $stmt2 = $db->prepare("INSERT INTO order_items (order_num,menu_id,menu_name,size,price,quantity) VALUES(?,?,?,?,?,?)");
      $stmt2->bind_param('isssii', $orderNum, $item['id'], $item['name'], $item['size'], $item['price'], $item['qty']);
      $stmt2->execute();

      // Reduce stock
      $stmt3 = $db->prepare("UPDATE stock SET quantity=GREATEST(0,quantity-?) WHERE menu_id=? AND size=?");
      $stmt3->bind_param('iss', $item['qty'], $item['id'], $item['size']);
      $stmt3->execute();
    }

    // Book table if provided
    if ($tableNo) {
      $stmt4 = $db->prepare("INSERT INTO bookings (table_no,user_name,order_num) VALUES(?,?,?)");
      $stmt4->bind_param('isi', $tableNo, $user, $orderNum);
      $stmt4->execute();
    }

    echo json_encode(['status'=>'ok','order_num'=>$orderNum]);
    break;

  case 'get_orders':
    $db = getDB();
    $user = $_GET['user'] ?? '';
    if ($user) {
      $stmt = $db->prepare("SELECT o.*, GROUP_CONCAT(oi.menu_name,'(',oi.size,')x',oi.quantity SEPARATOR ', ') as item_summary
        FROM orders o LEFT JOIN order_items oi ON o.order_num=oi.order_num
        WHERE o.user_name=? GROUP BY o.order_num ORDER BY o.created_at DESC");
      $stmt->bind_param('s', $user);
      $stmt->execute();
      $result = $stmt->get_result();
    } else {
      $result = $db->query("SELECT o.*, GROUP_CONCAT(oi.menu_name,'(',oi.size,')x',oi.quantity SEPARATOR ', ') as item_summary
        FROM orders o LEFT JOIN order_items oi ON o.order_num=oi.order_num
        GROUP BY o.order_num ORDER BY o.created_at DESC");
    }
    $orders = [];
    while ($row=$result->fetch_assoc()) $orders[]=$row;
    echo json_encode(['status'=>'ok','orders'=>$orders]);
    break;

  case 'get_order_detail':
    $db = getDB();
    $num = (int)($_GET['order_num'] ?? 0);
    $stmt = $db->prepare("SELECT * FROM order_items WHERE order_num=?");
    $stmt->bind_param('i', $num);
    $stmt->execute();
    $items = [];
    while ($row=$stmt->get_result()->fetch_assoc()) $items[]=$row;
    echo json_encode(['status'=>'ok','items'=>$items]);
    break;

  case 'delete_order':
    $db = getDB();
    $num = (int)($input['order_num'] ?? 0);
    $stmt = $db->prepare("DELETE FROM orders WHERE order_num=?");
    $stmt->bind_param('i', $num);
    $stmt->execute();
    echo json_encode(['status'=>'ok']);
    break;

  // ── BOOKINGS ──
  case 'get_bookings':
    $db = getDB();
    $result = $db->query("SELECT * FROM bookings ORDER BY booked_at DESC");
    $bookings = [];
    while ($row=$result->fetch_assoc()) $bookings[]=$row;
    echo json_encode(['status'=>'ok','bookings'=>$bookings]);
    break;

  case 'free_table':
    $db = getDB();
    $tableNo = (int)($input['table_no'] ?? 0);
    $stmt = $db->prepare("DELETE FROM bookings WHERE table_no=?");
    $stmt->bind_param('i', $tableNo);
    $stmt->execute();
    echo json_encode(['status'=>'ok']);
    break;

  case 'clear_bookings':
    $db = getDB();
    $db->query("TRUNCATE TABLE bookings");
    echo json_encode(['status'=>'ok']);
    break;

  // ── MENU PRICE UPDATE (admin) ──
  case 'update_menu_price':
    $db = getDB();
    $id = $input['id'] ?? '';
    $ps = (int)($input['price_s'] ?? 0);
    $pm = (int)($input['price_m'] ?? 0);
    $pl = (int)($input['price_l'] ?? 0);
    $psg = (int)($input['price_single'] ?? 0);
    $stmt = $db->prepare("UPDATE menus SET price_s=?,price_m=?,price_l=?,price_single=? WHERE id=?");
    $stmt->bind_param('iiiis', $ps, $pm, $pl, $psg, $id);
    $stmt->execute();
    echo json_encode(['status'=>'ok']);
    break;

  // ── STATS (admin dashboard) ──
  case 'get_stats':
    $db = getDB();
    $totalRev  = $db->query("SELECT COALESCE(SUM(total),0) as t FROM orders")->fetch_assoc()['t'];
    $totalOrd  = $db->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'];
    $totalItems= $db->query("SELECT COALESCE(SUM(quantity),0) as c FROM order_items")->fetch_assoc()['c'];
    $cashOrd   = $db->query("SELECT COUNT(*) as c FROM orders WHERE pay_method='cash'")->fetch_assoc()['c'];
    $qrisOrd   = $db->query("SELECT COUNT(*) as c FROM orders WHERE pay_method='qris'")->fetch_assoc()['c'];

    $topRes = $db->query("SELECT menu_name, SUM(quantity) as sold FROM order_items GROUP BY menu_name ORDER BY sold DESC LIMIT 6");
    $top = [];
    while ($r=$topRes->fetch_assoc()) $top[]=$r;

    echo json_encode([
      'status'=>'ok',
      'total_revenue'=>(int)$totalRev,
      'total_orders'=>(int)$totalOrd,
      'total_items'=>(int)$totalItems,
      'cash_orders'=>(int)$cashOrd,
      'qris_orders'=>(int)$qrisOrd,
      'top_menus'=>$top
    ]);
    break;

  default:
    http_response_code(404);
    echo json_encode(['error'=>'Action not found: '.$action]);
}
?>
