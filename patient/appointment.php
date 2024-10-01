<?php
session_start();
include('../configuration/config.php');

// Check if the patient is logged in and patient_id is set
if (!isset($_SESSION['patient_id'])) {
    // Redirect the user to the login page if patient_id is not set
    header('Location: login.php');
    exit();
} else {
    // If patient_id is set, store it in a variable
    $patient_id = $_SESSION['patient_id'];
}

// Handle form submission
if (isset($_POST['book_appointment'])) {
    // Get the posted values
    $appointment_date = $_POST['date'];
    $appointment_time = $_POST['time'];
    $appointment_reason = $_POST['appointment_reason'];
    $appointment_status = 'Pending'; // Default status

    // Prevent booking past dates
    if (strtotime($appointment_date) < strtotime(date('Y-m-d'))) {
        $err = "Cannot book an appointment in the past.";
    } else {
        // SQL query to insert the appointment data
        $query = "INSERT INTO appointments (patient_id, appointment_date, appointment_time, appointment_reason, appointment_status)
                  VALUES (?, ?, ?, ?, ?)";

        // Check if the SQL statement can be prepared
        if ($stmt = $mysqli->prepare($query)) {
            // Bind the parameters to the query
            $stmt->bind_param('issss', $patient_id, $appointment_date, $appointment_time, $appointment_reason, $appointment_status);

            // Execute the query and check for success
            if ($stmt->execute()) {
                $success = "Appointment Booked Successfully";
            } else {
                // Log detailed error for debugging
                $err = "Failed to Book Appointment. Error: " . $stmt->error;
            }
        } else {
            // Log detailed error for debugging
            $err = "Failed to prepare the query. Error: " . $mysqli->error;
        }
    }
}
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

            <br>

            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-body">
                                <h3>Book an Appointment</h3>
                                <!-- Display success or error message -->
                                <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
                                <?php if (isset($err)) { echo "<div class='alert alert-danger'>$err</div>"; } ?>
                                
                                <form method="POST">
                                    <div class="form-group">
                                        <label for="date">Choose Appointment Date</label>
                                        <input type="date" name="date" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="time">Choose Appointment Time</label>
                                        <input type="time" name="time" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="appointment_reason">Reason for Appointment</label>
                                        <input type="text" name="appointment_reason" class="form-control" required>
                                    </div>
                                    <button type="submit" name="book_appointment" class="btn btn-primary mt-3">Book Appointment</button>
                                </form>
                            </div>
                        </div>
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
