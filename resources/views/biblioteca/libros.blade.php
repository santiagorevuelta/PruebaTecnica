@extends('biblioteca.layout')
@section('title', 'Libros')

@section('content')
<h1 class="page-title">Libros</h1>

<div id="alerta" class="alert"></div>

<div class="card">
    <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
        <input type="text" id="buscar-titulo" placeholder="Buscar por título..."
               style="flex:1; min-width:200px; padding:8px 10px; border:1px solid #ccc; border-radius:4px; font-size:14px;">
        <input type="number" id="buscar-anio" placeholder="Año (ej: 1967)"
               style="width:140px; padding:8px 10px; border:1px solid #ccc; border-radius:4px; font-size:14px;">
        <label style="font-size:14px; display:flex; align-items:center; gap:6px; white-space:nowrap;">
            <input type="checkbox" id="solo-disponibles"> Solo con stock
        </label>
        <button class="btn btn-primary" onclick="buscar()">Buscar</button>
    </div>
</div>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <strong id="resultado-count"></strong>
        <button class="btn btn-success" onclick="mostrarFormulario()">+ Nuevo libro</button>
    </div>
    <div id="tabla-libros"><div class="loading">Cargando...</div></div>
    <div id="paginacion" style="margin-top:16px; display:flex; gap:8px; justify-content:center;"></div>
</div>

