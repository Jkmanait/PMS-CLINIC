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
    $patient_name = $_POST['patient_name'];
    $guardian_name = $_POST['guardian_name'];

    if (strtotime($appointment_date) < strtotime(date('Y-m-d'))) {
        echo json_encode(['status' => 'error', 'message' => 'Cannot book an appointment in the past.']);
        exit();
    }

    $mysqli->begin_transaction();

    try {
        $count_query = "SELECT COUNT(*) as appointment_count FROM appointments WHERE appointment_date = ?";
        if ($count_stmt = $mysqli->prepare($count_query)) {
            $count_stmt->bind_param('s', $appointment_date);
            $count_stmt->execute();
            $count_result = $count_stmt->get_result();
            $appointment_count = $count_result->fetch_object()->appointment_count;
        } else {
            throw new Exception($mysqli->error);
        }

        $insert_appointment_query = "INSERT INTO appointments (patient_id, patient_name, appointment_date, appointment_time, appointment_reason, guardian_name) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = $mysqli->prepare($insert_appointment_query)) {
            $stmt->bind_param('isssss', $patient_id, $patient_name, $appointment_date, $appointment_time, $appointment_reason, $guardian_name);
            $stmt->execute();
            $appointment_id = $stmt->insert_id;
        } else {
            throw new Exception($mysqli->error);
        }

        $update_slots_query = "UPDATE appointment_schedule SET slots = slots - 1 WHERE date = ? AND time = ? AND slots > 0";
        if ($update_stmt = $mysqli->prepare($update_slots_query)) {
            $update_stmt->bind_param('ss', $appointment_date, $appointment_time);
            $update_stmt->execute();

            if ($update_stmt->affected_rows == 0) {
                throw new Exception("No available slots for the selected time.");
            }
        } else {
            throw new Exception($mysqli->error);
        }

        $mysqli->commit();
        echo json_encode(['status' => 'success', 'appointment_id' => $appointment_id, 'appointment_count' => $appointment_count + 1]);
    } catch (Exception $e) {
        $mysqli->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit();
}

$query_slots = "SELECT id, date, time, slots, exception_reason FROM appointment_schedule";
$result_slots = $mysqli->query($query_slots);

$events = [];
while ($row = $result_slots->fetch_assoc()) {
    $event_title = $row['exception_reason'] ?: ($row['slots'] > 0 ? 'Available ' . $row['time'] . ': ' . $row['slots'] . ' slots' : 'No available slots');
    $events[] = [
        'title' => $event_title,
        'start' => $row['date'],
        'allDay' => true,
        'color' => $row['slots'] > 0 ? 'green' : 'red',
        'id' => $row['id'],
        'slots' => $row['slots']
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
        /* Custom styles */
    </style>
</head>
<body>
    <div id="wrapper">
        <?php include('assets/inc/nav.php');?>

        <div class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h3>Book an Appointment</h3>
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for appointment input -->
        <div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="appointmentModalLabel">Appointment Booking</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
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
                            <div class="form-group">
                                <label for="guardian_name">Guardian Name:</label>
                                <input type="text" class="form-control" id="guardian_name" required>
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
                        if (info.event.extendedProps.slots == 0) {
                            alert('No available slots for this time. Please choose another time.');
                            return;
                        }

                        $('#appointmentCount').text('01');
                        $('#appointmentModal').modal('show');

                        $('#submitAppointment').off('click').on('click', function() {
                            let patientName = $('#patient_name').val();
                            let reason = $('#reason').val();
                            let guardianName = $('#guardian_name').val();

                            if (patientName && reason && guardianName) {
                                $.ajax({
                                    url: 'appointment.php',
                                    type: 'POST',
                                    data: {
                                        ajax: 'book_appointment',
                                        date: info.event.startStr.split('T')[0],
                                        time: info.event.title.includes('AM') ? 'AM' : 'PM',
                                        patient_name: patientName,
                                        reason: reason,
                                        guardian_name: guardianName
                                    },
                                    success: function(response) {
                                        var res = JSON.parse(response);
                                        if (res.status === 'success') {
                                            alert('Appointment booked successfully!');
                                            $('#appointmentCount').text(res.appointment_count.toString().padStart(2, '0'));
                                            $('#appointmentModal').modal('hide');
                                        } else {
                                            alert('No Available Slot: ' + res.message);
                                        }
                                    }
                                });
                            } else {
                                alert('Please complete all fields.');
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
