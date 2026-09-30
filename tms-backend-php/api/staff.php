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

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Staff.php';

$database = new Database();
$db = $database->getConnection();
$staff = new Staff($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $staff->readAll();
        $staff_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $staff_list]);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (
            !empty($data->department_id) && 
            !empty($data->full_name) && 
            !empty($data->email) && 
            !empty($data->dob) && 
            !empty($data->phone_number) && 
            !empty($data->qualification)
        ) {
            $staff->department_id = $data->department_id;
            $staff->full_name = $data->full_name;
            $staff->address = $data->address ?? null;
            $staff->email = $data->email;
            $staff->dob = $data->dob;
            $staff->phone_number = $data->phone_number;
            $staff->qualification = $data->qualification;

            if ($staff->create()) {
                http_response_code(201);
                echo json_encode(["status" => "success", "message" => "Staff added successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Failed to add staff"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Incomplete staff details provided"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}
?>