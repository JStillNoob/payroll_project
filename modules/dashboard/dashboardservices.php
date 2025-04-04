<?php
session_start();
include_once 'startup.php'; // Database connection

// Ensure $conn is defined and connected
$conn = $GLOBALS['conn'];

if (!$conn) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed."
    ]);
    exit;
}

$query = "SELECT * FROM EmployeeDashboard";
$result = mysqli_query($conn, $query);

if ($result) {
    $dashboardData = mysqli_fetch_assoc($result);
    if ($dashboardData) {
        echo json_encode([
            "success" => true,
            "data" => $dashboardData
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "No data found."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to fetch data."
    ]);
}
?>
