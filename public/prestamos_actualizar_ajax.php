<?php
require __DIR__ . '/../init.php';
require_login();

// Traer todos los préstamos activos/pendientes
$act = $mysqli->query("
SELECT p.id, p.equipo_id, p.fecha_entrega, p.observacion, p.estado,
       e.tipo, e.marca, e.modelo, e.serial_interno,
       COALESCE(est.nombre,d.nombre) AS nombre,
       COALESCE(est.apellido,d.apellido) AS apellido,
       COALESCE(est.ci,d.ci) AS ci,
       est.id AS est_id, d.id AS doc_id
FROM prestamos p
JOIN equipos e ON e.id=p.equipo_id
LEFT JOIN estudiantes est ON est.id=p.estudiante_id
LEFT JOIN docentes d ON d.id=p.docente_id
WHERE p.estado IN ('activo','pendiente','pendiente_devolucion')
ORDER BY p.fecha_entrega DESC
")->fetch_all(MYSQLI_ASSOC);

// Historial de cesiones docentes
foreach($act as &$p){
    if($p['doc_id']){
        $stmt=$mysqli->prepare("SELECT hc.*, d.nombre,d.apellido,d.ci FROM historial_cesiones hc JOIN docentes d ON d.id=hc.de_docente_id WHERE hc.prestamo_id=? ORDER BY hc.fecha ASC");
        $stmt->bind_param("i",$p['id']);
        $stmt->execute();
        $p['historial_cesiones']=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else $p['historial_cesiones']=[];
}
unset($p);

header('Content-Type: application/json');
echo json_encode(['prestamos'=>$act]);
