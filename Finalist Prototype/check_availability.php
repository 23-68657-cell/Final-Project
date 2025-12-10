<?php
include 'db_connect.php';

// 1. PREVENT BROWSER CACHING
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header('Content-Type: application/json');

if (!isset($_GET['date'])) {
    echo json_encode(['error' => 'Date parameter is required.']);
    exit;
}

$date = $_GET['date'];
$total_capacity = 20;

// --- MODE 1: Check a specific time slot (existing functionality) ---
if (isset($_GET['start']) && isset($_GET['end'])) {
    $start = (int)$_GET['start'];
    $end = (int)$_GET['end'];

    $sql = "SELECT SUM(guest_count) as total_booked, COUNT(CASE WHEN reservation_type = 'gathering' THEN 1 END) as gathering_count FROM reservations WHERE mission_date = ? AND status IN ('approved', 'confirmed', 'pending') AND (start_time < ? AND end_time > ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) { echo json_encode(['available' => 0, 'error' => $conn->error]); exit; }
    $stmt->bind_param("sii", $date, $end, $start);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    $booked = $result['total_booked'] ? (int)$result['total_booked'] : 0;
    if ($result['gathering_count'] > 0) { $booked = $total_capacity; }

    $available = $total_capacity - $booked;
    echo json_encode(['available' => max(0, $available), 'booked' => $booked]);

// --- MODE 2: Get availability for the entire day (new functionality) ---
} else {
    $daily_availability = [];
    $operating_hours_start = 8; // e.g., 8 AM
    $operating_hours_end = 17;   // e.g., 5 PM (hour 17)
    
    // --- NEW: Fetch dynamic daily limit ---
    $daily_limit = 50; // Default limit
    $limit_stmt = $conn->prepare("SELECT capacity FROM daily_limits WHERE date = ?");
    $limit_stmt->bind_param("s", $date);
    $limit_stmt->execute();
    $limit_result = $limit_stmt->get_result();
    if ($limit_row = $limit_result->fetch_assoc()) {
        $daily_limit = (int)$limit_row['capacity'];
    }
    $limit_stmt->close();

    // Fetch all relevant reservations for the day in one go
    // Note: This query fetches all reservation types. We'll filter for 'library' type for daily count.
    // The 'total_capacity' (20) is assumed to be the number of seats available in the library at any given hour.
    // The 'daily_limit' (50) is the total number of unique students who can book for the day.
    // This distinction is important for the informational hourly display vs. the daily booking limit.
    
    // First, get the count of existing library reservations for the day
    $sql_daily_count = "SELECT COUNT(*) as total_library_reservations FROM library_reservations WHERE reservation_date = ? AND status IN ('approved', 'confirmed', 'pending')";
    $stmt_daily_count = $conn->prepare($sql_daily_count);
    $stmt_daily_count->bind_param("s", $date);
    $stmt_daily_count->execute();
    $daily_reservations_count = $stmt_daily_count->get_result()->fetch_assoc()['total_library_reservations'];
    $stmt_daily_count->close();

    // Now, fetch all reservations (including non-library types) to calculate hourly availability
    $sql = "SELECT start_time, end_time, guest_count, reservation_type FROM reservations WHERE mission_date = ? AND status IN ('approved', 'confirmed', 'pending')";
    $stmt = $conn->prepare($sql);
    if (!$stmt) { echo json_encode(['error' => $conn->error]); exit; }
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $reservations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Calculate hourly availability (informational display)
    for ($hour = $operating_hours_start; $hour <= $operating_hours_end; $hour++) {
        $start_check = $hour;
        $end_check = $hour + 1;
        $booked_for_hour = 0;
        $is_exclusive = false;

        foreach ($reservations as $res) {
            if ($res['start_time'] < $end_check && $res['end_time'] > $start_check) {
                if ($res['reservation_type'] === 'gathering') { $is_exclusive = true; break; }
                // For library, each reservation is 1 guest. For other types, it might be different.
                // We sum guest_count to reflect total people occupying seats for this hour.
                $booked_for_hour += (int)$res['guest_count']; 
            }
        }

        $available_spots = $total_capacity - $booked_for_hour; // total_capacity is 20 from global scope
        $daily_availability[$hour] = [
            'available' => max(0, $available_spots),
            'is_exclusive' => $is_exclusive // Will typically be false for library context
        ];
    }

    echo json_encode([
        'daily_schedule' => $daily_availability,
        'daily_reservations_count' => $daily_reservations_count,
        'daily_limit' => $daily_limit
    ]);
}

$conn->close();
?>