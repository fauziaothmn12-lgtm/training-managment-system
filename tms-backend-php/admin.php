<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config/database.php';

$database = new Database();
$db = $database->getConnection();

$username = "admin";
$password = "password123";
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Kufuta wa zamani
$stmt_del = $db->prepare("DELETE FROM users WHERE username = :username");
$stmt_del->execute([':username' => $username]);

// Kuingiza Admin Mpya
$query = "INSERT INTO users (name, username, email, phone_number, password, role) 
          VALUES ('System Admin', :username, 'admin@tms.com', '0712345678', :password, 'admin')";

$stmt = $db->prepare($query);

if ($stmt->execute([':username' => $username, ':password' => $hashed_password])) {
    echo json_encode(["status" => "success", "message" => "Admin created successfully! Username: admin, Password: password123"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to create admin"]);
}
?>