<?php
require __DIR__ . '/../init.php';
require_login();

$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';
$tipo_solicitante = $_GET['tipo_solicitante'] ?? ''; // 'docente' o 'estudiante'
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Préstamos — Inventario</title>
<link rel="stylesheet" href="../css/tabla_prestamo_index.css">
<style>
body { font-family:'Segoe UI', Roboto, sans-serif; background:#f4f6f9; margin:0; padding:0; }
.container { max-width:1200px; margin:20px auto; padding:0 15px; }
.card { background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); padding:20px; margin-bottom:25px; }
h2 { margin-top:0; color:#2c3e50; }
table { width:100%; border-collapse:collapse; margin-top:10px; }
thead { background: linear-gradient(90deg,#1e3c72,#2a5298); color:#fff; }
th, td { padding:12px; text-align:left; border-bottom:1px solid #eee; }
tbody tr:hover { background:#f0f4f8; }
.muted { color:#888; text-align:center; }
select, input { padding:6px 10px; border-radius:6px; border:1px solid #ccc; margin-bottom:10px; }
button { padding:7px 14px; border:none; border-radius:6px; background:#1e3c72; color:#fff; cursor:pointer; transition:0.3s; }
button:hover { background:#2a5298; }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
<div class="container">

<div class="card">
<h2>Préstamos activos / pendientes <span class="pill" id="contadorActivos">0</span></h2>
<table id="tablaPrestamos">
<thead>
<tr>
<th>Equipo</th>
<th>Serial</th>
<th>Solicitante / Historial</th>
<th>Fecha</th>
<th>Obs</th>
<th>Estado</th>
<th>Acción</th>
</tr>
</thead>
<tbody>
<tr><td colspan="7" class="muted">Cargando...</td></tr>
</tbody>
</table>
</div>

<div class="card">
<h2>Historial de devoluciones</h2>
<br>
<form id="filtroPrestamos" style="margin-bottom:15px;">
<label>Desde: <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>"></label>
<label>Hasta: <input type="date" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>"></label>
<label>Ver solo:
<select name="tipo_solicitante">
<option value="">Todos</option>
<option value="docente" <?= $tipo_solicitante==='docente'?'selected':'' ?>>Docentes</option>
<option value="estudiante" <?= $tipo_solicitante==='estudiante'?'selected':'' ?>>Estudiantes</option>
</select>
</label>
</form>
<div id="tablaHistorial">Cargando...</div>
<div id="paginacionHistorial" style="margin-top:15px;"></div>
</div>

</div>

<script>
// ---- PRÉSTAMOS ACTIVOS ----
function actualizarPrestamos() {
    fetch('prestamos_actualizar_ajax.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.querySelector('#tablaPrestamos tbody');
        const contador = document.getElementById('contadorActivos');
        tbody.innerHTML = '';
        if(!data.prestamos || data.prestamos.length===0){
            tbody.innerHTML='<tr><td colspan="7" class="muted">No hay préstamos activos.</td></tr>';
            contador.textContent='0';
            return;
        }
        contador.textContent = data.prestamos.length;
        data.prestamos.forEach(p=>{
            let historial='';
            if(p.est_id) historial=`${p.nombre} ${p.apellido} (CI: ${p.ci}) (Estudiante)`;
            else {
                if(p.historial_cesiones.length>0) p.historial_cesiones.forEach(h=>{
                    historial+=`${h.nombre} ${h.apellido} (CI: ${h.ci}) (Docente)<br>`;
                });
                historial+=`${p.nombre} ${p.apellido} (CI: ${p.ci}) (Docente)`;
            }
            let estado='';
            if(p.estado==='pendiente') estado='<span style="color:orange;font-weight:bold">Préstamo pendiente</span>';
            else if(p.estado==='pendiente_devolucion') estado='<span style="color:#eab308;font-weight:bold">Devolución pendiente</span>';
            else estado='<span style="color:green;font-weight:bold">Activo</span>';
            let accion='';
            if(p.estado==='pendiente') accion=`<button onclick="aprobar(${p.id},'prestamo')">Aprobar préstamo</button>`;
            else if(p.estado==='pendiente_devolucion') accion=`<button onclick="aprobar(${p.id},'devolucion')">Aprobar devolución</button>`;
            tbody.innerHTML+=`<tr>
                <td>${p.tipo} ${p.marca} ${p.modelo}</td>
                <td>${p.serial_interno}</td>
                <td>${historial}</td>
                <td>${p.fecha_entrega ?? '-'}</td>
                <td>${p.observacion ?? ''}</td>
                <td>${estado}</td>
                <td>${accion}</td>
            </tr>`;
        });
    })
    .catch(console.error);
}

function aprobar(id,tipo){
    let url = tipo==='prestamo'?'prestamo_aprobar_ajax.php':'prestamo_aprobar_devolucion.php';
    Swal.fire({
        title: tipo==='prestamo'?'¿Aprobar esta solicitud?':'¿Confirmar devolución?',
        icon:'question',
        showCancelButton:true,
        confirmButtonText:'Sí'
    }).then(res=>{
        if(res.isConfirmed){
            fetch(url,{
                method:'POST',
                headers:{'Content-Type':'application/json'},
                body:JSON.stringify({id})
            })
            .then(r=>r.json())
            .then(data=>{
                if(data.ok) Swal.fire('Éxito',data.ok,'success');
                else Swal.fire('Error',data.error,'error');
                actualizarPrestamos();
                cargarHistorial();
            });
        }
    });
}

// ---- HISTORIAL DEVOLUCIONES ----
const form = document.getElementById('filtroPrestamos');
let paginaActual=1;
function cargarHistorial(pagina=1){
    paginaActual=pagina;
    const datos=new FormData(form);
    datos.append('pagina',paginaActual);
    fetch('prestamos_historial_ajax.php',{method:'POST',body:datos})
    .then(res=>res.text())
    .then(html=>{
        document.getElementById('tablaHistorial').innerHTML=html;
        actualizarBotones();
    });
}
function actualizarBotones(){
    const totalPaginasEl=document.getElementById('totalPaginas');
    const totalPaginas = totalPaginasEl?parseInt(totalPaginasEl.dataset.total):1;
    const cont=document.getElementById('paginacionHistorial');
    cont.innerHTML='';
    const btnPrev=document.createElement('button');
    btnPrev.textContent='Anterior';
    btnPrev.disabled=paginaActual<=1;
    btnPrev.onclick=()=>cargarHistorial(paginaActual-1);
    cont.appendChild(btnPrev);
    const btnNext=document.createElement('button');
    btnNext.textContent='Siguiente';
    btnNext.disabled=paginaActual>=totalPaginas;
    btnNext.onclick=()=>cargarHistorial(paginaActual+1);
    cont.appendChild(btnNext);
}

// Detectar cambios en filtros
form.querySelectorAll('input,select').forEach(el=>el.addEventListener('change',()=>cargarHistorial(1)));

// Ejecutar al inicio y actualizar cada 2s
actualizarPrestamos();
cargarHistorial();
setInterval(actualizarPrestamos,2000);
</script>
</body>
</html>
