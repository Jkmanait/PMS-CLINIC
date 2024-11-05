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
    /* Custom font sizes */
        h4 {
            font-size: 18px;
        }
        h5 {
            font-size: 16px;
        }
        h6 {
            font-size: 14px;
        }
        p.text-muted {
            font-size: 13px;
        }
        .card-box {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .img-thumbnail {
            width: 150px;
        }
        .avatar-lg {
            height: 150px;
        }

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
        <?php include('assets/inc/nav.php'); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php"); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <?php
        $patient_chart_id = $_GET['patient_chart_id'];

        // Fetch the first SOAP record to get the mdr_number
        $ret = "SELECT * FROM his_patient_chart WHERE patient_chart_id = ?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $patient_chart_id);
        $stmt->execute();
        $res = $stmt->get_result();

        // Assuming there's always a record fetched
        if ($row = $res->fetch_object()) {
            $patient_chart_pat_number = $row->patient_chart_pat_number; 
            ?>
            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        <!-- start page title -->
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="his_admin_dashboard.php">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Patient Chart</a></li>
                                            <li class="breadcrumb-item active">View Patient Chart Records</li>
                                        </ol>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="card-box">
                            <?php
                            
                            $ret_all = "SELECT * FROM his_patient_chart WHERE patient_chart_pat_number = ? ORDER BY created_at DESC"; // No LIMIT
                            $stmt_all = $mysqli->prepare($ret_all);
                            $stmt_all->bind_param('s', $patient_chart_pat_number);
                            $stmt_all->execute();
                            $res_all = $stmt_all->get_result();

                            // Check if any records were fetched
                            if ($res_all->num_rows > 0) {
                                while ($record = $res_all->fetch_object()) {
                                    $createdAt = $record->created_at; // Get the creation date for this record
                                    ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card-box">
                                                <!-- <img src="assets/images/users/patient.png" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image"> -->
                                                <div class="row text-left mt-3">
                                                    <div class="col-xl-7">
                                                        <div class="pl-xl-3 mt-3 mt-xl-0">
                                                            <h6 class="mb-3">Patient Name: <?php echo $record->patient_chart_pat_name; ?></h6>
                                                            <hr>
                                                            <h6 class="text-danger">Patient Sex: <?php echo $record->patient_chart_pat_sex; ?></h6>
                                                            <hr>
                                                            <h6 class="text-danger">Age: <?php echo $record->patient_chart_pat_age; ?> Years</h6>                                                    
                                                            
                                                            <hr>
                                                            <h6 class="text-danger">Patient Number: <?php echo $record->patient_chart_pat_number; ?></h6>
                                                            <hr>
                                                            <h6 class="text-danger">Patient Ailment: <?php echo $record->patient_chart_pat_ailment; ?></h6>
                                                            <hr>
                                                            <h6 class="text-danger">Parent/Guardian Name: <?php echo $record->patient_chart_pat_parent_name; ?></h6>
                                                            <hr>
                                                            <h6 class="text-danger">Address: <?php echo $record->patient_chart_pat_adr; ?></h6>
                                                            <hr>
                                                            <h6 class="text-danger">Date Recorded: <?php echo date("d/m/Y - h:i:s", strtotime($createdAt)); ?></h6>
                                                            <hr>
                                                        </div>
                                                    </div> <!-- end col -->

                                                    <div class="col-xl-5">
                                                        <div class="pl-xl-3 mt-3 mt-xl-0">
                                                            <h4 class="align-centre">Patient Chart Records</h4>
                                                            <hr>
                                                            <h5>Weight:</h5>
                                                            <p class="text-muted mb-4"><?php echo nl2br($record->patient_chart_weight); ?></p>
                                                            <h5>Lenght:</h5>
                                                            <p class="text-muted mb-4"><?php echo nl2br($record->patient_chart_lenght); ?></p>
                                                            <h5>Temparature:</h5>
                                                            <p class="text-muted mb-4"><?php echo nl2br($record->patient_chart_temp); ?></p>
                                                            <h5>Diagnosis:</h5>
                                                            <p class="text-muted mb-4"><?php echo nl2br($record->patient_chart_diagnosis); ?></p>
                                                            <h5>Priscription:</h5>
                                                            <p class="text-muted mb-4"><?php echo nl2br($record->patient_chart_prescription); ?></p>
                                                            <hr>
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->
                                            </div> <!-- end card-->
                                        </div> <!-- end col-->
                                    </div> <!-- end row -->
                                    <?php
                                }
                            } else {
                                echo "<p>No records found for this MDR number.</p>"; // Handle case where no records exist
                            }
                            ?>
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                </div> <!-- end row -->
            </div> <!-- container -->
        </div> <!-- content -->

        <!-- Footer Start -->
        <?php include('assets/inc/footer.php'); ?>
        <!-- end Footer -->

    </div>
    <?php 
        } // End of the if statement to check if there are any records
    ?>

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
