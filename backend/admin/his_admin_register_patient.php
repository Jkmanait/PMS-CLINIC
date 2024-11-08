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

if (isset($_POST['add_patient'])) {
    $pat_fname = $_POST['pat_fname'];
    $pat_lname = $_POST['pat_lname'];
    $pat_phone = $_POST['pat_phone'];
    $pat_sex = $_POST['pat_sex'];
    $pat_addr = $_POST['pat_addr'];
    $pat_age = $_POST['pat_age'];
    $pat_dob = $_POST['pat_dob'];
    $pat_ailment = $_POST['pat_ailment'];
    $pat_parent_name = $_POST['pat_parent_name'];
    $pat_date_joined = $_POST['pat_date_joined']; 

    // Generate a unique patient number
    $pat_number = generateUniquePatientNumber($mysqli);

    // SQL to insert values into the updated table
    $query = "INSERT INTO his_patients (pat_fname, pat_lname, pat_dob, pat_age, pat_parent_name, pat_number, pat_addr, pat_phone, pat_sex, pat_date_joined, pat_ailment) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('sssssssssss', $pat_fname, $pat_lname, $pat_dob, $pat_age, $pat_parent_name, $pat_number, $pat_addr, $pat_phone, $pat_sex, $pat_date_joined, $pat_ailment);
    $stmt->execute();

    // Alert message
    if ($stmt) {
        $success = "Patient Details Added";
    } else {
        $err = "Please Try Again Or Try Later";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('assets/inc/head.php'); ?>
    <style>
        body, label, input, select, button {
            font-size: px;
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                        <li class="breadcrumb-item active">Add Patient</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Add Patient Details</h4>
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
                                                <label for="inputEmail4" class="col-form-label">First Name</label>
                                                <input type="text" required name="pat_fname" class="form-control" placeholder="Patient's First Name">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputPassword4" class="col-form-label">Last Name</label>
                                                <input required type="text" name="pat_lname" class="form-control" placeholder="Patient's Last Name">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="pat_dob" class="col-form-label">Date Of Birth</label>
                                                <input type="date" required name="pat_dob" class="form-control">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="pat_date_joined" class="col-form-label">Date of visit</label>
                                                <input type="date" required name="pat_date_joined" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="pat_age" class="col-form-label">Patient Age</label>
                                                <input required type="text" name="pat_age" class="form-control" placeholder="Patient's Age">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="pat_parent_name" class="col-form-label">Parent/Guardian Name</label>
                                                <input type="text" name="pat_parent_name" class="form-control" placeholder="Parent/Guardian Name">
                                            </div>    
                                        </div>
                                        <div class="form-row">                                 
                                            <div class="form-group col-md-6">
                                                <label for="pat_addr" class="col-form-label">Address</label>
                                                <input required type="text" class="form-control" name="pat_addr" placeholder="Patient's Address">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="pat_phone" class="col-form-label">Mobile Number</label>
                                                <input required type="text" name="pat_phone" class="form-control" maxlength="11" pattern="09\d{9}" title="Mobile number must start with 09 and be 11 digits long">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="pat_ailment" class="col-form-label">Patient Ailment</label>
                                                <input required type="text" name="pat_ailment" class="form-control">
                                            </div>
                                            <div class="form-group col-md-1">
                                                <label for="pat_sex" class="col-form-label">Patient Sex</label>
                                                <select required name="pat_sex" class="form-control">
                                                    <option>Male</option>
                                                    <option>Female</option>
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" name="add_patient" class="ladda-button btn btn-primary" data-style="expand-right">Add Patient</button>
                                    </form>
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
    <script src="assets/libs/ladda/spin.js"></script>
    <script src="assets/libs/ladda/ladda.js"></script>
    <script src="assets/js/pages/loading-btn.init.js"></script>
</body>
</html>
