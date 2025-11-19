<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ordering_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data safely
$gsuite = $_POST['gsuite'] ?? '';
$sr_code = $_POST['sr_code'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$course_section = $_POST['course_section'] ?? '';
$year_level = $_POST['year'] ?? '';
$birthdate = $_POST['birthdate'] ?? '';
$gender = $_POST['gender'] ?? '';
$password_raw = $_POST['password'] ?? '';

// Check if password is missing
if (empty($password_raw)) {
  die("Error: Password cannot be empty.");
}

$password = password_hash($password_raw, PASSWORD_DEFAULT);

// ✅ Use prepared statements (for safety)
$stmt = $conn->prepare("INSERT INTO users (gsuite, sr_code, fullname, course_section, year_level, birthdate, gender, password)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $gsuite, $sr_code, $fullname, $course_section, $year_level, $birthdate, $gender, $password);

// Execute
if ($stmt->execute()) {
  echo "✅ Account created successfully! <a href='../Login/login.html'>Login here</a>";
} else {
  echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
