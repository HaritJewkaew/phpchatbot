<?php
include 'db.php';

require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// ตั้งค่า JWT Secret Key
$jwtSecret = 'your_jwt_secret_key';

// เชื่อมต่อฐานข้อมูล
try {
    $pdo = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Database connection failed']));
}

// รับข้อมูลจากการร้องขอ POST
$data = json_decode(file_get_contents('php://input'), true);
$Username = $data['Username'] ?? null;
$Password = $data['Password'] ?? null;

// ตรวจสอบข้อมูลที่รับเข้ามา
if (!$Username || !$Password) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required']);
    exit;
}

try {
    // ตรวจสอบว่าผู้ใช้มีอยู่ในฐานข้อมูลหรือไม่
    $stmt = $pdo->prepare("SELECT * FROM users WHERE Username = ?");
    $stmt->execute([$Username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    // เปรียบเทียบรหัสผ่านกับรหัสผ่านที่เก็บไว้ในฐานข้อมูล
    if (!password_verify($Password, $user['Password'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid password']);
        exit;
    }

    // สร้าง JWT token สำหรับ session
    $payload = [
        'userId' => $user['id'],
        'username' => $user['Username'],
        'exp' => time() + 3600 // Token หมดอายุใน 1 ชั่วโมง
    ];
    $token = JWT::encode($payload, $jwtSecret, 'HS256');

    // ตอบกลับสำเร็จพร้อมกับ token
    echo json_encode(['message' => 'Sign-in successful', 'token' => $token]);
} catch (PDOException $e) {
    // จัดการข้อผิดพลาด
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred during sign-in']);
}
?>