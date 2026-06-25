/* =============================================
   DJAKA COFFEE - Shared Script
   ============================================= */

/* ── TOAST ── */
function showToast(msg, type='info') {
  let c = document.getElementById('toast-container');
  if (!c) { c = document.createElement('div'); c.id='toast-container'; document.body.appendChild(c); }
  const t = document.createElement('div');
  t.className = 'toast';
  const icons = { info:'ℹ️', success:'✅', error:'❌', warning:'⚠️' };
  t.innerHTML = `<span>${icons[type]||icons.info}</span><span>${msg}</span>`;
  c.appendChild(t);
  setTimeout(()=>t.remove(), 3200);
}

/* ── SESSION ── */
const Session = {
  set(k,v){ localStorage.setItem('djaka_'+k, JSON.stringify(v)); },
  get(k){ try{ return JSON.parse(localStorage.getItem('djaka_'+k)); }catch{ return null; } },
  clear(k){ localStorage.removeItem('djaka_'+k); },
  clearAll(){ Object.keys(localStorage).filter(k=>k.startsWith('djaka_')).forEach(k=>localStorage.removeItem(k)); }
};

/* ── CART ── */
const Cart = {
  _key: 'cart',
  items() { return Session.get(this._key)||[]; },
  save(items){ Session.set(this._key, items); },
  add(item) {
    const items = this.items();
    const idx = items.findIndex(i=>i.id===item.id && i.size===item.size);
    if (idx>-1) { items[idx].qty += item.qty; }
    else { items.push({...item}); }
    this.save(items);
  },
  update(id, size, qty) {
    let items = this.items();
    const idx = items.findIndex(i=>i.id===id && i.size===size);
    if (idx>-1) {
      if (qty<=0) items.splice(idx,1);
      else items[idx].qty = qty;
    }
    this.save(items);
  },
  remove(id, size) {
    this.save(this.items().filter(i=>!(i.id===id && i.size===size)));
  },
  clear() { this.save([]); },
  total() { return this.items().reduce((s,i)=>s+i.price*i.qty, 0); },
  count() { return this.items().reduce((s,i)=>s+i.qty, 0); }
};

/* ── ORDERS ── */
const Orders = {
  _key: 'orders',
  all() { return Session.get(this._key)||[]; },
  save(o){ Session.set(this._key, o); },
  add(order) {
    const all = this.all();
    order.createdAt = order.createdAt || new Date().toISOString();
    order.status = order.status || "Pesanan Masuk";
    all.push(order);
    this.save(all);
  },
  genOrderNum() {
    const used = this.all().map(o=>o.orderNum);
    let n;
    do { n = Math.floor(100+Math.random()*900); } while(used.includes(n));
    return n;
  }
};

