<?php
session_start();
include('../../configuration/config.php');

if(isset($_POST['add_xray'])) {
    $xray_pat_name = $_POST['xray_pat_name'];
    $xray_pat_number = $_POST['xray_pat_number'];
    $xray_pat_adr = $_POST['xray_pat_adr'];
    $xray_pat_age = $_POST['xray_pat_age'];
    $xray_description = $_POST['xray_description'];

    // Ensure directory exists and create if not
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/xrays/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // File upload logic
    $xray_image = $_FILES['xray_image']['name'];
    $target_file = $target_dir . basename($xray_image);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an image
    if (!empty($_FILES["xray_image"]["tmp_name"])) {
        $check = getimagesize($_FILES["xray_image"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["xray_image"]["tmp_name"], $target_file)) {
                // Insert into database
                $query = "INSERT INTO his_xrays (xray_pat_name, xray_pat_number, xray_pat_adr, xray_pat_age, xray_description, xray_image_path) 
                          VALUES(?,?,?,?,?,?)";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param('ssssss', $xray_pat_name, $xray_pat_number, $xray_pat_adr, $xray_pat_age, $xray_description, $target_file);
                $stmt->execute();

                if($stmt) {
                    $success = "X-ray uploaded successfully.";
                } else {
                    $err = "Error occurred while uploading.";
                }
            } else {
                $err = "There was an error uploading the file.";
            }
        } else {
            $err = "File is not an image.";
        }
    } else {
        $err = "No file uploaded.";
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
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">X-Ray</a></li>
                                                <li class="breadcrumb-item active">Add X-Ray</li>
                                            </ol>
                                        </div>
                                        <h4 class="page-title">Add X-Ray</h4>
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
                                            <form method="post" enctype="multipart/form-data">
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label for="inputEmail4" class="col-form-label">Patient Name</label>
                                                        <input type="text" required="required" readonly name="xray_pat_name" value="<?php echo $row->pat_fname;?> <?php echo $row->pat_lname;?>" class="form-control" id="inputEmail4" placeholder="Patient's Name">
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="inputPassword4" class="col-form-label">Patient Age</label>
                                                        <input required="required" type="text" readonly name="xray_pat_age" value="<?php echo $row->pat_age;?>" class="form-control"  id="inputPassword4" placeholder="Patient Age">
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="inputPassword4" class="col-form-label">Patient Address</label>
                                                        <input required="required" type="text" readonly name="xray_pat_adr" value="<?php echo $row->pat_addr;?>" class="form-control"  id="inputPassword4" placeholder="Patient Address">
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label for="inputEmail4" class="col-form-label">Patient Number</label>
                                                        <input type="text" required="required" readonly name="xray_pat_number" value="<?php echo $row->pat_number;?>" class="form-control" id="inputEmail4" placeholder="Patient Number">
                                                    </div>
                                                </div>
                                                <?php }?>

                                                <div class="form-group col-md-8">
                                                    <label class="col-form-label">X-ray Description</label>
                                                    <textarea name="xray_description" class="form-control" placeholder="Describe the X-ray" required></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-form-label">Upload X-ray Image</label>
                                                    <input type="file" name="xray_image" class="form-control" required>
                                                </div>

                                                <button type="submit" name="add_xray" class="ladda-button btn btn-primary" data-style="expand-right">Upload X-Ray</button>
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