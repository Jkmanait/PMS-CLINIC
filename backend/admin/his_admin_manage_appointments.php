<?php
session_start();
include('../../configuration/config.php');

// Fetch all appointments including the patient's name from the patients table
$query = "SELECT a.id, a.patient_id, a.appointment_date, a.appointment_time, a.appointment_reason, a.appointment_status, p.pname 
          FROM appointments a
          JOIN patient p ON a.patient_id = p.patient_id";

$result = $mysqli->query($query);

// Approve appointment
if (isset($_GET['approve_appointment_id'])) {
    $patient_id = intval($_GET['approve_appointment_id']);
    $stmt = $mysqli->prepare("UPDATE appointments SET appointment_status = 'Approved' WHERE patient_id = ?");
    $stmt->bind_param('i', $patient_id);
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
    $patient_id = intval($_GET['disapprove_appointment_id']);
    $stmt = $mysqli->prepare("UPDATE appointments SET appointment_status = 'Disapproved' WHERE patient_id = ?");
    $stmt->bind_param('i', $patient_id);
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
                                <h4 class="page-title">Manage Appointments</h4>
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
                                                <th>Patient Account ID</th>
                                                <th>Patient Name</th>
                                                <th>Appointment Date</th>
                                                <th>Appointment Time</th>
                                                <th>Reason</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        // Fetch and display appointments with patient name
                                        $ret = "SELECT a.id, a.patient_id, a.appointment_date, a.appointment_time, a.appointment_reason, a.appointment_status, p.pname 
                                                FROM appointments a 
                                                JOIN patient p ON a.patient_id = p.patient_id
                                                ORDER BY a.created_at DESC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_object()) {
                                        ?>

                                            <tbody>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $row->patient_id; ?></td>
                                                    <td><?php echo $row->pname; // Display patient name ?></td>
                                                    <td><?php echo $row->appointment_date; ?></td>
                                                    <td><?php echo $row->appointment_time; ?></td>
                                                    <td><?php echo $row->appointment_reason; ?></td>
                                                    <td><?php echo $row->appointment_status; ?></td>
                                                    <td>
                                                    <?php if ($row->appointment_status != 'Approved') { ?>
                                                        <a href="his_admin_manage_appointments.php?approve_appointment_id=<?php echo $row->patient_id;?>" class="badge badge-success">
                                                            <i class="fas fa-check"></i> Approve
                                                        </a>
                                                    <?php } ?>

                                                    <?php if ($row->appointment_status != 'Disapproved') { ?>
                                                        <a href="his_admin_manage_appointments.php?disapprove_appointment_id=<?php echo $row->patient_id;?>" class="badge badge-danger">
                                                            <i class="fas fa-times"></i> Disapprove
                                                        </a>

                                                    <?php } ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        <?php $cnt++; } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('assets/inc/footer.php'); ?>
            </div>
        </div>

        <!-- Vendor scripts -->
        <script src="assets/js/vendor.min.js"></script>
        <script src="assets/libs/footable/footable.all.min.js"></script>
        <script src="assets/js/pages/foo-tables.init.js"></script>
        <script src="assets/js/app.min.js"></script>
    </div>
</body>
</html>