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
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php'); ?>
        <!-- end Topbar -->

        <div class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h3>Access Your Medical Records</h3>

                            <!-- Form for entering MDR Number and Patient Number -->
                            <form method="POST" class="mb-4">
                                <div class="form-group col-md-4">
                                    <label for="mdr_number">MRN:</label>
                                    <input type="text" class="form-control" name="mdr_number" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="pat_number">KPID:</label>
                                    <input type="text" class="form-control" name="pat_number" required>
                                </div>
                                <button type="submit" name="fetch_records" class="btn btn-primary">View Records</button>
                            </form>

                            <!-- Modal for displaying medical records -->
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
                                                                    </div> <!-- end col -->

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
                                                                    </div> <!-- end col -->
                                                                </div> <!-- end row -->
                                                            </div> <!-- end card -->
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Start -->
        <?php include('assets/inc/footer.php'); ?>
        <!-- end Footer -->

    </div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>
    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

    <script>
        // Check if the modal should be shown based on PHP variable
        <?php if ($showModal): ?>
            var myModal = new bootstrap.Modal(document.getElementById('recordsModal'));
            myModal.show();
        <?php endif; ?>
    </script>

</body>
</html>
