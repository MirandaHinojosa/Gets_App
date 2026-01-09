<?php
require_once 'api_config.php';

$competicion_id = $_GET['competicion_id'] ?? 0;

$sql = "SELECT c.*, e.nombre as equipo_nombre, cl.nombre as club_nombre
        FROM clasificaciones clf
        JOIN equipos e ON clf.equipo_id = e.id
        JOIN clubes cl ON e.club_id = cl.id
        JOIN competiciones c ON clf.competicion_id = c.id
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

echo json_encode($clasificacion);
$stmt->close();
$conn->close();
?>