<?php
require_once '../../config/config.php';

if (isset($_GET['term'])) {
    $term = $_GET['term'] . '%';

    $sql = "SELECT ticker, company_name FROM tickers_fondos WHERE ticker LIKE :term LIMIT 10";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':term', $term, PDO::PARAM_STR);
    $stmt->execute();

    $results = $stmt->fetchAll();

    echo json_encode($results);
}
