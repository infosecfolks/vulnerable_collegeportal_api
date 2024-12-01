<?php
require_once __DIR__ . '/../config.php';

// Handle GET Requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['roll_number'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Missing roll number']);
        exit();
    }

    $roll_number = $_GET['roll_number'];

    try {
        $query = "SELECT username, attendance FROM students WHERE roll_number = '$roll_number'";
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

// Handle POST Requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate roll_number
    if (!isset($data['roll_number']) || empty($data['roll_number'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Missing roll number']);
        exit();
    }

    $roll_number = $data['roll_number'];
    $attendance = isset($data['attendance']) ? (int)$data['attendance'] : null; // Convert to integer
    $cgpa = isset($data['cgpa']) ? (float)$data['cgpa'] : null; // Convert to float
    $fee_status = isset($data['fee_status']) ? $data['fee_status'] : null; // Leave as string
    $scholarship = isset($data['scholarship']) ? (int)$data['scholarship'] : null; // Convert to integer

    try {
        // Mass assignment query
        $query = "UPDATE students SET 
            attendance = " . ($attendance !== null ? "'$attendance'" : "attendance") . ", 
            cgpa = " . ($cgpa !== null ? "'$cgpa'" : "cgpa") . ", 
            fee_status = " . ($fee_status !== null ? "'$fee_status'" : "fee_status") . ",
            scholarship = " . ($scholarship !== null ? "'$scholarship'" : "scholarship") . "
            WHERE roll_number = '$roll_number'";

        if ($conn->query($query)) {
            echo json_encode(['message' => 'Student information updated successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Failed to update student information']);
        }
    } catch (mysqli_sql_exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
