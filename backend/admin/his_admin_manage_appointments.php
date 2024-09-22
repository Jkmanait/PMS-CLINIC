<?php
session_start();
include('../../configuration/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];

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
                                                <th>Patient ID</th>
                                                
                                                <th>Appointment Date</th>
                                                <th>Appointment Time</th>
                                                <th>Reason</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                            // Fetch details of all appointments
                                            $ret = "SELECT * FROM appointments ORDER BY created_at DESC";
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
