<?php
session_start();
include('../../configuration/config.php');
include('assets/inc/checklogin.php');
check_login();
$aid = $_SESSION['ad_id'];

if (isset($_GET['delete_soap_id'])) {
    $id = intval($_GET['delete_soap_id']);
    $adn = "DELETE FROM his_soap_records WHERE soap_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        $success = "Medical Record Deleted";
    } else {
        $err = "Try Again Later";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
    
<?php include('assets/inc/head.php');?>

<style>
    body, label, th, td, h4, h1, h2, h3, h5, h6, .breadcrumb-item a {
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

    th {
        font-size: 20px;
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
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Medical Records</a></li>
                                        <li class="breadcrumb-item active">Manage Medical Records</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">Manage Medical Records</h4>
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
                                            <div class="form-group mr-2" style="display:none">
                                                <select id="demo-foo-filter-status" class="custom-select custom-select-sm">
                                                    <option value="">Show all</option>
                                                    <option value="Discharged">Discharged</option>
                                                    <option value="OutPatients">OutPatients</option>
                                                    <!-- <option value="InPatients">InPatients</option> -->
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
                                            
                                            <th data-toggle="true">Patient Name</th>
                                            <th data-hide="phone">Age</th>
                                            <th data-hide="phone">Patient Sex</th>
                                            <th data-hide="phone">MRN</th>
                                            <th data-hide="phone">Address</th>
                                            
                                        
                                            <th data-hide="phone">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        // Get details of all SOAP records
                                        $ret = "SELECT soap_pat_name, soap_pat_sex, mdr_number, soap_pat_adr, soap_pat_age, soap_id 
                                                FROM his_soap_records 
                                                GROUP BY mdr_number 
                                                ORDER BY created_at DESC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_object()) {
                                        ?>
                                            <tr>
                                                <tr class="<?php echo ($row->soap_pat_sex == 'Female') ? 'female' : ''; ?>">
                                                <td><?php echo $row->soap_pat_name; ?></td>
                                                <td><?php echo $row->soap_pat_age;?> Year's Old</td>
                                                <td><?php echo $row->soap_pat_sex;?></td>
                                                <td><?php echo $row->mdr_number; ?></td>
                                                <td><?php echo $row->soap_pat_adr; ?></td>
                                                
                                                
                                                <td>
                                                    <a href="his_admin_view_single_medical_record.php?soap_id=<?php echo $row->soap_id; ?>" class="badge badge-success">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="his_admin_upate_single_medical_record.php?soap_id=<?php echo $row->soap_id; ?>" class="badge badge-warning">
                                                        <i class="fas fa-eye-dropper"></i> Add
                                                    </a>
                                                    <a href="javascript:void(0);" class="badge badge-danger delete-record" data-soap-id="<?php echo $row->soap_id; ?>" data-toggle="modal" data-target="#confirmDeleteModal">
                                                        <i class="fas fa-trash-alt"></i> Delete
                                                    </a>
                                                </td>

                                                <!-- Modal Structure -->
                                                <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete this record?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- JavaScript to Handle Deletion -->
                                                <script>
                                                    let soapIdToDelete;

                                                    // Set the soap ID when the delete link is clicked
                                                    document.querySelectorAll('.delete-record').forEach(link => {
                                                        link.addEventListener('click', function() {
                                                            soapIdToDelete = this.getAttribute('data-soap-id');
                                                        });
                                                    });

                                                    // Handle the delete confirmation
                                                    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
                                                        if (soapIdToDelete) {
                                                            window.location.href = "his_admin_manage_medical_record.php?delete_soap_id=" + soapIdToDelete;
                                                        }
                                                    });
                                                </script>

                                                <!-- Include Bootstrap for the modal -->
                                                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
                                                <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
                                                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

                                            </tr>
                                        <?php 
                                            $cnt++; 
                                        } 
                                        ?>
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

    <div class="rightbar-overlay"></div>

    <script src="assets/js/vendor.min.js"></script>
    <script src="assets/libs/footable/footable.all.min.js"></script>
    <script src="assets/js/pages/foo-tables.init.js"></script>
    <script src="assets/js/app.min.js"></script>
    
</body>

</html>