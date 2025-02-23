<?php
require_once '../../config/config.php';

if (isset($_GET['term'])) {
    $term = $_GET['term'] . '%';
    $sql = "SELECT ticker, company_name FROM tickers_acciones WHERE ticker LIKE ? LIMIT 10";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $stmt->bind_result($ticker_acciones, $company_name);

    $results = [];
    while ($stmt->fetch()) {
        $results[] = $ticker_acciones . ' - ' . $company_name;
    }
    $stmt->close();

    echo json_encode($results);
}
