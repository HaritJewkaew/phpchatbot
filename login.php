<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit; // Exit for preflight request
}

require 'db.php'; // Database connection
require 'jwt_helper.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'POST':
        handleLogin($pdo, $input);
        break;
    default:
        http_response_code(405); // 405 Method Not Allowed
        echo json_encode(['error' => 'Invalid request method']);
        break;
}

function handleLogin($pdo, $input) {
    try {
        $username = $input['Username'] ?? null;
        $password = $input['Password'] ?? null;

        if (!$username || !$password) {
            http_response_code(400);
            echo json_encode(['error' => 'Username and password are required']);
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE Username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['Password'])) {
            http_response_code(401); // Unauthorized
            echo json_encode(['error' => 'Invalid username or password']);
            exit;
        }

        $jwtSecret = "supermaneatenbycat";
        $payload = [
            'userId' => $user['ID'],
            'username' => $user['Username'],
            'exp' => time() + (1440 * 60)
        ];

        $token = generateJWT($payload, $jwtSecret);

        $validatedData = validateJWT($token, $jwtSecret);

        echo json_encode(['message' => 'Login successful', 'token' => $token]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred during login', 'details' => $e->getMessage()]);
    }
}
