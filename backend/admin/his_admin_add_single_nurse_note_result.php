<!--Server side code to handle  Patient Registration-->
<?php
	session_start();
	include('assets/inc/config.php');
		if(isset($_POST['add_nurse_note_result']))
		{
			$nur_note_name = $_POST['nur_note_name'];
			$nur_note_ailment = $_POST['nur_note_ailment'];
            $nur_note_number  = $_POST['nur_note_number'];
            $nur_note_tests = $_POST['nur_note_tests'];
            $nur_number  = $_GET['nur_number'];
            $nur_note_results = $_POST['nur_note_results'];
            //$pres_ins = $_POST['pres_ins'];
            //$pres_pat_ailment = $_POST['pres_pat_ailment'];
            //sql to insert captured values
			$query="UPDATE   his_nurse_note  SET nur_note_name=?, nur_note_ailment=?, nur_note_number=?, nur_note_tests=?, nur_note_results=? WHERE  nur_number = ? ";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('ssssss', $nur_note_name, $nur_note_ailment, $nur_note_number, $nur_note_tests, $nur_note_results, $nur_number);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Nurse's Note Results Addded";
			}
			else {
				$err = "Please Try Again Or Try Later";
			}
			
			
		}
?>
<!--End Server Side-->
<!--End Patient Registration-->
<!DOCTYPE html>
<html lang="en">
    
    <!--Head-->
    <?php include('assets/inc/head.php');?>
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
                $nur_number = $_GET['nur_number'];
                $ret="SELECT  * FROM his_nurse_note WHERE nur_number=?";
                $stmt= $mysqli->prepare($ret) ;
                $stmt->bind_param('s',$nur_number);
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
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Laboratory</a></li>
                                                <li class="breadcrumb-item active">Add Nurse's Note Result</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">Add Nurse's Note Result</h4>
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

                                                    <div class="form-group col-md-6">
                                                        <label for="inputEmail4" class="col-form-label">Patient Name</label>
                                                        <input type="text" required="required" readonly name="nur_note_name" value="<?php echo $row->nur_note_name;?>" class="form-control" id="inputEmail4" placeholder="Patient's First Name">
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="inputPassword4" class="col-form-label">Patient Ailment</label>
                                                        <input required="required" type="text" readonly name="nur_note_ailment" value="<?php echo $row->nur_note_ailment;?>" class="form-control"  id="inputPassword4" placeholder="Patient`s Last Name">
                                                    </div>

                                                </div>

                                                <div class="form-row">

                                                    <div class="form-group col-md-12">
                                                        <label for="inputEmail4" class="col-form-label">Patient Number</label>
                                                        <input type="text" required="required" readonly name="nur_note_number" value="<?php echo $row->nur_note_number;?>" class="form-control" id="inputEmail4" placeholder="DD/MM/YYYY">
                                                    </div>


                                                </div>

                                                
                                                <hr>
                                                

                                                <div class="form-group">
                                                        <label for="inputAddress" class="col-form-label">Nurse's Note Tests</label>
                                                        <textarea required="required"  type="text" class="form-control" name="nur_note_tests" id="editor"><?php echo $row->nur_note_tests;?></textarea>
                                                </div>

                                                <div class="form-group">
                                                        <label for="inputAddress" class="col-form-label">Nurse's Note Result</label>
                                                        <textarea required="required"   type="text" class="form-control" name="nur_note_results" id="editor1"></textarea>
                                                </div>

                                                <button type="submit" name="add_nurse_note_result" class="ladda-button btn btn-success" data-style="expand-right">Add Nurse's Note Result</button>

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
            <?php }?>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

       
        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        <script src="//cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
        <script type="text/javascript">
         CKEDITOR.replace('editor')
        </script>
        <!-- <script type="text/javascript">
         CKEDITOR.replace('editor1')
        </script> -->

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