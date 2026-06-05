@extends('biblioteca.layout')
@section('title', 'Préstamos')

@section('content')
<h1 class="page-title">Préstamos</h1>

<div id="alerta" class="alert"></div>

<div style="display:grid; grid-template-columns:1fr 360px; gap:20px; align-items:start;">

    {{-- Lista de préstamos --}}
    <div>
        <div class="card">
            <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-bottom:16px;">
                <select id="filtro-estado" onchange="cargarPrestamos()" style="padding:7px 10px; border:1px solid #ccc; border-radius:4px; font-size:14px;">
                    <option value="">Todos los estados</option>
                    <option value="activo">Activo</option>
                    <option value="devuelto">Devuelto</option>
                    <option value="vencido">Vencido</option>
                </select>
                <strong id="resultado-count" style="font-size:14px; color:#555;"></strong>
            </div>
            <div id="tabla-prestamos"><div class="loading">Cargando...</div></div>
            <div id="paginacion" style="margin-top:16px; display:flex; gap:8px; justify-content:center;"></div>
        </div>
    </div>

    {{-- Formulario nuevo préstamo --}}
    <div class="card" style="position:sticky; top:20px;">
        <h3 style="margin-bottom:16px; font-size:16px;">Nuevo préstamo</h3>

        <div id="form-alerta" class="alert"></div>

        <div class="form-group">
            <label>Usuario *</label>
            <select id="f-usuario">
                <option value="">Seleccionar usuario...</option>
            </select>
            <div class="form-error" id="e-usuario"></div>
        </div>

        <div class="form-group">
            <label>Libro *</label>
            <select id="f-libro">
                <option value="">Seleccionar libro...</option>
            </select>
            <div class="form-error" id="e-libro"></div>
        </div>

        <div class="form-group">
            <label>Fecha de préstamo</label>
            <input type="date" id="f-fecha-prestamo">
        </div>

        <div class="form-group">
            <label>Fecha devolución estimada *</label>
            <input type="date" id="f-fecha-devolucion">
            <div class="form-error" id="e-fecha"></div>
        </div>

        <button class="btn btn-primary" style="width:100%;" onclick="crearPrestamo()">Registrar préstamo</button>
    </div>

</div>
@endsection

