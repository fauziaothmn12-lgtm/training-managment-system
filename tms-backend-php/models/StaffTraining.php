<?php
class StaffTraining {
    private $conn;
    private $table_name = "staff_training";

    public $staff_id;
    public $training_id;
    public $enrollment_date;
    public $attendance_status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Kumpangia au Mfanyakazi Kujisajili Kwenye Mafunzo
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (staff_id, training_id, enrollment_date, attendance_status) 
                  VALUES (:staff_id, :training_id, :enrollment_date, :attendance_status)";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':staff_id', $this->staff_id);
        $stmt->bindParam(':training_id', $this->training_id);
        $stmt->bindParam(':enrollment_date', $this->enrollment_date);
        $stmt->bindParam(':attendance_status', $this->attendance_status);

        return $stmt->execute();
    }

    // 2. Kuangalia Historia ya Mafunzo ya Mfanyakazi Mmoja
    public function getStaffHistory($staff_id) {
        $query = "SELECT st.*, t.name as training_name, t.start_time, t.end_time, t.place 
                  FROM " . $this->table_name . " st 
                  JOIN trainings t ON st.training_id = t.training_id 
                  WHERE st.staff_id = :staff_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':staff_id', $staff_id);
        $stmt->execute();
        return $stmt;
    }
}
?>