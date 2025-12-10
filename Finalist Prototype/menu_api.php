<?php
header('Content-Type: application/json');
include 'db_connect.php';

$method = $_SERVER['REQUEST_METHOD'];

// === GET: FETCH ALL ITEMS ===
if ($method === 'GET') {
    $result = $conn->query("SELECT * FROM menu_items ORDER BY category, name");
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    echo json_encode($items);
    exit();
}

// === POST: ADD or UPDATE ITEM ===
if ($method === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'];
    $category = $_POST['category'];
    $desc = $_POST['description'];
    $price_solo = $_POST['price_solo'];
    $price_group = $_POST['price_group'] ?? 0;
    $sizes = $_POST['sizes'] ?? null; // Get the sizes field
    // $is_sizing = $_POST['is_sizing'] ?? 0; // This field seems deprecated/unused with the new 'sizes' column
    $addons = $_POST['addons'] ?? '[]';

    // Handle Image Upload
    $image_path = $_POST['existing_image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/menu/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true); // Ensure directory exists
        $file_name = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $file_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = $target_file;
        }
    }

    if ($id) {
        // Update
        $stmt = $conn->prepare("UPDATE menu_items SET name=?, category=?, price_solo=?, price_group=?, description=?, image_path=?, sizes=?, addons=? WHERE id=?");
        $stmt->bind_param("ssddssssi", $name, $category, $price_solo, $price_group, $desc, $image_path, $sizes, $addons, $id);
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO menu_items (name, category, price_solo, price_group, description, image_path, sizes, addons) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssddssss", $name, $category, $price_solo, $price_group, $desc, $image_path, $sizes, $addons);
    }

    if ($stmt->execute()) echo json_encode(['success' => true]);
    else echo json_encode(['success' => false, 'error' => $stmt->error]);
    exit();
}

// === DELETE ITEM ===
if ($method === 'DELETE') {
    // Read raw body for DELETE ID
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $conn->query("DELETE FROM menu_items WHERE id=$id");
    echo json_encode(['success' => true]);
    exit();
}
?>