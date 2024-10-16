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
    $mdr_number = trim($_POST['mdr_number']);
    $pat_number = trim($_POST['pat_number']);

    // Validate input
    if (!empty($mdr_number) && !empty($pat_number)) {
        // Query to fetch the medical records
        $query = "SELECT * FROM his_soap_records WHERE mdr_number = ? AND soap_pat_number = ?";
        $stmt = $mysqli->prepare($query);
        if ($stmt) {
            $stmt->bind_param('ss', $mdr_number, $pat_number);
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
        echo "<p class='text-danger'>Please fill in both fields.</p>";
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
                                    <h3>Access Your Medical Records</h3>
                                </div>

                                <form method="POST" class="mb-4">
                                    <div class="form-group mb-3">
                                        <label for="mdr_number">MRN: (Medical Record Number)</label>
                                        <input type="text" class="form-control" name="mdr_number" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="pat_number">Patient Number:</label>
                                        <input type="text" class="form-control" name="pat_number" required>
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
                                            <?php foreach ($medical_records as $record): ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card-box">
                                                            <div class="row text-left mt-3">
                                                                <div class="col-xl-7">
                                                                    <div class="pl-xl-3 mt-3 mt-xl-0">
                                                                        <h6 class="mb-3">Patient Name: <?php echo htmlspecialchars($record['soap_pat_name']); ?></h6>
                                                                        <hr>
                                                                        <h6 class="text-danger">Patient Sex: <?php echo htmlspecialchars($record['soap_pat_sex']); ?></h6>
                                                                        <hr>
                                                                        <h6 class="text-danger">Age: <?php echo htmlspecialchars($record['soap_pat_age']); ?> Years</h6>
                                                                        <hr>
                                                                        <h6 class="text-danger">MRN: <?php echo htmlspecialchars($record['mdr_number']); ?></h6>
                                                                        <hr>
                                                                        <h6 class="text-danger">KPID: <?php echo htmlspecialchars($record['soap_pat_number']); ?></h6>
                                                                        <hr>
                                                                        <h6 class="text-danger">Patient Ailment: <?php echo htmlspecialchars($record['soap_pat_ailment']); ?></h6>
                                                                        <hr>
                                                                        <h6 class="text-danger">Parent/Guardian Name: <?php echo htmlspecialchars($record['soap_pat_parent_name']); ?></h6>
                                                                        <hr>
                                                                        <h6 class="text-danger">Address: <?php echo htmlspecialchars($record['soap_pat_adr']); ?></h6>
                                                                        <hr>
                                                                        <h6 class="text-danger">Date Recorded: <?php echo date("d/m/Y - h:i:s", strtotime($record['created_at'])); ?></h6>
                                                                        <hr>
                                                                    </div>
                                                                </div>

                                                                <div class="col-xl-5">
                                                                    <div class="pl-xl-3 mt-3 mt-xl-0">
                                                                        <h4 class="align-centre">Medical Records</h4>
                                                                        <hr>
                                                                        <h5>Subjective:</h5>
                                                                        <p class="text-muted mb-4"><?php echo nl2br(htmlspecialchars($record['soap_subjective'])); ?></p>
                                                                        <h5>Objective:</h5>
                                                                        <p class="text-muted mb-4"><?php echo nl2br(htmlspecialchars($record['soap_objective'])); ?></p>
                                                                        <h5>Assessment:</h5>
                                                                        <p class="text-muted mb-4"><?php echo nl2br(htmlspecialchars($record['soap_assessment'])); ?></p>
                                                                        <h5>Plan:</h5>
                                                                        <p class="text-muted mb-4"><?php echo nl2br(htmlspecialchars($record['soap_plan'])); ?></p>
                                                                        <hr>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-custom btn-sm mt-2" data-bs-dismiss="modal">Close</button>
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
