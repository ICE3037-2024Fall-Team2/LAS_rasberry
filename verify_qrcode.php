<?php
session_start();
require 'db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $reservation_id = $input['reservation_id'];

    $sql = "SELECT * FROM reservations WHERE reservation_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $reservation = $result->fetch_assoc();
        $currentDate = new DateTime();
        $reservationDate = new DateTime($reservation['date']);
        $reservationTime = DateTime::createFromFormat('H:i', $reservation['time']);


        if ($reservationDate->format('Y-m-d') === $currentDate->format('Y-m-d')) {
            
            $currentTime = new DateTime();
            $interval = abs($reservationTime->getTimestamp() - $currentTime->getTimestamp());
            if ($interval <= 300) {
                $update_sql = "UPDATE reservations SET verified = TRUE WHERE reservation_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("s", $reservation_id);
                if ($update_stmt->execute()) {
                    echo json_encode(['status' => 'success']);
                    exit();
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update reservation.']);
                    exit();
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'It is not the right time for this reservation.']);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Reservation is not for today.']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Reservation not found.']);
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
