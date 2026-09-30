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
require_once __DIR__ . '/../models/Training.php';

$database = new Database();
$db = $database->getConnection();
$training = new Training($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $training->readAll();
        $trainings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $trainings]);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->name) && !empty($data->start_time) && !empty($data->end_time)) {
            $training->name = $data->name;
            $training->age_requirement = $data->age_requirement ?? null;
            $training->qualification = $data->qualification ?? null;
            $training->country = $data->country ?? null;
            $training->region = $data->region ?? null;
            $training->place = $data->place ?? null;
            $training->profession = $data->profession ?? null;
            $training->start_time = $data->start_time;
            $training->end_time = $data->end_time;

            if ($training->create()) {
                http_response_code(201);
                echo json_encode(["status" => "success", "message" => "Training program created successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Failed to create training program"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Incomplete training details provided"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}
?>