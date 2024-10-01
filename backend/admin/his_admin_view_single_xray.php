<?php
  session_start();
  include('../../configuration/config.php');
  include('assets/inc/checklogin.php');
  check_login();
  $aid=$_SESSION['ad_id'];
?>
<!DOCTYPE html>
<html lang="en">
    
<?php include ('assets/inc/head.php');?>

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
            <?php include('assets/inc/nav.php');?>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
                <?php include("assets/inc/sidebar.php");?>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->
            <?php
     $xray_pat_numberr=$_GET['xray_pat_number'];
     $xray_id=$_GET['xray_id'];
     $ret="SELECT * FROM his_xrays WHERE xray_id = ?";
     $stmt= $mysqli->prepare($ret);
     $stmt->bind_param('i', $xray_id);
     $stmt->execute();
     $res = $stmt->get_result();
 
     while($row = $res->fetch_object()) {
         $mysqlDateTime = $row->uploaded_at;

    
            
?>

<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="his_admin_dashboard.php">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="javascript: void(0);">X-Ray</a></li>
                                <li class="breadcrumb-item active">View X-Ray</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-xl-5">
                                <div class="tab-content pt-0">
                                    <div class="tab-pane active show" id="product-1-item">
                                        <!-- Display the image -->
                                        <img src="../../xray/<?php echo $row->xray_image_path; ?>" alt="X-Ray" class="img-fluid mx-auto d-block rounded">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-7">
                                <div class="pl-xl-3 mt-3 mt-xl-0">
                                    <h2 class="mb-3">Name : <?php echo htmlspecialchars($row->xray_pat_name); ?></h2>
                                    <hr>
                                    <h3 class="mb-3">Age : <?php echo htmlspecialchars($row->xray_pat_age); ?> Years</h3>
                                    <hr>
                                    <h3 class="mb-3">Patient Number : <?php echo htmlspecialchars($row->xray_pat_number); ?></h3>
                                    <hr>
                                    <h3 class="mb-3">Date Recorded : <?php echo date("d/m/Y - h:m:s", strtotime($mysqlDateTime)); ?></h3>
                                    <hr>
                                    <h2 class="align-centre">X-Ray Description</h2>
                                    <hr>
                                    <p class="text-muted mb-4">
                                        <?php echo htmlspecialchars($row->xray_description); ?>
                                    </p>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php include('assets/inc/footer.php'); ?>
</div>

<?php }?>


            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

        

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        
    </body>

</html>