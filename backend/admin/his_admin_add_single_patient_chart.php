<?php
session_start();
include('../../configuration/config.php');

if (isset($_POST['add_patient_chart_record'])) {  
    
    // Retrieve data from POST request
    $patient_chart_pat_name = $_POST['patient_chart_pat_name'];
    $patient_chart_pat_parent_name = $_POST['patient_chart_pat_parent_name'];
    $patient_chart_pat_sex = $_POST['patient_chart_pat_sex'];
    $patient_chart_pat_age = $_POST['patient_chart_pat_age'];
    $patient_chart_pat_adr = $_POST['patient_chart_pat_adr'];
    $patient_chart_pat_number = $_POST['patient_chart_pat_number'];
    $patient_chart_pat_ailment = $_POST['patient_chart_pat_ailment'];
    $patient_chart_weight = $_POST['patient_chart_weight'];
    $patient_chart_length = $_POST['patient_chart_length'];
    $patient_chart_temp = $_POST['patient_chart_temp'];
    $patient_chart_diagnosis = $_POST['patient_chart_diagnosis'];
    $patient_chart_prescription = $_POST['patient_chart_prescription'];
    $patient_chart_pat_date_joined = $_POST['patient_chart_pat_date_joined']; // New field for date joined

    // Check if a record already exists for the patient
    $check_query = "SELECT * FROM his_patient_chart WHERE patient_chart_pat_number = ?";
    $check_stmt = $mysqli->prepare($check_query);
    $check_stmt->bind_param('s', $patient_chart_pat_number);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $err = "Medical record for this patient already exists!";
    } else {
        // Insert new record with the date joined
        $query = "INSERT INTO his_patient_chart (
                    patient_chart_pat_name, patient_chart_pat_parent_name, patient_chart_pat_sex, 
                    patient_chart_pat_age, patient_chart_pat_adr, patient_chart_pat_number, 
                    patient_chart_pat_ailment, patient_chart_weight, patient_chart_length, 
                    patient_chart_temp, patient_chart_diagnosis, patient_chart_prescription,
                    patient_chart_pat_date_joined
                  ) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                  
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssssssssssss', $patient_chart_pat_name, $patient_chart_pat_parent_name, 
            $patient_chart_pat_sex, $patient_chart_pat_age, $patient_chart_pat_adr, 
            $patient_chart_pat_number, $patient_chart_pat_ailment, $patient_chart_weight, 
            $patient_chart_length, $patient_chart_temp, $patient_chart_diagnosis, 
            $patient_chart_prescription, $patient_chart_pat_date_joined);

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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Patient Chart</a></li>
                                            <li class="breadcrumb-item active">Add Patient Chart</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">Add Patient Chart</h4>
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
                                                    <input type="text" required="required" readonly name="patient_chart_pat_name" value="<?php echo $row->pat_fname; ?> <?php echo $row->pat_lname; ?>" class="form-control" id="inputName" placeholder="Patient's Name">
                                                </div>

                                                <!-- Patient Age -->
                                                <div class="form-group col-md-4">
                                                    <label for="inputAge" class="col-form-label">Patient Age</label>
                                                    <input required="required" type="text" readonly name="patient_chart_pat_age" value="<?php echo $row->pat_age; ?>" class="form-control" id="inputAge" placeholder="Patient's Age">
                                                </div>

                                                <!-- Patient Sex -->
                                                <div class="form-group col-md-4">
                                                    <label for="inputAddress" class="col-form-label">Patient Sex</label>
                                                    <input required="required" type="text" readonly name="patient_chart_pat_sex" value="<?php echo $row->pat_sex; ?>" class="form-control" id="inputAddress" placeholder="Patient Sex">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <!-- Patient Address -->
                                                <div class="form-group col-md-4">
                                                    <label for="inputAddress" class="col-form-label">Patient Address</label>
                                                    <input required="required" type="text" readonly name="patient_chart_pat_adr" value="<?php echo $row->pat_addr; ?>" class="form-control" id="inputAddress" placeholder="Patient's Address">
                                                </div>

                                                <!-- Parent/Guardian Name -->
                                                <div class="form-group col-md-4">
                                                    <label for="inputParentName" class="col-form-label">Parent/Guardian Name</label>
                                                    <input type="text" required="required" readonly name="patient_chart_pat_parent_name" value="<?php echo $row->pat_parent_name; ?>" class="form-control" id="inputParentName" placeholder="Parent/Guardian Name">
                                                </div>

                                                <!-- Patient Ailment -->
                                                <div class="form-group col-md-4">
                                                <label class="col-form-label">Patient Ailment</label>
                                                <input type="text" name="patient_chart_pat_ailment" value="<?php echo $row->pat_ailment; ?>" class="form-control" required="required">
                                            </div>
                                            </div>

                                            <!-- Patient Number (Hidden) -->
                                            <input type="hidden" name="patient_chart_pat_number" value="<?php echo $row->pat_number; ?>">
                                            <input type="hidden" name="patient_chart_pat_date_joined" value="<?php echo $row->pat_date_joined; ?>">
                                            <?php } ?>
                                            
                                            <hr>

                                            
                                            <div class="form-row">
                                                <!-- patient chart weight -->
                                                <div class="form-group col-md-4">
                                                <label for="patientchartWeight" class="col-form-label">Weight</label>
                                                <textarea required="required" class="form-control" name="patient_chart_weight" id="patientchartWeight" placeholder="Enter Patient Weight"></textarea>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <!-- patient chart lenght -->
                                                <label for="patientchartLength" class="col-form-label">Length</label>
                                                <textarea required="required" class="form-control" name="patient_chart_length" id="patientchartLength" placeholder="Enter Patient Length"></textarea>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="patientchartTemp" class="col-form-label">Temperature</label>
                                                <textarea required="required" class="form-control" name="patient_chart_temp" id="patientchartTemp" placeholder="Enter Patient Temperature"></textarea>
                                            </div>

                                            <script>
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    const temperatureField = document.getElementById("patientchartTemp");

                                                    // Use the blur event to detect when the user leaves the field
                                                    temperatureField.addEventListener("blur", function() {
                                                        let value = temperatureField.value;

                                                        // Append "°C" if the field is not empty and doesn't already have "°C"
                                                        if (value && !value.endsWith("°C")) {
                                                            temperatureField.value = value + "°C";
                                                        }
                                                    });

                                                    // Optional: Remove "°C" on focus, allowing the user to edit easily
                                                    temperatureField.addEventListener("focus", function() {
                                                        let value = temperatureField.value;

                                                        // Remove the "°C" suffix if it's there, for easier editing
                                                        if (value.endsWith("°C")) {
                                                            temperatureField.value = value.slice(0, -2);
                                                        }
                                                    });
                                                });
                                            </script>

                                            <div class="form-group col-md-6">
                                                <!-- patient chart Diagnosis -->
                                                <label for="patientchartDiagnosis" class="col-form-label">Diagnosis</label>
                                                <textarea required="required" class="form-control" name="patient_chart_diagnosis" id="patientchartDiagnosis" placeholder="Enter Patient Diagnosis"></textarea>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <!-- patient chart Prescription -->
                                                <label for="patientchartPrescription" class="col-form-label">Prescription</label>
                                                <textarea required="required" class="form-control" name="patient_chart_prescription" id="patientchartPrescription" placeholder="Enter Patient Prescription"></textarea>
                                            </div>
                                            </div>
                                            <button type="submit" name="add_patient_chart_record" class="btn btn-primary">Add Patient Chart</button>
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
