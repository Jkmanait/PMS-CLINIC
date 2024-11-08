<?php
session_start();
include('../../configuration/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];

// Fetch unique dates from the database for the dropdown
$query = "SELECT DISTINCT pat_date_joined FROM his_patients ORDER BY pat_date_joined ASC";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<?php include('assets/inc/head.php'); ?>

<style>
    /* Make text bigger and color black */
    body,
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
        font-size: 18px;
        color: black;
    }

    /* Increase font size for table headers */
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
    input[type="text"],
    button {
        font-size: 18px;
        color: black;
    }

    /* Pagination */
    .pagination {
        font-size: 18px;
    }

    /* Dropdown styling */
    .dropdown-menu {
        position: absolute;
        top: 100%;  /* Ensures it appears below the button */
        left: 0;
        right: auto;
        z-index: 1000;  /* Make sure it’s above other content */
    }

</style>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <?php include('assets/inc/nav.php'); ?>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <?php include("assets/inc/sidebar.php"); ?>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Patients</a></li>
                                        <li class="breadcrumb-item active">View Patients</li>
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
                                            <div class="form-group mr-2">
                                                <input id="filter-name" type="text" placeholder="Search Name" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered toggle-circle mb-0" id="patientsTable">
                                        <thead>
                                            <tr>
                                                <th data-toggle="true">Check</th>
                                                <th data-hide="phone">
                                                    Date of Visit
                                                    <button class="btn dropdown-toggle" style="color: black;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <?php 
                                                            while ($row = $result->fetch_assoc()) {
                                                                $date = $row['pat_date_joined'];
                                                                echo "<a class='dropdown-item' href='#' onclick='filterByDate(\"$date\")'>" . date("F j, Y", strtotime($date)) . "</a>";
                                                            }
                                                        ?>
                                                        <a class="dropdown-item" href="#" onclick="clearDateFilter()">Clear Date Filter</a>
                                                    </div>
                                                </th>
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
                                                * Get details of all patients ordered by pat_date_joined
                                                */
                                            $ret = "SELECT * FROM his_patients ORDER BY pat_date_joined ASC"; // Order by date joined in ascending order
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            while ($row = $res->fetch_object()) {
                                                // Check if the patient has a chart
                                                $chartCheck = $mysqli->prepare("SELECT * FROM his_patient_chart WHERE patient_chart_pat_number = ?");
                                                $chartCheck->bind_param("s", $row->pat_number);
                                                $chartCheck->execute();
                                                $chartRes = $chartCheck->get_result();
                                                $hasChart = $chartRes->num_rows > 0; // True if there is at least one record

                                                // Determine the check mark or cross mark HTML
                                                $checkMark = $hasChart ? '<i class="fas fa-check" style="color: green;"></i>' : '<i class="fas fa-times" style="color: red;"></i>'; // Cross mark icon if no chart
                                            ?>
                                                <tr class="<?php echo ($row->pat_sex == 'Female') ? 'female' : ''; ?>" data-name="<?php echo strtolower($row->pat_fname . ' ' . $row->pat_lname); ?>" data-date="<?php echo $row->pat_date_joined; ?>">
                                                    <td><?php echo $checkMark; ?></td>
                                                    <td><?php echo $row->pat_date_joined; ?></td>
                                                    <td><?php echo $row->pat_fname; ?> <?php echo $row->pat_lname; ?></td>
                                                    <td><?php echo $row->pat_age; ?></td>
                                                    <td><?php echo $row->pat_sex; ?></td>
                                                    <td><?php echo $row->pat_addr; ?></td>
                                                    <td><?php echo $row->pat_parent_name; ?></td>
                                                    <td><?php echo $row->pat_phone; ?></td>
                                                    <td>
                                                        <a href="his_admin_view_single_patient.php?pat_id=<?php echo $row->pat_id; ?>&pat_number=<?php echo $row->pat_number; ?>" class="badge badge-success">
                                                            <i class="mdi mdi-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- container-fluid -->
            </div> <!-- content -->

        </div> <!-- content-page -->
    </div> <!-- wrapper -->

    <!-- Add jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- Script for filtering table -->
    <script>
        // Event listener for search input
        document.getElementById('filter-name').addEventListener('input', filterTable);

        function filterTable() {
            const nameFilter = document.getElementById('filter-name').value.toLowerCase();

            const rows = document.querySelectorAll('#patientsTable tbody tr');
            rows.forEach(row => {
                const name = row.getAttribute('data-name');

                if (name.includes(nameFilter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function filterByDate(date) {
            const rows = document.querySelectorAll('#patientsTable tbody tr');
            rows.forEach(row => {
                const rowDate = row.getAttribute('data-date');
                if (rowDate === date) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function clearDateFilter() {
            const rows = document.querySelectorAll('#patientsTable tbody tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        }

        function clearFilters() {
            document.getElementById('filter-name').value = '';
            filterTable();
        }

        $(document).ready(function() {
    // Initialize dropdown manually in case there's an issue with auto-initialization
    $('#dropdownMenuButton').dropdown();
});
    </script>

    <!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<!-- Vendor js -->
<script src="assets/js/vendor.min.js"></script>

<!-- App js -->
<script src="assets/js/app.min.js"></script>
</body>
</html>
