<?php
session_start();
include('../../configuration/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php');?>

<style>
    /* Make text bigger and color black */
    body, label, th, td, h4, h1, h2, h3, h5, h6, .breadcrumb-item a {
        font-size: 18px; /* Adjust size as needed */
        color: black;    /* Text color */
    }

    th {
        font-size: 20px;
        background-color: #d3d3d3; /* Light gray background */
        color: black; /* Black text */
    }

    /* Style for female patients */
    .female {
        background-color: #ffcccb; /* Light pink background */
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

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <br>
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Patient Chart</a></li>
                                        <li class="breadcrumb-item active">Add patient Chart</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Patient Details</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <h4 class="header-title"></h4>
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-12 text-sm-center form-inline">
                                            <div class="form-group mr-2" style="display:none">
                                                <select id="demo-foo-filter-status" class="custom-select custom-select-sm">
                                                    <option value="">Show all</option>
                                                    <option value="Discharged">Discharged</option>
                                                    <option value="OutPatients">OutPatients</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <input id="demo-foo-search" type="text" placeholder="Search" class="form-control form-control-sm" autocomplete="on">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered toggle-circle mb-0">
                                        <thead>
                                            <tr>
                                                <th data-toggle="true">Check</th>
                                                <th data-toggle="true">Patient Name</th>
                                                <th data-hide="phone">Age</th>
                                                <th data-hide="phone">Patient Sex</th>
                                                <th data-hide="phone">Address</th>
                                                <th data-hide="phone">Guardian Name</th>
                                                <th data-hide="phone">Mobile Number</th>
                                                <th data-hide="phone">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        /*
                                            * Get details of all patients ordered by pat_id
                                            * Join with his_patient_chart to check for existing charts
                                            */
                                        $ret = "SELECT p.*, 
                                                        (SELECT COUNT(*) FROM his_patient_chart WHERE patient_chart_pat_number = p.pat_number) as has_chart 
                                                FROM his_patients p 
                                                ORDER BY p.pat_id ASC"; // Order by pat_id in ascending order
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        while ($row = $res->fetch_object()) {
                                        ?>
                                            <tr class="<?php echo ($row->pat_sex == 'Female') ? 'female' : ''; ?>">
                                                <td>
                                                    <?php if ($row->has_chart > 0): ?>
                                                        <i class="fas fa-check" style="color: green;"></i> <!-- Check mark if chart exists -->
                                                    <?php else: ?>
                                                        <i class="fas fa-times" style="color: red;"></i> <!-- Cross mark if no chart -->
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $row->pat_fname; ?> <?php echo $row->pat_lname; ?></td>
                                                <td><?php echo $row->pat_age; ?> Year's Old</td>
                                                <td><?php echo $row->pat_sex; ?></td>
                                                <td><?php echo $row->pat_addr; ?></td>
                                                <td><?php echo $row->pat_parent_name; ?></td>
                                                <td><?php echo $row->pat_phone; ?></td>
                                                <td><a href="his_admin_add_single_patient_chart.php?pat_number=<?php echo $row->pat_number;?>" class="badge badge-success"><i class="fas fa-file-signature"></i> Add Patient Chart</a></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                        <tfoot>
                                        <tr class="active">
                                            <td colspan="8">
                                                <div class="text-right">
                                                    <ul class="pagination pagination-rounded justify-content-end footable-pagination m-t-10 mb-0"></ul>
                                                </div>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div> <!-- end .table-responsive-->
                            </div> <!-- end card-box -->
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

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
    
</body>

</html>
