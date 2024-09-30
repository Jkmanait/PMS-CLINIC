<?php
  session_start();
  include('../../configuration/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['ad_id'];
?>
<!DOCTYPE html>
<html lang="en">
    
    <!--Head Code-->
    <?php include("assets/inc/head.php");?>
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
            <?php include('assets/inc/nav.php');?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <?php include('assets/inc/sidebar.php');?>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <br>
                                    <h4 class="page-title">Dashboard</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 
                        

                        <div class="row">
                        <div class="row">
    <!-- Out Patients -->
    <div class="col-md-8 col-xl-5 mb-6 mr-xl-2">
        <div class="widget-rounded-circle card-box ">
            <div class="row">
                <div class="col-6">
                    <div class="avatar-lg rounded-circle bg-soft-primary border-dark border">
                        <i class="fab fa-accessible-icon font-22 avatar-title" style="color: black;"></i> <!-- Icon color black -->
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-right">
                        <?php
                            // Summing up number of outpatients
                            $result = "SELECT count(*) FROM his_patients WHERE pat_type = 'OutPatient'";
                            $stmt = $mysqli->prepare($result);
                            $stmt->execute();
                            $stmt->bind_result($outpatient);
                            $stmt->fetch();
                            $stmt->close();
                        ?>
                        <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $outpatient; ?></span></h3>
                        <p class="mb-1 text-truncate" style="color: black;">Out Patients</p> <!-- Text color black -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- In Patients -->
    <div class="col-md-8 col-xl-5 mb-6 mr-xl-2">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class="col-6">
                    <div class="avatar-lg rounded-circle bg-soft-primary border-dark border">
                        <i class="mdi mdi-hotel font-22 avatar-title" style="color: black;"></i> <!-- Icon color black -->
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-right">
                        <?php
                            // Summing up number of inpatients
                            $result = "SELECT count(*) FROM his_patients WHERE pat_type = 'InPatient'";
                            $stmt = $mysqli->prepare($result);
                            $stmt->execute();
                            $stmt->bind_result($inpatient);
                            $stmt->fetch();
                            $stmt->close();
                        ?>
                        <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $inpatient; ?></span></h3>
                        <p class="mb-1 text-truncate" style="color: black;">In Patients</p> <!-- Text color black -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Corporation Assets -->
    <div class="col-md-8 col-xl-5 mb-6 mr-xl-2">
        <div class="widget-rounded-circle card-box ">
            <div class="row">
                <div class="col-6">
                    <div class="avatar-lg rounded-circle bg-soft-primary border-dark border">
                        <i class="mdi mdi-flask font-22 avatar-title" style="color: black;"></i> <!-- Icon color black -->
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-right">
                        <?php
                            // Summing up number of assets
                            $result = "SELECT count(*) FROM his_equipments";
                            $stmt = $mysqli->prepare($result);
                            $stmt->execute();
                            $stmt->bind_result($assets);
                            $stmt->fetch();
                            $stmt->close();
                        ?>
                        <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $assets; ?></span></h3>
                        <p class="mb-1 text-truncate" style="color: black;">Corporation Assets</p> <!-- Text color black -->
                    </div>
                </div>
            </div>
        </div>

<!-- Fetch Appointment Data and Services -->
    <?php
// Fetch appointment data for the graph
$appointmentsData = [];
if ($mysqli) {
    $result = "SELECT appointment_date, COUNT(*) as total FROM appointments GROUP BY appointment_date";
    $stmt = $mysqli->prepare($result);
    if ($stmt) {
        $stmt->execute();
        $stmt->bind_result($appointment_date, $total);

        while ($stmt->fetch()) {
            $appointmentsData[] = ['date' => $appointment_date, 'total' => $total];
        }
        $stmt->close();
    } else {
        echo "Error in SQL query: " . $mysqli->error;
    }
}
?>

<!-- Appointment Graph Section -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card-box" style="background-color: #f0f0f0;"> <!-- Gray background for the graph container -->
            <h4 class="header-title mb-3">Appointments Over Time</h4>
            <canvas id="appointmentGraph" style="height: 400px; background-color: #f0f0f0;"></canvas> <!-- Gray background for the canvas -->
        </div>
    </div>
</div>

<!-- Footer and Other Content -->

<!-- Chart.js Script for Graph -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data for the appointments graph
    var appointmentData = <?php echo json_encode($appointmentsData); ?>;
    var labels = appointmentData.map(function(item) {
        return item.date;
    });
    var data = appointmentData.map(function(item) {
        return item.total;
    });

    // Create the graph
    var ctx = document.getElementById('appointmentGraph').getContext('2d');
    var appointmentChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Appointments',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                pointBackgroundColor: 'black', // Black color for points (circles on the graph)
                pointBorderColor: 'black'      // Black color for point borders
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: 'black' // Black color for Y-axis labels
                    }
                },
                x: {
                    ticks: {
                        color: 'black' // Black color for X-axis labels
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: 'black' // Black color for legend text
                    }
                }
            }
        }
    });
</script>

    </div>

    <!-- Pharmaceuticals -->
    <div class="col-md-8 col-xl-5 mb-6 mr-xl-2">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class="col-6">
                    <div class="avatar-lg rounded-circle bg-soft-primary border-dark border">
                        <i class="mdi mdi-pill font-22 avatar-title" style="color: black;"></i> <!-- Icon color black -->
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-right">
                        <?php
                            // Summing up number of pharmaceuticals
                            $result = "SELECT count(*) FROM his_pharmaceuticals";
                            $stmt = $mysqli->prepare($result);
                            $stmt->execute();
                            $stmt->bind_result($phar);
                            $stmt->fetch();
                            $stmt->close();
                        ?>
                        <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $phar; ?></span></h3>
                        <p class="mb-1 text-truncate" style="color: black;">Pharmaceuticals</p> <!-- Text color black -->
                    </div>
                </div>
            </div>
        </div>
    </div>    
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

        <!-- Dashboar 1 init js-->
        <script src="assets/js/pages/dashboard-1.init.js"></script>

        <!-- App js-->
        <script src="assets/js/app.min.js"></script>
        
    </body>

</html>