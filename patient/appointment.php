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
    $appointment_status = 'Pending';

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

        $insert_appointment_query = "INSERT INTO appointments (patient_id, patient_name, appointment_date, appointment_time, appointment_reason, appointment_status) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = $mysqli->prepare($insert_appointment_query)) {
            $stmt->bind_param('isssss', $patient_id, $patient_name, $appointment_date, $appointment_time, $appointment_reason, $appointment_status);
            $stmt->execute();
            $appointment_id = $stmt->insert_id; // Get the ID of the inserted appointment
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

        // Commit transaction
        $mysqli->commit();
        echo json_encode(['status' => 'success', 'appointment_id' => $appointment_id, 'appointment_count' => $appointment_count + 1]); // Return the updated count
    } catch (Exception $e) {
        $mysqli->rollback();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit();
}

// Fetch available slots and exceptions from the appointment_schedule table
$query_slots = "SELECT id, date, time, slots, exception_reason FROM appointment_schedule";
$result_slots = $mysqli->query($query_slots);

$events = [];
while ($row = $result_slots->fetch_assoc()) {
    // Always create an event for each time slot
    if ($row['exception_reason']) {
        $event_title = ' ' . $row['exception_reason']; // Show exception reason if exists
    } else {
        $event_title = $row['slots'] > 0 ? 'Available ' . $row['time'] . ': ' . $row['slots'] . ' slots' : 'No available slots';
    }

    $events[] = [
        'title' => $event_title,
        'start' => $row['date'],
        'allDay' => true,
        'color' => $row['slots'] > 0 ? 'green' : 'red', // Green for available, red for no slots
        'id' => $row['id'],
        'slots' => $row['slots'] // Include slot count in the event data
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
        body {
            background-color: #ffeef8; /* Light pink background */
            color: black; /* Default text color */
        }


        .form-control {
            border: 1px solid #ff66b2; /* Light pink border for input fields */
        }

        .btn-light-pink {
            background-color: #ff66b2; /* Light pink button background */
            color: black; /* Black text color */
            border: 1px solid #ff66b2; /* Light pink border */
        }

        .btn-light-pink:hover {
            background-color: #ff4d94; /* Slightly darker pink on hover */
            border-color: #ff4d94; /* Slightly darker pink border on hover */
        }

        .modal-header {
            background-color: #ff99cc; /* Lighter pink for modal header */
            color: black; /* Dark text for contrast */
        }

        .card-box {
            border: 1px solid #ff66b2; /* Light pink border for card boxes */
        }

        h6.text-danger {
            color: #d5006d; /* Slightly darker pink for important text */
        }

        .text-muted {
            color: #555; /* Darker text for muted information */
        }
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

    <div id="wrapper">

        <?php include('assets/inc/nav.php');?>

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
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submitAppointment">Book Appointment</button>
                    </div>
                </div>
            </div>
        </div>

       <!-- Modal for receipt -->
            <div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="receiptModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="receiptModalLabel">Receipt</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Clinic name centered at the top -->
                            <h4 class="text-center">Dr. Bolong Pedia Clinic</h4>
                            <br>
                            <h4 class="text-center" style="font-size: 20px;">Priority No. Slip</h4>
                            <!-- Appointment number box -->
                            <p class="text-center">
                                <span id="appointmentCount" style="font-size: 45px; font-weight: bold; border: 2px solid #000; padding: 10px 20px; display: inline-block; border-radius: 10px; background-color: #D80073; color: white;"></span>
                            </p>
                            <!-- Patient name with label -->
                            <p class="text" style="font-size: 20px;" id="patientName">Name of Patient: 
                                <span id="patientNameValue" style="font-weight: bold;"></span>
                            </p>
                            <!-- Serial code -->
                            <p class="text" style="font-size: 18px;" id="serialCode">Serial Code: 
                                <span id="serialCodeValue" style="font-weight: bold;"></span>
                            </p>
                            <br>
                            <p class="text-center" style="font-weight: bold;">Please take a picture or have a screenshot.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

                // Set the initial appointment count to 01
                $('#appointmentCount').text('01');
                $('#appointmentModal').modal('show');

                $('#submitAppointment').off('click').on('click', function() {
                    let patientName = $('#patient_name').val();
                    let reason = $('#reason').val();

                    if (patientName && reason) {
                        $.ajax({
                            url: 'appointment.php',
                            type: 'POST',
                            data: {
                                ajax: 'book_appointment',
                                date: info.event.startStr.split('T')[0],
                                time: info.event.title.includes('AM') ? 'AM' : 'PM',
                                patient_name: patientName,
                                reason: reason
                            },
                            success: function(response) {
                                var res = JSON.parse(response);
                                if (res.status === 'success') {
                                    alert('Appointment booked successfully!');
                                    // Ensure the appointment count is displayed with two digits
                                    let appointmentCount = res.appointment_count.toString().padStart(2, '0');
                                    $('#appointmentCount').text(appointmentCount);
                                    
                                    // Set the patient name in the modal
                                    $('#patientNameValue').text(patientName);
                                    
                                    // Generate an 8-character random alphanumeric serial code
                                    let serialCode = Math.random().toString(36).substring(2, 10).toUpperCase();
                                    $('#serialCodeValue').text(serialCode);

                                    $('#receiptModal').modal('show');
                                    
                                    let remainingSlots = parseInt(info.event.extendedProps.slots) - 1;
                                    if (remainingSlots > 0) {
                                        info.event.setProp('title', 'Available ' + info.event.title.split(' ')[1] + ': ' + remainingSlots + ' slots');
                                        info.event.setExtendedProp('slots', remainingSlots);
                                    } else {
                                        info.event.setProp('title', 'No available slot');
                                        info.event.setProp('color', 'red');
                                        info.event.setExtendedProp('slots', 0);
                                    }
                                    
                                    $('#appointmentModal').modal('hide');
                                } else {
                                    alert('No Available Slot: ' + res.message);
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