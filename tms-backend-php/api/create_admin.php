<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config/database.php';

$database = new Database();
$db = $database->getConnection();

$username = "admin";
$password = "password123";
// Mfumo unatumia BCRYPT ku-hash password vizuri
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Safisha admin wa zamani kama yupo
$stmt_del = $db->prepare("DELETE FROM users WHERE username = :username");
$stmt_del->execute([':username' => $username]);

// Ingiza Admin Mpya mwenye hash sahihi
$query = "INSERT INTO users (name, username, email, phone_number, password, role) 
          VALUES ('System Admin', :username, 'admin@tms.com', '0712345678', :password, 'admin')";

$stmt = $db->prepare($query);

if ($stmt->execute([':username' => $username, ':password' => $hashed_password])) {
    echo json_encode([
        "status" => "success", 
        "message" => "Admin created successfully! You can now login.",
        "credentials" => [
            "username" => "admin",
            "password" => "password123"
        ]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to create admin"]);
}
?>
