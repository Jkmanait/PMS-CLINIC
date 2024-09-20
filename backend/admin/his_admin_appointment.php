<?php
	session_start();
	include('../../configuration/config.php');

    // Check if the form was submitted
    if (isset($_POST['book_appointment'])) {
        // Capture form data
        $admin_id = $_POST['admin_id']; // Admin ID (from form)
        $patient_id = $_SESSION['patientID']; // Assuming patient ID is stored in session
        $appointment_date = $_POST['appointment_date'];
        $appointment_time = $_POST['appointment_time'];
        
        // Prepare SQL query to insert appointment data
        $query = "INSERT INTO appointments (admin_id, patient_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('iiss', $admin_id, $patient_id, $appointment_date, $appointment_time);
        $stmt->execute();
        
        // Check if the statement executed successfully
        if ($stmt) {
            // Appointment booked successfully
            $success = "Appointment booked successfully!";
        } else {
            // There was an error
            $err = "Failed to book appointment. Please try again.";
        }
        
        // Close the statement
        $stmt->close();
    }

    // Query to fetch appointment data
    $query = "SELECT 
                a.appointment_date, 
                a.appointment_time, 
                a.created_at,
                p.patient_name 
              FROM appointments a 
              JOIN patients p ON a.patient_id = p.patient_id";  // Assuming you have a 'patients' table

    $appointments = $mysqli->query($query); // Run the query
?>

<!DOCTYPE html>
<html lang="en">
    <!-- Head Code -->
    <?php include("assets/inc/head.php"); ?>

    <body>
        <div id="wrapper">
            <!-- Topbar Start -->
            <?php include('assets/inc/nav.php'); ?>
            <!-- End Topbar -->

            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <h3>Appointments</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Booked On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($appointments->num_rows > 0) { 
                                    while($row = $appointments->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?= $row['patient_name'] ?></td>
                                        <td><?= $row['appointment_date'] ?></td>
                                        <td><?= $row['appointment_time'] ?></td>
                                        <td><?= $row['created_at'] ?></td>
                                    </tr>
                                <?php } 
                                } else { ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No Appointments Found</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php'); ?>
            <!-- End Footer -->

            <script src="assets/js/vendor.min.js"></script>
            <script src="assets/libs/flatpickr/flatpickr.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
        </div>
    </body>
</html>
