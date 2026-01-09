<?php
require_once 'api_config.php';

$competicion_id = $_GET['competicion_id'] ?? 0;

if ($competicion_id <= 0) {
    echo json_encode(["success" => false, "message" => "ID de competición inválido"]);
    exit();
}

// 1. Información de la competición
$sql = "SELECT * FROM competiciones WHERE id = ?";
$competicion = getSingleResult($conn, $sql, [$competicion_id], "i");

if (!$competicion) {
    echo json_encode(["success" => false, "message" => "Competición no encontrada"]);
    exit();
}

// 2. Clasificación
$sql_clasificacion = "SELECT clf.*, e.nombre as equipo_nombre, c.nombre as club_nombre
                      FROM clasificaciones clf
                      JOIN equipos e ON clf.equipo_id = e.id
                      JOIN clubes c ON e.club_id = c.id
                      WHERE clf.competicion_id = ?
                      ORDER BY clf.posicion";

$clasificacion = getResultsWithParams($conn, $sql_clasificacion, [$competicion_id], "i");

// 3. Partidos
$sql_partidos = "SELECT p.*,
                 el.nombre as equipo_local_nombre,
                 ev.nombre as equipo_visitante_nombre,
                 c.nombre as competicion_nombre
                 FROM partidos p
                 JOIN equipos el ON p.equipo_local_id = el.id
                 JOIN equipos ev ON p.equipo_visitante_id = ev.id
                 JOIN competiciones c ON p.competicion_id = c.id
                 WHERE p.competicion_id = ?
                 ORDER BY p.fecha_hora ASC";

$partidos = getResultsWithParams($conn, $sql_partidos, [$competicion_id], "i");

echo json_encode([
    "success" => true,
    "data" => [
        "competicion" => $competicion,
        "clasificacion" => $clasificacion,
        "partidos" => $partidos
    ]
]);

$conn->close();
?>