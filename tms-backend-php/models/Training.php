<?php
class Training {
    private $conn;
    private $table_name = "trainings";

    public $training_id;
    public $name;
    public $age_requirement;
    public $qualification;
    public $country;
    public $region;
    public $place;
    public $profession;
    public $start_time;
    public $end_time;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Kusoma Kozi Zote za Mafunzo
    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY start_time ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // 2. Kuongeza Kozi Mpya ya Mafunzo
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, age_requirement, qualification, country, region, place, profession, start_time, end_time) 
                  VALUES (:name, :age_requirement, :qualification, :country, :region, :place, :profession, :start_time, :end_time)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':age_requirement', $this->age_requirement);
        $stmt->bindParam(':qualification', $this->qualification);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':region', $this->region);
        $stmt->bindParam(':place', $this->place);
        $stmt->bindParam(':profession', $this->profession);
        $stmt->bindParam(':start_time', $this->start_time);
        $stmt->bindParam(':end_time', $this->end_time);

        return $stmt->execute();
    }
}
?>