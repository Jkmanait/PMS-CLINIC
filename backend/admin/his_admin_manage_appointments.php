<?php
session_start();
include('../../configuration/config.php');

// Fetch all appointments including the patient's guardian name and address from the patients table
$ret = "SELECT a.id, a.patient_id, a.patient_name, p.paddress, a.appointment_date, a.appointment_time, a.appointment_reason, p.pname AS guardian_name 
        FROM appointments a 
        JOIN patient p ON a.patient_id = p.patient_id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC"; // Changed order to newest first

// Approve appointment
if (isset($_GET['approve_appointment_id'])) {
    $appointment_id = intval($_GET['approve_appointment_id']);
    $stmt = $mysqli->prepare("UPDATE appointments SET appointment_status = 'Approved' WHERE id = ?");
    $stmt->bind_param('i', $appointment_id);
    $stmt->execute();
    $stmt->close();

    if ($stmt) {
        $success = "Appointment Approved";
    } else {
        $err = "Try Again Later";
    }
}

// Disapprove appointment
if (isset($_GET['disapprove_appointment_id'])) {
    $appointment_id = intval($_GET['disapprove_appointment_id']);
    $stmt = $mysqli->prepare("UPDATE appointments SET appointment_status = 'Disapproved' WHERE id = ?");
    $stmt->bind_param('i', $appointment_id);
    $stmt->execute();
    $stmt->close();

    if ($stmt) {
        $success = "Appointment Disapproved";
    } else {
        $err = "Try Again Later";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include('assets/inc/head.php'); ?>

<style>
    th {
        font-size: 20px;
        background-color: #d3d3d3; /* Light gray background */
        color: black; /* Black text */
    }
    
    body, label, th, td, h4, h1, h2, h3, h5, h6, .breadcrumb-item a {
        font-size: 15px;
        color: black;
    }
    th {
        font-size: 20px; /* Increased font size for headers */
        font-weight: bold; /* Bold headers */
    }
    td {
        font-size: 18px; /* Increased font size for table data */
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
    .search-bar {
        margin: 20px 0; /* Add some spacing around the search bar */
    }
</style>

<body>
    <div id="wrapper">
        <!-- Topbar and Sidebar includes -->
        <?php include('assets/inc/nav.php'); ?>
        <?php include("assets/inc/sidebar.php"); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <!-- Page title -->
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Appointments</a></li>
                                        <li class="breadcrumb-item active">Calendar Appointments</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Calendar Appointments</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="search-bar">
                        <input type="text" id="search" placeholder="Search appointments..." class="form-control">
                    </div>

                    <!-- Appointments Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered toggle-circle mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Patient Guardian</th>
                                    <th>Patient Name</th>
                                    <th>Address</th>
                                    <th>Appointment Date</th>
                                    <th>Appointment Time</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $mysqli->prepare($ret);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                $last_date = '';
                                $date_count = 1; // Initialize a count for each date

                                while ($row = $res->fetch_object()) {
                                    $appointment_am_pm = date("A", strtotime($row->appointment_time));

                                    // Format the appointment date
                                    $formatted_date = (new DateTime($row->appointment_date))->format('F j, Y');

                                    // Check if the date has changed
                                    if ($last_date != $row->appointment_date) {
                                        echo '<tr><td colspan="7" style="font-weight: bold;">' . htmlspecialchars($formatted_date) . '</td></tr>';
                                        $last_date = $row->appointment_date;
                                        $date_count = 1; // Reset count for the new date
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo $date_count; ?></td>
                                        <td><?php echo htmlspecialchars($row->guardian_name); ?></td>
                                        <td><?php echo htmlspecialchars($row->patient_name); ?></td>
                                        <td><?php echo htmlspecialchars($row->paddress); ?></td>
                                        <td><?php echo htmlspecialchars($formatted_date); ?></td> <!-- Use the formatted date here -->
                                        <td><?php echo htmlspecialchars($row->appointment_time . " " . $appointment_am_pm); ?></td>
                                        <td><?php echo htmlspecialchars($row->appointment_reason); ?></td>
                                    </tr>
                                <?php 
                                    $date_count++; // Increment count for the current date
                                } 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php include('assets/inc/footer.php'); ?>
            </div>
        </div>
<!-- END wrapper -->

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
