<?php
require_once '../../config/config.php';

$term = $_GET['term'] ?? '';

if (!empty($term)) {
    $sql = "SELECT ticker, company_name FROM tickers_cedears WHERE ticker LIKE ? OR company_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $like_term = "%$term%";
    $stmt->bind_param("ss", $like_term, $like_term);
    $stmt->execute();
    $result = $stmt->get_result();

    $tickers = [];
    while ($row = $result->fetch_assoc()) {
        $tickers[] = $row['ticker'] . ' - ' . $row['company_name'];
    }

    echo json_encode($tickers);
}

$stmt->close();
$conn->close();
