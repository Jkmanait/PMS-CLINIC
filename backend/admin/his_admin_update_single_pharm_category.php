
<?php
	session_start();
	include('../../configuration/config.php');
		if(isset($_POST['update_pharmaceutical_category']))
		{
			$pharm_cat_name = $_GET['pharm_cat_name'];
			$pharm_cat_vendor = $_POST['pharm_cat_vendor'];
			$pharm_cat_desc=$_POST['pharm_cat_desc'];
            
            
            //sql to update captured values
			$query="UPDATE  his_pharmaceuticals_categories SET  pharm_cat_vendor=?, pharm_cat_desc=? WHERE pharm_cat_name = ?";
			$stmt = $mysqli->prepare($query);
			$rc=$stmt->bind_param('sss',   $pharm_cat_vendor, $pharm_cat_desc, $pharm_cat_name);
			$stmt->execute();
			/*
			*Use Sweet Alerts Instead Of This Fucked Up Javascript Alerts
			*echo"<script>alert('Successfully Created Account Proceed To Log In ');</script>";
			*/ 
			//declare a varible which will be passed to alert function
			if($stmt)
			{
				$success = "Pharmaceutical Category Upadated ";
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
                $pharm_cat_name=$_GET['pharm_cat_name'];
                $ret="SELECT  * FROM his_pharmaceuticals_categories WHERE pharm_cat_name=?";
                $stmt= $mysqli->prepare($ret) ;
                $stmt->bind_param('s',$pharm_cat_name);
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pharmaceuticals</a></li>
                                            <li class="breadcrumb-item active">Manage Pharmaceutical Category</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title"><?php echo $row->pharm_cat_name;?></h4>
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
                                            <div class="form-row" >
                                                <div class="form-group col-md-6" style="display:none">
                                                    <label for="inputEmail4" class="col-form-label">Pharmaceutical Category Name</label>
                                                    <input  type="text" value="<?php echo $row->pharm_cat_name;?>" required="required" name="pharm_cat_name" class="form-control" id="inputEmail4" >
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="inputPassword4" class="col-form-label">Pharmaceutical Category Vendor</label>
                                                    <input required="required" value="<?php echo $row->pharm_cat_vendor;?>" type="text" name="pharm_cat_vendor" class="form-control"  id="inputPassword4">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="inputAddress" class="col-form-label">Pharmaceutical Category Description</label>
                                                <textarea required="required" type="text" class="form-control" name="pharm_cat_desc" id="editor"><?php echo $row->pharm_cat_desc;?></textarea>
                                            </div>

                                           <button type="submit" name="update_pharmaceutical_category" class="ladda-button btn btn-danger" data-style="expand-right">Update Category</button>

                                        </form>
                                     
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
                <?php }?>

        </div>
        <!-- END wrapper -->
        <!--Load CK EDITOR Javascript-->
        <script src="//cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
        <script type="text/javascript">
        CKEDITOR.replace('editor')
        </script>
       
        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

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