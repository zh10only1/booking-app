<?php
include 'db-connection.php';

// Allow only the frontend at http://localhost:3000 to send requests
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM bookings");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch all bookings data
        $bookings = $result->fetch_all(MYSQLI_ASSOC);
        http_response_code(200); // OK
        echo json_encode(["success" => true, "bookings" => $bookings]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(["success" => false, "message" => "No bookings found."]);
    }

    // Close the statement
    $stmt->close();
} else {
    http_response_code(400); // Bad Request
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

// Close the database connection
$conn->close();
?>