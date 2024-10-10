<?php
  session_start();
  include('../configuration/config.php');
  // Example login logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get username and password from login form
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Query to check if the username and password are correct
    $query = "SELECT * FROM patients WHERE username = ? AND password = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        // User exists, fetch data
        $patientRow = $result->fetch_assoc();
        
        // Set patientID in session
        $_SESSION['patientID'] = $patientRow['patient_id'];  // Store patient ID in session
        $_SESSION['patientLogin'] = true;  // Store login status in session
        
        // Redirect to dashboard or home page
        header("Location: dashboard.php");
        exit();
    } else {
        // Invalid credentials
        $error = "Invalid username or password";
    }
}
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

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                

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
    <!-- doctor -->
<div class="col-md-8 col-xl-5 mb-6 mr-xl-2">
    <div class="widget-rounded-circle card-box">
        <div class="row">
            <div class="col-6">
                <div class="avatar-lg rounded-circle bg-soft-primary border-dark border">
                    <i class="fas fa-user-md fa-2x avatar-title" style="color: black;"></i> <!-- Adjusted to fa-2x for proper sizing -->
                </div>
            </div>
            <div class="col-6">
                <div class="text-right">
                    <?php
                        // Ensure the database connection is established
                        if ($mysqli) {
                            // Summing up number of doctors
                            $result = "SELECT count(*) FROM doctor";
                            $stmt = $mysqli->prepare($result);
                            if ($stmt) {
                                $stmt->execute();
                                $stmt->bind_result($doctor);
                                $stmt->fetch();
                                $stmt->close();
                            } else {
                                echo "Error in SQL query: " . $mysqli->error;
                            }
                        } else {
                            echo "Database connection error";
                        }
                    ?>
                    <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $doctor; ?></span></h3>
                    <p class="mb-1 text-truncate text-dark">List of Doctor</p>
                    
                    <!-- View Button -->
                    <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#doctorModal">View Doctors</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Services Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceModalLabel">List of Services</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch and display the list of services
                        $result = "SELECT service_name, description FROM services"; // Adjust query for services
                        $stmt = $mysqli->prepare($result);
                        if ($stmt) {
                            $stmt->execute();
                            $stmt->bind_result($service_name, $description); // Bind the results

                            while ($stmt->fetch()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($service_name) . '</td>'; // Display service name
                                echo '<td>' . htmlspecialchars($description) . '</td>'; // Display service description
                                echo '</tr>';
                            }
                            $stmt->close();
                        } else {
                            echo "Error in SQL query: " . $mysqli->error;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Services Overview -->
<div class="col-md-8 col-xl-5 mb-6 mr-xl-2">
    <div class="widget-rounded-circle card-box">
        <div class="row">
            <div class="col-6">
                <div class="avatar-lg rounded-circle bg-soft-primary border-dark border">
                    <i class="fas fa-stethoscope fa-2x avatar-title" style="color: black;"></i> 
                </div>
            </div>
            <div class="col-6">
                <div class="text-right">
                    <?php
                        // Ensure the database connection is established
                        if ($mysqli) {
                            // Summing up the number of services
                            $result = "SELECT count(*) FROM services";
                            $stmt = $mysqli->prepare($result);
                            if ($stmt) {
                                $stmt->execute();
                                $stmt->bind_result($service_count);
                                $stmt->fetch();
                                $stmt->close();
                            } else {
                                echo "Error in SQL query: " . $mysqli->error;
                            }
                        } else {
                            echo "Database connection error";
                        }
                    ?>
                    <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $service_count; ?></span></h3>
                    <p class="mb-1 text-truncate text-dark">Services Offered</p>
                    
                    <!-- View Button -->
                    <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#serviceModal">View Services</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- doctor -->
<div class="modal fade" id="doctorModal" tabindex="-1" aria-labelledby="doctorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="doctorModalLabel">List of Doctors</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Doctor Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch and display the list of doctors
                        $result = "SELECT docname FROM doctor"; // Adjusted query to fetch only docname
                        $stmt = $mysqli->prepare($result);
                        if ($stmt) {
                            $stmt->execute();
                            $stmt->bind_result($docname); // Bind only docname

                            while ($stmt->fetch()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($docname) . '</td>'; // Display doctor name
                                echo '</tr>';
                            }
                            $stmt->close();
                        } else {
                            echo "Error in SQL query: " . $mysqli->error;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    <!-- Fetch Appointment Data and Services -->
    <?php
        // Ensure the session is started only if not already active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Assuming the patient ID is stored in the session when the user logs in
        $patient_id = $_SESSION['patient_id'] ?? null;

        $appointmentsData = [];
        if ($mysqli && $patient_id) {
            // Fetch only the logged-in user's appointments
            $result = "SELECT appointment_date, COUNT(*) as total FROM appointments WHERE patient_id = ? GROUP BY appointment_date";
            $stmt = $mysqli->prepare($result);
            if ($stmt) {
                // Bind the patient ID to the query
                $stmt->bind_param('i', $patient_id);
                $stmt->execute();
                $stmt->bind_result($appointment_date, $total);

                // Fetch the results into an array
                while ($stmt->fetch()) {
                    $appointmentsData[] = ['date' => $appointment_date, 'total' => $total];
                }
                $stmt->close();
            } else {
                echo "Error in SQL query: " . $mysqli->error;
            }
        } else {
            echo "Error: No patient ID found or database connection error.";
        }
    ?>



<!-- Appointment Graph Section -->
<div class="row mt-5">
    <div class="col-12 d-flex justify-content-center">
        <div class="card-box" style="background-color: #f0f0f0;"> <!-- Gray background for the graph container -->
            <h4 class="header-title mb-3">Your Appointments</h4>
            <canvas id="appointmentGraph" style="height: 300px; width: 100%; background-color: #f0f0f0;"></canvas> <!-- Gray background for the canvas -->
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
        <!-- Bootstrap JS and dependencies (Ensure these are included in your project) -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

        <!-- Dashboar 1 init js-->
        <script src="assets/js/pages/dashboard-1.init.js"></script>

        <!-- App js-->
        <script src="assets/js/app.min.js"></script>
        
    </body>

</html>