/* ── STOCK ── */
const Stock = {
  _key: 'stock',
  _default() {
    const sizes = ['S','M','L'];
    const menus = [
      // makanan
      {id:'food1',name:'Nasi Goreng Djaka',cat:'makanan',image:'assets/menu/nasgor.jpg',prices:{S:28000,M:35000,L:45000}},
      {id:'food2',name:'Roti Bakar Spesial',cat:'makanan',image:'assets/menu/food2.jpg',prices:{S:18000,M:22000,L:28000}},
      {id:'food3',name:'Sandwich Keju',cat:'makanan',image:'assets/menu/food3.jpg',prices:{S:22000,M:28000,L:35000}},
      {id:'food4',name:'Kentang Goreng',cat:'makanan',image:'assets/menu/food4.jpg',prices:{S:15000,M:20000,L:27000}},
      {id:'food5',name:'Pasta Krim Djaka',cat:'makanan',image:'assets/menu/food5.jpg',prices:{S:32000,M:40000,L:52000}},
      // minuman
      {id:'drink1',name:'Espresso Tubruk',cat:'minuman',image:'assets/menu/kopi es.jpg',prices:{S:18000,M:22000,L:28000}},
      {id:'drink2',name:'Latte Susu Segar',cat:'minuman',image:'assets/menu/drink2.jpg',prices:{S:22000,M:28000,L:34000}},
      {id:'drink3',name:'Cold Brew Djaka',cat:'minuman',image:'assets/menu/drink3.jpg',prices:{S:25000,M:32000,L:40000}},
      {id:'drink4',name:'Matcha Latte',cat:'minuman',image:'assets/menu/drink4.jpg',prices:{S:24000,M:30000,L:38000}},
      {id:'drink5',name:'Cokelat Panas',cat:'minuman',image:'assets/menu/drink5.jpg',prices:{S:20000,M:25000,L:32000}},
      // dessert
      {id:'dessert1',name:'Tiramisu Djaka',cat:'dessert',image:'assets/menu/nasgor.jpg.jpg',prices:{S:28000,M:35000,L:44000}},
      {id:'dessert2',name:'Cheesecake Kopi',cat:'dessert',image:'assets/menu/dessert2.jpg',prices:{S:30000,M:38000,L:48000}},
      {id:'dessert3',name:'Brownies Coklat',cat:'dessert',image:'assets/menu/dessert3.jpg',prices:{S:20000,M:26000,L:33000}},
      {id:'dessert4',name:'Pudding Karamel',cat:'dessert',image:'assets/menu/dessert4.jpg',prices:{S:18000,M:23000,L:30000}},
      {id:'dessert5',name:'Affogato',cat:'dessert',image:'assets/menu/dessert5.jpg',prices:{S:25000,M:32000,L:40000}},
      // mix plater
      {id:'mix1',name:'Plater Sore',cat:'mixplater',image:'assets/menu/mix1.jpg',prices:{S:45000,M:58000,L:72000}},
      {id:'mix2',name:'Paket Hemat Duo',cat:'mixplater',image:'assets/menu/mix2.jpg',prices:{S:55000,M:68000,L:85000}},
      {id:'mix3',name:'Family Plater',cat:'mixplater',image:'assets/menu/mix3.jpg',prices:{S:75000,M:92000,L:115000}},
      {id:'mix4',name:'Snack Box Premium',cat:'mixplater',image:'assets/menu/mix4.jpg',prices:{S:50000,M:63000,L:80000}},
      {id:'mix5',name:'Snack Djaka',cat:'mixplater',image:'assets/menu/mix5.jpg',prices:{S:60000,M:75000,L:95000}},
      // special (no size)
      {id:'special1',name:'Pizza',cat:'special',image:'assets/menu/special1.jpg',prices:{single:120000}},
    ];
    const st = {};
    menus.forEach(m=>{
      st[m.id] = {};
      if (m.cat==='special') { st[m.id]['single']=20; }
      else sizes.forEach(s=>{ st[m.id][s]=20; });
    });
    return { menus, stock: st };
  },
  get() {
    const saved = Session.get(this._key);
    if (!saved) { const d=this._default(); Session.set(this._key,d); return d; }
    return saved;
  },
  save(d){ Session.set(this._key,d); },
  reduce(id,size,qty=1) {
    const d=this.get();
    if (d.stock[id] && d.stock[id][size]!==undefined) {
      d.stock[id][size] = Math.max(0, d.stock[id][size]-qty);
      this.save(d);
    }
  },
  increase(id,size,qty=1) {
    const d=this.get();
    if (d.stock[id] && d.stock[id][size]!==undefined) {
      d.stock[id][size] = d.stock[id][size]+qty;
      this.save(d);
    }
  },
  isAvailable(id,size) {
    const d=this.get();
    return (d.stock[id]&&d.stock[id][size]>0);
  }
};

/* ── MENU DATA (with emoji avatars for demo) ── */
const MENU_EMOJI = {
  makanan:['🍛','🍞','🥪','🍟','🍝'],
  minuman:['☕','🥛','🧊','🍵','🍫'],
  dessert:['🍮','🍰','🍫','🍮','🍨'],
  mixplater:['🍱','🎁','🍽️','📦','🥡'],
  special:['⭐']
};

