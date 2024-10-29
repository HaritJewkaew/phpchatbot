<?php
include 'db.php';
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
$Email = $data['Email'] ?? null;
$Password = $data['Password'] ?? null;
$Name = $data['Name'] ?? null;

// ตรวจสอบข้อมูลที่รับเข้ามา
if (!$Username || !$Email || !$Password || !$Name) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required']);
    exit;
}

try {
    // เข้ารหัสรหัสผ่าน
    $HashedPassword = password_hash($Password, PASSWORD_BCRYPT);

    // เตรียมคำสั่ง SQL สำหรับเพิ่มผู้ใช้ใหม่
    $stmt = $pdo->prepare("INSERT INTO users (Name, Email, Password, Username) VALUES (?, ?, ?, ?)");
    $stmt->execute([$Name, $Email, $HashedPassword, $Username]);

    // ตอบกลับสำเร็จ
    echo json_encode(['message' => 'User registered successfully', 'userId' => $pdo->lastInsertId()]);
} catch (PDOException $e) {
    // จัดการข้อผิดพลาด
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred during registration']);
}
?>
