<?php
session_start();
include('../../configuration/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php'); ?>

<style>
    /* Make text bigger and color black */
    body, label, th, td, h4, h1, h2, h3, h5, h6, .breadcrumb-item a {
        font-size: 18px; /* Adjust size as needed */
        color: black;    /* Text color */
    }

    /* Increase font size for table headers */
    th {
        font-size: 20px; /* Larger font for headers */
    }

    /* Larger font size for page titles */
    h4.page-title {
        font-size: 24px;
        color: black;
    }

    /* Search input and buttons */
    input[type="text"], button {
        font-size: 18px;
        color: black;
    }

    /* Pagination */
    .pagination {
        font-size: 18px;
    }
</style>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include("assets/inc/nav.php"); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php"); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <!-- Get Details Of A Single User And Display Them Here -->
        <?php
        $pat_number = $_GET['pat_number'];
        $pat_id = $_GET['pat_id'];

        // Fetch patient details
        $ret = "SELECT * FROM his_patients WHERE pat_id=?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $pat_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_object();
        $mysqlDateTime = $row->pat_date_joined;

        // Fetch SOAP record if available
        $soap_ret = "SELECT mdr_number FROM his_soap_records WHERE soap_pat_number = ?";
        $soap_stmt = $mysqli->prepare($soap_ret);
        $soap_stmt->bind_param('s', $pat_number);
        $soap_stmt->execute();
        $soap_res = $soap_stmt->get_result();
        $soap_row = $soap_res->fetch_object();
        ?>

        <div class="content-page">
            <div class="content">
                <!-- Start Content-->
                <div class="container-fluid">
                    <!-- Start page title -->
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                        <li class="breadcrumb-item active">View Patients</li>
                                    </ol>
                                </div>
                                <h4 class="page-title"><?php echo htmlspecialchars($row->pat_fname); ?> <?php echo htmlspecialchars($row->pat_lname); ?>'s Profile</h4>
                            </div>
                        </div>
                    </div>
                    <!-- End page title -->

                    <div class="row">
                        <div class="col-lg-4 col-xl-4">
                            <div class="card-box text-center">
                                <img src="assets/images/users/patient.png" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                                <div class="text-left mt-3">
                                    <style>
                                        .info-text {
                                            font-size: 18px; /* Adjust this size as per your requirement */
                                            color: black; /* Text color set to black */
                                        }
                                        .info-text strong {
                                            font-size: 18px; /* Ensures strong text is also bigger */
                                            color: black;
                                        }
                                        .info-text span {
                                            font-size: 18px; /* Ensures strong text is also bigger */
                                            color: black;
                                        }
                                    </style>

                                    <p class="text-muted mb-2 font-13 info-text">
                                        <strong>Patient Name:</strong>
                                        <span class="ml-2"><?php echo htmlspecialchars($row->pat_fname); ?> <?php echo htmlspecialchars($row->pat_lname); ?></span>
                                    </p>
                                    <p class="text-muted mb-2 font-13 info-text">
                                        <strong>Patient Sex:</strong>
                                        <span class="ml-2"><?php echo htmlspecialchars($row->pat_sex); ?> </span>
                                    </p>
                                    <p class="text-muted mb-2 font-13 info-text">
                                        <strong>Parent/Guardian Name:</strong>
                                        <span class="ml-2"><?php echo htmlspecialchars($row->pat_parent_name); ?></span>
                                    </p>
                                    <p class="text-muted mb-2 font-13 info-text">
                                        <strong>Mobile:</strong>
                                        <span class="ml-2"><?php echo htmlspecialchars($row->pat_phone); ?></span>
                                    </p>
                                    <p class="text-muted mb-2 font-13 info-text">
                                        <strong>Address:</strong>
                                        <span class="ml-2"><?php echo htmlspecialchars($row->pat_addr); ?></span>
                                    </p>
                                    <p class="text-muted mb-2 font-13 info-text">
                                        <strong>Date Of Birth:</strong>
                                        <span class="ml-2"><?php echo htmlspecialchars($row->pat_dob); ?></span>
                                    </p>
                                    <p class="text-muted mb-2 font-13 info-text">
                                        <strong>Age:</strong>
                                        <span class="ml-2"><?php echo htmlspecialchars($row->pat_age); ?> Years</span>
                                    </p>
                                    <p class="text-muted mb-2 font-13 info-text">
                                        <strong>Ailment:</strong>
                                        <span class="ml-2"><?php echo htmlspecialchars($row->pat_ailment); ?></span>
                                    </p>
                                    <p class="text-muted mb-2 font-13 info-text">
                                        <strong>Patient Number:</strong>
                                        <span class="ml-2"><?php echo htmlspecialchars($row->pat_number); ?></span>
                                    </p>
                                    <p class="text-muted mb-2 font-13 info-text">
                                        <strong>Patient MRN:</strong>
                                        <span class="ml-2"><?php echo $soap_row ? htmlspecialchars($soap_row->mdr_number) : 'N/A'; ?></span>
                                    </p>
                                    <hr>
                                    <p class="text-muted mb-2 font-13 info-text">
                                        <strong>Date Recorded:</strong>
                                        <span class="ml-2"><?php echo date("d/m/Y - h:i", strtotime($mysqlDateTime)); ?></span>
                                    </p>
                                    <hr>
                                </div>
                            </div> <!-- end card-box -->
                        </div> <!-- end col -->

                        <div class="col-lg-8 col-xl-8">
                            <div class="card-box">
                                <ul class="nav nav-pills navtab-bg nav-justified">
                                    <li class="nav-item">
                                        <a href="#aboutme" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                            Medical Records History
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#patient-chart" data-toggle="tab" aria-expanded="false" class="nav-link">
                                            Patient Chart
                                        </a>
                                    </li>
                                </ul>
                                <!-- Medical History -->
                                <div class="tab-content">
                                    <div class="tab-pane show active" id="aboutme">
                                        <ul class="list-unstyled timeline-sm" style="margin-left: 85px;"> 
                                            <?php
                                            $soap_pat_number = $_GET['pat_number'];
                                            $ret = "SELECT * FROM his_soap_records WHERE soap_pat_number = ?";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->bind_param('s', $soap_pat_number); // Assuming pat_number is a string
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            
                                            if ($res->num_rows > 0) {
                                                while ($row = $res->fetch_object()) {
                                            ?>
                                                    <li class="timeline-sm-item">
                                                        <span class="timeline-sm-date"><?php echo date("Y-m-d", strtotime($row->created_at)); ?></span>
                                                        <h5 class="mt-0 mb-1"><?php echo htmlspecialchars($row->soap_pat_ailment); ?></h5>
                                                        <p class="text-muted mt-2">
                                                            <strong>Subjective:</strong> <?php echo nl2br(htmlspecialchars($row->soap_subjective)); ?><br>
                                                            <strong>Objective:</strong> <?php echo nl2br(htmlspecialchars($row->soap_objective)); ?><br>
                                                            <strong>Assessment:</strong> <?php echo nl2br(htmlspecialchars($row->soap_assessment)); ?><br>
                                                            <strong>Plan:</strong> <?php echo nl2br(htmlspecialchars($row->soap_plan)); ?>
                                                        </p>
                                                    </li>
                                            <?php 
                                                }
                                            } else {
                                            ?>
                                                <li class="timeline-sm-item">
                                                    <p class="text-muted mt-2">No Medical Record Yet</p>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div> <!-- end tab-pane -->

                                    <div class="tab-pane" id="patient-chart">
                                        <ul class="list-unstyled timeline-sm" style="margin-left: 85px;">
                                            <?php
                                            // Fetch patient chart records
                                            $chart_ret = "SELECT * FROM his_patient_chart WHERE patient_chart_pat_number = ?";
                                            $chart_stmt = $mysqli->prepare($chart_ret);
                                            $chart_stmt->bind_param('s', $pat_number); // Assuming pat_number is a string
                                            $chart_stmt->execute();
                                            $chart_res = $chart_stmt->get_result();

                                            if ($chart_res->num_rows > 0) {
                                                while ($chart_row = $chart_res->fetch_object()) {
                                            ?>
                                                    <li class="timeline-sm-item">
                                                        <span class="timeline-sm-date"><?php echo date("Y-m-d", strtotime($chart_row->created_at)); ?></span>
                                                        <h5 class="mt-0 mb-1"><?php echo htmlspecialchars($chart_row->patient_chart_pat_ailment); ?></h5>
                                                        <p class="text-muted mt-2">
                                                            <strong>Weight:</strong> <?php echo nl2br(htmlspecialchars($chart_row->patient_chart_weight)); ?> kg<br>
                                                            <strong>Length:</strong> <?php echo nl2br(htmlspecialchars($chart_row->patient_chart_length)); ?> cm<br>
                                                            <strong>Temperature:</strong> <?php echo nl2br(htmlspecialchars($chart_row->patient_chart_temp)); ?> <br>
                                                            <strong>Diagnosis:</strong> <?php echo nl2br(htmlspecialchars($chart_row->patient_chart_diagnosis)); ?><br>
                                                            <strong>Prescription:</strong> <?php echo nl2br(htmlspecialchars($chart_row->patient_chart_prescription)); ?>
                                                        </p>
                                                    </li>
                                            <?php 
                                                }
                                            } else {
                                            ?>
                                                <li class="timeline-sm-item">
                                                    <p class="text-muted mt-2">No Patient Chart Record Yet</p>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div> <!-- end tab-pane -->
                                </div> <!-- end tab-content -->

                            </div> <!-- end card-box -->
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div> <!-- container -->
            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php'); ?>
            <!-- end Footer -->
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>
</html>
