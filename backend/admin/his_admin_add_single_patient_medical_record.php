<?php
session_start();
include('../../configuration/config.php');

if (isset($_POST['add_soap_record'])) {
    $mdr_number = $_POST['mdr_number']; // Medical Record Number
    $soap_pat_name = $_POST['soap_pat_name'];
    $soap_pat_parent_name = $_POST['soap_pat_parent_name']; // Added
    $soap_pat_sex = $_POST['soap_pat_sex']; //added
    $soap_pat_age = $_POST['soap_pat_age'];
    $soap_pat_adr = $_POST['soap_pat_adr'];
    $soap_pat_number = $_POST['soap_pat_number'];
    $soap_pat_ailment = $_POST['soap_pat_ailment'];
    $soap_subjective = $_POST['soap_subjective'];
    $soap_objective = $_POST['soap_objective'];
    $soap_assessment = $_POST['soap_assessment'];
    $soap_plan = $_POST['soap_plan'];

    // First, check if a SOAP record already exists for the patient
    $check_query = "SELECT * FROM his_soap_records WHERE soap_pat_number = ?";
    $check_stmt = $mysqli->prepare($check_query);
    $check_stmt->bind_param('s', $soap_pat_number);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Record already exists
        $err = "Medical record for this patient already exists!";
    } else {
        // Proceed to insert the new SOAP record
        $query = "INSERT INTO his_soap_records (mdr_number, soap_pat_name, soap_pat_parent_name, soap_pat_sex, soap_pat_age, soap_pat_adr, soap_pat_number, soap_pat_ailment, soap_subjective, soap_objective, soap_assessment, soap_plan) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssssssssssss', $mdr_number, $soap_pat_name, $soap_pat_parent_name, $soap_pat_sex, $soap_pat_age, $soap_pat_adr, $soap_pat_number, $soap_pat_ailment, $soap_subjective, $soap_objective, $soap_assessment, $soap_plan);

        // Success or error message
        if ($stmt->execute()) {
            $success = "Medical Record Added Successfully";
        } else {
            $err = "Please Try Again Later";
        }
    }
}
?>

<!--End Server Side-->
<!--End Patient Registration-->
<!DOCTYPE html>
<html lang="en">

<!--Head-->
<?php include('assets/inc/head.php'); ?>

<style>
    /* Make text bigger and color black */
    body,
    label,
    th,
    td,
    h4,
    h1,
    h2,
    h3,
    h5,
    h6,
    .breadcrumb-item a {
        font-size: 18px; /* Adjust size as needed */
        color: black; /* Text color */
    }

    /* Increase font size for table headers */
    th {
        font-size: 20px; /* Larger font for headers */
    }

    /* Larger font size for page titles */
    h4.page-title {
        font-size: 24px;
        color: black;
    }

    /* Search input and buttons */
    input[type="text"],
    button {
        font-size: 18px;
        color: black;
    }

    /* Pagination */
    .pagination {
        font-size: 18px;
    }