@section('scripts')
<script>
    async function cargarPrestamos(pagina = 1) {
        const estado = document.getElementById('filtro-estado').value;
        let url = `/prestamos?page=${pagina}&per_page=8`;
        if (estado) url += '&estado=' + estado;

        document.getElementById('tabla-prestamos').innerHTML = '<div class="loading">Cargando...</div>';

        const res = await apiFetch(url);
        if (!res) return;

        const prestamos = res.body.data;
        const meta      = res.body.meta;

        document.getElementById('resultado-count').textContent = `${meta.total} préstamo(s)`;

        if (prestamos.length === 0) {
            document.getElementById('tabla-prestamos').innerHTML = '<p style="color:#888;font-size:14px;">No hay préstamos.</p>';
            document.getElementById('paginacion').innerHTML = '';
            return;
        }

        let html = `<table>
            <thead><tr>
                <th>ID</th><th>Libro</th><th>Usuario</th>
                <th>F. préstamo</th><th>F. devolución</th><th>Estado</th><th></th>
            </tr></thead><tbody>`;

        prestamos.forEach(p => {
            const badge = `<span class="badge badge-${p.estado}">${p.estado}</span>`;
            const btnDev = p.estado !== 'devuelto'
                ? `<button class="btn btn-success" style="padding:4px 10px;font-size:12px;"
                           onclick="devolver(${p.id})">Devolver</button>`
                : '';

            html += `<tr>
                <td>${p.id}</td>
                <td style="font-size:13px;">${p.libro?.titulo ?? '—'}</td>
                <td style="font-size:13px;">${p.usuario?.nombre ?? '—'}</td>
                <td style="font-size:13px;">${p.fecha_prestamo}</td>
                <td style="font-size:13px;">${p.fecha_devolucion_estimada}</td>
                <td>${badge}</td>
                <td>${btnDev}</td>
            </tr>`;
        });

        html += '</tbody></table>';
        document.getElementById('tabla-prestamos').innerHTML = html;

        let pag = '';
        if (meta.last_page > 1) {
            for (let i = 1; i <= meta.last_page; i++) {
                const activo = i === meta.current_page ? 'btn-primary' : 'btn-secondary';
                pag += `<button class="btn ${activo}" style="padding:5px 12px;" onclick="cargarPrestamos(${i})">${i}</button>`;
            }
        }
        document.getElementById('paginacion').innerHTML = pag;
    }

    async function cargarSelectores() {
        // Usuarios activos
        const resU = await apiFetch('/autores?per_page=100');  // reutilizo endpoint, pero cargo usuarios aparte
        const resUsuarios = await apiFetch('/prestamos?per_page=1'); // solo para verificar auth

        // Cargamos usuarios via un endpoint básico
        const resU2 = await fetch('/api/estadisticas', { headers: authHeaders() });

        // Obtener usuarios activos desde la API de préstamos (trick: busco los que aparecen en préstamos)
        // Para el select, construyo la lista manualmente cargando desde /api
        const usuarios = await apiFetch('/prestamos?per_page=100');

        // Cargar libros disponibles
        const libros = await apiFetch('/libros?solo_disponibles=1&per_page=100');
        if (!libros) return;

        const selLibro = document.getElementById('f-libro');
        libros.body.data.forEach(l => {
            const opt = document.createElement('option');
            opt.value = l.id;
            opt.textContent = `${l.titulo} (stock: ${l.stock_disponible})`;
            selLibro.appendChild(opt);
        });

        // Para usuarios, los cargo desde los préstamos existentes (IDs únicos)
        // Mejor: llamo endpoint de estadísticas y luego cargo lista de usuarios con un truco
        await cargarUsuariosSelect();
    }

    async function cargarUsuariosSelect() {
        // Hack simple: llamamos a la API de préstamos para extraer usuarios únicos
        // En un proyecto real habría un endpoint GET /api/usuarios
        const res = await apiFetch('/prestamos?per_page=100');
        if (!res) return;

        const usuariosVistos = new Map();
        res.body.data.forEach(p => {
            if (p.usuario && !usuariosVistos.has(p.usuario.id)) {
                usuariosVistos.set(p.usuario.id, p.usuario);
            }
        });

        const sel = document.getElementById('f-usuario');
        usuariosVistos.forEach(u => {
            const opt = document.createElement('option');
            opt.value = u.id;
            opt.textContent = `${u.nombre} — ${u.email}`;
            sel.appendChild(opt);
        });
    }

    async function devolver(id) {
        if (!confirm('¿Confirmar devolución del préstamo #' + id + '?')) return;

        const res = await apiFetch('/prestamos/' + id + '/devolver', { method: 'PUT' });
        if (!res) return;

        if (res.body.success) {
            showAlert('alerta', 'Préstamo marcado como devuelto. Stock actualizado.');
            cargarPrestamos();
        } else {
            showAlert('alerta', res.body.message, 'error');
        }
    }

    function validarFormPrestamo() {
        let ok = true;

        const usuario  = document.getElementById('f-usuario').value;
        const libro    = document.getElementById('f-libro').value;
        const fechaDev = document.getElementById('f-fecha-devolucion').value;
        const hoy      = new Date().toISOString().split('T')[0];

        const eU = document.getElementById('e-usuario');
        const eL = document.getElementById('e-libro');
        const eF = document.getElementById('e-fecha');

        if (!usuario) { eU.textContent = 'Selecciona un usuario.'; eU.style.display = 'block'; ok = false; }
        else { eU.style.display = 'none'; }

        if (!libro) { eL.textContent = 'Selecciona un libro.'; eL.style.display = 'block'; ok = false; }
        else { eL.style.display = 'none'; }

        if (!fechaDev) {
            eF.textContent = 'La fecha de devolución es obligatoria.'; eF.style.display = 'block'; ok = false;
        } else if (fechaDev <= hoy) {
            eF.textContent = 'La fecha debe ser posterior a hoy.'; eF.style.display = 'block'; ok = false;
        } else { eF.style.display = 'none'; }

        return ok;
    }

    async function crearPrestamo() {
        if (!validarFormPrestamo()) return;

        const data = {
            id_usuario:                Number(document.getElementById('f-usuario').value),
            id_libro:                  Number(document.getElementById('f-libro').value),
            fecha_prestamo:            document.getElementById('f-fecha-prestamo').value || undefined,
            fecha_devolucion_estimada: document.getElementById('f-fecha-devolucion').value,
        };

        const res = await apiFetch('/prestamos', { method: 'POST', body: JSON.stringify(data) });
        if (!res) return;

        const alertEl = document.getElementById('form-alerta');

        if (!res.body.success) {
            alertEl.className = 'alert alert-error show';
            alertEl.textContent = res.body.message;
            return;
        }

        alertEl.className = 'alert alert-success show';
        alertEl.textContent = 'Préstamo registrado correctamente.';
        setTimeout(() => alertEl.classList.remove('show'), 4000);

        // Limpiar formulario
        document.getElementById('f-usuario').value = '';
        document.getElementById('f-libro').value   = '';
        document.getElementById('f-fecha-prestamo').value = '';
        document.getElementById('f-fecha-devolucion').value = '';

        cargarPrestamos();
    }

    function onAfterLogin() { cargarPrestamos(); cargarSelectores(); }

    document.addEventListener('DOMContentLoaded', () => {
        // Fecha de préstamo por defecto = hoy
        const hoy = new Date().toISOString().split('T')[0];
        document.getElementById('f-fecha-prestamo').value = hoy;

        if (getToken()) { cargarPrestamos(); cargarSelectores(); }
    });
</script>
@endsection
