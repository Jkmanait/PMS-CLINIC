<?php
session_start();
include('../configuration/config.php');

// Ensure session contains patient_id
if (!isset($_SESSION['patient_id'])) {
    $_SESSION['patient_id'] = 1; // Dummy ID for demonstration, replace with actual logic
}

$patient_id = $_SESSION['patient_id'];
$success = $err = '';

// Function to fetch patient details
function get_patient_details($mysqli, $patient_id) {
    $query = "SELECT pname, pemail FROM patient WHERE patient_id=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $patient_id);
    $stmt->execute();
    $stmt->bind_result($pname, $pemail);
    $stmt->fetch();
    $stmt->close();
    return ['pname' => $pname, 'pemail' => $pemail];
}

// Update Profile
if (isset($_POST['update_profile'])) {
    $pname = trim($_POST['pname']);
    $pemail = trim($_POST['pemail']);

    if (!empty($pname) && !empty($pemail)) {
        $query = "UPDATE patient SET pname=?, pemail=? WHERE patient_id=?";
        $stmt = $mysqli->prepare($query);
        if ($stmt) {
            $stmt->bind_param('ssi', $pname, $pemail, $patient_id);
            if ($stmt->execute()) {
                $success = "Profile Updated Successfully";
            } else {
                $err = "Error: Could not update profile. " . $stmt->error;
            }
            $stmt->close();
        } else {
            $err = "Error: Could not prepare statement. " . $mysqli->error;
        }
    } else {
        $err = "Both name and email are required.";
    }
}

// Change Password
if (isset($_POST['update_pwd'])) {
    $old_password = sha1(md5($_POST['old_password']));
    $new_password = sha1(md5($_POST['new_password']));
    $confirm_password = sha1(md5($_POST['confirm_password']));

    // Check if new passwords match
    if ($new_password !== $confirm_password) {
        $err = "New password and confirmation password do not match.";
    } else {
        // Verify old password
        $query = "SELECT ppassword FROM patient WHERE patient_id=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $patient_id);
        $stmt->execute();
        $stmt->bind_result($current_password);
        $stmt->fetch();
        $stmt->close();

        if ($current_password === $old_password) {
            // Update password
            $query = "UPDATE patient SET ppassword=? WHERE patient_id=?";
            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param('si', $new_password, $patient_id);
                if ($stmt->execute()) {
                    $success = "Password Updated Successfully";
                } else {
                    $err = "Error: Could not update password. " . $stmt->error;
                }
                $stmt->close();
            } else {
                $err = "Error: Could not prepare statement. " . $mysqli->error;
            }
        } else {
            $err = "Old password is incorrect.";
        }
    }
}

// Fetch current patient details for display
$patient_details = get_patient_details($mysqli, $patient_id);

?>

<!DOCTYPE html>
<html lang="en">
<?php include('assets/inc/head.php'); ?>

<body>
    <div id="wrapper">
        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php'); ?>
        <!-- End Topbar -->

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <h4 class="page-title"><?php echo $patient_details['pname']; ?>'s Profile</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <!-- Profile section -->
                        <div class="col-lg-4 col-xl-4">
                            <div class="card-box text-center">
                                <h4 class="mb-0"><?php echo $patient_details['pname']; ?></h4>
                                <p class="text-muted">@Patient Account</p>

                                <div class="text-left mt-3">
                                    <p class="text-muted mb-2"><strong>Full Name :</strong> <span><?php echo $patient_details['pname']; ?></span></p>
                                    <p class="text-muted mb-2"><strong>Email :</strong> <span><?php echo $patient_details['pemail']; ?></span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Update Profile and Password -->
                        <div class="col-lg-8 col-xl-8">
                            <div class="card-box">
                                <ul class="nav nav-pills navtab-bg nav-justified">
                                    <li class="nav-item">
                                        <a href="#profile" data-toggle="tab" class="nav-link active">Update Profile</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#password" data-toggle="tab" class="nav-link">Change Password</a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <!-- Update Profile Form -->
                                    <div class="tab-pane show active" id="profile">
                                        <form method="post">
                                            <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Personal Info</h5>
                                            <?php if ($success) { echo "<p class='alert alert-success'>$success</p>"; } ?>
                                            <?php if ($err) { echo "<p class='alert alert-danger'>$err</p>"; } ?>

                                            <div class="form-group">
                                                <label for="pname">Full Name</label>
                                                <input type="text" name="pname" class="form-control" id="pname" value="<?php echo $patient_details['pname']; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="pemail">Email Address</label>
                                                <input type="email" name="pemail" class="form-control" id="pemail" value="<?php echo $patient_details['pemail']; ?>">
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" name="update_profile" class="btn btn-success">Save</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Change Password Form -->
                                    <div class="tab-pane" id="password">
                                        <form method="post">
                                            <h5 class="mb-4 text-uppercase"><i class="mdi mdi-lock-reset mr-1"></i> Change Password</h5>
                                            <?php if ($success) { echo "<p class='alert alert-success'>$success</p>"; } ?>
                                            <?php if ($err) { echo "<p class='alert alert-danger'>$err</p>"; } ?>

                                            <div class="form-group">
                                                <label for="old_password">Old Password</label>
                                                <input type="password" name="old_password" class="form-control" id="old_password" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="new_password">New Password</label>
                                                <input type="password" name="new_password" class="form-control" id="new_password" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="confirm_password">Confirm New Password</label>
                                                <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
                                            </div>

                                            <div class="text-right">
                                                <button type="submit" name="update_pwd" class="btn btn-success">Update Password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> <!-- end row -->

                </div> <!-- container -->
            </div> <!-- content -->

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php'); ?>
            <!-- end Footer -->
        </div>
    </div>
    <!-- END wrapper -->

    <!-- Vendor js -->
    <script src="assets/js/vendor.min.js"></script>
    <!-- App js -->
    <script src="assets/js/app.min.js"></script>
</body>
</html>
