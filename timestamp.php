<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); 
header("Access-Control-Allow-Headers: Content-Type, Authorization"); 

// เชื่อมต่อฐานข้อมูล
include 'db.php'; 

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// จัดการการร้องขอ GET
switch ($method) {
    case 'GET':
        handleGet($pdo);
        break;
    default:
        http_response_code(405); // 405 Method Not Allowed
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function handleGet($pdo) {
    try {

        $sql = "SELECT sr.ผู้รับ , srs.R_name, stretcher_register_send_time, stretcher_register_return_time, stretcher_register_accept_date FROM stretcher_register sr LEFT JOIN stretcher_request_staff srs ON sr.ผู้รับ = srs.Line_name";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();


        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


        echo json_encode($result);

    } catch (PDOException $e) {

        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch data', 'details' => $e->getMessage()]);
    }
}
?>