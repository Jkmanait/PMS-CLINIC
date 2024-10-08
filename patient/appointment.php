<?php
session_start();
include('../configuration/config.php');

// Check if the patient is logged in and patient_id is set
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

    // SQL query to insert the appointment data
    $query = "INSERT INTO appointments (patient_id, appointment_date, appointment_time, appointment_reason, appointment_status)
              VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param('issss', $patient_id, $appointment_date, $appointment_time, $appointment_reason, $appointment_status);
        if ($stmt->execute()) {
            // Decrease the slot count in the appointment_slots table
            $update_slots_query = "UPDATE appointment_slots SET slots = slots - 1 WHERE date = ? AND time = ?";
            if ($update_stmt = $mysqli->prepare($update_slots_query)) {
                $update_stmt->bind_param('ss', $appointment_date, $appointment_time);
                $update_stmt->execute();
            }

            // Insert into patient_appointments table for admin tracking
            $log_query = "INSERT INTO patient_appointments (patient_id, appointment_date, appointment_time, appointment_reason, appointment_status)
                          VALUES (?, ?, ?, ?, ?)";
            if ($log_stmt = $mysqli->prepare($log_query)) {
                $log_stmt->bind_param('issss', $patient_id, $appointment_date, $appointment_time, $appointment_reason, $appointment_status);
                $log_stmt->execute();
            }

            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => $mysqli->error]);
    }
    exit();
}

// Fetch available appointment slots (AM/PM)
$query_slots = "SELECT date, time, slots FROM appointment_slots WHERE slots > 0";
$result_slots = $mysqli->query($query_slots);

// Array to store appointment slots
$appointment_slots = [];
while ($row = $result_slots->fetch_assoc()) {
    $appointment_slots[] = [
        'date' => $row['date'],
        'time' => $row['time'],
        'slots' => $row['slots']
    ];
}

// Fetch holiday exceptions
$query_holidays = "SELECT date, reason FROM calendar_exceptions";
$result_holidays = $mysqli->query($query_holidays);

// Array to store holiday exceptions
$holidays = [];
while ($row = $result_holidays->fetch_assoc()) {
    $holidays[] = [
        'date' => $row['date'],
        'reason' => $row['reason']
    ];
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
            width: 1300px; /* Increase width of the calendar */
            height: 700px; /* Set height to make it taller */
            margin: auto;
        }

        .fc-header-toolbar {
            font-size: 16px;
        }

        .fc-daygrid-day-number {
            font-size: 14px;
        }
    </style>
</head>
<body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <?php include('assets/inc/nav.php');?>
            <!-- end Topbar -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                 <!-- Start Content-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-19">
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
                events: [
                    <?php foreach ($appointment_slots as $slot) : ?>
                    {
                        title: 'Available (<?php echo $slot['time'] === 'AM' ? 'AM' : 'PM'; ?>): <?php echo $slot['slots']; ?> slots',
                        start: '<?php echo $slot['date'] . 'T' . ($slot['time'] == 'AM' ? '09:00' : '13:00'); ?>',
                        allDay: true,
                        color: 'green',
                        id: '<?php echo $slot['date'] . "-" . $slot['time']; ?>' // Unique event ID
                    },
                    <?php endforeach; ?>

                    <?php foreach ($holidays as $holiday) : ?>
                    {
                        title: '<?php echo $holiday['reason']; ?>',
                        start: '<?php echo $holiday['date']; ?>',
                        allDay: true,
                        color: 'red'
                    },
                    <?php endforeach; ?>
                ],
                eventClick: function(info) {
                    // Prompt the user for the reason for the appointment
                    let reason = prompt("Please enter the reason for your appointment:");

                    if (reason) {
                        // Send AJAX request to book the appointment
                        $.ajax({
                            url: 'book_appointment.php',
                            type: 'POST',
                            data: {
                                ajax: 'book_appointment',
                                date: info.event.startStr.split('T')[0],
                                time: info.event.title.includes('AM') ? 'AM' : 'PM',
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
</body>
</html>
