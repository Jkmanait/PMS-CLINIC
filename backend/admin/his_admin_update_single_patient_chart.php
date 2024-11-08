<?php
session_start();
include('../../configuration/config.php');

// Fetch existing patient chart record based on patient_chart_id (GET)
if (isset($_GET['patient_chart_id'])) {
    $patient_chart_id = $_GET['patient_chart_id'];

    $ret = "SELECT * FROM his_patient_chart WHERE patient_chart_id=?";
    $stmt = $mysqli->prepare($ret);
    $stmt->bind_param('s', $patient_chart_id);
    $stmt->execute();
    $res = $stmt->get_result();

    // Check if there is a matching record
    if ($res->num_rows > 0) {
        $row = $res->fetch_object();
    } else {
        echo "No Medical record found for this Medical Record Number.";
    }
}

// Handle form submission (POST)
if (isset($_POST['add_chart_record'])) {
  
    $patient_chart_pat_name = $_POST['patient_chart_pat_name'];
    $patient_chart_pat_sex = $_POST['patient_chart_pat_sex'];
    $patient_chart_pat_parent_name = $_POST['patient_chart_pat_parent_name'];
    $patient_chart_pat_adr = $_POST['patient_chart_pat_adr'];
    $patient_chart_pat_age = $_POST['patient_chart_pat_age'];
    $patient_chart_pat_number = $_POST['patient_chart_pat_number'];
    $patient_chart_pat_ailment = $_POST['patient_chart_pat_ailment'];
    $patient_chart_weight = $_POST['patient_chart_weight'];
    $patient_chart_length = $_POST['patient_chart_length'];
    $patient_chart_temp = $_POST['patient_chart_temp'];
    $patient_chart_diagnosis = $_POST['patient_chart_diagnosis'];
    $patient_chart_prescription = $_POST['patient_chart_prescription'];
    $patient_chart_pat_date_joined = $_POST['patient_chart_pat_date_joined'];

    // SQL to insert captured values
    $query = "INSERT INTO his_patient_chart ( patient_chart_pat_name, patient_chart_pat_sex, patient_chart_pat_parent_name, patient_chart_pat_adr, patient_chart_pat_age, patient_chart_pat_number, patient_chart_pat_ailment, patient_chart_weight, patient_chart_length, patient_chart_temp, patient_chart_diagnosis, patient_chart_prescription, patient_chart_pat_date_joined) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sssssssssssss', $patient_chart_pat_name, $patient_chart_pat_sex, $patient_chart_pat_parent_name, $patient_chart_pat_adr, $patient_chart_pat_age, $patient_chart_pat_number, $patient_chart_pat_ailment, $patient_chart_weight, $patient_chart_length, $patient_chart_temp, $patient_chart_diagnosis, $patient_chart_prescription, $patient_chart_pat_date_joined);
    $stmt->execute();

    if ($stmt) {
        $success = "Medical Record Added Successfully";
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
        <?php include("assets/inc/nav.php"); ?>
        <?php include("assets/inc/sidebar.php"); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
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
                                <h4 class="page-title">Add New Patient Chart</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Fill all fields</h4>

                                    <form method="post">
                                        <input type="hidden" name="patient_chart_id" value="<?php echo isset($row->patient_chart_id) ? $row->patient_chart_id : ''; ?>">

                                        <div class="form-row">
                                        <div class="form-group col-md-4">
                                                <label for="inputName" class="col-form-label">Patient Name</label>
                                                <input type="text" required="required" readonly name="patient_chart_pat_name" value="<?php echo isset($row->patient_chart_pat_name) ? $row->patient_chart_pat_name : ''; ?>" class="form-control" id="inputName" placeholder="Patient's Name">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="col-form-label">Patient Age</label>
                                                <input type="text" required="required" readonly name="patient_chart_pat_age" value="<?php echo isset($row->patient_chart_pat_age) ? $row->patient_chart_pat_age : ''; ?>" class="form-control">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="col-form-label">Patient Sex</label>
                                                <input type="text" required="required" readonly name="patient_chart_pat_sex" value="<?php echo isset($row->patient_chart_pat_sex) ? $row->patient_chart_pat_sex : ''; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label class="col-form-label">Patient Number</label>
                                                <input type="text" required="required" readonly name="patient_chart_pat_number" value="<?php echo isset($row->patient_chart_pat_number) ? $row->patient_chart_pat_number : ''; ?>" class="form-control">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="col-form-label">Patient Parent Name</label>
                                                <input type="text" required="required" readonly name="patient_chart_pat_parent_name" value="<?php echo isset($row->patient_chart_pat_parent_name) ? $row->patient_chart_pat_parent_name : ''; ?>" class="form-control">
                                            </div>                                                                      
                                            <div class="form-group col-md-4">
                                                <label class="col-form-label">Address</label>
                                                <input type="text" required="required" readonly name="patient_chart_pat_adr" value="<?php echo isset($row->patient_chart_pat_adr) ? $row->patient_chart_pat_adr : ''; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="form-row">                                        
                                            <div class="form-group col-md-4">
                                                <label class="col-form-label">Patient Ailment</label>
                                                <input type="text" name="patient_chart_pat_ailment" value="<?php echo isset($row->patient_chart_pat_ailment) ? $row->patient_chart_pat_ailment : ''; ?>" class="form-control" placeholder="Patient's Ailment">
                                            </div>
                                    
                                        <div class="form-group col-md-4">
                                            <label class="col-form-label">Weight</label>
                                            <textarea class="form-control" name="patient_chart_weight" required="required" placeholder="Enter Patient Weight"></textarea>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="col-form-label">Length</label>
                                            <textarea class="form-control" name="patient_chart_length" required="required" placeholder="Enter Patient Length"></textarea>
                                        </div>
                                        <div class="form-group col-md-4">
                                                <label for="patientchartTemp" class="col-form-label">Temperature</label>
                                                <textarea required="required" class="form-control" name="patient_chart_temp" id="patientchartTemp" placeholder="Enter Patient Temperature"></textarea>
                                            </div>

                                            <!-- Patient Number (Hidden) -->
                                            <input type="hidden" name="patient_chart_pat_date_joined" value="<?php echo $row->pat_date_joined; ?>">

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
                                        <div class="form-group col-md-4">
                                            <label class="col-form-label">Diagnosis</label>
                                            <textarea class="form-control" name="patient_chart_diagnosis" required="required" placeholder="Enter Patient Diagnosis"></textarea>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="col-form-label">Prescription</label>
                                            <textarea class="form-control" name="patient_chart_prescription" required="required" placeholder="Enter Patient Prescription"></textarea>
                                        </div>

                                        <button type="submit" name="add_chart_record" class="btn btn-primary">Add Medical Record</button>
                                    </form>

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
