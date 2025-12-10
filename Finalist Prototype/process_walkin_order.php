<?php

// --- Database Connection ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bat_cave_db"; // Make sure this is your correct database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- File Upload Handling ---
$upload_dir = 'uploads/proofs/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$proof_path = null;
if (isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] == 0) {
    $file_name = time() . '_' . basename($_FILES["proof_image"]["name"]);
    $target_file = $upload_dir . $file_name;
    if (move_uploaded_file($_FILES["proof_image"]["tmp_name"], $target_file)) {
        $proof_path = $target_file;
    }
}

// --- Data Preparation ---
$customer_name = $_POST['full_name'];
$customer_email = $_POST['email'];
$payment_method = $_POST['payment_method'];
$reference_number = isset($_POST['reference_number']) ? $_POST['reference_number'] : null;
$order_items_json = $_POST['order_details'];
$order_items = json_decode($order_items_json, true);

// --- Server-Side Price Calculation (for security) ---
$total_price = 0;
$item_ids = array_map(function($item) {
    // Extract only the numeric ID part
    $id_parts = explode('_', $item['id']);
    return (int)$id_parts[0];
}, $order_items);


if (!empty($item_ids)) {
    $ids_string = implode(',', $item_ids);
    $sql = "SELECT id, price_solo FROM menu_items WHERE id IN ($ids_string)";
    $result = $conn->query($sql);
    
    $prices = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $prices[$row['id']] = $row['price_solo'];
        }
    }

    foreach ($order_items as $item) {
        $id_parts = explode('_', $item['id']);
        $db_id = (int)$id_parts[0];
        if (isset($prices[$db_id])) {
            $total_price += $prices[$db_id] * $item['qty'];
        }
    }
}

// --- SQL Insertion ---
$stmt = $conn->prepare("INSERT INTO product_orders (customer_name, customer_email, order_items, total_price, payment_method, reference_number, proof_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssdsis", $customer_name, $customer_email, $order_items_json, $total_price, $payment_method, $reference_number, $proof_path);

if ($stmt->execute()) {
    // Redirect back to the menu with a success status
    header("Location: 2menu_walkin.php?status=success");
} else {
    // Handle error - you might want to log this
    header("Location: 2menu_walkin.php?status=error");
}

$stmt->close();
$conn->close();
?>