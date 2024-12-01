<?php
// marks.php

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check for the roll_number parameter in the GET request
    if (!isset($_GET['roll_number'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Missing roll number']);
        exit();
    }

    $roll_number = $_GET['roll_number']; // Directly use user input without sanitization

    try {
        // Query to fetch student details based on roll_number
        $query = "SELECT username, cgpa FROM students WHERE roll_number = '$roll_number'";
        $result = $conn->query($query); // Vulnerable to SQL injection

        if ($result && $result->num_rows === 1) {
            // Return the student data as JSON
            echo json_encode($result->fetch_assoc());
        } else {
            // Student not found
            http_response_code(404); // Not Found
            echo json_encode(['error' => 'Student not found']);
        }
    } catch (mysqli_sql_exception $e) {
        // Handle database errors
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Handle PUT request to update CGPA using request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract roll_number and cgpa from the request body
    $roll_number = $data['roll_number'] ?? null; // Directly use user input without sanitization
    $cgpa = $data['cgpa'] ?? null; // Directly use user input without sanitization

    // Validate roll_number and cgpa
    if (!$roll_number || !$cgpa) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Missing roll number or CGPA']);
        exit();
    }

    try {
        // Query to update the CGPA field for the specified roll_number
        $query = "UPDATE students SET cgpa = '$cgpa' WHERE roll_number = '$roll_number'";
        $conn->query($query); // Vulnerable to SQL injection

        echo json_encode(['message' => 'CGPA updated successfully']);
    } catch (mysqli_sql_exception $e) {
        // Handle database errors
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
