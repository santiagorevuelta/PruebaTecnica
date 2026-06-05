@extends('biblioteca.layout')
@section('title', 'Dashboard')

@section('content')
<h1 class="page-title">Dashboard</h1>

<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px; margin-bottom:28px;">
    <div class="card" style="text-align:center; padding:24px;">
        <div style="font-size:32px; font-weight:700; color:#2980b9;" id="stat-libros">—</div>
        <div style="color:#666; font-size:13px; margin-top:4px;">Total libros</div>
    </div>
    <div class="card" style="text-align:center; padding:24px;">
        <div style="font-size:32px; font-weight:700; color:#27ae60;" id="stat-disponibles">—</div>
        <div style="color:#666; font-size:13px; margin-top:4px;">Con stock</div>
    </div>
    <div class="card" style="text-align:center; padding:24px;">
        <div style="font-size:32px; font-weight:700; color:#e67e22;" id="stat-activos">—</div>
        <div style="color:#666; font-size:13px; margin-top:4px;">Préstamos activos</div>
    </div>
    <div class="card" style="text-align:center; padding:24px;">
        <div style="font-size:32px; font-weight:700; color:#e74c3c;" id="stat-vencidos">—</div>
        <div style="color:#666; font-size:13px; margin-top:4px;">Préstamos vencidos</div>
    </div>
    <div class="card" style="text-align:center; padding:24px;">
        <div style="font-size:32px; font-weight:700; color:#8e44ad;" id="stat-usuarios">—</div>
        <div style="color:#666; font-size:13px; margin-top:4px;">Usuarios activos</div>
    </div>
</div>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <strong>Préstamos recientes</strong>
        <a href="/biblioteca/prestamos" class="btn btn-primary" style="font-size:13px; padding:6px 14px;">Ver todos</a>
    </div>
    <div id="tabla-recientes"><div class="loading">Cargando...</div></div>
</div>
@endsection

@section('scripts')
<script>
    async function cargarDashboard() {
        const stats = await apiFetch('/estadisticas');
        if (!stats) return;

        const d = stats.body.data;
        document.getElementById('stat-libros').textContent      = d.total_libros;
        document.getElementById('stat-disponibles').textContent = d.libros_disponibles;
        document.getElementById('stat-activos').textContent     = d.prestamos_activos;
        document.getElementById('stat-vencidos').textContent    = d.prestamos_vencidos;
        document.getElementById('stat-usuarios').textContent    = d.total_usuarios;

        const res = await apiFetch('/prestamos?per_page=5');
        if (!res) return;

        const prestamos = res.body.data;
        if (!prestamos || prestamos.length === 0) {
            document.getElementById('tabla-recientes').innerHTML = '<p style="color:#888;font-size:14px;">Sin préstamos registrados.</p>';
            return;
        }

        let html = `<table>
            <thead><tr>
                <th>ID</th><th>Libro</th><th>Usuario</th>
                <th>Fecha préstamo</th><th>Devolución estimada</th><th>Estado</th>
            </tr></thead><tbody>`;

        prestamos.forEach(p => {
            const badge = `<span class="badge badge-${p.estado}">${p.estado}</span>`;
            html += `<tr>
                <td>${p.id}</td>
                <td>${p.libro?.titulo ?? '—'}</td>
                <td>${p.usuario?.nombre ?? '—'}</td>
                <td>${p.fecha_prestamo}</td>
                <td>${p.fecha_devolucion_estimada}</td>
                <td>${badge}</td>
            </tr>`;
        });

        html += '</tbody></table>';
        document.getElementById('tabla-recientes').innerHTML = html;
    }

    function onAfterLogin() { cargarDashboard(); }
    document.addEventListener('DOMContentLoaded', () => {
        if (getToken()) cargarDashboard();
    });
</script>
@endsection
