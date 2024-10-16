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
/* General Styles */
body {
    background-color: #ffeef8; 
    color: black; 
}

/* Make text bigger and color black */
label, th, td, h4, h1, h2, h3, h5, h6, .breadcrumb-item a {
    font-size: 18px; 
    color: black;    
}

/* Increase font size for table headers */
th {
    font-size: 20px; 
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
    border: 1px solid gray; 
}

/* Avatar Circle Background */
.avatar-lg {
    background-color: #ff99cc; 
    border: 2px solid #ff66b2; 
}

/* Graph Background */
.card-box-graph {
    background-color: #f0f0f0; 
}

/* Text in the graph */
.text-dark {
    color: black; 
}

.btn-custom {
    background-color: #ff66b2; 
    border-color: #ff66b2; 
    color: white; 
}

.btn-custom:hover {
    background-color: #ff4d94; 
    border-color: #ff4d94; 
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
                            <div class="page-title-box" style="text-align: center;">
                            <br>
                            <h4 class="page-title" style="font-weight: bold;">Patient's Dashboard</h4>
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
                    <button type="button" class="btn btn-custom btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#doctorModal">View Doctors</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Appointment History Modal -->
<div class="modal fade" id="appointmentHistoryModal" tabindex="-1" aria-labelledby="appointmentHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentHistoryModalLabel">Your Appointment History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Reason</th>
                            <th>Patient Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch patient's appointment history
                        $patient_id = $_SESSION['patient_id']; // Ensure patient is logged in
                        $query_history = "SELECT appointment_date, appointment_time, appointment_reason, patient_name FROM appointments WHERE patient_id = ? ORDER BY appointment_date DESC, appointment_time DESC";
                        $stmt = $mysqli->prepare($query_history);
                        $stmt->bind_param('i', $patient_id);
                        $stmt->execute();
                        $result_history = $stmt->get_result();

                        if ($result_history->num_rows > 0) {
                            while ($row = $result_history->fetch_assoc()) {
                                // Extract AM/PM from appointment_time
                                $time_format = date('g:i A', strtotime($row['appointment_time']));
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['appointment_date']) . '</td>';
                                echo '<td>' . htmlspecialchars($time_format) . '</td>'; // Only AM/PM format
                                echo '<td>' . htmlspecialchars($row['appointment_reason']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['patient_name']) . '</td>'; // Patient name
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4" class="text-center">No appointments found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-custom btn-sm mt-2" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Appointment Overview -->
<div class="col-md-8 col-xl-5 mb-6 mr-xl-2">
    <div class="widget-rounded-circle card-box">
        <div class="row">
            <div class="col-6">
                <div class="avatar-lg rounded-circle bg-soft-primary border-dark border">
                    <i class="fas fa-calendar-alt fa-2x avatar-title" style="color: black;"></i> 
                </div>
            </div>
            <div class="col-6">
                <div class="text-right">
                    <?php
                        // Ensure the database connection is established
                        if ($mysqli) {
                            // Count the number of appointments
                            $result = "SELECT count(*) FROM appointments WHERE patient_id = ?";
                            $stmt = $mysqli->prepare($result);
                            $stmt->bind_param('i', $patient_id);
                            if ($stmt) {
                                $stmt->execute();
                                $stmt->bind_result($appointment_count);
                                $stmt->fetch();
                                $stmt->close();
                            } else {
                                echo "Error in SQL query: " . $mysqli->error;
                            }
                        } else {
                            echo "Database connection error";
                        }
                    ?>
                    <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $appointment_count; ?></span></h3>
                    <p class="mb-1 text-truncate text-dark">Appointments</p>
                    
                    <!-- View Button -->
                    <button type="button" class="btn btn-custom btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#appointmentHistoryModal">View Appointment History</button>
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
            <button type="button" class="btn btn-custom btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#doctorModal">Close</button>
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



<div class="col-md-10 col-xl-8 mb-9 custom-left-margin">
    <div class="widget-rounded-circle">
        <div class="card-box card-box-graph">
            <h4 class="header-title mb-3">Your Appointments</h4>
            <canvas id="appointmentGraph" style="height: 400px; background-color: #f0f0f0;"></canvas>
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