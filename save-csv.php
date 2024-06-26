<?php
include 'db-connection.php';

header('Access-Control-Allow-Origin: https://departures.summertimes.com.cy');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');


function parseDate($dateStr) {
    $formats = ['d-M-y', 'd M Y', 'd/M/Y'];
    foreach ($formats as $format) {
        $dateObj = DateTime::createFromFormat($format, $dateStr);
        if ($dateObj !== false) {
            return $dateObj;
        }
    }
    return false; // Return false if none of the formats match
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == UPLOAD_ERR_OK) {
    // Check if the file type is CSV
    $fileType = mime_content_type($_FILES['csv_file']['tmp_name']);

    if ($fileType == 'text/csv') {
        // Open uploaded CSV file with read-only mode
        $csvFile = fopen($_FILES['csv_file']['tmp_name'], 'r');

        // Skip the first line
        fgetcsv($csvFile);

        // Prepare the SQL statements
        $selectStmt = $conn->prepare("SELECT * FROM bookings WHERE BookingNumber = ?");
        $updateStmt = $conn->prepare("UPDATE bookings SET DepartureDate=?, TO_Name=?, FlightNumber=?, FlightDepTime=?, PickUpTime=?, PickupDate=?, Hotel=?, PickupPoint=?, ServiceType=?, FlyFrom=?, FlyTo=?, Lang=? WHERE BookingNumber=?");
        $insertStmt = $conn->prepare("INSERT INTO bookings (DepartureDate, BookingNumber, TO_Name, FlightNumber, FlightDepTime, PickUpTime, PickupDate, Hotel, PickupPoint, ServiceType, FlyFrom, FlyTo, Lang) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Parse data from CSV file line by line
        while (($line = fgetcsv($csvFile)) !== FALSE) {
            // Trim and validate date strings
            $departureDateStr = trim($line[0]);
            $pickupDateStr = trim($line[6]);

            // Convert date strings to Y-m-d format
            $departureDateObj = parseDate($departureDateStr);
            $pickupDateObj = parseDate($pickupDateStr);

            if ($departureDateObj !== false && $pickupDateObj !== false) {
                $departureDate = $departureDateObj->format('Y-m-d');
                $pickupDate = $pickupDateObj->format('Y-m-d');
            } else {
                // Handle date parsing error
                fclose($csvFile);
                http_response_code(400); // Bad Request
                echo json_encode(["success" => false, "message" => "Invalid date format in CSV file."]);
                exit;
            }

            $bookingNumber = $line[1];
            $toName = $line[2];
            $flightNumber = $line[3];
            $flightDepTime = $line[4];
            $pickUpTime = $line[5];
            $hotel = $line[7];
            $pickupPoint = $line[8];
            $serviceType = $line[9];
            $flyFrom = $line[10];
            $flyTo = $line[11];
            $language = $line[12];

            // Check if bookingNumber already exists
            $selectStmt->bind_param("s", $bookingNumber);
            $selectStmt->execute();
            $result = $selectStmt->get_result();
            if ($result->num_rows > 0) {
                // Update existing record
                $updateStmt->bind_param("sssssssssssss", $departureDate, $toName, $flightNumber, $flightDepTime, $pickUpTime, $pickupDate, $hotel, $pickupPoint, $serviceType, $flyFrom, $flyTo, $language, $bookingNumber);
                $updateStmt->execute();
            } else {
                // Insert new record
                $insertStmt->bind_param("sssssssssssss", $departureDate, $bookingNumber, $toName, $flightNumber, $flightDepTime, $pickUpTime, $pickupDate, $hotel, $pickupPoint, $serviceType, $flyFrom, $flyTo, $language);
                $insertStmt->execute();
            }
        }

        // Close prepared statements
        $selectStmt->close();
        $updateStmt->close();
        $insertStmt->close();

        // Close opened CSV file
        fclose($csvFile);

        // Delete CSV file after processing
        unlink($_FILES['csv_file']['tmp_name']);

        // Delete records older than 5 days from the current date
        $deleteStmt = $conn->prepare("DELETE FROM bookings WHERE DepartureDate < CURDATE() - INTERVAL 5 DAY");
        $deleteStmt->execute();
        $deleteStmt->close();

        http_response_code(200);
        echo json_encode(["success" => true, "message" => "CSV file successfully processed."]);
    } else {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid file format. Only CSV files are allowed."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Error uploading file. Please try again."]);
}

// Close the database connection
$conn->close();
?>