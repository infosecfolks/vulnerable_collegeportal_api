<?php
// fees.php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../utils/validate_role.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['roll_number'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Missing roll number']);
        exit();
    }

    $roll_number = $_GET['roll_number'];

    try {
        $query = "SELECT username, fee_status, scholarship FROM students WHERE roll_number = '$roll_number'";
        $result = $conn->query($query);

        if ($result && $result->num_rows === 1) {
            echo json_encode($result->fetch_assoc());
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['error' => 'Student not found']);
        }
    } catch (mysqli_sql_exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
