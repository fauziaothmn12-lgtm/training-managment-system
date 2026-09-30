<?php
class Department {
    private $conn;
    private $table_name = "departments";

    public $department_id;
    public $name;
    public $manager_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Kusoma Idara Zote (Pamoja na jina la Manager)
    public function readAll() {
        $query = "SELECT d.department_id, d.name, d.created_at, u.name as manager_name 
                  FROM " . $this->table_name . " d 
                  LEFT JOIN users u ON d.manager_id = u.user_id 
                  ORDER BY d.department_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // 2. Kuongeza Idara Mpya
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, manager_id) VALUES (:name, :manager_id)";
        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->manager_id = !empty($this->manager_id) ? $this->manager_id : null;

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':manager_id', $this->manager_id);

        return $stmt->execute();
    }
}
?>