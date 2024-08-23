<?php
    $aid=$_SESSION['ad_id'];
    $ret="select * from his_admin where ad_id=?";
    $stmt= $mysqli->prepare($ret);
    $stmt->bind_param('i', $aid);
    $stmt->execute();
    $res=$stmt->get_result();
    while($row=$res->fetch_object())
    {
?>
    <div class="navbar-custom" style="height: 100px; padding: 15px; background-color: light-green;">
        <ul class="list-unstyled topnav-menu float-right mb-0">

            <li class="d-none d-sm-block">
                <form class="app-search">
                    <div class="app-search-box">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search..." style="font-size: 18px;">
                            <div class="input-group-append">
                                <button class="btn" type="submit">
                                    <i class="fe-search" style="font-size: 20px;"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </li>

            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="assets/images/users/<?php echo $row->ad_dpic;?>" alt="dpic" class="rounded-circle" style="height: 50px; width: 50px;">
                    <span class="pro-user-name ml-1" style="font-size: 20px;">
                        <?php echo $row->ad_fname;?> <?php echo $row->ad_lname;?> <i class="mdi mdi-chevron-down"></i> 
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0" style="font-size: 18px;">Welcome !</h6>
                    </div>

                    <a href="his_admin_account.php" class="dropdown-item notify-item" style="font-size: 18px;">
                        <i class="fe-user"></i>
                        <span>My Account</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <a href="his_admin_logout_partial.php" class="dropdown-item notify-item" style="font-size: 18px;">
                        <i class="fe-log-out"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </li>
        </ul>

        <!-- LOGO -->
        <div class="logo-box">
            <a href="his_admin_dashboard.php" class="logo text-center">
                <span class="logo-lg">
                    <img src="assets/images/logo.png.png" style="height: 100px; width: 100px; margin-top: -18px;" alt="Logo">
                </span>
                <span class="logo-sm">
                    <img src="assets/images/logo.png.png" style="height: 100px; width: 100px; margin-top: -18px;" alt="Logo">
                </span>
            </a>
        </div>

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
            <li>
                <a href="javascript:history.back()" class="nav-link waves-effect waves-light" style="font-size: 18px;">
                    <i class="fe-arrow-left"></i> Back
                </a>
            </li>

            <li>
                <button class="button-menu-mobile waves-effect waves-light" style="font-size: 20px;">
                    <i class="fe-menu"></i>
                </button>
            </li>

            <li class="dropdown d-none d-lg-block">
                <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" style="font-size: 18px;">
                    Create New
                    <i class="mdi mdi-chevron-down"></i> 
                </a>
                <div class="dropdown-menu">
                    <a href="his_admin_add_employee.php" class="dropdown-item" style="font-size: 18px;">
                        <i class="fe-users mr-1"></i>
                        <span>Employee</span>
                    </a>
                    <a href="his_admin_register_patient.php" class="dropdown-item" style="font-size: 18px;">
                        <i class="fe-activity mr-1"></i>
                        <span>Patient</span>
                    </a>
                    <a href="his_admin_add_payroll.php" class="dropdown-item" style="font-size: 18px;">
                        <i class="fe-layers mr-1"></i>
                        <span>Payroll</span>
                    </a>
                    <a href="his_admin_add_vendor.php" class="dropdown-item" style="font-size: 18px;">
                        <i class="fe-shopping-cart mr-1"></i>
                        <span>Vendor</span>
                    </a>
                    <a href="his_admin_add_medical_record.php" class="dropdown-item" style="font-size: 18px;">
                        <i class="fe-list mr-1"></i>
                        <span>Medical Report</span>
                    </a>
                    <a href="his_admin_lab_report.php" class="dropdown-item" style="font-size: 18px;">
                        <i class="fe-hard-drive mr-1"></i>
                        <span>Laboratory Report</span>
                    </a>
                    <a href="his_admin_surgery_records.php" class="dropdown-item" style="font-size: 18px;">
                        <i class="fe-anchor mr-1"></i>
                        <span>Surgical/Theatre Report</span>
                    </a>
                    <div class="dropdown-divider"></div>
                </div>
            </li>
        </ul>
    </div>
<?php }?>
