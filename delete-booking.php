<?php
include 'db-connection.php';

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['bookingNumber'])) {
    $bookingNumber = $_GET['bookingNumber'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("DELETE FROM bookings WHERE BookingNumber = ?");
    $stmt->bind_param("s", $bookingNumber);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        http_response_code(200); // OK
        echo json_encode(["success" => true, "message" => "Booking deleted successfully."]);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(["success" => false, "message" => "No booking exists against this number."]);
    }

    // Close the statement
    $stmt->close();
} else {
    http_response_code(400); // Bad Request
    echo json_encode(["success" => false, "message" => "Invalid request. Please provide a booking number."]);
}

// Close the database connection
$conn->close();
?>