/* ── MENU IMAGE HELPER ── */
// Returns an <img> tag pointing to assets/menu/{id}.jpg with emoji fallback
function menuImg(menuOrId, emoji, height='100%') {
  const menu = typeof menuOrId === 'object' ? menuOrId : { id: menuOrId };
  const imageSrc = menu.image || `assets/menu/${menu.id}.jpg`;
  return `<img
    src="${imageSrc}"
    alt=""
    style="width:100%;height:${height};object-fit:cover;display:block;"
    onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
  /><span class="card-emoji-fallback" style="display:none;width:100%;height:100%;align-items:center;justify-content:center;font-size:3.5rem;">${emoji}</span>`;
}

/* ── FORMAT CURRENCY ── */
function fmtRp(n){ return 'Rp '+n.toLocaleString('id-ID'); }

/* ── GUARD: require login ── */
function requireLogin(role) {
  const user = Session.get('user');
  if (!user) { window.location.href='login.html'; return false; }
  if (role && user.role !== role) { window.location.href='login.html'; return false; }
  return true;
}

/* ── LOGOUT ── */
function logout() {
  Session.clear('user');
  window.location.href='login.html';
}

/* ── BOOKING MEJA DATA ── */
const Booking = {
  _key:'booking',
  tables: [1,2,3,4,5,6,7,8,9,10],
  all(){ return Session.get(this._key)||[]; },
  save(b){ Session.set(this._key,b); },
  book(tableNo, user, orderNum){
    const all=this.all();
    all.push({tableNo,user,orderNum,time:Date.now()});
    this.save(all);
  },
  getBooked(){ return this.all().map(b=>b.tableNo); },
  getMyTable(orderNum){ const b=this.all().find(b=>b.orderNum===orderNum); return b?b.tableNo:null; }
};


/* ── REPORTS ── */
const Reports = {
  _key:'reports',
  _dailyHistory:'daily_history',
  _monthlyHistory:'monthly_history',
  all(){ return Session.get(this._key)||[]; },
  save(v){ Session.set(this._key,v); },
  todayKey(){ return new Date().toISOString().slice(0,10); },
  monthKey(){ return new Date().toISOString().slice(0,7); },
  generateDaily(){
    const orders = Orders.all();
    const today=this.todayKey();
    const todayOrders=orders.filter(o=>(o.createdAt||'').slice(0,10)===today);
    const total=todayOrders.reduce((s,o)=>s+o.total,0);
    const report={date:today,total,orders:todayOrders,createdAt:new Date().toISOString()};
    const all=this.all().filter(r=>r.date!==today);
    all.unshift(report);
    this.save(all);
    return report;
  },
  dailyHistory(){ return Session.get(this._dailyHistory)||[]; },
  saveDailyHistory(v){ Session.set(this._dailyHistory,v); },
  monthlyHistory(){ return Session.get(this._monthlyHistory)||[]; },
  saveMonthlyHistory(v){ Session.set(this._monthlyHistory,v); },
  resetDaily(){
    const report=this.generateDaily();
    const history=this.dailyHistory();
    history.unshift({...report, resetAt:new Date().toISOString()});
    this.saveDailyHistory(history);
    const today=this.todayKey();
    const remaining=Orders.all().filter(o=>(o.createdAt||'').slice(0,10)!==today);
    Orders.save(remaining);
    this.save(this.all().filter(r=>r.date!==today));
  },
  resetMonthly(){
    const reports=this.all();
    const total=reports.reduce((s,r)=>s+r.total,0);
    const monthData={month:this.monthKey(),total,reports:[...reports],createdAt:new Date().toISOString()};
    const history=this.monthlyHistory();
    history.unshift(monthData);
    this.saveMonthlyHistory(history);
    this.save([]);
  }
};
