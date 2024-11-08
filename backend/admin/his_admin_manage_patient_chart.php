<?php
session_start();
include('../../configuration/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];

// Delete all records associated with a given patient number
if (isset($_GET['delete_chart_number'])) {
    $number = $_GET['delete_chart_number'];
    $adn = "DELETE FROM his_patient_chart WHERE patient_chart_pat_number = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('s', $number);
    
    if ($stmt->execute()) {
        $success = "Medical Record Deleted";
    } else {
        $err = "Error deleting record. Please try again.";
    }
    $stmt->close();
}

// Fetch unique dates from the database for the dropdown
$query = "SELECT DISTINCT patient_chart_pat_date_joined FROM his_patient_chart ORDER BY patient_chart_pat_date_joined ASC";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">
    
<?php include('assets/inc/head.php');?>

<style>
    body, label, th, td, h4, h1, h2, h3, h5, h6, .breadcrumb-item a {
        font-size: 18px;
        color: black;
    }

    th {
        font-size: 20px;
        background-color: #d3d3d3;
        color: black;
    }

    .female {
        background-color: #ffcccb;
    }

    h4.page-title {
        font-size: 24px;
        color: black;
    }
    input[type="text"], button {
        font-size: 18px;
        color: black;
    }
    .pagination {
        font-size: 18px;
    }
</style>

<body>

    <div id="wrapper">

        <?php include('assets/inc/nav.php');?>
        <?php include("assets/inc/sidebar.php");?>

        <div class="content-page">
            <div class="content">

                <div class="container-fluid">
                    
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Patients Chart</a></li>
                                        <li class="breadcrumb-item active">Manage Patients Chart</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Manage Patient Chart</h4>
                            </div>
                        </div>
                    </div>     

                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <h4 class="header-title"></h4>
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-12 text-sm-center form-inline">
                                            <div class="form-group mr-2">
                                                <input id="filter-name" type="text" placeholder="Search by Name" class="form-control form-control-sm">
                                            </div>                    
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered toggle-circle mb-0" id="patientsTable">
                                        <thead>
                                        <tr>
                                            <th>Date of Visit
                                                <button class="btn dropdown-toggle" style="color: black;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <?php 
                                                        while ($row = $result->fetch_assoc()) {
                                                            $date = $row['patient_chart_pat_date_joined'];
                                                            echo "<a class='dropdown-item' href='#' onclick='filterByDate(\"$date\")'>" . date("F j, Y", strtotime($date)) . "</a>";
                                                        }
                                                    ?>
                                                    <a class="dropdown-item" href="#" onclick="clearDateFilter()">Clear Date Filter</a>
                                                </div>
                                            </th>
                                            <th>Patient Name</th>
                                            <th>Age</th>
                                            <th>Sex</th>
                                            <th>Address</th>
                                            <th>Guardian Name</th>
                                            <th>Patient Number</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $ret = "SELECT patient_chart_pat_date_joined, patient_chart_pat_name, patient_chart_pat_sex, patient_chart_pat_number, patient_chart_pat_adr, patient_chart_pat_parent_name, patient_chart_pat_age, patient_chart_id 
                                                FROM his_patient_chart 
                                                GROUP BY patient_chart_pat_number
                                                ORDER BY patient_chart_pat_date_joined DESC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        while ($row = $res->fetch_object()) {
                                        ?>
                                            <tr class="<?php echo ($row->patient_chart_pat_sex == 'Female') ? 'female' : ''; ?>">
                                                <td><?php echo $row->patient_chart_pat_date_joined; ?></td>
                                                <td><?php echo $row->patient_chart_pat_name; ?></td>
                                                <td><?php echo $row->patient_chart_pat_age; ?></td>
                                                <td><?php echo $row->patient_chart_pat_sex; ?></td>
                                                <td><?php echo $row->patient_chart_pat_adr; ?></td>
                                                <td><?php echo $row->patient_chart_pat_parent_name; ?></td>
                                                <td><?php echo $row->patient_chart_pat_number; ?></td>
                                                <td>
                                                    <a href="his_admin_view_single_patient_chart.php?patient_chart_id=<?php echo $row->patient_chart_id; ?>" class="badge badge-success">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="his_admin_update_single_patient_chart.php?patient_chart_id=<?php echo $row->patient_chart_id; ?>" class="badge badge-warning">
                                                        <i class="fas fa-eye-dropper"></i> Add
                                                    </a>
                                                    <a href="javascript:void(0);" class="badge badge-danger delete-record" data-chart-number="<?php echo $row->patient_chart_pat_number; ?>" data-toggle="modal" data-target="#confirmDeleteModal">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </a>
                                                </td>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include('assets/inc/footer.php');?>
        </div>
    </div>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this patient record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        let chartNumberToDelete;

        document.querySelectorAll('.delete-record').forEach(link => {
            link.addEventListener('click', function() {
                chartNumberToDelete = this.getAttribute('data-chart-number');
            });
        });

        document.getElementById('confirmDeleteButton').addEventListener('click', function() {
            if (chartNumberToDelete) {
                window.location.href = "his_admin_manage_patient_chart.php?delete_chart_number=" + chartNumberToDelete;
            }
        });

        document.getElementById('filter-name').addEventListener('input', filterTable);

        function filterTable() {
            const nameFilter = document.getElementById('filter-name').value.toLowerCase();
            const rows = document.querySelectorAll('#patientsTable tbody tr');
            rows.forEach(row => {
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                row.style.display = name.includes(nameFilter) ? '' : 'none';
            });
        }

        function filterByDate(date) {
            const rows = document.querySelectorAll('#patientsTable tbody tr');
            rows.forEach(row => {
                const rowDate = row.querySelector('td:nth-child(1)').textContent;
                row.style.display = rowDate === date ? '' : 'none';
            });
        }

        function clearDateFilter() {
            const rows = document.querySelectorAll('#patientsTable tbody tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        }
    </script>
    <div class="rightbar-overlay"></div>
    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/js/app.min.js"></script>
</body>
</html>
