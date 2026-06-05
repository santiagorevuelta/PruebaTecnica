<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biblioteca') — Sistema de Biblioteca</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f4f6f8;
            color: #333;
            min-height: 100vh;
        }

        nav {
            background: #2c3e50;
            padding: 0 24px;
            display: flex;
            align-items: center;
            gap: 0;
            height: 56px;
        }

        nav .brand {
            color: #fff;
            font-weight: 600;
            font-size: 18px;
            margin-right: 32px;
            text-decoration: none;
        }

        nav a {
            color: #adb5c6;
            text-decoration: none;
            padding: 0 14px;
            height: 56px;
            display: flex;
            align-items: center;
            font-size: 14px;
            transition: color .15s, background .15s;
        }

        nav a:hover, nav a.active {
            color: #fff;
            background: #3d5166;
        }

        nav .spacer { flex: 1; }

        nav .user-info {
            color: #adb5c6;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        nav .btn-logout {
            background: none;
            border: 1px solid #556;
            color: #adb5c6;
            padding: 5px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        nav .btn-logout:hover { border-color: #e74c3c; color: #e74c3c; }

        .container { max-width: 1100px; margin: 0 auto; padding: 28px 20px; }

        .page-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .card {
            background: #fff;
            border-radius: 6px;
            border: 1px solid #dde3ea;
            padding: 20px;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }

        .btn-primary { background: #2980b9; color: #fff; }
        .btn-primary:hover { background: #2471a3; }
        .btn-danger  { background: #e74c3c; color: #fff; }
        .btn-success { background: #27ae60; color: #fff; }
        .btn-secondary { background: #95a5a6; color: #fff; }

        .alert {
            padding: 10px 14px;
            border-radius: 4px;
            margin-bottom: 16px;
            font-size: 14px;
            display: none;
        }

        .alert-success { background: #d5f5e3; color: #1e8449; border: 1px solid #a9dfbf; }
        .alert-error   { background: #fadbd8; color: #c0392b; border: 1px solid #f1948a; }
        .alert.show { display: block; }

        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { background: #f1f3f5; padding: 10px 12px; text-align: left; font-weight: 600; color: #555; border-bottom: 2px solid #dde3ea; }
        td { padding: 10px 12px; border-bottom: 1px solid #eef0f3; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafbfc; }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-activo   { background: #d5f5e3; color: #1e8449; }
        .badge-devuelto { background: #d6eaf8; color: #1a5276; }
        .badge-vencido  { background: #fadbd8; color: #c0392b; }
        .badge-ok       { background: #d5f5e3; color: #1e8449; }
        .badge-sin-stock{ background: #fdebd0; color: #784212; }

        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 500; margin-bottom: 5px; color: #555; }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group input:focus,
        .form-group select:focus { outline: none; border-color: #2980b9; }
        .form-error { color: #e74c3c; font-size: 12px; margin-top: 4px; display: none; }

        .loading { color: #888; font-size: 14px; padding: 20px; text-align: center; }

        /* Login overlay */
        #login-overlay {
            position: fixed; inset: 0;
            background: rgba(44,62,80,.85);
            display: flex; align-items: center; justify-content: center;
            z-index: 999;
        }

        #login-box {
            background: #fff;
            border-radius: 8px;
            padding: 32px 36px;
            width: 340px;
        }

        #login-box h2 { font-size: 20px; margin-bottom: 20px; color: #2c3e50; }
        #login-box .btn { width: 100%; padding: 10px; font-size: 15px; margin-top: 4px; }
    </style>
</head>
<body>

{{-- Login overlay --}}
<div id="login-overlay" style="display:none;">
    <div id="login-box">
        <h2>Iniciar sesión</h2>
        <div id="login-error" class="alert alert-error"></div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" id="login-email" placeholder="admin@biblioteca.com">
        </div>
        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" id="login-password" placeholder="••••••••">
        </div>
        <button class="btn btn-primary" onclick="doLogin()">Entrar</button>
    </div>
</div>

<nav>
    <a href="/biblioteca" class="brand">Biblioteca</a>
    <a href="/biblioteca" class="{{ request()->is('biblioteca') ? 'active' : '' }}">Dashboard</a>
    <a href="/biblioteca/libros" class="{{ request()->is('biblioteca/libros') ? 'active' : '' }}">Libros</a>
    <a href="/biblioteca/prestamos" class="{{ request()->is('biblioteca/prestamos') ? 'active' : '' }}">Préstamos</a>
    <div class="spacer"></div>
    <div class="user-info">
        <span id="nav-user"></span>
        <button class="btn-logout" onclick="doLogout()">Salir</button>
    </div>
</nav>

<div class="container">
    @yield('content')
</div>

<script>
    const API = '/api';

    function getToken() { return localStorage.getItem('api_token'); }

    function authHeaders() {
        return {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + getToken(),
        };
    }

    async function apiFetch(url, options = {}) {
        const res = await fetch(API + url, {
            headers: authHeaders(),
            ...options,
        });
        const json = await res.json();
        if (res.status === 401) { showLogin(); return null; }
        return { status: res.status, body: json };
    }

    function showAlert(id, msg, type = 'success') {
        const el = document.getElementById(id);
        if (!el) return;
        el.className = 'alert alert-' + type + ' show';
        el.textContent = msg;
        setTimeout(() => el.classList.remove('show'), 4000);
    }

    function showLogin() {
        document.getElementById('login-overlay').style.display = 'flex';
    }

    function checkAuth() {
        if (!getToken()) { showLogin(); return; }
        const user = JSON.parse(localStorage.getItem('api_user') || '{}');
        document.getElementById('nav-user').textContent = user.name || '';
    }

    async function doLogin() {
        const email    = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;
        const errEl    = document.getElementById('login-error');
        errEl.classList.remove('show');

        const res = await fetch(API + '/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ email, password }),
        });

        const json = await res.json();

        if (!res.ok) {
            errEl.textContent = json.message || 'Error de autenticación';
            errEl.classList.add('show');
            return;
        }

        localStorage.setItem('api_token', json.data.token);
        localStorage.setItem('api_user', JSON.stringify(json.data.user));
        document.getElementById('login-overlay').style.display = 'none';
        document.getElementById('nav-user').textContent = json.data.user.name;
        if (typeof onAfterLogin === 'function') onAfterLogin();
    }

    async function doLogout() {
        await apiFetch('/logout', { method: 'POST' });
        localStorage.removeItem('api_token');
        localStorage.removeItem('api_user');
        showLogin();
    }

    document.addEventListener('DOMContentLoaded', checkAuth);
</script>

@yield('scripts')
</body>
</html>
