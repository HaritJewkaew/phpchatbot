<?php
header("Access-Control-Allow-Origin: *"); // Enable CORS for all routes
header("Content-Type: application/json; charset=UTF-8");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/') {
    echo json_encode(["message" => "Hello, World!"]);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/api/data') {
    $result = $conn->query("SELECT * FROM stretcher_register");

    if ($result) {
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode($rows);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/api/timestamp') {
    $result = $conn->query("SELECT ผู้รับ, stretcher_register_send_time, stretcher_register_return_time, stretcher_register_accept_date FROM stretcher_register");

    if ($result) {
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode($rows);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/api/people') {
    $result = $conn->query("SELECT stretcher_register_accept_date, from_depcode, send_depcode, stretcher_work_status_id, stretcher_register_accept_date, ผู้รับ FROM stretcher_register");

    if ($result) {
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode($rows);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/api/home') {
    $result = $conn->query("SELECT ผู้รับ, from_depcode, send_depcode, stretcher_work_status_id, stretcher_register_accept_date FROM stretcher_register");

    if ($result) {
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode($rows);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/api/users') {
    $result = $conn->query("SELECT * FROM users");

    if ($result) {
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode($rows);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/addusers') {
    $data = json_decode(file_get_contents("php://input"), true);

    $ID = $data['ID'];
    $Name = $data['Name'];
    $Role = $data['Role'];

    // Check if user with the same ID already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE ID = ?");
    $stmt->bind_param("s", $displayName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["error" => "User with this ID already exists"]);
    } else {
        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (ชื่อ, ตำแหน่ง) VALUES (?, ?)");
        $stmt->bind_param("ss", $Name, $Role);

        if ($stmt->execute()) {
            echo json_encode(["message" => "User added successfully"]);
        } else {
            echo json_encode(["error" => $stmt->error]);
        }
    }
    $stmt->close();
}

$conn->close();
?>
