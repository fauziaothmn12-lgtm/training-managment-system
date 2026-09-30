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
require_once __DIR__ . '/../models/Depertment.php';

$database = new Database();
$db = $database->getConnection();
$department = new Department($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $department->readAll();
        $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode(["status" => "success", "data" => $departments]);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->name)) {
            $department->name = $data->name;
            $department->manager_id = $data->manager_id ?? null;

            if ($department->create()) {
                http_response_code(201);
                echo json_encode(["status" => "success", "message" => "Department created successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Failed to create department"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Department name is required"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}
?>