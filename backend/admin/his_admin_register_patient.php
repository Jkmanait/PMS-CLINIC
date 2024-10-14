<!--Server side code to handle  Patient Registration-->
<?php
    session_start();
    include('../../configuration/config.php');
    if(isset($_POST['add_patient'])) {
        $pat_fname = $_POST['pat_fname'];
        $pat_lname = $_POST['pat_lname'];
        $pat_number = $_POST['pat_number'];
        $pat_phone = $_POST['pat_phone'];
        $pat_sex = $_POST['pat_sex'];
        $pat_addr = $_POST['pat_addr'];
        $pat_age = $_POST['pat_age'];
        $pat_dob = $_POST['pat_dob'];
        $pat_ailment = $_POST['pat_ailment'];
        $pat_parent_name = $_POST['pat_parent_name']; // New field for parent or guardian name
        
        // SQL to insert captured values, including the new column pat_parent_name
        $query = "INSERT INTO his_patients (pat_fname, pat_ailment, pat_lname, pat_age, pat_dob, pat_number, pat_phone, pat_sex, pat_addr, pat_parent_name) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ssssssssss', $pat_fname, $pat_ailment, $pat_lname, $pat_age, $pat_dob, $pat_number, $pat_phone, $pat_sex, $pat_addr, $pat_parent_name);
        $stmt->execute();
        
        // Declare a variable which will be passed to alert function
        if($stmt) {
            $success = "Patient Details Added";
        } else {
            $err = "Please Try Again Or Try Later";
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
    /* Make all text uppercase and black */
    body, label, input, select, button {
        font-size: px;
        color: black;
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                        <li class="breadcrumb-item active">Add Patient</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Add Patient Details</h4>
                            </div>
                        </div>
                    </div>     
                    <!-- end page title --> 
                    <!-- Form row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Fill All Fields</h4>
                                    <!-- Add Patient Form -->
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputEmail4" class="col-form-label">First Name</label>
                                                <input type="text" required="required" name="pat_fname" class="form-control" id="inputEmail4" placeholder="Patient's First Name">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputPassword4" class="col-form-label">Last Name</label>
                                                <input required="required" type="text" name="pat_lname" class="form-control" id="inputPassword4" placeholder="Patient's Last Name">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="inputEmail4" class="col-form-label">Date Of Birth</label>
                                                <input type="date" required="required" name="pat_dob" class="form-control" id="inputEmail4" placeholder="DD/MM/YYYY">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="inputPassword4" class="col-form-label">Patient Age</label>
                                                <input required="required" type="text" name="pat_age" class="form-control" id="inputPassword4" placeholder="Patient's Age">
                                            </div>
                                        </div>

                                        <!-- Parent Name Field -->
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="pat_parent_name" class="col-form-label">Parent/Guardian Name</label>
                                                <input type="text" name="pat_parent_name" class="form-control" id="pat_parent_name" placeholder="Parent/Guardian Name">
                                            </div>                                        

                                            <div class="form-group col-md-6">
                                                <label for="inputAddress" class="col-form-label">Address</label>
                                                <input required="required" type="text" class="form-control" name="pat_addr" id="inputAddress" placeholder="Patient's Address">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="inputCity" class="col-form-label">Mobile Number</label>
                                                <input required="required" type="text" name="pat_phone" class="form-control" id="inputCity" maxlength="11" pattern="09\d{9}" title="Mobile number must start with 09 and be 11 digits long">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label for="inputCity" class="col-form-label">Patient Ailment</label>
                                                <input required="required" type="text" name="pat_ailment" class="form-control" id="inputCity">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="inputState" class="col-form-label">Patient Sex</label>
                                                <select id="inputState" required="required" name="pat_sex" class="form-control">
                                                    <option>Male</option>
                                                    <option>Female</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2" style="display:none">
                                            <?php
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

                                            // Call the function to get a unique patient number
                                            $pat_number = generateUniquePatientNumber($mysqli);
                                            ?>

                                                <label for="inputZip" class="col-form-label">Patient Number</label>
                                                <input type="text" name="pat_number" value="<?php echo $pat_number;?>" class="form-control" id="inputZip">
                                            </div>
                                        </div>

                                        <button type="submit" name="add_patient" class="ladda-button btn btn-primary" data-style="expand-right">Add Patient</button>

                                    </form>
                                    <!-- End Patient Form -->
                                </div> <!-- end card-body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php'); ?>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->


    <!-- Right bar overlay -->
    <div class="rightbar-overlay"></div>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

    <!-- Loading buttons js -->
    <script src="assets/libs/ladda/spin.js"></script>
    <script src="assets/libs/ladda/ladda.js"></script>

    <!-- Buttons init js -->
    <script src="assets/js/pages/loading-btn.init.js"></script>
    
</body>

</html>
