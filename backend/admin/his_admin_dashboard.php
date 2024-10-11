<?php
session_start();
include('../../configuration/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];
?>
<!DOCTYPE html>
<html lang="en">

<!-- Head Code -->
<?php include("assets/inc/head.php"); ?>
<style>
    /* General Styles */
    body {
        background-color: #ffeef8; /* Light pink background */
        color: black; /* Default text color */
    }

    /* Make text bigger and color black */
    label, th, td, h4, h1, h2, h3, h5, h6, .breadcrumb-item a {
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

    /* Card Box Background Color */
    .card-box {
        
        border: 1px solid gray; /* Slightly darker pink border */
    }

    /* Avatar Circle Background */
    .avatar-lg {
        background-color: #ff99cc; /* Lighter pink for avatar background */
        border: 2px solid #c7007f; /* Darker pink border for avatar */
    }

    /* Graph Background */
    .card-box-graph {
        background-color: #f0f0f0; /* Gray background for the graph container */
    }

    /* Text in the graph */
    .text-dark {
        color: black; /* Ensure text color is black */
    }
</style>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php'); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include('assets/inc/sidebar.php'); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- Start page title -->
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
    <!-- Appointments -->
<div class="col-md-8 col-xl-5 mb-6 mr-xl-2">
    <div class="widget-rounded-circle card-box">
        <div class="row">
            <div class="col-6">
                <div class="avatar-lg rounded-circle bg-soft-primary border-dark border">
                    <i class="fas fa-calendar-check font-22 avatar-title" style="color: black;"></i> <!-- Icon color black -->
                </div>
            </div>
            <div class="col-6">
                <div class="text-right">
                    <?php
                    // Get today's date
                    $today = date('Y-m-d');

                    // Uncomment this line to test with a hardcoded date
                    $today = '2024-10-12'; // Hardcoded for testing

                    // Summing up number of appointments for today
                    $result = "SELECT COUNT(*) FROM appointments WHERE appointment_date = ?";
                    $stmt = $mysqli->prepare($result);

                    if (!$stmt) {
                        die("Prepare failed: " . $mysqli->error);
                    }

                    $stmt->bind_param('s', $today);
                    $stmt->execute();
                    $stmt->bind_result($appointment_count);
                    $stmt->fetch();

                    // Close the statement
                    $stmt->close();
                    ?>
                    <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $appointment_count; ?></span></h3>
                    <p class="mb-1 text-truncate" style="color: black;">Number of Appointments for today</p> <!-- Text color black -->
                </div>
            </div>
        </div>
    </div>
</div>


                        <!-- out Patients -->
                        <div class="col-md-8 col-xl-5 mb-6 mr-xl-2">
                            <div class="widget-rounded-circle card-box">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-lg rounded-circle bg-soft-primary border-dark border">
                                            <i class="mdi mdi-hospital font-22 avatar-title" style="color: black;"></i> <!-- Icon color black -->
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-right">
                                            <?php
                                            // Summing up number of inpatients using mdr_number
                                            $result = "SELECT count(DISTINCT mdr_number) FROM his_soap_records";
                                            $stmt = $mysqli->prepare($result);
                                            $stmt->execute();
                                            $stmt->bind_result($inpatient);
                                            $stmt->fetch();
                                            $stmt->close();
                                            ?>
                                            <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $inpatient; ?></span></h3>
                                            <p class="mb-1 text-truncate" style="color: black;">Out Patients</p> <!-- Text color black -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Fetch Appointment Data for the graph -->
                        <?php
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
                        <div class="col-md-5 col-xl-5 mb-6 mr-xl-2">
                            <div class="widget-rounded-circle">
                                <div class="card-box card-box-graph">
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
                                        backgroundColor: 'rgba(255, 105, 180, 0.2)', // Light pink background for the graph
                                        borderColor: 'rgba(255, 20, 147, 1)', // Darker pink for the line
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

                        <!-- Note Section -->
                        <div class="col-md-5 col-xl-5 mb-6 mr-xl-2">
                        <div class="card-box" style="height: 370px;">
                            
                        <h4 class="header-title mb-3" style="text-align: center; font-weight: bold;">Patient who makes the appointment today</h4>

                                <?php
                                // Define the number of patients to display per page
                                $patients_per_page = 1; // Show one patient at a time
                                $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                $offset = ($current_page - 1) * $patients_per_page;

                                // Fetch total count of appointments
                                $count_query = "SELECT COUNT(*) AS total FROM appointments";
                                $count_result = $mysqli->query($count_query);
                                $total_patients = $count_result->fetch_object()->total;
                                $total_pages = ceil($total_patients / $patients_per_page);

                                // Fetch the specific appointment for the current page
                                $ret = "SELECT a.id, a.patient_id, a.patient_name, p.paddress, a.appointment_date, a.appointment_time, a.appointment_reason, p.pname AS guardian_name 
                                        FROM appointments a 
                                        JOIN patient p ON a.patient_id = p.patient_id
                                        ORDER BY a.created_at DESC 
                                        LIMIT $offset, $patients_per_page";
                                $stmt = $mysqli->prepare($ret);
                                $stmt->execute();
                                $res = $stmt->get_result();

                                if ($row = $res->fetch_object()) {
                                    // Extract only AM/PM from the time
                                    $appointment_am_pm = date("A", strtotime($row->appointment_time));
                                    ?>

                                    <ul class="list-unstyled">
                                        <li>
                                            <strong>Guardian:</strong> <?php echo $row->guardian_name; ?><br>
                                            <br>
                                            <strong>Patient Name:</strong> <?php echo $row->patient_name; ?><br>
                                            <br>
                                            <strong>Address:</strong> <?php echo $row->paddress; ?><br>
                                            <br>
                                            <strong>Appointment Date:</strong> <?php echo $row->appointment_date; ?><br>
                                            <br>
                                            <strong>Time:</strong> <?php echo $row->appointment_time . " " . $appointment_am_pm; ?><br>
                                            <br>
                                            <strong>Reason:</strong> <?php echo $row->appointment_reason; ?><br>
                                        </li>
                                    </ul>

                                    <?php
                                    // Navigation links for previous and next
                                echo '<div class="navigation" style="margin-left: 470px; margin-top: -20px;">';
                                if ($current_page > 1) {
                                    echo '<a href="?page=' . ($current_page - 1) . '" class="btn btn-secondary">← Previous</a>';
                                }
                                if ($current_page < $total_pages) {
                                    echo '<a href="?page=' . ($current_page + 1) . '" class="btn btn-secondary">Next →</a>';
                                }
                                echo '</div>';
                                } else {
                                    echo '<p>No appointments found.</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>


                    </div>
                </div>
            </div>

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php'); ?>
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

            <!-- Dashboard 1 init js-->
            <script src="assets/js/pages/dashboard-1.init.js"></script>

            <!-- App js-->
            <script src="assets/js/app.min.js"></script>

        </body>

</html>
