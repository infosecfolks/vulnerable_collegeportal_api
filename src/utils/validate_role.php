<?php
// validate_role.php

function validate_role($allowed_roles = []) {
    // Check if the Authorization header is set
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'Authorization header missing']);
        exit();
    }

    // Extract the Bearer token from the Authorization header
    $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
    if (strpos($auth_header, 'Bearer ') !== 0) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid Authorization format']);
        exit();
    }

    $encoded_token = substr($auth_header, 7); // Remove 'Bearer ' prefix
    $decoded_token = base64_decode($encoded_token);

    if (!$decoded_token) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid Base64 encoding']);
        exit();
    }

    // Extract username and password from the decoded token
    list($username, $password) = explode(":", $decoded_token, 2);

    // Validate the user credentials (implement your own validate_user function)
    if (!validate_user($username, $password)) {
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'Invalid credentials']);
        exit();
    }

    // Check if the user has a valid role
    if (!in_array($role, $allowed_roles)) {
        http_response_code(403); // Forbidden
        echo json_encode(['error' => 'Insufficient permissions']);
        exit();
    }
}
?>
