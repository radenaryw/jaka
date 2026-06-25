<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>Login — Djaka Coffee</title>
<link rel="stylesheet" href="style.css"/>
<style>
  body {
    min-height:100vh;
    background:linear-gradient(135deg,#1a0a00 0%,#3d1a00 50%,#6b3a2a 100%);
    display:flex; align-items:center; justify-content:center;
    padding:20px;
    position:relative; overflow:hidden;
  }
  /* coffee steam particles */
  body::before, body::after {
    content:''; position:absolute;
    width:300px; height:300px; border-radius:50%;
    background:radial-gradient(circle,rgba(212,168,67,.07),transparent 70%);
    animation:float 8s ease-in-out infinite;
  }
  body::before { top:-80px; left:-80px; }
  body::after  { bottom:-80px; right:-80px; animation-delay:4s; }
  @keyframes float {
    0%,100%{ transform:translateY(0) scale(1); }
    50%    { transform:translateY(-30px) scale(1.05); }
  }

  .login-wrap {
    width:100%; max-width:440px; position:relative; z-index:1;
    animation:fadeUp .6s cubic-bezier(.4,0,.2,1);
  }
  @keyframes fadeUp {
    from { opacity:0; transform:translateY(30px); }
    to   { opacity:1; transform:none; }
  }

  .login-box {
    background:rgba(253,248,242,.97);
    border-radius:24px;
    box-shadow:0 24px 80px rgba(0,0,0,.55);
    overflow:hidden;
  }

  .login-header {
    background:linear-gradient(135deg,#1a0a00,#3d1a00);
    padding:36px 32px 28px;
    text-align:center;
  }
  .logo-wrap {
    width:88px; height:88px; margin:0 auto 16px;
    border-radius:50%; border:3px solid #d4a843;
    overflow:hidden; box-shadow:0 8px 32px rgba(0,0,0,.4);
    background:linear-gradient(135deg,#6b3a2a,#3d1a00);
    display:flex; align-items:center; justify-content:center;
    font-size:2.8rem;
  }
  .login-header h1 {
    font-family:'Playfair Display',serif;
    color:#d4a843; font-size:1.7rem; letter-spacing:1px;
  }
  .login-header p {
    color:#c8a97e; font-size:.82rem; margin-top:4px;
  }

  .login-body { padding:28px 32px 32px; }

  /* Role selector */
  .role-tabs {
    display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-bottom:24px;
  }
  .role-tab {
    display:flex; flex-direction:column; align-items:center; gap:5px;
    padding:12px 8px; border-radius:12px; border:2px solid #e8d5c0;
    cursor:pointer; transition:.25s; background:#fff;
  }
  .role-tab:hover { border-color:#b5651d; background:#fdf8f2; }
  .role-tab.active { border-color:#b5651d; background:linear-gradient(135deg,rgba(181,101,29,.1),rgba(107,58,42,.08)); }
  .role-tab .role-icon { font-size:1.6rem; }
  .role-tab .role-name { font-size:.78rem; font-weight:600; color:#3d1a00; }
  .role-tab.active .role-name { color:#b5651d; }

  .form-section { display:none; }
  .form-section.active { display:block; animation:fadeIn .3s ease; }
  @keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:none} }

  .google-hint {
    font-size:.78rem; color:#888; margin-top:4px; padding-left:2px;
  }

  .btn-login {
    width:100%; padding:14px; font-size:1rem; font-weight:700;
    background:linear-gradient(135deg,#b5651d,#6b3a2a);
    color:#fff; border:none; border-radius:12px; cursor:pointer;
    letter-spacing:.5px; transition:.25s;
    box-shadow:0 6px 20px rgba(181,101,29,.35);
    font-family:'DM Sans',sans-serif;
  }
  .btn-login:hover {
    transform:translateY(-2px);
    box-shadow:0 10px 28px rgba(181,101,29,.48);
  }

  .error-msg {
    background:#fce4ec; color:#c62828; padding:10px 14px;
    border-radius:8px; font-size:.83rem; margin-bottom:14px;
    display:none; border-left:3px solid #e05252;
  }
  .error-msg.show { display:block; animation:fadeIn .2s; }

  .divider { height:1px; background:var(--steam); margin:20px 0; }
  .footer-note { text-align:center; font-size:.75rem; color:#aaa; margin-top:18px; }
</style>
</head>
<body>
<div class="login-wrap">
  <div class="login-box">
    <div class="login-header">
      <div class="logo-wrap">☕</div>
      <h1>Djaka Coffee</h1>
      <p>Selamat datang! Silakan masuk untuk melanjutkan.</p>
    </div>

    <div class="login-body">
      <!-- Role Tabs -->
      <div class="role-tabs">
        <div class="role-tab active" data-role="customer" onclick="selectRole('customer')">
          <span class="role-icon">🧑‍💼</span>
          <span class="role-name">Customer</span>
        </div>
        <div class="role-tab" data-role="kasir" onclick="selectRole('kasir')">
          <span class="role-icon">💳</span>
          <span class="role-name">Kasir</span>
        </div>
        <div class="role-tab" data-role="admin" onclick="selectRole('admin')">
          <span class="role-icon">🛠️</span>
          <span class="role-name">Admin</span>
        </div>
      </div>

      <div class="error-msg" id="errMsg">Username atau password salah!</div>

      <!-- Customer Form -->
      <div class="form-section active" id="form-customer">
        <div class="form-group">
          <label>Email Google</label>
          <input type="email" class="form-control" id="cust-email" placeholder="nama@gmail.com"/>
          <span class="google-hint">Masukkan email Google yang valid</span>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" class="form-control" id="cust-pass" placeholder="Password akun Google"/>
        </div>
      </div>

      <!-- Kasir Form -->
      <div class="form-section" id="form-kasir">
        <div class="form-group">
          <label>Username Kasir</label>
          <input type="text" class="form-control" id="kasir-user" placeholder="kasir"/>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" class="form-control" id="kasir-pass" placeholder="••••••••••••"/>
        </div>
      </div>

      <!-- Admin Form -->
      <div class="form-section" id="form-admin">
        <div class="form-group">
          <label>Username Admin</label>
          <input type="text" class="form-control" id="admin-user" placeholder="admin"/>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" class="form-control" id="admin-pass" placeholder="••••••••••••"/>
        </div>
      </div>

      <button class="btn-login" onclick="doLogin()">Masuk →</button>
      <p class="footer-note">© 2025 Djaka Coffee &nbsp;|&nbsp; All rights reserved</p>
    </div>
  </div>
</div>

<script src="script.js"></script>
<script>
let currentRole = 'customer';

function selectRole(role) {
  currentRole = role;
  document.querySelectorAll('.role-tab').forEach(t=>t.classList.remove('active'));
  document.querySelector(`[data-role="${role}"]`).classList.add('active');
  document.querySelectorAll('.form-section').forEach(s=>s.classList.remove('active'));
  document.getElementById('form-'+role).classList.add('active');
  document.getElementById('errMsg').classList.remove('show');
}

function showErr(msg) {
  const el = document.getElementById('errMsg');
  el.textContent = msg;
  el.classList.add('show');
  setTimeout(()=>el.classList.remove('show'), 3500);
}

function isValidEmail(e) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e);
}

function doLogin() {
  const err = document.getElementById('errMsg');
  err.classList.remove('show');

  if (currentRole === 'customer') {
    const email = document.getElementById('cust-email').value.trim();
    const pass  = document.getElementById('cust-pass').value;
    if (!isValidEmail(email)) { showErr('Masukkan email Google yang valid!'); return; }
    if (pass.length < 6) { showErr('Password minimal 6 karakter!'); return; }
    Session.set('user', { role:'customer', email, name: email.split('@')[0] });
    window.location.href = 'pembeli.html';

  } else if (currentRole === 'kasir') {
    const u = document.getElementById('kasir-user').value.trim();
    const p = document.getElementById('kasir-pass').value;
    if (u !== 'kasir' || p !== 'kasirdjaka123') { showErr('Username atau password kasir salah!'); return; }
    Session.set('user', { role:'kasir', name:'Kasir' });
    window.location.href = 'kasir.html';

  } else if (currentRole === 'admin') {
    const u = document.getElementById('admin-user').value.trim();
    const p = document.getElementById('admin-pass').value;
    if (u !== 'admin' || p !== 'admindjaka123') { showErr('Username atau password admin salah!'); return; }
    Session.set('user', { role:'admin', name:'Admin' });
    window.location.href = 'admin.html';
  }
}

// Enter key
document.addEventListener('keydown', e=>{ if(e.key==='Enter') doLogin(); });

// Already logged in
const usr = Session.get('user');
if (usr) {
  if (usr.role==='customer') window.location.href='pembeli.html';
  else if (usr.role==='kasir') window.location.href='kasir.html';
  else if (usr.role==='admin') window.location.href='admin.html';
}
</script>
</body>
</html>
