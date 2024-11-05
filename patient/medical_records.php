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
$showModal = false; // Flag to determine if modal should be shown
$noRecordsFound = false; // Flag for no records found
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fetch_records'])) {
    $pat_number = trim($_POST['pat_number']);

    // Validate input
    if (!empty($pat_number)) {
        // Query to fetch the medical records
        $query = "SELECT * FROM his_patient_chart WHERE patient_chart_pat_number = ?";
        $stmt = $mysqli->prepare($query);
        if ($stmt) {
            $stmt->bind_param('s', $pat_number);
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch records
            while ($row = $result->fetch_assoc()) {
                $medical_records[] = $row;
            }
            $stmt->close();

            // Set the flags to show the modal and check for records
            $showModal = true;
            $noRecordsFound = empty($medical_records);
        } else {
            echo "<p class='text-danger'>Error preparing statement: " . htmlspecialchars($mysqli->error) . "</p>";
        }
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    
    <style>
        body {
            background-color: #ffeef8; /* Light pink background */
            color: black; /* Default text color */
        }

        .page-title-box h3 {
            color: #ff66b2; /* Light pink for headings */
        }

        .form-control {
            border: 1px solid #ff66b2; /* Light pink border for input fields */
        }

        .btn-light-pink {
            background-color: #ff66b2; /* Light pink button background */
            color: black; /* Black text color */
            border: 1px solid #ff66b2; /* Light pink border */
        }

        .btn-light-pink:hover {
            background-color: #ff4d94; /* Slightly darker pink on hover */
            border-color: #ff4d94; /* Slightly darker pink border on hover */
        }

        .modal-header {
            background-color: #ff99cc; /* Lighter pink for modal header */
            color: black; /* Dark text for contrast */
        }

        .card-box {
            border: 1px solid #ff66b2; /* Light pink border for card boxes */
        }

        h6.text-danger {
            color: #d5006d; /* Slightly darker pink for important text */
        }

        .text-muted {
            color: #555; /* Darker text for muted information */
        }
    </style>
</head>
<body>

    <div id="wrapper">

        <?php include('assets/inc/nav.php'); ?>
        <br>
        <br>
        <br>
        <br>
        <div class="account-pages mt-5 mb-5">
            <div class="container"> 
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="text-center w-75 m-auto">
                                    <h3>Access Your Records</h3>
                                </div>

                                <form method="POST" class="mb-4">
                                    <div class="form-group mb-3">
                                        <label for="pat_number">Patient Number: (KPID)</label>
                                        <input type="text" class="form-control" name="pat_number" placeholder="XXXXXXXX" required>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" name="fetch_records" class="btn btn-light-pink btn-block mt-3">View Records</button>
                                    </div>
                                </form>
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->

                        <div class="modal fade" id="recordsModal" tabindex="-1" aria-labelledby="recordsModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 1500px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="recordsModalLabel">Your Medical Records</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if ($noRecordsFound): ?>
                    <p>No Medical Record</p>
                <?php else: ?>
                    <ul class="list-unstyled timeline-sm" style="margin-left: 0;">
                        
                           
                                <div class="row">
                                    <div class="col-xl-7">
                                        <p class="text-muted mb-2 font-13 info-text">
                                            <strong>Patient Name:</strong>
                                            <span class="ml-2"><?php echo htmlspecialchars($medical_records[0]['patient_chart_pat_name']); ?></span>
                                        </p>
                                        <p class="text-muted mb-2 font-13 info-text">
                                            <strong>Patient Sex:</strong>
                                            <span class="ml-2"><?php echo htmlspecialchars($medical_records[0]['patient_chart_pat_sex']); ?></span>
                                        </p>
                                        <p class="text-muted mb-2 font-13 info-text">
                                            <strong>Age:</strong>
                                            <span class="ml-2"><?php echo htmlspecialchars($medical_records[0]['patient_chart_pat_age']); ?> Years</span>
                                        </p>
                                        <p class="text-muted mb-2 font-13 info-text">
                                            <strong>KPID:</strong>
                                            <span class="ml-2"><?php echo htmlspecialchars($medical_records[0]['patient_chart_pat_number']); ?></span>
                                        </p>
                                        <p class="text-muted mb-2 font-13 info-text">
                                            <strong>Parent/Guardian Name:</strong>
                                            <span class="ml-2"><?php echo htmlspecialchars($medical_records[0]['patient_chart_pat_parent_name']); ?></span>
                                        </p>
                                        <p class="text-muted mb-2 font-13 info-text">
                                            <strong>Address:</strong>
                                            <span class="ml-2"><?php echo htmlspecialchars($medical_records[0]['patient_chart_pat_adr']); ?></span>
                                        </p>
                                    </div>
                                    <?php foreach ($medical_records as $record): ?>
                                    <li class="timeline-sm-item">
                                    <span class="timeline-sm-date"><?php echo date("Y-m-d", strtotime($record['created_at'])); ?></span>
                                    <h5 class="mt-0 mb-1">Ailment: <?php echo htmlspecialchars($record['patient_chart_pat_ailment']); ?></h5>
                                    <div class="col-xl-5">
                                        <p class="text-muted mb-2 font-13 info-text">
                                            <strong>Weight:</strong>
                                            <span class="ml-2"><?php echo nl2br(htmlspecialchars($record['patient_chart_weight'])); ?> kg</span>
                                        </p>
                                        <p class="text-muted mb-2 font-13 info-text">
                                            <strong>Length:</strong>
                                            <span class="ml-2"><?php echo nl2br(htmlspecialchars($record['patient_chart_length'])); ?> cm</span>
                                        </p>
                                        <p class="text-muted mb-2 font-13 info-text">
                                            <strong>Temperature:</strong>
                                            <span class="ml-2"><?php echo nl2br(htmlspecialchars($record['patient_chart_temp'])); ?></span>
                                        </p>
                                        <p class="text-muted mb-2 font-13 info-text">
                                            <strong>Diagnosis:</strong>
                                            <span class="ml-2"><?php echo nl2br(htmlspecialchars($record['patient_chart_diagnosis'])); ?></span>
                                        </p>
                                        <p class="text-muted mb-2 font-13 info-text">
                                            <strong>Prescription:</strong>
                                            <span class="ml-2"><?php echo nl2br(htmlspecialchars($record['patient_chart_prescription'])); ?></span>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-custom btn-sm mt-2" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        
        </div>
    </div>
</div>

                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div> <!-- end container -->
        </div> <!-- end account-pages -->

        <?php include('assets/inc/footer.php'); ?>

    </div>

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

    <script>
        <?php if ($showModal): ?>
            var myModal = new bootstrap.Modal(document.getElementById('recordsModal'));
            myModal.show();
        <?php endif; ?>
    </script>

</body>
</html>