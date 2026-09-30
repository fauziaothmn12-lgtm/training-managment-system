<?php
class Staff {
    private $conn;
    private $table_name = "staff";

    public $staff_id;
    public $department_id;
    public $full_name;
    public $address;
    public $email;
    public $dob;
    public $phone_number;
    public $qualification;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Kusoma Wafanyakazi Wote na Idara Zao
    public function readAll() {
        $query = "SELECT s.*, d.name as department_name 
                  FROM " . $this->table_name . " s 
                  LEFT JOIN departments d ON s.department_id = d.department_id 
                  ORDER BY s.staff_id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // 2. Kuongeza Mfanyakazi Mpya
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (department_id, full_name, address, email, dob, phone_number, qualification) 
                  VALUES (:department_id, :full_name, :address, :email, :dob, :phone_number, :qualification)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':department_id', $this->department_id);
        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':dob', $this->dob);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':qualification', $this->qualification);

        return $stmt->execute();
    }
}
?>