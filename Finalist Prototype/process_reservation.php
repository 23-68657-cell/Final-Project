<?php
// === 1. CONNECT TO DATABASE ===
$conn = new mysqli("localhost", "root", "", "bat_cave_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// === 2. PROCESS SUBMISSION ===
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get Basic Inputs
    $full_name = htmlspecialchars($_POST['full_name']);
    $sr_code = htmlspecialchars($_POST['sr_code']);
    $date = $_POST['reservation_date'];

    // === 3. SET DEFAULT/STATIC VALUES for DB ===
    $res_type = 'library'; // New reservation type
    $guests = 1; // Each reservation is for one person
    $email = $sr_code . '@g.batstate-u.edu.ph'; // Store SR-Code in email field for now
    $phone = 'N/A';
    $start_time = 8; // Default start time (e.g., 8 AM)
    $end_time = 17; // Default end time (e.g., 5 PM)
    $duration = $end_time - $start_time;
    $equipment = 'N/A';
    $special_request = 'Library Seat Reservation';
    $payment_method = 'N/A';
    $payment_term = 'N/A';
    $payable_amount = 0;
    $total_bill = 0;
    $ref_number = '';
    $proof_path = '';

    // === SERVER-SIDE DAILY CAPACITY CHECK ===
    $daily_limit = 50; // Must match the limit in check_availability.php
    $sql_count = "SELECT COUNT(*) as total_booked FROM reservations WHERE mission_date = ? AND reservation_type = 'library' AND status IN ('approved', 'confirmed', 'pending')";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->bind_param("s", $date);
    $stmt_count->execute();
    $result_count = $stmt_count->get_result()->fetch_assoc();
    $current_booked = $result_count['total_booked'];
    $stmt_count->close();

    if ($current_booked >= $daily_limit) {
        header("Location: gathering_booking.php?status=error&message=Daily capacity reached for this date.");
        exit();
    }
    // === 4. HANDLE UPLOADS ===
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    
    $id_path = "";
    if (!empty($_FILES['id_photo']['name'])) {
        $id_path = $upload_dir . time() . "_id_" . basename($_FILES['id_photo']['name']);
        move_uploaded_file($_FILES['id_photo']['tmp_name'], $id_path);
    }

    // === 5. INSERT & REDIRECT ===
    $sql = "INSERT INTO reservations (reservation_type, full_name, email, phone_number, mission_date, start_time, end_time, duration, guest_count, equipment_addons, special_request, payment_method, payment_term, payable_amount, total_bill, reference_number, proof_image, valid_id_image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'approved')";
    
    $stmt = $conn->prepare($sql);
    // s = string, i = integer, d = double
    $stmt->bind_param("sssssiiiissssddsss", $res_type, $full_name, $email, $phone, $date, $start_time, $end_time, $duration, $guests, $equipment, $special_request, $payment_method, $payment_term, $payable_amount, $total_bill, $ref_number, $proof_path, $id_path);

    if ($stmt->execute()) {
        // SUCCESS: Redirect back to index.php with success flag
        header("Location: gathering_booking.php?status=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>