<?php
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if roll_number parameter exists in GET request
    if (!isset($_GET['roll_number'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Missing roll number']);
        exit();
    }

    $roll_number = $_GET['roll_number'];

    try {
        // Fetch student details based on roll_number
        $query = "SELECT roll_number, username, attendance, fee_status, cgpa FROM students WHERE roll_number = '$roll_number'";
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
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['roll_number']) && isset($data['newusername'])) {
        // Vulnerable: Update username without proper authentication
        $roll_number = $data['roll_number'];
        $newusername = $data['newusername'];

        try {
            $query = "UPDATE students SET username = '$newusername' WHERE roll_number = '$roll_number'";
            if ($conn->query($query)) {
                echo json_encode(['message' => 'Username updated successfully']);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(['error' => 'Failed to update username']);
            }
        } catch (mysqli_sql_exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
    } elseif (isset($data['roll_number'])) {
        // Original functionality: Create new record if not found
        $roll_number = $data['roll_number'];
        $username = $data['username'] ?? 'Unknown';
        $attendance = $data['attendance'] ?? 0;
        $fee_status = $data['fee_status'] ?? 'Unpaid';
        $cgpa = $data['cgpa'] ?? 0.0;

        try {
            $query = "SELECT * FROM students WHERE roll_number = '$roll_number'";
            $result = $conn->query($query);

            if ($result && $result->num_rows === 1) {
                echo json_encode(['message' => 'Student record found', 'data' => $result->fetch_assoc()]);
            } else {
                $insertQuery = "INSERT INTO students (roll_number, username, attendance, fee_status, cgpa)
                                VALUES ('$roll_number', '$username', '$attendance', '$fee_status', '$cgpa')";
                if ($conn->query($insertQuery)) {
                    echo json_encode([
                        'message' => 'New student record created successfully',
                        'roll_number' => $roll_number,
                        'username' => $username,
                        'attendance' => $attendance,
                        'fee_status' => $fee_status,
                        'cgpa' => $cgpa
                    ]);
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(['error' => 'Failed to create new student record']);
                }
            }
        } catch (mysqli_sql_exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid parameters in POST request']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['roll_number']) || !isset($data['cgpa'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Missing roll number or CGPA']);
        exit();
    }

    $roll_number = $data['roll_number'];
    $cgpa = $data['cgpa'];

    try {
        $query = "UPDATE students SET cgpa = '$cgpa' WHERE roll_number = '$roll_number'";
        if ($conn->query($query)) {
            echo json_encode(['message' => 'CGPA updated successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Failed to update CGPA']);
        }
    } catch (mysqli_sql_exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method']);
}
?>
