<?php
    session_start();
    include('../configuration/config.php');
    
    // Check if the patient is logged in
    if (!isset($_SESSION['patientLogin'])) {
        $_SESSION['loginMsg'] = 'You must log in first!';
        header("Location: login.php");
        exit();
    }

    // Check if patientID is set in session
    if (!isset($_SESSION['patientID'])) {
        die("Error: Patient ID not found. Please log in.");
    }

    $patientID = $_SESSION['patientID']; // Assuming patient ID is stored in session

    // Ensure adminID is passed correctly (e.g., from query string or form input)
    if (!isset($_GET['adminID'])) {
        die("Error: Admin ID not provided.");
    }
    
    $adminID = intval($_GET['adminID']); // Admin ID from query parameter
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $appointmentDate = $_POST['date'];
        $appointmentTime = $_POST['time'];
        
        // Check if admin ID exists in the database
        $checkAdmin = $mysqli->prepare("SELECT ad_id FROM his_admin WHERE ad_id = ?");
        $checkAdmin->bind_param("i", $adminID);
        $checkAdmin->execute();
        $result = $checkAdmin->get_result();
        
        if ($result->num_rows == 0) {
            die("Error: Admin ID does not exist.");
        }

        // Insert the appointment into the database
        $insertAppointment = "INSERT INTO appointments (admin_id, patient_id, appointment_date, appointment_time) 
                              VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($insertAppointment);
        $stmt->bind_param("iiss", $adminID, $patientID, $appointmentDate, $appointmentTime);
        
        if ($stmt->execute()) {
            echo "Appointment successfully booked!";
        } else {
            echo "Error: " . $mysqli->error;
        }
        
        $stmt->close();
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

            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-body">
                                <h3>Book an Appointment</h3>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label for="date">Choose Appointment Date</label>
                                        <input type="date" name="date" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="time">Choose Appointment Time</label>
                                        <input type="time" name="time" class="form-control" required>
                                    </div>
                                    <input type="hidden" name="adminID" value="<?= $adminID ?>"> <!-- The ID of the admin -->
                                    <button type="submit" class="btn btn-primary mt-3">Book Appointment</button>
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
