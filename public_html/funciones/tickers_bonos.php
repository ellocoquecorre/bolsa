<?php
require_once '../../config/config.php';

if (isset($_GET['term'])) {
    $term = $_GET['term'] . '%';
    $sql = "SELECT ticker_bonos, company_name_bonos FROM ticker_bonos WHERE ticker_bonos LIKE ? LIMIT 10";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $stmt->bind_result($ticker, $company_name);

    $results = [];
    while ($stmt->fetch()) {
        $results[] = [
            'ticker' => $ticker,
            'company_name' => $company_name
        ];
    }
    $stmt->close();

    echo json_encode($results);
}