{{-- Modal nuevo libro --}}
<div id="modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:100; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:8px; padding:28px; width:480px; max-height:90vh; overflow-y:auto;">
        <h3 style="margin-bottom:20px;" id="modal-titulo">Nuevo libro</h3>
        <div id="modal-alerta" class="alert"></div>

        <div class="form-group">
            <label>Título *</label>
            <input type="text" id="f-titulo">
            <div class="form-error" id="e-titulo"></div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div class="form-group">
                <label>ISBN</label>
                <input type="text" id="f-isbn" placeholder="978-...">
                <div class="form-error" id="e-isbn"></div>
            </div>
            <div class="form-group">
                <label>Año de publicación</label>
                <input type="number" id="f-anio" min="1000" max="2025">
            </div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div class="form-group">
                <label>Número de páginas</label>
                <input type="number" id="f-paginas" min="1">
            </div>
            <div class="form-group">
                <label>Stock disponible *</label>
                <input type="number" id="f-stock" min="0" value="1">
                <div class="form-error" id="e-stock"></div>
            </div>
        </div>
        <div class="form-group">
            <label>Descripción</label>
            <textarea id="f-descripcion" rows="3"></textarea>
        </div>
        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:8px;">
            <button class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
            <button class="btn btn-primary" id="btn-guardar" onclick="guardarLibro()">Guardar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let paginaActual = 1;
    let editandoId   = null;

    async function cargarLibros(pagina = 1) {
        paginaActual = pagina;
        const titulo      = document.getElementById('buscar-titulo').value;
        const anio        = document.getElementById('buscar-anio').value;
        const disponibles = document.getElementById('solo-disponibles').checked;

        let url = `/libros?page=${pagina}&per_page=10`;
        if (titulo)      url += '&titulo=' + encodeURIComponent(titulo);
        if (anio)        url += '&anio=' + anio;
        if (disponibles) url += '&solo_disponibles=1';

        document.getElementById('tabla-libros').innerHTML = '<div class="loading">Cargando...</div>';

        const res = await apiFetch(url);
        if (!res) return;

        const libros = res.body.data;
        const meta   = res.body.meta;

        document.getElementById('resultado-count').textContent = `${meta.total} libro(s) encontrado(s)`;

        if (libros.length === 0) {
            document.getElementById('tabla-libros').innerHTML = '<p style="color:#888;font-size:14px;">No se encontraron libros.</p>';
            document.getElementById('paginacion').innerHTML = '';
            return;
        }

        let html = `<table>
            <thead><tr>
                <th>ID</th><th>Título</th><th>Autores</th><th>Año</th><th>ISBN</th><th>Stock</th><th>Acciones</th>
            </tr></thead><tbody>`;

        libros.forEach(l => {
            const autores = (l.autores || []).map(a => a.nombre_completo).join(', ') || '—';
            const stock   = l.disponible
                ? `<span class="badge badge-ok">${l.stock_disponible}</span>`
                : `<span class="badge badge-sin-stock">Sin stock</span>`;

            html += `<tr>
                <td>${l.id}</td>
                <td><strong>${l.titulo}</strong></td>
                <td style="color:#555;font-size:13px;">${autores}</td>
                <td>${l.year_publicacion ?? '—'}</td>
                <td style="font-size:12px;color:#777;">${l.isbn ?? '—'}</td>
                <td>${stock}</td>
                <td>
                    <button class="btn btn-primary" style="padding:4px 10px;font-size:12px;"
                            onclick="editarLibro(${l.id})">Editar</button>
                    <button class="btn btn-danger" style="padding:4px 10px;font-size:12px;margin-left:4px;"
                            onclick="eliminarLibro(${l.id}, '${l.titulo.replace(/'/g,"\\'")}')">Borrar</button>
                </td>
            </tr>`;
        });

        html += '</tbody></table>';
        document.getElementById('tabla-libros').innerHTML = html;

        // Paginación
        let pag = '';
        if (meta.last_page > 1) {
            for (let i = 1; i <= meta.last_page; i++) {
                const activo = i === meta.current_page ? 'btn-primary' : 'btn-secondary';
                pag += `<button class="btn ${activo}" style="padding:5px 12px;" onclick="cargarLibros(${i})">${i}</button>`;
            }
        }
        document.getElementById('paginacion').innerHTML = pag;
    }

    function buscar() { cargarLibros(1); }

    // Búsqueda en tiempo real con debounce
    let debounce;
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('buscar-titulo').addEventListener('input', () => {
            clearTimeout(debounce);
            debounce = setTimeout(() => cargarLibros(1), 400);
        });

        if (getToken()) cargarLibros();
    });

    function onAfterLogin() { cargarLibros(); }

    function mostrarFormulario() {
        editandoId = null;
        document.getElementById('modal-titulo').textContent = 'Nuevo libro';
        document.getElementById('btn-guardar').textContent  = 'Crear libro';
        limpiarFormulario();
        document.getElementById('modal').style.display = 'flex';
    }

    async function editarLibro(id) {
        const res = await apiFetch('/libros/' + id);
        if (!res) return;

        const l = res.body.data;
        editandoId = id;

        document.getElementById('modal-titulo').textContent = 'Editar libro';
        document.getElementById('btn-guardar').textContent  = 'Guardar cambios';
        document.getElementById('f-titulo').value      = l.titulo;
        document.getElementById('f-isbn').value        = l.isbn ?? '';
        document.getElementById('f-anio').value        = l.year_publicacion ?? '';
        document.getElementById('f-paginas').value     = l.numero_paginas ?? '';
        document.getElementById('f-stock').value       = l.stock_disponible;
        document.getElementById('f-descripcion').value = l.descripcion ?? '';
        document.getElementById('modal').style.display = 'flex';
    }

    function cerrarModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function limpiarFormulario() {
        ['f-titulo','f-isbn','f-anio','f-paginas','f-stock','f-descripcion'].forEach(id => {
            document.getElementById(id).value = '';
        });
        document.getElementById('f-stock').value = '1';
        ['e-titulo','e-isbn','e-stock'].forEach(id => {
            document.getElementById(id).style.display = 'none';
        });
        const a = document.getElementById('modal-alerta');
        a.className = 'alert'; a.textContent = '';
    }

    function validarFormulario() {
        let ok = true;
        const titulo = document.getElementById('f-titulo').value.trim();
        const stock  = document.getElementById('f-stock').value;

        const eT = document.getElementById('e-titulo');
        const eS = document.getElementById('e-stock');

        if (!titulo) {
            eT.textContent = 'El título es obligatorio.'; eT.style.display = 'block'; ok = false;
        } else { eT.style.display = 'none'; }

        if (stock === '' || Number(stock) < 0) {
            eS.textContent = 'Stock debe ser 0 o más.'; eS.style.display = 'block'; ok = false;
        } else { eS.style.display = 'none'; }

        return ok;
    }

    async function guardarLibro() {
        if (!validarFormulario()) return;

        const data = {
            titulo:           document.getElementById('f-titulo').value.trim(),
            isbn:             document.getElementById('f-isbn').value.trim() || null,
            year_publicacion: document.getElementById('f-anio').value || null,
            numero_paginas:   document.getElementById('f-paginas').value || null,
            stock_disponible: Number(document.getElementById('f-stock').value),
            descripcion:      document.getElementById('f-descripcion').value.trim() || null,
        };

        const url    = editandoId ? '/libros/' + editandoId : '/libros';
        const method = editandoId ? 'PUT' : 'POST';

        const res = await apiFetch(url, { method, body: JSON.stringify(data) });
        if (!res) return;

        const alertEl = document.getElementById('modal-alerta');

        if (!res.body.success) {
            const errores = res.body.errors ?? {};
            let msg = res.body.message;
            Object.values(errores).forEach(e => { msg += ' ' + e[0]; });

            // Mostrar errores de ISBN si vienen del servidor
            if (errores.isbn) {
                const eI = document.getElementById('e-isbn');
                eI.textContent = errores.isbn[0]; eI.style.display = 'block';
            }

            alertEl.className = 'alert alert-error show';
            alertEl.textContent = msg;
            return;
        }

        cerrarModal();
        showAlert('alerta', editandoId ? 'Libro actualizado.' : 'Libro creado correctamente.');
        cargarLibros(paginaActual);
    }

    async function eliminarLibro(id, titulo) {
        if (!confirm(`¿Eliminar "${titulo}"? Esta acción es reversible (soft delete).`)) return;

        const res = await apiFetch('/libros/' + id, { method: 'DELETE' });
        if (!res) return;

        if (res.body.success) {
            showAlert('alerta', 'Libro eliminado.');
            cargarLibros(paginaActual);
        } else {
            showAlert('alerta', res.body.message, 'error');
        }
    }
</script>
@endsection
