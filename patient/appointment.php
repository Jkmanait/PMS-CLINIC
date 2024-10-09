<?php
session_start();
include('../configuration/config.php');

// Check if the patient is logged in
if (!isset($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit();
} else {
    $patient_id = $_SESSION['patient_id'];
}

// Handle AJAX request to book the appointment
if (isset($_POST['ajax']) && $_POST['ajax'] == 'book_appointment') {
    // Get the posted values
    $appointment_date = $_POST['date'];
    $appointment_time = $_POST['time'];
    $appointment_reason = $_POST['reason'];
    $appointment_status = 'Pending'; // Default status

    // Prevent booking past dates
    if (strtotime($appointment_date) < strtotime(date('Y-m-d'))) {
        echo json_encode(['status' => 'error', 'message' => 'Cannot book an appointment in the past.']);
        exit();
    }

    // Start transaction to avoid issues with race conditions
    $mysqli->begin_transaction();

    try {
        // Insert the new appointment into `appointment` table
        $insert_appointment_query = "INSERT INTO appointment (patient_id, appointment_date, appointment_time, appointment_reason, appointment_status) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $mysqli->prepare($insert_appointment_query)) {
            $stmt->bind_param('issss', $patient_id, $appointment_date, $appointment_time, $appointment_reason, $appointment_status);
            $stmt->execute();
        } else {
            throw new Exception($mysqli->error);
        }

        // Decrease the slot count in the `appointment_schedule` table
        $update_slots_query = "UPDATE appointment_schedule SET slots = slots - 1 WHERE date = ? AND time = ? AND slots > 0";
        if ($update_stmt = $mysqli->prepare($update_slots_query)) {
            $update_stmt->bind_param('ss', $appointment_date, $appointment_time);
            $update_stmt->execute();

            // If no rows were affected, it means there were no available slots
            if ($update_stmt->affected_rows == 0) {
                throw new Exception("No available slots for the selected time.");
            }
        } else {
            throw new Exception($mysqli->error);
        }

        // Commit transaction
        $mysqli->commit();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        // Rollback the transaction on error
        $mysqli->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit();
}

// Fetch available slots and exceptions from the appointment_schedule table
$query_slots = "SELECT id, date, time, slots, exception_reason FROM appointment_schedule WHERE slots > 0 OR exception_reason IS NOT NULL";
$result_slots = $mysqli->query($query_slots);

$events = [];
while ($row = $result_slots->fetch_assoc()) {
    if ($row['slots'] > 0) {
        // Available slots
        $events[] = [
            'title' => 'Available ' . $row['time'] . ': ' . $row['slots'] . ' slots',
            'start' => $row['date'],
            'allDay' => true,
            'color' => 'green',
            'id' => $row['id']  // 'id' is now selected in the query
        ];
    } else if (!empty($row['exception_reason'])) {
        // Calendar exceptions (holidays, etc.)
        $events[] = [
            'title' => $row['exception_reason'],
            'start' => $row['date'],
            'allDay' => true,
            'color' => 'red'
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("assets/inc/head.php"); ?>
    <title>Book an Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .calendar-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 700px;
            margin: 0 auto;
        }

        #calendar {
            width: 1300px;
            height: 700px;
            margin: auto;
        }
    </style>
</head>
<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php'); ?>
        <!-- end Topbar -->

        <div class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <br>
                            <br>
                            <h3>Book an Appointment</h3>
                            <div class="calendar-container">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    selectable: true,
                    events: <?php echo json_encode($events); ?>,
                    eventClick: function(info) {
                        // Prompt the user for the reason for the appointment
                        let reason = prompt("Please enter the reason for your appointment:");
                        let time = info.event.title.includes('AM') ? 'AM' : 'PM'; // Determine time based on event title

                        if (reason) {
                            // Send AJAX request to book the appointment
                            $.ajax({
                                url: 'appointment.php',
                                type: 'POST',
                                data: {
                                    ajax: 'book_appointment',
                                    date: info.event.startStr.split('T')[0], // Extract date from event start
                                    time: time, // Use determined time
                                    reason: reason
                                },
                                success: function(response) {
                                    var res = JSON.parse(response);
                                    if (res.status === 'success') {
                                        alert('Appointment booked successfully!');
                                        // Reduce the available slots by 1
                                        info.event.setProp('title', info.event.title.replace(/(\d+)/, function(match) {
                                            return parseInt(match) - 1;
                                        }));
                                    } else {
                                        alert('Error: ' + res.message);
                                    }
                                }
                            });
                        }
                    }
                });
                calendar.render();
            });
        </script>

    </div>
</body>
</html>
