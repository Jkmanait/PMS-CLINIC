<?php
session_start();
include('../../configuration/config.php');

// Function to generate a unique patient number
function generateUniquePatientNumber($mysqli) {
    $length = 7;
    do {
        $pat_number = substr(str_shuffle('0123456789'), 1, $length);
        
        // Check if the patient number already exists in the database
        $stmt = $mysqli->prepare("SELECT pat_number FROM his_patients WHERE pat_number = ?");
        $stmt->bind_param('s', $pat_number);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;

    } while ($exists);  // Keep generating a new number until it's unique

    return $pat_number;
}

if (isset($_POST['add_appointment'])) {
    $patient_name = $_POST['patient_name'];
    $patient_phone = $_POST['patient_phone'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $appointment_reason = $_POST['appointment_reason'];
    $guardian_name = $_POST['guardian_name']; // New field for guardian name

    // You need to fetch or generate the patient_id here
    $patient_id = 1; // Placeholder, replace with actual logic to get the patient ID

    // Insert the appointment into the appointments table
    $insert_appointment_query = "INSERT INTO appointments (patient_id, patient_name, appointment_date, appointment_time, appointment_reason, guardian_name) 
                                 VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($insert_appointment_query)) {
        $stmt->bind_param('isssss', $patient_id, $patient_name, $appointment_date, $appointment_time, $appointment_reason, $guardian_name);
        
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $success = "Appointment Details Added";
        } else {
            $err = "Please Try Again Or Try Later";
        }
    } else {
        $err = "Database error: " . $mysqli->error;
    }
}
?>


<!-- HTML Form Code remains unchanged -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('assets/inc/head.php'); ?>
    <style>
        body, label, input, select, button {
            font-size: 14px;
            color: black;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <?php include("assets/inc/nav.php"); ?>
        <?php include("assets/inc/sidebar.php"); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="his_admin_dashboard.php">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Appointments</a></li>
                                        <li class="breadcrumb-item active">Add Appointment</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Add Appointment Details</h4>
                            </div>
                        </div>
                    </div>     

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Fill All Fields</h4>
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="patient_name" class="col-form-label">Patient Name</label>
                                                <input type="text" required name="patient_name" class="form-control" placeholder="Patient's Name">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="patient_phone" class="col-form-label">Patient Phone</label>
                                                <input required type="text" name="patient_phone" class="form-control" placeholder="Patient's Phone Number">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="appointment_date" class="col-form-label">Appointment Date</label>
                                                <input type="date" required name="appointment_date" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="appointment_time" class="col-form-label">Appointment Time</label>
                                                <select required name="appointment_time" class="form-control">
                                                    <option value="AM">AM</option>
                                                    <option value="PM">PM</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="appointment_reason" class="col-form-label">Appointment Reason</label>
                                                <input required type="text" name="appointment_reason" class="form-control" placeholder="Reason for the Appointment">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="guardian_name" class="col-form-label">Guardian's Name</label>
                                                <input required type="text" name="guardian_name" class="form-control" placeholder="Guardian's Name">
                                            </div>
                                        </div>

                                        <button type="submit" name="add_appointment" class="btn btn-primary">Add Appointment</button>
                                    </form>
                                    <?php if(isset($success)): ?>
                                        <div class="alert alert-success mt-3"><?= $success; ?></div>
                                    <?php endif; ?>
                                    <?php if(isset($err)): ?>
                                        <div class="alert alert-danger mt-3"><?= $err; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <?php include('assets/inc/footer.php'); ?>
        </div>
    </div>

    <div class="rightbar-overlay"></div>
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
</body>
</html>
