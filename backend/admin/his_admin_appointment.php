<?php
session_start();
include('../../configuration/config.php');

// Function to sanitize user input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// If the admin submits the form to add slots
if (isset($_POST['add_slots'])) {
    $date = sanitizeInput($_POST['date']);
    $am_slots = (int)sanitizeInput($_POST['am_slots']);
    $pm_slots = (int)sanitizeInput($_POST['pm_slots']);

    // Insert AM slots if greater than 0
    if ($am_slots > 0) {
        $stmt_am = $mysqli->prepare("INSERT INTO appointment_schedule (date, time, slots) VALUES (?, 'AM', ?)");
        $stmt_am->bind_param("si", $date, $am_slots);
        $stmt_am->execute();
        $stmt_am->close();
    }

    // Insert PM slots if greater than 0
    if ($pm_slots > 0) {
        $stmt_pm = $mysqli->prepare("INSERT INTO appointment_schedule (date, time, slots) VALUES (?, 'PM', ?)");
        $stmt_pm->bind_param("si", $date, $pm_slots);
        $stmt_pm->execute();
        $stmt_pm->close();
    }
}

// If the admin submits the form to add holiday exceptions
if (isset($_POST['add_exception'])) {
    $holiday_date = sanitizeInput($_POST['holiday_date']);
    $holiday_reason = sanitizeInput($_POST['holiday_reason']);

    // Insert holiday into appointment_schedule table (slots = 0, time = '')
    $stmt_exception = $mysqli->prepare("INSERT INTO appointment_schedule (date, time, slots, exception_reason) VALUES (?, '', 0, ?)");
    $stmt_exception->bind_param("ss", $holiday_date, $holiday_reason);
    $stmt_exception->execute();
    $stmt_exception->close();
}

// Fetch available appointment slots
$query_slots = "SELECT * FROM appointment_schedule WHERE slots > 0";
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
$query_exceptions = "SELECT * FROM appointment_schedule WHERE slots = 0";
$result_exceptions = $mysqli->query($query_exceptions);

// Array to store exceptions (e.g., holidays)
$calendar_exceptions = [];
while ($row = $result_exceptions->fetch_assoc()) {
    $calendar_exceptions[] = [
        'id' => $row['id'],
        'title' => $row['exception_reason'] ? $row['exception_reason'] : "Exception",
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
<head>
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
            width: 80%;
            height: 700px;
            margin: auto;
        }
        .form-container {
            max-width: 10%;
            margin-left: auto;
            margin-right: auto;
        }
        .row {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <?php include('assets/inc/nav.php'); ?>
        <?php include("assets/inc/sidebar.php"); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
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

                    <div class="row">
                        <div id="calendar"></div>

                        <div class="form-container">
                            <form method="POST" action="">
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
                                <button type="submit" name="add_slots" class="btn btn-success">Add Slots</button>
                                </form>
                                <hr class="my-4">
                                <form method="POST" action="">
                                <div class="form-group mt-4">
                                    <label for="holiday_date">Holiday Date</label>
                                    <input type="date" id="holiday_date" name="holiday_date" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="holiday_reason">Holiday Reason</label>
                                    <input type="text" id="holiday_reason" name="holiday_reason" class="form-control" placeholder="e.g., Christmas">
                                </div>
                                <button type="submit" name="add_exception" class="btn btn-danger">Add Holiday</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

            <?php include('assets/inc/footer.php'); ?>

        </div>
    </div>

    <div class="rightbar-overlay"></div>
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

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- Footable js -->
    <script src="assets/libs/footable/footable.all.min.js"></script>

    <!-- Init js -->
    <script src="assets/js/pages/foo-tables.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
</body>
</html>
