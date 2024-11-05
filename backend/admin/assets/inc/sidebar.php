<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Navigation</title>
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Add your CSS file here -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .left-side-menu {
            font-size: 18px;
        }
        .active-menu-item a {
            color: #C71585 !important; /* Dark pink color */
            font-size: 20px; /* Increased font size */
        }
        .metismenu li {
            font-size: 20px; /* Increased font size for li */
        }
        .metismenu li a {
            color: black; /* Default link color */
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 20px; /* Increased font size */
            transition: color 0.3s; /* Smooth transition for color change */
        }
        .metismenu li a:hover {
            color: #C71585 !important; /* Dark pink color on hover */
        }
        .metismenu li a span {
            font-size: 22px; /* Increased font size for span */
            color: black; /* Span color set to black */
        }
        .menu-title {
            font-size: 22px; /* Increased font size for menu title */
            color: black;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="left-side-menu">
        <div class="slimscroll-menu">
            <div id="sidebar-menu">
                <ul class="metismenu" id="side-menu">
                    <br>
                    <li class="menu-title">
                        <span>Navigation</span>
                    </li>
                    <li>
                        <a href="his_admin_dashboard.php">
                            <i class="fe-airplay"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript: void(0);">
                            <i class="fab fa-accessible-icon"></i>
                            <span> Patients </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="his_admin_register_patient.php">Register Patient</a></li>
                            <li><a href="his_admin_view_patients.php">View Patients</a></li>
                            <li><a href="his_admin_manage_patient.php">Manage Patients</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);">
                            <i class="fe-file-text"></i>
                            <span> Patient Chart </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="his_admin_add_medical_record.php">Add Diagnoses and Prescription</a></li>
                            <li><a href="his_admin_manage_medical_record.php">Manage Diagnoses and Prescription</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);">
                            <i class="fa fa-calendar"></i>
                            <span> Appointment </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="his_admin_appointment.php">Calendar Appointment</a></li>
                            <li><a href="his_admin_manage_appointments.php">Appointment Details</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);">
                            <i class="fe-share"></i>
                            <span> Reporting </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="his_admin_outpatient_records.php">OutPatient Records</a></li>
                            <li><a href="his_admin_medical_records.php">Medical Records</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);">
                            <i class="fe-file-text"></i>
                            <span> Medical Records </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-second-level" aria-expanded="false">
                            <li><a href="his_admin_add_medical_record.php">Add Medical Record</a></li>
                            <li><a href="his_admin_manage_medical_record.php">Manage Medical Records</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <script>
        // Function to set the active menu item based on the current page
        function setActiveMenuItem() {
            const currentPath = window.location.pathname.split("/").pop(); // Get the current page name
            document.querySelectorAll('#side-menu li > a').forEach(item => {
                const href = item.getAttribute('href');
                if (href === currentPath) {
                    item.parentElement.classList.add('active-menu-item'); // Add active class if it's the current page
                } else if (href === 'javascript: void(0);') {
                    // If the link has sub-menu, check if any child is the current page
                    const subMenuItems = item.parentElement.querySelectorAll('.nav-second-level li a');
                    subMenuItems.forEach(subItem => {
                        if (subItem.getAttribute('href') === currentPath) {
                            item.parentElement.classList.add('active-menu-item');
                        }
                    });
                }
            });
        }

        // Call the function to set the active menu item
        setActiveMenuItem();

        // JavaScript to handle the click event
        document.querySelectorAll('#side-menu li > a').forEach(item => {
            item.addEventListener('click', function() {
                // Remove 'active-menu-item' class from all li elements
                document.querySelectorAll('#side-menu li').forEach(li => {
                    li.classList.remove('active-menu-item');
                });
                // Add 'active-menu-item' class to the clicked li
                this.parentElement.classList.add('active-menu-item');
            });
        });
    </script>
</body>
</html>
