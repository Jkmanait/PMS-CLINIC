<?php
session_start();
include('../../configuration/config.php');

// Fetch all appointments including the patient's name from the patients table
$query = "SELECT a.id, a.patient_id, a.appointment_date, a.appointment_time, a.appointment_reason, a.appointment_status, p.pname 
          FROM appointments a
          JOIN patient p ON a.patient_id = p.patient_id";

$result = $mysqli->query($query);

// Update appointment status and create notification
if (isset($_POST['update_status'])) {
    $appointment_id = $_POST['appointment_id'];
    $new_status = $_POST['new_status'];
    $patient_id = $_POST['patient_id'];

    // Update appointment status
    $update_query = "UPDATE appointments SET appointment_status = ? WHERE id = ?";
    
    if ($stmt = $mysqli->prepare($update_query)) {
        $stmt->bind_param('si', $new_status, $appointment_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Create notification message based on status
            $message = "";
            if ($new_status == "Approved") {
                $message = "Your appointment on " . $_POST['appointment_date'] . " at " . $_POST['appointment_time'] . " has been approved.";
            } elseif ($new_status == "Canceled") {
                $message = "Your appointment on " . $_POST['appointment_date'] . " at " . $_POST['appointment_time'] . " has been canceled.";
            }

            // Insert notification for patient
            $notification_query = "INSERT INTO notifications (patient_id, message) VALUES (?, ?)";
            if ($notif_stmt = $mysqli->prepare($notification_query)) {
                $notif_stmt->bind_param('is', $patient_id, $message);
                $notif_stmt->execute();
            }

            $success = "Appointment status updated and notification sent successfully.";
        } else {
            $err = "Failed to update appointment status. No changes made.";
        }
    } else {
        // Output SQL error for debugging
        $err = "Failed to prepare the update query: " . $mysqli->error;
        echo $err;
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

                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <h4 class="header-title"></h4>
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-12 text-sm-center form-inline" >
                                            <div class="form-group">
                                                <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table id="demo-foo-filtering" class="table table-bordered toggle-circle mb-0" data-page-size="7">
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
                                            // Fetch details of all appointments
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
                                                            <a href="his_admin_manage_appointments.php?approve_appointment_patient_id=<?php echo $row->patient_id;?>" class="badge badge-success">
                                                                <i class="fas fa-check"></i> Approve
                                                            </a>
                                                        <?php } ?>

                                                        <?php if ($row->appointment_status != 'Disapproved') { ?>
                                                            <a href="his_admin_manage_appointments.php?disapprove_appointment_patient_id=<?php echo $row->patient_id;?>" class="badge badge-danger">
                                                                <i class="fas fa-times"></i> Disapprove
                                                            </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            </tbody>


                                        <?php $cnt = $cnt + 1; } ?>
                                        <tfoot>
                                            <tr class="active">
                                                <td colspan="8">
                                                    <div class="text-right">
                                                        <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
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

    <!-- Footable js -->
    <script src="assets/libs/footable/footable.all.min.js"></script>

    <!-- Init js -->
    <script src="assets/js/pages/foo-tables.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
    
</body>
</html>
