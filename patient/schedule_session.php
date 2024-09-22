<?php
session_start();
include('../configuration/config.php');

// Fetch notifications for the logged-in patient

$query = "SELECT * FROM notifications WHERE patient_id = ? AND status = 'Unread' ORDER BY created_at DESC";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $patient_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
    <!-- Head Code -->
    <?php include("assets/inc/head.php"); ?>

    <body>
        <div id="wrapper">
            <!-- Topbar Start -->
            <?php include('assets/inc/nav.php'); ?>
            <!-- End Topbar -->

            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-body">
                                <h3>Your Notifications</h3>
                                <?php if ($result->num_rows > 0) { ?>
                                    <ul class="list-group">
                                        <?php while ($row = $result->fetch_assoc()) { ?>
                                            <li class="list-group-item">
                                                <?php echo $row['message']; ?>
                                                <span class="text-muted"><?php echo $row['created_at']; ?></span>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                <?php } else { ?>
                                    <p>No new notifications</p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Start -->
            <?php include('assets/inc/footer.php'); ?>
            <!-- End Footer -->

            <script src="assets/js/vendor.min.js"></script>
            <script src="assets/js/app.min.js"></script>
        </div>
    </body>
</html>
