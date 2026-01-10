<?php
require_once 'api_config.php';

$competicion_id = $_GET['competicion_id'] ?? 0;

$sql = "SELECT clf.*, e.nombre as equipo_nombre, c.nombre as club_nombre
        FROM clasificaciones clf
        JOIN equipos e ON clf.equipo_id = e.id
        JOIN clubes c ON e.club_id = c.id
        WHERE clf.competicion_id = ?
        ORDER BY clf.posicion ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $competicion_id);
$stmt->execute();
$result = $stmt->get_result();

$clasificacion = [];
while ($row = $result->fetch_assoc()) {
    $clasificacion[] = $row;
}
echo json_encode([
    "success" => true,
    "data" => $clasificacion,
    "message" => count($clasificacion) . " equipos en clasificación"
]);

$stmt->close();
$conn->close();
?>
