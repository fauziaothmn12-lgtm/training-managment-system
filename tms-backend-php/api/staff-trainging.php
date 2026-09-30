<?php
<?php
// 1. Ruhusu Frontend kutoka domain/port yoyote kusoma API hii (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// 2. Weka aina ya majibu kuwa JSON
header("Content-Type: application/json; charset=UTF-8");

// 3. Jibu maombi ya "OPTIONS" (Browser ikifanya jaribio kabla ya kutuma POST)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Tumia __DIR__ kueleza exact path ya faili
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Stafftraining.php';
$database = new Database();
$db = $database->getConnection();
$staffTraining = new StaffTraining($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['staff_id'])) {
            $stmt = $staffTraining->getStaffHistory($_GET['staff_id']);
            $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

            http_response_code(200);
            echo json_encode(["status" => "success", "data" => $history]);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "staff_id parameter is required"]);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->staff_id) && !empty($data->training_id)) {
            $staffTraining->staff_id = $data->staff_id;
            $staffTraining->training_id = $data->training_id;
            $staffTraining->enrollment_date = date('Y-m-d');
            $staffTraining->attendance_status = $data->attendance_status ?? 'pending';

            try {
                if ($staffTraining->register()) {
                    http_response_code(201);
                    echo json_encode(["status" => "success", "message" => "Staff successfully registered for training"]);
                } else {
                    http_response_code(500);
                    echo json_encode(["status" => "error", "message" => "Failed to register staff for training"]);
                }
            } catch (PDOException $e) {
                http_response_code(409); // Conflict (e.g. duplicate enrollment)
                echo json_encode(["status" => "error", "message" => "Staff is already enrolled in this training program"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "staff_id and training_id are required"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}
?>