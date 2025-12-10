<?php
// 1. SILENCE ERRORS (Crucial for JSON)
error_reporting(E_ALL);
ini_set('display_errors', 0); 

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// 2. DATABASE CONFIGURATION
$host = "localhost";
$username = "root";  // Default for XAMPP
$password = "";      // Default for XAMPP (leave empty)
$dbname = "bat_cave_db";

// 3. CONNECT
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// 4. HANDLE REQUESTS
$method = $_SERVER['REQUEST_METHOD'];

// --- GET: Fetch for Homepage ---
if ($method === 'GET') {
    $sql = "SELECT id, title, type, content, created_at FROM announcements ORDER BY created_at DESC";
    $result = $conn->query($sql);

    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    echo json_encode($data);
}

// --- POST: Create or Delete for Admin ---
elseif ($method === 'POST') {
    // Read JSON input (for Create)
    $jsonInput = file_get_contents('php://input');
    $data = json_decode($jsonInput, true);

    // Case A: Create New
    if (isset($data['title'])) {
        $stmt = $conn->prepare("INSERT INTO announcements (title, type, content) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $data['title'], $data['type'], $data['content']);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        $stmt->close();
    } 
    // Case B: Delete (sent as FormData)
    elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = intval($_POST['id']);
        $sql = "DELETE FROM announcements WHERE id = $id";
        if ($conn->query($sql)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
    }
}

$conn->close();
?>