</style>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include("assets/inc/nav.php"); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php"); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <?php
        $pat_number = $_GET['pat_number'];
        $ret = "SELECT * FROM his_patients WHERE pat_number=?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('s', $pat_number);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {
        ?>
            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        <!-- start page title -->
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="his_admin_dashboard.php">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Medical Records</a></li>
                                            <li class="breadcrumb-item active">Add Medical Record</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Add Medical Record</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        <!-- Form row -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Fill all fields</h4>
                                        <!--Add Patient Form-->
                                        <form method="post">
                                            <div class="form-row">
                                                <!-- Patient Name -->
                                                <div class="form-group col-md-4">
                                                    <label for="inputName" class="col-form-label">Patient Name</label>
                                                    <input type="text" required="required" readonly name="soap_pat_name" value="<?php echo $row->pat_fname; ?> <?php echo $row->pat_lname; ?>" class="form-control" id="inputName" placeholder="Patient's Name">
                                                </div>

                                                <!-- Patient Age -->
                                                <div class="form-group col-md-4">
                                                    <label for="inputAge" class="col-form-label">Patient Age</label>
                                                    <input required="required" type="text" readonly name="soap_pat_age" value="<?php echo $row->pat_age; ?>" class="form-control" id="inputAge" placeholder="Patient's Age">
                                                </div>

                                                <!-- Patient Sex -->
                                                <div class="form-group col-md-4">
                                                    <label for="inputAddress" class="col-form-label">Patient Sex</label>
                                                    <input required="required" type="text" readonly name="soap_pat_sex" value="<?php echo $row->pat_sex; ?>" class="form-control" id="inputAddress" placeholder="Patient Sex">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <!-- Patient Address -->
                                                <div class="form-group col-md-4">
                                                    <label for="inputAddress" class="col-form-label">Patient Address</label>
                                                    <input required="required" type="text" readonly name="soap_pat_adr" value="<?php echo $row->pat_addr; ?>" class="form-control" id="inputAddress" placeholder="Patient's Address">
                                                </div>

                                                <!-- Parent/Guardian Name -->
                                                <div class="form-group col-md-4">
                                                    <label for="inputParentName" class="col-form-label">Parent/Guardian Name</label>
                                                    <input type="text" required="required" readonly name="soap_pat_parent_name" value="<?php echo $row->pat_parent_name; ?>" class="form-control" id="inputParentName" placeholder="Parent/Guardian Name">
                                                </div>

                                                <!-- Patient Ailment -->
                                                <div class="form-group col-md-4">
                                                <label class="col-form-label">Patient Ailment</label>
                                                <input type="text" name="soap_pat_ailment" value="<?php echo $row->pat_ailment; ?>" class="form-control" required="required">
                                            </div>
                                            </div>

                                            <!-- Patient Number (Hidden) -->
                                            <input type="hidden" name="soap_pat_number" value="<?php echo $row->pat_number; ?>">

                                            <?php } ?>
                                            <hr>

                                            <!-- Medical Record Number (Hidden) -->
                                            <div class="form-row">
                                                <div class="form-group col-md-2" style="display:none">
                                                    <?php 
                                                    // Function to generate a random MDR number
                                                    function generateMDRNumber() {
                                                        // Generate 16 random digits
                                                        $randomDigits = substr(str_shuffle('0123456789'), 0, 16);
                                                        // Format it as ####-####-####-####
                                                        $mdr_number = substr($randomDigits, 0, 4) . '-' .
                                                                      substr($randomDigits, 4, 4) . '-' .
                                                                      substr($randomDigits, 0, 4) . '-' .
                                                                      substr($randomDigits, 4, 4);
                                                        return $mdr_number;
                                                    }
                                                    $mdr_number = generateMDRNumber();
                                                    ?>
                                                    <label for="inputMDR" class="col-form-label">Medical Record Number</label>
                                                    <input type="text" name="mdr_number" value="<?php echo $mdr_number; ?>" class="form-control" id="inputMDR" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <!-- SOAP Subjective -->
                                                <label for="soapSubjective" class="col-form-label">Subjective</label>
                                                <textarea required="required" class="form-control" name="soap_subjective" id="soapSubjective" placeholder="Enter subjective information"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <!-- SOAP Objective -->
                                                <label for="soapObjective" class="col-form-label">Objective</label>
                                                <textarea required="required" class="form-control" name="soap_objective" id="soapObjective" placeholder="Enter objective information"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <!-- SOAP Assessment -->
                                                <label for="soapAssessment" class="col-form-label">Assessment</label>
                                                <textarea required="required" class="form-control" name="soap_assessment" id="soapAssessment" placeholder="Enter assessment information"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <!-- SOAP Plan -->
                                                <label for="soapPlan" class="col-form-label">Plan</label>
                                                <textarea required="required" class="form-control" name="soap_plan" id="soapPlan" placeholder="Enter plan information"></textarea>
                                            </div>

                                            <button type="submit" name="add_soap_record" class="btn btn-primary">Add Medical Record</button>
                                        </form>

                                        <?php if (isset($success)) { ?>
                                            <div class="alert alert-success" role="alert">
                                                <?php echo $success; ?>
                                            </div>
                                        <?php } ?>

                                        <?php if (isset($err)) { ?>
                                            <div class="alert alert-danger" role="alert">
                                                <?php echo $err; ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- container-fluid -->
                </div> <!-- content -->
            </div> <!-- content-page -->
        </div> <!-- wrapper -->
    </div>

    <!-- Footer -->
    <?php include('assets/inc/footer.php'); ?>
    <!-- End Footer -->
     <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

<!-- Vendor js -->
<script src="assets/js/vendor.min.js"></script>

<!-- App js -->
<script src="assets/js/app.min.js"></script>
</body>

</html>
