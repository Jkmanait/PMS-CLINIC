<?php
  session_start();
  include('../configuration/config.php');
  
  $aid=$_SESSION['ad_id'];
?>
<!DOCTYPE html>
<html lang="en">
    
    <!--Head Code-->
    <?php include("assets/inc/head.php");?>
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

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                

                    <!-- Start Content-->
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <br>
                                    <h4 class="page-title">Dashboard</h4>
                                </div>
                            </div>
                        </div>     
                        <!-- end page title --> 
                        

                        <div class="row">
                        <div class="row">
    <!-- doctor -->
<div class="col-md-8 col-xl-5 mb-6 mr-xl-2">
    <div class="widget-rounded-circle card-box">
        <div class="row">
            <div class="col-6">
                <div class="avatar-lg rounded-circle bg-soft-primary border-dark border">
                    <i class="fas fa-user-md fa-2x avatar-title" style="color: black;"></i> <!-- Adjusted to fa-2x for proper sizing -->
                </div>
            </div>
            <div class="col-6">
                <div class="text-right">
                    <?php
                        // Ensure the database connection is established
                        if ($mysqli) {
                            // Summing up number of doctors
                            $result = "SELECT count(*) FROM doctor";
                            $stmt = $mysqli->prepare($result);
                            if ($stmt) {
                                $stmt->execute();
                                $stmt->bind_result($doctor);
                                $stmt->fetch();
                                $stmt->close();
                            } else {
                                echo "Error in SQL query: " . $mysqli->error;
                            }
                        } else {
                            echo "Database connection error";
                        }
                    ?>
                    <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $doctor; ?></span></h3>
                    <p class="mb-1 text-truncate text-dark">List of Doctor</p>
                    
                    <!-- View Button -->
                    <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#doctorModal">View Doctors</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="doctorModal" tabindex="-1" aria-labelledby="doctorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="doctorModalLabel">List of Doctors</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Doctor Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch and display the list of doctors
                        $result = "SELECT docname FROM doctor"; // Adjusted query to fetch only docname
                        $stmt = $mysqli->prepare($result);
                        if ($stmt) {
                            $stmt->execute();
                            $stmt->bind_result($docname); // Bind only docname

                            while ($stmt->fetch()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($docname) . '</td>'; // Display doctor name
                                echo '</tr>';
                            }
                            $stmt->close();
                        } else {
                            echo "Error in SQL query: " . $mysqli->error;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- doctor -->
<div class="col-md-8 col-xl-5 mb-6 mr-xl-2">
    <div class="widget-rounded-circle card-box">
        <div class="row">
            <div class="col-6">
                <div class="avatar-lg rounded-circle bg-soft-primary border-dark border">
                    <i class="fas fa-stethoscope fa-2x avatar-title" style="color: black;"></i> 
                </div>
            </div>
            <div class="col-6">
                <div class="text-right">
                    <?php
                        // Ensure the database connection is established
                        if ($mysqli) {
                            // Summing up number of doctors
                            $result = "SELECT count(*) FROM doctor";
                            $stmt = $mysqli->prepare($result);
                            if ($stmt) {
                                $stmt->execute();
                                $stmt->bind_result($doctor);
                                $stmt->fetch();
                                $stmt->close();
                            } else {
                                echo "Error in SQL query: " . $mysqli->error;
                            }
                        } else {
                            echo "Database connection error";
                        }
                    ?>
                    <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $doctor; ?></span></h3>
                    <p class="mb-1 text-truncate text-dark">Services Offered</p>
                    
                    <!-- View Button -->
                    <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#doctorModal">View Services</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="doctorModal" tabindex="-1" aria-labelledby="doctorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="doctorModalLabel">List of Doctors</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Doctor Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch and display the list of doctors
                        $result = "SELECT docname FROM doctor"; // Adjusted query to fetch only docname
                        $stmt = $mysqli->prepare($result);
                        if ($stmt) {
                            $stmt->execute();
                            $stmt->bind_result($docname); // Bind only docname

                            while ($stmt->fetch()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($docname) . '</td>'; // Display doctor name
                                echo '</tr>';
                            }
                            $stmt->close();
                        } else {
                            echo "Error in SQL query: " . $mysqli->error;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    <!-- Corporation Assets -->
    <div class="col-md-8 col-xl-5 mb-6 mr-xl-2">
        <div class="widget-rounded-circle card-box ">
            <div class="row">
                <div class="col-6">
                    <div class="avatar-lg rounded-circle bg-soft-primary border-dark border">
                        <i class="mdi mdi-flask font-22 avatar-title" style="color: black;"></i> <!-- Icon color black -->
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-right">
                        <?php
                            // Summing up number of assets
                            $result = "SELECT count(*) FROM his_equipments";
                            $stmt = $mysqli->prepare($result);
                            $stmt->execute();
                            $stmt->bind_result($assets);
                            $stmt->fetch();
                            $stmt->close();
                        ?>
                        <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $assets; ?></span></h3>
                        <p class="mb-1 text-truncate" style="color: black;">Corporation Assets</p> <!-- Text color black -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pharmaceuticals -->
    <div class="col-md-8 col-xl-5 mb-6 mr-xl-2">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class="col-6">
                    <div class="avatar-lg rounded-circle bg-soft-primary border-dark border">
                        <i class="mdi mdi-pill font-22 avatar-title" style="color: black;"></i> <!-- Icon color black -->
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-right">
                        <?php
                            // Summing up number of pharmaceuticals
                            $result = "SELECT count(*) FROM his_pharmaceuticals";
                            $stmt = $mysqli->prepare($result);
                            $stmt->execute();
                            $stmt->bind_result($phar);
                            $stmt->fetch();
                            $stmt->close();
                        ?>
                        <h3 class="text-dark mt-1"><span data-plugin="counterup"><?php echo $phar; ?></span></h3>
                        <p class="mb-1 text-truncate" style="color: black;">Pharmaceuticals</p> <!-- Text color black -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



                            

                        

                <!-- Footer Start -->
                <?php include('assets/inc/footer.php');?>
                <!-- end Footer -->

           
        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>

        <!-- Plugins js-->
        <script src="assets/libs/flatpickr/flatpickr.min.js"></script>
        <script src="assets/libs/jquery-knob/jquery.knob.min.js"></script>
        <script src="assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.time.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.tooltip.min.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.selection.js"></script>
        <script src="assets/libs/flot-charts/jquery.flot.crosshair.js"></script>
        <!-- Bootstrap JS and dependencies (Ensure these are included in your project) -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

        <!-- Dashboar 1 init js-->
        <script src="assets/js/pages/dashboard-1.init.js"></script>

        <!-- App js-->
        <script src="assets/js/app.min.js"></script>
        
    </body>

</html>
