<?php
class User {
    private $conn;
    private $table_name = "users";

    public $user_id;
    public $name;
    public $username;
    public $email;
    public $phone_number;
    public $password;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Tafuta User kwa kutumia Username (Inatumika kwenye Login API)
    public function getUserByUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        
        // Kuzuia SQL Injection
        $username = htmlspecialchars(strip_tags($username));
        $stmt->bindParam(':username', $username);
        
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2. Kuongeza User Mpya (Kama mkitaka kuweka Sajili/Register za Watumiaji)
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, username, email, phone_number, password, role) 
                  VALUES (:name, :username, :email, :phone_number, :password, :role)";
        
        $stmt = $this->conn->prepare($query);

        // Hash password kabla ya kuihifadhi kwenye Database
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role);

        return $stmt->execute();
    }
}
?>