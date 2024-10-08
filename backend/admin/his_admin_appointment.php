<?php
session_start();
include('../../configuration/config.php');

// If the admin submits the form to add slots
if (isset($_POST['add_slots'])) {
    $date = $_POST['date'];
    $am_slots = $_POST['am_slots'];
    $pm_slots = $_POST['pm_slots'];

    // Insert AM slots
    if ($am_slots > 0) {
        $am_time = 'AM';
        $query_am = "INSERT INTO appointment_slots (date, time, slots) VALUES ('$date', '$am_time', '$am_slots')";
        $mysqli->query($query_am);
    }

    // Insert PM slots
    if ($pm_slots > 0) {
        $pm_time = 'PM';
        $query_pm = "INSERT INTO appointment_slots (date, time, slots) VALUES ('$date', '$pm_time', '$pm_slots')";
        $mysqli->query($query_pm);
    }
}

// If the admin submits the form to add holiday exceptions
if (isset($_POST['add_exception'])) {
    $holiday_date = $_POST['holiday_date'];
    $holiday_reason = $_POST['holiday_reason'];

    // Insert holiday into calendar_exceptions table
    $query_exception = "INSERT INTO calendar_exceptions (date, reason) VALUES ('$holiday_date', '$holiday_reason')";
    $mysqli->query($query_exception);
}

// Fetch available appointment slots
$query_slots = "SELECT * FROM appointment_slots";
$result_slots = $mysqli->query($query_slots);

// Array to store appointment slots
$appointment_slots = [];
while ($row = $result_slots->fetch_assoc()) {
    $appointment_slots[] = [
        'id' => $row['id'],
        'title' => "Available " . $row['time'] . " Slots: " . $row['slots'],
        'start' => $row['date'],
        'backgroundColor' => '#28a745', // green for available slots
        'borderColor' => '#28a745'
    ];
}

// Fetch calendar exceptions (e.g., holidays or closed days)
$query_exceptions = "SELECT * FROM calendar_exceptions";
$result_exceptions = $mysqli->query($query_exceptions);

// Array to store exceptions (e.g., holidays)
$calendar_exceptions = [];
while ($row = $result_exceptions->fetch_assoc()) {
    $calendar_exceptions[] = [
        'id' => $row['id'],
        'title' => $row['reason'] ? $row['reason'] : "Exception",
        'start' => $row['date'],
        'allDay' => true,
        'backgroundColor' => '#dc3545', // red for exceptions/holidays
        'borderColor' => '#dc3545'
    ];
}

// Combine slots and exceptions into one array for the calendar
$events = array_merge($appointment_slots, $calendar_exceptions);
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php'); ?>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>

<style>
    /* Custom styles */
    body, label, th, td, h4, h1, h2, h3, h5, h6, .breadcrumb-item a {
        font-size: 15px;
        color: black;
    }
    th {
        font-size: 17px;
    }
    h4.page-title {
        font-size: 24px;
        color: black;
    }
    input[type="text"], button {
        font-size: 15px;
        color: black;
    }
    .pagination {
        font-size: 15px;
    }
    #calendar {
        width: 80%; /* Increase width of the calendar */
        height: 700px; /* Set height to make it taller */
        margin: auto;
    }
    .form-container {
        max-width: 10%; /* Adjust width for both forms */
        margin-left: auto;
        margin-right: auto;
    }
    .row {
        display: flex;
        justify-content: space-between;
    }
</style>

<body>

    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php'); ?>
        <!-- end Topbar -->

        <!-- Left Sidebar Start -->
        <?php include("assets/inc/sidebar.php"); ?>
        <!-- Left Sidebar End -->

        <!-- Start Page Content here -->
        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Appointments</a></li>
                                        <li class="breadcrumb-item active">Manage Appointments</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Manage Appointments</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- Row with Calendar and Form -->
                    <div class="row">
                        <!-- FullCalendar Integration -->
                        <div id="calendar"></div>

                        <!-- Combined Form for Admin to Add Slots and Holiday Exceptions -->
                        <div class="form-container">
                            <form method="POST" action="">
                                <!-- Existing Fields for Adding Slots -->
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" id="date" name="date" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="am_slots">AM Slots</label>
                                    <input type="number" id="am_slots" name="am_slots" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="pm_slots">PM Slots</label>
                                    <input type="number" id="pm_slots" name="pm_slots" class="form-control" required>
                                </div>
                                
                                <!-- Submit Button for Adding Slots -->
                                <button type="submit" name="add_slots" class="btn btn-success">Add Slots</button>

                                <!-- Holiday Date and Reason Fields at the Bottom -->
                                <div class="form-group mt-4"> <!-- Add some margin-top for spacing -->
                                    <label for="holiday_date">Holiday Date</label>
                                    <input type="date" id="holiday_date" name="holiday_date" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="holiday_reason">Holiday Reason</label>
                                    <input type="text" id="holiday_reason" name="holiday_reason" class="form-control" placeholder="e.g., Christmas" required>
                                </div>
                                
                                <!-- Submit Button for Adding Holiday -->
                                <button type="submit" name="add_exception" class="btn btn-danger">Add Holiday</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <?php include('assets/inc/footer.php'); ?>

        </div>
    </div>

    <!-- Right bar overlay -->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?php echo json_encode($events); ?>,
                editable: false,
                eventClick: function(info) {
                    if(info.event.backgroundColor == '#28a745') {
                        alert('Available Slots: ' + info.event.title);
                    } else if(info.event.backgroundColor == '#dc3545') {
                        alert('Exception: ' + info.event.title);
                    }
                }
            });

            calendar.render();
        });
    </script>

</body>
</html>
