<?php
session_start();
include('../../configuration/config.php');

// Fetch all appointments including the patient's guardian name and address from the patients table
$query = "SELECT a.id, a.patient_id, a.patient_name, p.paddress, a.appointment_date, a.appointment_time, a.appointment_reason, a.appointment_status, p.pname AS guardian_name
          FROM appointments a
          JOIN patient p ON a.patient_id = p.patient_id";

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

                    <!-- Appointments Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
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
                                            // Fetch and display appointments with guardian name and address
                                            $ret = "SELECT a.id, a.patient_id, a.patient_name, p.paddress, a.appointment_date, a.appointment_time, a.appointment_reason, p.pname AS guardian_name 
                                                    FROM appointments a 
                                                    JOIN patient p ON a.patient_id = p.patient_id
                                                    ORDER BY a.created_at DESC";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($row = $res->fetch_object()) {
                                                // Extract only AM/PM from the time
                                                $appointment_am_pm = date("A", strtotime($row->appointment_time));
                                            ?>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $row->guardian_name; // Display patient guardian name ?></td>
                                                    <td><?php echo $row->patient_name; // Display patient name and address ?></td>
                                                    <td><?php echo $row->paddress; // Display patient name and address ?></td>
                                                    <td><?php echo $row->appointment_date; ?></td>
                                                    <td><?php echo $row->appointment_time . " " . $appointment_am_pm; // Display appointment time with AM/PM ?></td>
                                                    <td><?php echo $row->appointment_reason; ?></td>
                                                </tr>
                                            <?php $cnt++; } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
