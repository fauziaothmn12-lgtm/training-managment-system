<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// Kupokea JSON Data inayotoka Frontend
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->username) && !empty($data->password)) {
    $found_user = $user->getUserByUsername($data->username);

    if ($found_user && password_verify($data->password, $found_user['password'])) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Login successful",
            "user" => [
                "user_id" => $found_user['user_id'],
                "name" => $found_user['name'],
                "username" => $found_user['username'],
                "email" => $found_user['email'],
                "role" => $found_user['role']
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Invalid username or password"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Username and password are required"]);
}
?>