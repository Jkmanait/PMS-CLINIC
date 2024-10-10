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
    $patient_name = $_POST['patient_name']; // Get patient name from POST data
    $appointment_status = 'Pending'; // Default status

    // Prevent booking past dates
    if (strtotime($appointment_date) < strtotime(date('Y-m-d'))) {
        echo json_encode(['status' => 'error', 'message' => 'Cannot book an appointment in the past.']);
        exit();
    }

    // Start transaction to avoid issues with race conditions
    $mysqli->begin_transaction();

    try {
        // Insert the new appointment into `appointments` table, including the patient's name
        $insert_appointment_query = "INSERT INTO appointments (patient_id, patient_name, appointment_date, appointment_time, appointment_reason, appointment_status) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = $mysqli->prepare($insert_appointment_query)) {
            $stmt->bind_param('isssss', $patient_id, $patient_name, $appointment_date, $appointment_time, $appointment_reason, $appointment_status);
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
            <?php include('assets/inc/nav.php');?>
            <!-- end Topbar -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

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

        <!-- Include Bootstrap for the modal -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<!-- Modal for appointment input -->
<div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel">Appointment Booking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="appointmentForm">
                    <div class="form-group">
                        <label for="patient_name">Patient Name:</label>
                        <input type="text" class="form-control" id="patient_name" required>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason for Appointment:</label>
                        <input type="text" class="form-control" id="reason" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitAppointment">Book Appointment</button>
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
                let time = info.event.title.includes('AM') ? 'AM' : 'PM'; // Determine time based on event title

                // Open the modal when an event is clicked
                $('#appointmentModal').modal('show');

                // Handle form submission inside the modal
                $('#submitAppointment').off('click').on('click', function() {
    let patientName = $('#patient_name').val(); // Get patient name from input
    let reason = $('#reason').val(); // Get reason from input

    if (patientName && reason) {
        // Send AJAX request to book the appointment
        $.ajax({
            url: 'appointment.php', // Your PHP file that handles booking
            type: 'POST',
            data: {
                ajax: 'book_appointment',
                date: info.event.startStr.split('T')[0], // Extract date from event start
                time: info.event.title.includes('AM') ? 'AM' : 'PM', // Determine time based on event title
                patient_name: patientName, // Include patient name
                reason: reason // Include reason
            },
            success: function(response) {
                var res = JSON.parse(response);
                if (res.status === 'success') {
                    alert('Appointment booked successfully!');
                    // Update UI to reflect the reduced slots
                    info.event.setProp('title', info.event.title.replace(/(\d+)/, function(match) {
                        return parseInt(match) - 1;
                    }));
                    $('#appointmentModal').modal('hide'); // Close the modal
                } else {
                    alert('Error: ' + res.message);
                }
            }
        });
    } else {
        alert('Please provide both patient name and reason for the appointment.');
    }
});

            }
        });
        calendar.render();
    });
</script>

    </div>

<!-- Footer Start -->
<?php include('assets/inc/footer.php');?>
                <!-- end Footer -->

           
        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- Plugins js-->
        <script src="assets/libs/flatpickr/flatpickr.min.js"></script>
        <script src="assets/libs/jquery-knob/jquery.knob.min.js"></script>
        <script src="assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.time.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.tooltip.min.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.selection.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.crosshair.js"></script>
        <!-- Bootstrap JS and dependencies (Ensure these are included in your project) -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

        <!-- Dashboar 1 init js-->
        <script src="assets/js/pages/dashboard-1.init.js"></script>

        <!-- App js-->
        <script src="assets/js/app.min.js"></script>

</body>
</html>
