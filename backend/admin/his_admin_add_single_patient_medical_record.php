<?php
    session_start();
    include('../../configuration/config.php');
    
    if (isset($_POST['add_soap_record'])) {
        $mdr_number = $_POST['mdr_number']; // Medical Record Number
        $soap_pat_name = $_POST['soap_pat_name'];
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
            $query = "INSERT INTO his_soap_records (mdr_number, soap_pat_name, soap_pat_age, soap_pat_adr, soap_pat_number, soap_pat_ailment, soap_subjective, soap_objective, soap_assessment, soap_plan) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ssssssssss', $mdr_number, $soap_pat_name, $soap_pat_age, $soap_pat_adr, $soap_pat_number, $soap_pat_ailment, $soap_subjective, $soap_objective, $soap_assessment, $soap_plan);
            
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
    <?php include('assets/inc/head.php');?>

    <style>
    /* Make text bigger and color black */
    body, label, th, td, h4, h1, h2, h3, h5, h6, .breadcrumb-item a {
        font-size: 18px; /* Adjust size as needed */
        color: black;    /* Text color */
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
    input[type="text"], button {
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
            <?php include("assets/inc/nav.php");?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <?php include("assets/inc/sidebar.php");?>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->
            <?php
                $pat_number = $_GET['pat_number'];
                $ret="SELECT  * FROM his_patients WHERE pat_number=?";
                $stmt= $mysqli->prepare($ret) ;
                $stmt->bind_param('s',$pat_number);
                $stmt->execute() ;//ok
                $res=$stmt->get_result();
                //$cnt=1;
                while($row=$res->fetch_object())
                {
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
                                                        <input type="text" required="required" readonly name="soap_pat_name" value="<?php echo $row->pat_fname;?> <?php echo $row->pat_lname;?>" class="form-control" id="inputName" placeholder="Patient's Name">
                                                    </div>

                                                    <!-- Patient Age -->
                                                    <div class="form-group col-md-4">
                                                        <label for="inputAge" class="col-form-label">Patient Age</label>
                                                        <input required="required" type="text" readonly name="soap_pat_age" value="<?php echo $row->pat_age;?>" class="form-control" id="inputAge" placeholder="Patient's Age">
                                                    </div>

                                                    <!-- Patient Address -->
                                                    <div class="form-group col-md-4">
                                                        <label for="inputAddress" class="col-form-label">Patient Address</label>
                                                        <input required="required" type="text" readonly name="soap_pat_adr" value="<?php echo $row->pat_addr;?>" class="form-control" id="inputAddress" placeholder="Patient's Address">
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <!-- Patient Number -->
                                                    <div class="form-group col-md-6">
                                                        <label for="inputNumber" class="col-form-label">Patient Number</label>
                                                        <input type="text" required="required" readonly name="soap_pat_number" value="<?php echo $row->pat_number;?>" class="form-control" id="inputNumber" placeholder="Patient Number">
                                                    </div>

                                                    <!-- Patient Ailment -->
                                                    <div class="form-group col-md-6">
                                                        <label for="inputAilment" class="col-form-label">Patient Ailment</label>
                                                        <input required="required" type="text" readonly name="soap_pat_ailment" value="<?php echo $row->pat_ailment;?>" class="form-control" id="inputAilment" placeholder="Patient Ailment">
                                                    </div>
                                                </div>
                                                <?php }?>
                                                <hr>

                                                <!-- Medical Record Number (Hidden) -->
                                                <div class="form-row">
                                                    <div class="form-group col-md-2" style="display:none">
                                                        <?php 
                                                            $length = 5;    
                                                            $mdr_number =  substr(str_shuffle('0123456789'),1,$length);
                                                        ?>
                                                        <label for="inputMDR" class="col-form-label">Medical Record Number</label>
                                                        <input type="text" name="mdr_number" value="<?php echo $mdr_number;?>" class="form-control" id="inputMDR">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <!-- SOAP Subjective -->
                                                    <label for="soapSubjective" class="col-form-label">Subjective</label>
                                                    <textarea required="required" type="text" class="form-control" name="soap_subjective" id="soapSubjective" placeholder="Enter subjective information"></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <!-- SOAP Objective -->
                                                    <label for="soapObjective" class="col-form-label">Objective</label>
                                                    <textarea required="required" type="text" class="form-control" name="soap_objective" id="soapObjective" placeholder="Enter objective information"></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <!-- SOAP Assessment -->
                                                    <label for="soapAssessment" class="col-form-label">Assessment</label>
                                                    <textarea required="required" type="text" class="form-control" name="soap_assessment" id="soapAssessment" placeholder="Enter assessment"></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <!-- SOAP Plan -->
                                                    <label for="soapPlan" class="col-form-label">Plan</label>
                                                    <textarea required="required" type="text" class="form-control" name="soap_plan" id="soapPlan" placeholder="Enter plan"></textarea>
                                                </div>

                                                <!-- Submit Button -->
                                                <button type="submit" name="add_soap_record" class="ladda-button btn btn-primary" data-style="expand-right">Add Medical Record</button>
                                            </form>

                                            <!--End Patient Form-->
                                        </div> <!-- end card-body -->
                                    </div> <!-- end card-->
                                </div> <!-- end col -->
                            </div>
                            <!-- end row -->

                        </div> <!-- container -->

                    </div> <!-- content -->

                    <!-- Footer Start -->
                    <?php include('assets/inc/footer.php');?>
                    <!-- end Footer -->

                </div>
            

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

       
        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        <script src="//cdn.ckeditor.com/4.6.2/basic/ckeditor.js"></script>
        <script type="text/javascript">
        CKEDITOR.replace('editor')
        </script>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- App js-->
        <script src="assets/js/app.min.js"></script>

        <!-- Loading buttons js -->
        <script src="assets/libs/ladda/spin.js"></script>
        <script src="assets/libs/ladda/ladda.js"></script>

        <!-- Buttons init js-->
        <script src="assets/js/pages/loading-btn.init.js"></script>
        
    </body>

</html>