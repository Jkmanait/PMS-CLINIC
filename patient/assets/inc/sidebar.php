<?php
session_start();
include('../configuration/config.php');

// Check if the patient is logged in
if (!isset($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit();
}

// Handle fetching medical records
$medical_records = [];
$patient_info = null;
$showModal = false; // Flag to determine if modal should be shown
$noRecordsFound = false; // Flag for no records found
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fetch_records'])) {
    $pat_number = trim($_POST['pat_number']);

    // Validate input
    if (!empty($pat_number)) {
        // Query to fetch medical records from his_patient_chart
        $chart_query = "SELECT * FROM his_patient_chart WHERE patient_chart_pat_number = ?";
        $stmt_chart = $mysqli->prepare($chart_query);
        if ($stmt_chart) {
            $stmt_chart->bind_param('s', $pat_number);
            $stmt_chart->execute();
            $chart_result = $stmt_chart->get_result();

            // Fetch chart records
            while ($row = $chart_result->fetch_assoc()) {
                $medical_records[] = $row;
            }
            $stmt_chart->close();
        } else {
            echo "<p class='text-danger'>Error preparing chart statement: " . htmlspecialchars($mysqli->error) . "</p>";
        }

        // Query to fetch patient info from his_patients
        $patient_query = "SELECT * FROM his_patients WHERE pat_number = ?";
        $stmt_patient = $mysqli->prepare($patient_query);
        if ($stmt_patient) {
            $stmt_patient->bind_param('s', $pat_number);
            $stmt_patient->execute();
            $patient_result = $stmt_patient->get_result();

            // Fetch patient info (should be one row)
            if ($patient_result->num_rows > 0) {
                $patient_info = $patient_result->fetch_assoc();
            }
            $stmt_patient->close();
        } else {
            echo "<p class='text-danger'>Error preparing patient statement: " . htmlspecialchars($mysqli->error) . "</p>";
        }

        // Set flags based on results
        $showModal = true;
        $noRecordsFound = empty($medical_records) && is_null($patient_info);
    } else {
        echo "<p class='text-danger'>Please fill in the Patient Number.</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    
    <?php include("assets/inc/head.php"); ?>
    <title>Access Medical Records</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 300px;
            height: 100vh;
            background-color: #f6e7e3; 
            color: black;
            padding: 20px;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
        }

        .content {
            margin-left: 300px;
            padding: 20px;
            width: 100%;
        }

        .btn-light-pink {
            background-color: #ff66b2;
            color: black;
            border: 1px solid #ff66b2;
        }

        .btn-light-pink:hover {
            background-color: #ff4d94;
            color: white;
            border-color: #ff4d94;
        }
    </style>
</head>
<body>
<div id="wrapper">

<?php include('assets/inc/nav.php'); ?>

<div class="sidebar">
    <div class="text-center w-75 m-auto">
    <br>
        <br>
        <br>
        <br>
        <h3>Access Your Records</h3>
   

    <form method="POST" class="mb-4">
        <div class="form-group mb-3">
            <label for="pat_number">Patient Number: (KPID)</label>
            <input type="text" class="form-control" name="pat_number" placeholder="XXXXXXXX" required>
        </div>
        <div class="text-center">
            <button type="submit" name="fetch_records" class="btn btn-light-pink btn-block mt-3">View Records</button>
        </div>
    </form>
    </div>
</div>
<br>
<br>
<br>
<br>

<div class="row">
    <div class="col-lg-2 col-xl-8"> <!-- Adjusted width for larger card -->
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-10" style="margin-left: 700px;"> <!-- Increase column width here as needed -->
                <div class="card" style="max-width: 90%; margin: 0px;"> <!-- Set max-width for control -->
                <div class="card-body" style="padding: 40px; margin-left: 20px;">
                    <?php if ($showModal && !$noRecordsFound): ?>
                        <div class="row">
                            <!-- Patient Information Section -->
                            <div class="col-md-6">
                                <h5>Your Medical Records:</h5>
                                <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($patient_info['pat_fname']); ?> <?php echo htmlspecialchars($patient_info['pat_lname']); ?></p>
                                <p><strong>Age:</strong> <?php echo htmlspecialchars($patient_info['pat_age']); ?> Years</p>
                                <p><strong>Sex:</strong> <?php echo htmlspecialchars($patient_info['pat_sex']); ?></p>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($patient_info['pat_addr']); ?></p>
                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($patient_info['pat_phone']); ?></p>
                                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($patient_info['pat_dob']); ?></p>
                                <p><strong>Ailment:</strong> <?php echo htmlspecialchars($patient_info['pat_ailment']); ?></p>
                            </div>

                            <!-- Patient Chart Section -->
                            <div class="col-md-6">
                                <div class="tab-pane" id="patient-chart">
                                    <ul class="list-unstyled timeline-sm" style="margin-left: 0;">
                                        <?php
                                        // Fetch patient chart records
                                        $chart_ret = "SELECT * FROM his_patient_chart WHERE patient_chart_pat_number = ?";
                                        $chart_stmt = $mysqli->prepare($chart_ret);
                                        $chart_stmt->bind_param('s', $pat_number);
                                        $chart_stmt->execute();
                                        $chart_res = $chart_stmt->get_result();

                                        if ($chart_res->num_rows > 0) {
                                            while ($chart_row = $chart_res->fetch_object()) {
                                        ?>
                                                <li class="timeline-sm-item">
                                                    <span class="timeline-sm-date"><?php echo date("Y-m-d", strtotime($chart_row->created_at)); ?></span>
                                                    <h5 class="mt-0 mb-1"><?php echo htmlspecialchars($chart_row->patient_chart_pat_ailment); ?></h5>
                                                    <p class="text-muted mt-2">
                                                        <strong>Weight:</strong> <?php echo htmlspecialchars($chart_row->patient_chart_weight); ?> kg<br>
                                                        <strong>Length:</strong> <?php echo htmlspecialchars($chart_row->patient_chart_length); ?> cm<br>
                                                        <strong>Temperature:</strong> <?php echo htmlspecialchars($chart_row->patient_chart_temp); ?><br>
                                                        <strong>Diagnosis:</strong> <?php echo htmlspecialchars($chart_row->patient_chart_diagnosis); ?><br>
                                                        <strong>Prescription:</strong> <?php echo htmlspecialchars($chart_row->patient_chart_prescription); ?>
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
                                </div>
                            </div>
                        </div>
                    <?php elseif ($showModal && $noRecordsFound): ?>
                        <p class="text-danger">No Medical Record Found</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


</body>
</html>
