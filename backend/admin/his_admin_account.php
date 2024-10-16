<?php
session_start();
include('../../configuration/config.php');
if (isset($_POST['update_profile'])) {
    $ad_fname = $_POST['ad_fname'];
    $ad_lname = $_POST['ad_lname'];
    $ad_id = $_SESSION['ad_id'];
    $ad_email = $_POST['ad_email'];
    $ad_dpic = $_FILES["ad_dpic"]["name"];
    move_uploaded_file($_FILES["ad_dpic"]["tmp_name"], "assets/images/users/" . $_FILES["ad_dpic"]["name"]);

    // SQL to update captured values
    $query = "UPDATE his_admin SET ad_fname=?, ad_lname=?, ad_email=?, ad_dpic=? WHERE ad_id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ssssi', $ad_fname, $ad_lname, $ad_email, $ad_dpic, $ad_id);
    $stmt->execute();

    if ($stmt) {
        $success = "Profile Updated";
    } else {
        $err = "Please Try Again Or Try Later";
    }
}

// Change Password
if (isset($_POST['update_pwd'])) {
    $ad_id = $_SESSION['ad_id'];
    $ad_pwd = sha1(md5($_POST['ad_pwd'])); // double encrypt 

    // SQL to update captured values
    $query = "UPDATE his_admin SET ad_pwd =? WHERE ad_id = ?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('si', $ad_pwd, $ad_id);
    $stmt->execute();

    if ($stmt) {
        $success = "Password Updated";
    } else {
        $err = "Please Try Again Or Try Later";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include('assets/inc/head.php'); ?>

<style>
    /* Body and text colors */
    body {
        background-color: #ffe6f2; /* Light pink background */
        font-size: 18px; /* Font size adjustment */
    }

    /* Text color adjustments */
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
        color: #333; /* Darker text for contrast */
    }

    /* Increase font size for table headers */
    th {
        font-size: 20px; /* Larger font for headers */
    }

    /* Larger font size for page titles */
    h4.page-title {
        font-size: 24px;
        color: #333; /* Darker text for contrast */
    }

    /* Search input and buttons */
    input[type="text"],
    button {
        font-size: 18px;
        color: #333; /* Darker text for input fields */
    }

    /* Pagination */
    .pagination {
        font-size: 18px;
    }

    /* Card box styles */
    .card-box {
        background-color: #fff; /* White background for cards */
        border: 1px solid #f2b2d1; /* Light pink border */
        border-radius: 8px; /* Rounded corners */
        padding: 20px; /* Padding inside the card */
    }

    /* Button styles */
    .btn-success {
        background-color: #ff80b3; /* Light pink button color */
        border-color: #ff4d94; /* Darker pink border color */
    }

    /* Button hover effect */
    .btn-success:hover {
        background-color: #ff4d94; /* Darker pink on hover */
    }

    /* Input fields */
    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="file"] {
        border: 1px solid #ff4d94; /* Pink border for input fields */
    }
    .nav-link {
    color: black; /* Default text color */
}

.nav-link.active {
    background-color: #ff4d94; /* Dark pink background for active tab */
    color: white; /* Text color for active tab */
}
</style>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php'); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include('assets/inc/sidebar.php'); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <?php
        $aid = $_SESSION['ad_id'];
        $ret = "select * from his_admin where ad_id=?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $aid);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {
        ?>
            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        <!-- Start page title -->
                        <br>
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Profile</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title"><?php echo $row->ad_fname; ?> <?php echo $row->ad_lname; ?>'s Profile</h4>
                                </div>
                            </div>
                        </div>
                        <!-- End page title -->

                        <div class="row">
                            <div class="col-lg-4 col-xl-4">
                                <div class="card-box text-center">
                                    <img src="assets/images/users/<?php echo $row->ad_dpic; ?>" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                                    <h4 class="mb-0"><?php echo $row->ad_fname; ?> <?php echo $row->ad_lname; ?></h4>
                                    <p class="text-muted">@System_Administrator_HMIS</p>
                                    <div class="text-left mt-3" style="font-size: 18px;">
                                        <p class="text-muted mb-2" style="font-size: 18px;"><strong>Full Name :</strong> <span class="ml-2"><?php echo $row->ad_fname; ?> <?php echo $row->ad_lname; ?></span></p>
                                        <p class="text-muted mb-2" style="font-size: 18px;"><strong>Email :</strong> <span class="ml-2"><?php echo $row->ad_email; ?></span></p>
                                    </div>
                                </div> <!-- end card-box -->
                            </div> <!-- end col-->

                            <div class="col-lg-8 col-xl-8">
                                <div class="card-box">
                                   

                                        <!-- Tab Navigation -->
                                        <ul class="nav nav-justified">
                                            <li class="nav-item">
                                                <a href="#aboutme" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    Update Profile
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#settings" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                    Change Password
                                                </a>
                                            </li>
                                        </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="aboutme">
                                            <form method="post" enctype="multipart/form-data">
                                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Personal Info</h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstname">First Name</label>
                                                            <input type="text" name="ad_fname" class="form-control" id="firstname" placeholder="<?php echo $row->ad_fname; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="lastname">Last Name</label>
                                                            <input type="text" name="ad_lname" class="form-control" id="lastname" placeholder="<?php echo $row->ad_lname; ?>">
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="useremail">Email Address</label>
                                                            <input type="email" name="ad_email" class="form-control" id="useremail" placeholder="<?php echo $row->ad_email; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="useremail">Profile Picture</label>
                                                            <input type="file" name="ad_dpic" class="form-control btn btn-success" id="useremail" placeholder="<?php echo $row->ad_email; ?>">
                                                        </div>
                                                    </div>
                                                </div> <!-- end row -->

                                                <div class="text-right">
                                                    <button type="submit" name="update_profile" class="btn btn-success waves-effect waves-light mt-2"><i class="mdi mdi-content-save"></i> Save</button>
                                                </div>
                                            </form>
                                        </div> <!-- end tab-pane -->
                                        <div class="tab-pane" id="settings">
                                            <form method="post">
                                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-lock mr-1"></i> Change Password</h5>
                                                <div class="form-group">
                                                    <label for="pass1">New Password</label>
                                                    <input type="password" name="ad_pwd" class="form-control" id="pass1" required placeholder="Enter New Password">
                                                </div>
                                                <div class="text-right">
                                                    <button type="submit" name="update_pwd" class="btn btn-success waves-effect waves-light mt-2"><i class="mdi mdi-lock-open"></i> Update Password</button>
                                                </div>
                                            </form>
                                        </div> <!-- end tab-pane -->
                                    </div> <!-- end tab-content -->
                                </div> <!-- end card-box -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div> <!-- container -->
                </div> <!-- content -->
            </div> <!-- content-page -->
        <?php } ?>
    </div> <!-- wrapper -->

    <!-- Footer Start -->
    <?php include('assets/inc/footer.php'); ?>
    <!-- end Footer -->

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
