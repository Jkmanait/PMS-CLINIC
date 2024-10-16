<?php
session_start();
include('../../configuration/config.php'); // get configuration file
if (isset($_POST['admin_login'])) {
    $ad_email = $_POST['ad_email'];
    $ad_pwd = sha1(md5($_POST['ad_pwd'])); // double encrypt to increase security
    $stmt = $mysqli->prepare("SELECT ad_email, ad_pwd, ad_id FROM his_admin WHERE ad_email=? AND ad_pwd=?"); // sql to log in user
    $stmt->bind_param('ss', $ad_email, $ad_pwd); // bind fetched parameters
    $stmt->execute(); // execute bind
    $stmt->bind_result($ad_email, $ad_pwd, $ad_id); // bind result
    $rs = $stmt->fetch();
    $_SESSION['ad_id'] = $ad_id; // Assign session to admin id

    if ($rs) { // if it's successful
        header("location:his_admin_dashboard.php");
    } else {
        $err = "Access Denied Please Check Your Credentials";
    }
}
?>
<!-- End Login -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Admin - Patient Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="MartDevelopers" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/swal.js"></script>

    <?php if (isset($success)) { ?>
        <script>
            setTimeout(function() {
                swal("Success", "<?php echo $success; ?>", "success");
            }, 100);
        </script>
    <?php } ?>

    <?php if (isset($err)) { ?>
        <script>
            setTimeout(function() {
                swal("Failed", "<?php echo $err; ?>", "Failed");
            }, 100);
        </script>
    <?php } ?>
    
    <style>
        body {
            background-image: url('assets/images/bg1-pattern.jpg'); /* Keep the image background */
            background-color: #ffeef8; /* Light pink background color */
        }
        .background-shadow {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 192, 203, 0.5); 
            z-index: -1; 
        }
        .card {
            background-color: #ffebee; 
            border-radius: 10px;
            padding: 40px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); 
            min-height: 400px; 
        }
        .form-control {
            border: 1px solid #d81b60; 
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #d81b60; 
            border: none;
        }
        .btn-primary:hover {
            background-color: #c2185b; 
        }
        .text-muted {
            color: #d81b60;
        }
    </style>

</head>

<body class="authentication-bg">

    <div class="background-shadow"></div> 
    
    <div class="account-pages mt-5 mb-5 ">
        <div class="container">
            <div class="row justify-content-center" style="margin: 0;padding: 0;width: 100%;">
                <div class="col-md-8 col-lg-90 col-xl-50">
                    <div class="card">
                        <div class="card-body p-40">
                            <div class="text-center w-75 m-auto">
                                <p class="text-dark mb-40 mt-30">Enter your email address and password to access the admin panel.</p>
                            </div>

                            <form method='post'>
                                <div class="form-group mb-3">
                                    <label for="emailaddress">Email address</label>
                                    <input class="form-control" name="ad_email" type="email" id="emailaddress" required="" placeholder="Enter your email">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password">Password</label>
                                    <input class="form-control" name="ad_pwd" type="password" required="" id="password" placeholder="Enter your password">
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary btn-block" name="admin_login" type="submit"> Admin Log In </button>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p> <a href="his_admin_pwd_reset.php" class="text-white-50 ml-1">Forgot your password?</a></p>
                        </div> <!-- end col -->
                    </div> <!-- end row -->

                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- end container -->
    </div> <!-- end page -->

    <?php include("assets/inc/footer1.php"); ?>

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>
</html>
