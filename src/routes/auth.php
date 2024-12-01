<?php
// auth.php

require_once __DIR__ . '/../config.php';

error_reporting(E_ALL); // Enable error reporting
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        $token = base64_encode($username . ':' . $password);

        $response = [
            'message' => 'Authentication successful',
            'auth_token' => 'Bearer ' . $token,
            'role' => $user['role']
        ];

        // Fetch roll number if the user is a student
        if (strtolower($user['role']) === 'student') {
            $studentQuery = "SELECT roll_number FROM students WHERE username = '$username'";
            $studentResult = $conn->query($studentQuery);

            if ($studentResult === false) {
                http_response_code(500);
                echo json_encode(['error' => 'Database error: ' . $conn->error]);
                exit();
            }

            if ($studentResult->num_rows === 1) {
                $student = $studentResult->fetch_assoc();
                $response['roll_number'] = $student['roll_number'];
            } else {
                $response['roll_number'] = null;
            }
        }

        echo json_encode($response);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    }
}
?>
