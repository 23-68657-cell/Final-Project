<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bat_cave_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed.']));
}

$response = [
    'reservations' => [],
    'walkin_orders' => [], // Changed from walkin_orders to product_orders
    'stats' => [
        'revenue_today' => 0,
        'revenue_month' => 0,
        'pending' => 0,
        'weekly_revenue' => ['labels' => [], 'data' => []],
        'monthly_revenue' => ['labels' => [], 'data' => []],
        'popular_items' => []
    ]
];

// Handle POST requests for status updates or deletions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $source_table = isset($data['source_table']) ? $data['source_table'] : 'reservations'; // Default to reservations

    if (isset($data['action']) && $data['action'] === 'delete') {
        $table_to_update = ($source_table === 'product_orders') ? 'product_orders' : 'reservations';
        $stmt = $conn->prepare("DELETE FROM $table_to_update WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
        $conn->close();
        exit;
    }

    if (isset($data['status'])) {
        $status = $data['status'];
        $table_to_update = ($source_table === 'product_orders') ? 'product_orders' : 'reservations';
        $column_to_update = ($source_table === 'product_orders') ? 'order_status' : 'status';

        $stmt = $conn->prepare("UPDATE $table_to_update SET $column_to_update = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
        $conn->close();
        exit;
    }
}


// Fetch Reservations
$result = $conn->query("SELECT *, 'reservations' as source_table FROM reservations ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $response['reservations'][] = $row;
}

// Fetch Product Orders (from the new table)
$result = $conn->query("SELECT *, 'product_orders' as source_table FROM product_orders ORDER BY created_at DESC");
while ($row = $result->fetch_assoc()) {
    $response['walkin_orders'][] = $row;
}

// --- STATS CALCULATION ---

// Revenue Today (from both reservations and product orders)
$today = date('Y-m-d');
$rev_today_res = $conn->query("SELECT SUM(payable_amount) as total FROM reservations WHERE status IN ('approved', 'confirmed', 'done') AND DATE(mission_date) = '$today'")->fetch_assoc()['total'] ?? 0;
$rev_today_ord = $conn->query("SELECT SUM(total_price) as total FROM product_orders WHERE order_status IN ('Completed', 'Shipped') AND DATE(created_at) = '$today'")->fetch_assoc()['total'] ?? 0;
$response['stats']['revenue_today'] = $rev_today_res + $rev_today_ord;

// Revenue This Month
$month_start = date('Y-m-01');
$month_end = date('Y-m-t');
$rev_month_res = $conn->query("SELECT SUM(payable_amount) as total FROM reservations WHERE status IN ('approved', 'confirmed', 'done') AND mission_date BETWEEN '$month_start' AND '$month_end'")->fetch_assoc()['total'] ?? 0;
$rev_month_ord = $conn->query("SELECT SUM(total_price) as total FROM product_orders WHERE order_status IN ('Completed', 'Shipped') AND created_at BETWEEN '$month_start' AND '$month_end'")->fetch_assoc()['total'] ?? 0;
$response['stats']['revenue_month'] = $rev_month_res + $rev_month_ord;

// Pending Reservations Count
$response['stats']['pending'] = $conn->query("SELECT COUNT(*) as count FROM reservations WHERE status = 'pending'")->fetch_assoc()['count'] ?? 0;

// Weekly Revenue (Last 7 days)
$weekly_data = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $day_label = date('D', strtotime($date));
    
    $day_rev_res = $conn->query("SELECT SUM(payable_amount) as total FROM reservations WHERE status IN ('approved', 'confirmed', 'done') AND DATE(mission_date) = '$date'")->fetch_assoc()['total'] ?? 0;
    $day_rev_ord = $conn->query("SELECT SUM(total_price) as total FROM product_orders WHERE order_status IN ('Completed', 'Shipped') AND DATE(created_at) = '$date'")->fetch_assoc()['total'] ?? 0;

    $response['stats']['weekly_revenue']['labels'][] = $day_label;
    $response['stats']['weekly_revenue']['data'][] = $day_rev_res + $day_rev_ord;
}

// Monthly Revenue (Last 6 months)
for ($i = 5; $i >= 0; $i--) {
    $date = new DateTime(date('Y-m-01') . " -$i months");
    $month_label = $date->format('M');
    $month_start = $date->format('Y-m-01');
    $month_end = $date->format('Y-m-t');

    $month_rev_res = $conn->query("SELECT SUM(payable_amount) as total FROM reservations WHERE status IN ('approved', 'confirmed', 'done') AND mission_date BETWEEN '$month_start' AND '$month_end'")->fetch_assoc()['total'] ?? 0;
    $month_rev_ord = $conn->query("SELECT SUM(total_price) as total FROM product_orders WHERE order_status IN ('Completed', 'Shipped') AND created_at BETWEEN '$month_start' AND '$month_end'")->fetch_assoc()['total'] ?? 0;

    $response['stats']['monthly_revenue']['labels'][] = $month_label;
    $response['stats']['monthly_revenue']['data'][] = $month_rev_res + $month_rev_ord;
}

// Popular Items
$all_orders_json = $conn->query("SELECT order_items FROM product_orders WHERE order_status != 'Cancelled'");
$item_counts = [];
if ($all_orders_json) {
    while ($row = $all_orders_json->fetch_assoc()) {
        $items = json_decode($row['order_items'], true);
        if (is_array($items)) {
            foreach ($items as $item) {
                $name = $item['name'];
                $qty = (int)$item['qty'];
                if (isset($item_counts[$name])) {
                    $item_counts[$name] += $qty;
                } else {
                    $item_counts[$name] = $qty;
                }
            }
        }
    }
}
arsort($item_counts); // Sort by count descending
$response['stats']['popular_items'] = array_slice($item_counts, 0, 5, true); // Top 5


echo json_encode($response);

$conn->close();
?>