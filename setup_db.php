<?php
// =============================================
// DJAKA COFFEE — Database Setup Script
// Jalankan sekali: http://localhost/djaka-coffee/setup_db.php
// =============================================

$conn = new mysqli('localhost', 'root', '', '');
if ($conn->connect_error) die('Koneksi gagal: '.$conn->connect_error);

// Buat database
$conn->query("CREATE DATABASE IF NOT EXISTS djaka_coffee CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db('djaka_coffee');

// ── TABEL USERS ──
$conn->query("CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  name VARCHAR(100),
  role ENUM('customer','kasir','admin') DEFAULT 'customer',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");

// Insert akun kasir & admin
$conn->query("INSERT IGNORE INTO users (email, name, role) VALUES
  ('kasir@djaka.coffee','Kasir','kasir'),
  ('admin@djaka.coffee','Admin','admin')");

// ── TABEL MENU ──
$conn->query("CREATE TABLE IF NOT EXISTS menus (
  id VARCHAR(20) PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  category ENUM('makanan','minuman','dessert','mixplater','special') NOT NULL,
  price_s INT DEFAULT 0,
  price_m INT DEFAULT 0,
  price_l INT DEFAULT 0,
  price_single INT DEFAULT 0,
  emoji VARCHAR(10) DEFAULT '🍽️',
  active TINYINT(1) DEFAULT 1
) ENGINE=InnoDB");

// Seed menu data
$menus = [
  ['food1','Nasi Goreng Djaka','makanan',28000,35000,45000,0,'🍛'],
  ['food2','Roti Bakar Spesial','makanan',18000,22000,28000,0,'🍞'],
  ['food3','Sandwich Keju','makanan',22000,28000,35000,0,'🥪'],
  ['food4','Kentang Goreng','makanan',15000,20000,27000,0,'🍟'],
  ['food5','Pasta Krim Djaka','makanan',32000,40000,52000,0,'🍝'],
  ['drink1','Espresso Tubruk','minuman',18000,22000,28000,0,'☕'],
  ['drink2','Latte Susu Segar','minuman',22000,28000,34000,0,'🥛'],
  ['drink3','Cold Brew Djaka','minuman',25000,32000,40000,0,'🧊'],
  ['drink4','Matcha Latte','minuman',24000,30000,38000,0,'🍵'],
  ['drink5','Cokelat Panas','minuman',20000,25000,32000,0,'🍫'],
  ['dessert1','Tiramisu Djaka','dessert',28000,35000,44000,0,'🍮'],
  ['dessert2','Cheesecake Kopi','dessert',30000,38000,48000,0,'🍰'],
  ['dessert3','Brownies Coklat','dessert',20000,26000,33000,0,'🍫'],
  ['dessert4','Pudding Karamel','dessert',18000,23000,30000,0,'🍮'],
  ['dessert5','Affogato','dessert',25000,32000,40000,0,'🍨'],
  ['mix1','Plater Sore','mixplater',45000,58000,72000,0,'🍱'],
  ['mix2','Paket Hemat Duo','mixplater',55000,68000,85000,0,'🎁'],
  ['mix3','Family Plater','mixplater',75000,92000,115000,0,'🍽️'],
  ['mix4','Snack Box Premium','mixplater',50000,63000,80000,0,'📦'],
  ['mix5','Combo Djaka','mixplater',60000,75000,95000,0,'🥡'],
  ['special1','Paket Spesial Chef','special',0,0,0,120000,'⭐'],
];
foreach ($menus as $m) {
  $stmt = $conn->prepare("INSERT IGNORE INTO menus (id,name,category,price_s,price_m,price_l,price_single,emoji) VALUES(?,?,?,?,?,?,?,?)");
  $stmt->bind_param('sssiiiis',$m[0],$m[1],$m[2],$m[3],$m[4],$m[5],$m[6],$m[7]);
  $stmt->execute();
}

// ── TABEL STOCK ──
$conn->query("CREATE TABLE IF NOT EXISTS stock (
  id INT AUTO_INCREMENT PRIMARY KEY,
  menu_id VARCHAR(20) NOT NULL,
  size ENUM('S','M','L','single') NOT NULL,
  quantity INT DEFAULT 20,
  UNIQUE KEY uk_menu_size (menu_id, size),
  FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
) ENGINE=InnoDB");

// Seed stock
foreach ($menus as $m) {
  if ($m[2]==='special') {
    $conn->query("INSERT IGNORE INTO stock (menu_id,size,quantity) VALUES ('{$m[0]}','single',20)");
  } else {
    foreach (['S','M','L'] as $sz) {
      $conn->query("INSERT IGNORE INTO stock (menu_id,size,quantity) VALUES ('{$m[0]}','$sz',20)");
    }
  }
}

// ── TABEL BOOKINGS (meja) ──
$conn->query("CREATE TABLE IF NOT EXISTS bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  table_no INT NOT NULL,
  user_name VARCHAR(100),
  order_num INT,
  booked_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");

// ── TABEL ORDERS ──
$conn->query("CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_num INT NOT NULL UNIQUE,
  user_name VARCHAR(100),
  total INT DEFAULT 0,
  pay_method ENUM('cash','qris') DEFAULT 'cash',
  table_no INT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");

// ── TABEL ORDER ITEMS ──
$conn->query("CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_num INT NOT NULL,
  menu_id VARCHAR(20),
  menu_name VARCHAR(100),
  size VARCHAR(10),
  price INT,
  quantity INT,
  FOREIGN KEY (order_num) REFERENCES orders(order_num) ON DELETE CASCADE
) ENGINE=InnoDB");

echo json_encode(['status'=>'ok','message'=>'Database Djaka Coffee berhasil dibuat! Tabel: users, menus, stock, bookings, orders, order_items']);
$conn->close();
?>
