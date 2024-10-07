<?php
session_start();
include('../../configuration/config.php');

// Fetch existing SOAP record based on soap_id (GET)
if (isset($_GET['soap_id'])) {
    $soap_id = $_GET['soap_id'];

    $ret = "SELECT * FROM his_soap_records WHERE soap_id=?";
    $stmt = $mysqli->prepare($ret);
    $stmt->bind_param('s', $soap_id);
    $stmt->execute();
    $res = $stmt->get_result();

    // Check if there is a matching record
    if ($res->num_rows > 0) {
        $row = $res->fetch_object();
    } else {
        echo "No SOAP record found for this Medical Record Number.";
    }
}

// Handle form submission (POST)
if (isset($_POST['add_soap_record'])) {
    $soap_pat_name = $_POST['soap_pat_name'];
    $soap_pat_adr = $_POST['soap_pat_adr'];
    $soap_pat_age = $_POST['soap_pat_age'];
    $soap_pat_number = $_POST['soap_pat_number'];
    $mdr_number = $_POST['mdr_number'];
    $soap_pat_ailment = $_POST['soap_pat_ailment'];
    $soap_subjective = $_POST['soap_subjective'];
    $soap_objective = $_POST['soap_objective'];
    $soap_assessment = $_POST['soap_assessment'];
    $soap_plan = $_POST['soap_plan'];

    // Generate a new soap_id or get it from the request as necessary
    $new_soap_id = uniqid(); // Generating a unique soap_id, can also be a new increment if preferred

    // SQL to insert captured values, including the soap_id
    $query = "INSERT INTO his_soap_records (soap_id, mdr_number, soap_pat_name, soap_pat_adr, soap_pat_age, soap_pat_number, soap_pat_ailment, soap_subjective, soap_objective, soap_assessment, soap_plan) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sssssssssss', $new_soap_id, $mdr_number, $soap_pat_name, $soap_pat_adr, $soap_pat_age, $soap_pat_number, $soap_pat_ailment, $soap_subjective, $soap_objective, $soap_assessment, $soap_plan);
    $stmt->execute();

    if ($stmt) {
        $success = "SOAP Record Added Successfully";
    } else {
        $err = "Please Try Again Or Try Later";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include('assets/inc/head.php'); ?>

<body>
    <div id="wrapper">
        <!-- Topbar Start -->
        <?php include("assets/inc/nav.php"); ?>
        <!-- Left Sidebar Start -->
        <?php include("assets/inc/sidebar.php"); ?>

        <!-- Start Page Content -->
        <div class="content-page">
            <div class="content">
                <!-- Start Content-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="his_admin_dashboard.php">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">SOAP Records</a></li>
                                        <li class="breadcrumb-item active">Add SOAP Record</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Add New SOAP Record</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Fill all fields</h4>

                                    <!-- Add SOAP Record Form -->
                                    <form method="post">
                                        <input type="hidden" name="soap_id" value="<?php echo $soap_id; ?>"> <!-- Ensure soap_id is passed -->

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="inputName" class="col-form-label">Patient Name</label>
                                                <input type="text" required="required" readonly name="soap_pat_name" value="<?php echo isset($row->soap_pat_name) ? $row->soap_pat_name : ''; ?>" class="form-control" id="inputName" placeholder="Patient's Name">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="col-form-label">Patient Age</label>
                                                <input type="text" name="soap_pat_age" value="<?php echo isset($row->soap_pat_age) ? $row->soap_pat_age : ''; ?>" class="form-control" required="required">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="col-form-label">Patient Address</label>
                                                <input type="text" name="soap_pat_adr" value="<?php echo isset($row->soap_pat_adr) ? $row->soap_pat_adr : ''; ?>" class="form-control" required="required">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label class="col-form-label">Patient Number</label>
                                                <input type="text" name="soap_pat_number" value="<?php echo isset($row->soap_pat_number) ? $row->soap_pat_number : ''; ?>" class="form-control" required="required">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="col-form-label">Patient Ailment</label>
                                                <input type="text" name="soap_pat_ailment" value="<?php echo isset($row->soap_pat_ailment) ? $row->soap_pat_ailment : ''; ?>" class="form-control" required="required">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="col-form-label">MDR Number</label>
                                                <input type="text" name="mdr_number" value="<?php echo isset($row->mdr_number) ? $row->mdr_number : ''; ?>" class="form-control" required="required">
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="form-group">
                                            <label class="col-form-label">Subjective</label>
                                            <textarea class="form-control" name="soap_subjective" required="required"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">Objective</label>
                                            <textarea class="form-control" name="soap_objective" required="required"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">Assessment</label>
                                            <textarea class="form-control" name="soap_assessment" required="required"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">Plan</label>
                                            <textarea class="form-control" name="soap_plan" required="required"></textarea>
                                        </div>

                                        <button type="submit" name="add_soap_record" class="btn btn-primary">Add Medical Record</button>
                                    </form>
                                    <!-- End SOAP Record Form -->

                                    <?php if (isset($success)) { ?>
                                        <div class="alert alert-success mt-3"><?php echo $success; ?></div>
                                    <?php } elseif (isset($err)) { ?>
                                        <div class="alert alert-danger mt-3"><?php echo $err; ?></div>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rightbar-overlay"></div>
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
</body>
</html>
