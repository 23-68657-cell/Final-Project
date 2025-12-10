<?php
include 'db_connect.php';

// Start a session to store error messages if needed
session_start();

function redirect_with_error($message) {
    $_SESSION['error_message'] = $message;
    // Redirect back to the form page
    header("Location: gathering_booking.php?status=error");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- 1. Retrieve and Sanitize Form Data ---
    $full_name = trim($_POST['full_name']);
    $sr_code = trim($_POST['sr_code']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $reservation_date = $_POST['mission_date']; // This comes from the form

    // --- 2. Validate Inputs ---
    if (empty($full_name) || empty($sr_code) || empty($email) || empty($reservation_date)) {
        redirect_with_error("Please fill out all required fields.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_with_error("Invalid email format.");
    }

    // --- 3. Handle File Upload ---
    $upload_path = ''; // Initialize path
    if (isset($_FILES['valid_id_image']) && $_FILES['valid_id_image']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_extension = pathinfo($_FILES['valid_id_image']['name'], PATHINFO_EXTENSION);
        $unique_filename = uniqid('id_', true) . '.' . $file_extension;
        $upload_path = $upload_dir . $unique_filename;

        // Basic security checks
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($file_extension), $allowed_types)) {
            redirect_with_error("Invalid file type. Only JPG, PNG, and GIF are allowed.");
        }
        if ($_FILES['valid_id_image']['size'] > 5 * 1024 * 1024) { // 5MB limit
            redirect_with_error("File is too large. Maximum size is 5MB.");
        }

        if (!move_uploaded_file($_FILES['valid_id_image']['tmp_name'], $upload_path)) {
            redirect_with_error("Failed to upload the ID photo.");
        }
    } else {
        redirect_with_error("A valid ID photo is required.");
    }

    // --- 4. Insert into Database ---
    $sql = "INSERT INTO library_reservations (full_name, sr_code, email, phone_number, reservation_date, valid_id_image, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $full_name, $sr_code, $email, $phone_number, $reservation_date, $upload_path);

    if ($stmt->execute()) {
        header("Location: gathering_booking.php?status=success");
    } else {
        redirect_with_error("Failed to save reservation: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>