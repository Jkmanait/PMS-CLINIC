<?php
session_start();
$_SESSION["user"] = "";
$_SESSION["usertype"] = "";
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');
$_SESSION["date"] = $date;

include("configuration/config.php");
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['useremail'];
    $password = $_POST['userpassword'];

    // Basic sanitization
    $email = $mysqli->real_escape_string($email);
    $password = $mysqli->real_escape_string($password);

    // Fetch user from webuser table to check usertype
    $result = $mysqli->query("SELECT * FROM webuser WHERE email='$email'");
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $utype = $row['usertype'];

        if ($utype == 'p') {
            // Fetch patient details
            $checker = $mysqli->query("SELECT * FROM patient WHERE pemail='$email'");
            if ($checker->num_rows == 1) {
                $patient_data = $checker->fetch_assoc();

                // Verify password
                if ($patient_data['ppassword'] === $password) {
                    $_SESSION['user'] = $email;
                    $_SESSION['usertype'] = 'p';
                    $_SESSION['patient_id'] = $patient_data['patient_id'];

                    // Redirect to patient dashboard
                    header('Location: patient/index.php');
                    exit();
                } else {
                    $error = 'Invalid password.';
                }
            } else {
                $error = 'Invalid email or password.';
            }
        } elseif ($utype == 'a') {
            // Admin login handling
            $checker = $mysqli->query("SELECT * FROM admin WHERE aemail='$email' AND apassword='$password'");
            if ($checker->num_rows == 1) {
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'a';
                header('Location: admin/index.php');
                exit();
            } else {
                $error = 'Invalid email or password.';
            }
        } elseif ($utype == 'd') {
            // Doctor login handling
            $checker = $mysqli->query("SELECT * FROM doctor WHERE docemail='$email' AND docpassword='$password'");
            if ($checker->num_rows == 1) {
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'd';
                header('Location: doctor/index.php');
                exit();
            } else {
                $error = 'Invalid email or password.';
            }
        }
    } else {
        $error = 'No account found for this email.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/login.css">
    <link rel="preload" as="image" href="./assets/images/hero-bg.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Patient Login</title>   
    <style>
        body {
            background-color: #ffe4e1; /* Light pink background */
        }
        .container {
            background-color: #ffebee; /* Light pink container */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 60%;
            margin: auto;
        }
        .header-text {
            color: #d81b60; /* Darker pink for header */
        }
        .sub-text {
            color: #f06292; /* Lighter pink for subtext */
        }
        .form-label {
            color: #d81b60; /* Darker pink for labels */
        }
        .input-text {
            border: 1px solid #d81b60; /* Pink border for inputs */
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        .login-btn {
            background-color: #d81b60; 
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }
        .login-btn:hover {
            background-color: #c2185b; 
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="preloader" data-preloader>
    <div class="circle"></div>
</div>

<center>
    <div class="container">
        <table border="0" style="margin: 0;padding: 0;width: 100%;">
            <tr>
                <td><p class="header-text">Welcome Back!</p></td>
            </tr>
            <div class="form-body">
                <tr>
                    <td><p class="sub-text">Login with your details to continue</p></td>
                </tr>
                <tr>
                    <form action="" method="POST">
                        <td class="label-td">
                            <label for="useremail" class="form-label">Email: </label>
                        </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <input type="email" name="useremail" class="input-text" placeholder="Email Address" required>
                    </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <label for="userpassword" class="form-label">Password: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td position-relative">
                        <input type="password" id="userpassword" name="userpassword" class="input-text" placeholder="Password" required>
                        <span class="password-toggle">
                            <i id="togglePassword" class="fas fa-eye"></i>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><br>
                        <td colspan="2" class="text-center">
                            <span style="color: rgb(255, 62, 62);"><?php echo $error; ?></span>
                        </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        <input type="submit" value="Login" class="login-btn">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center">
                        <label for="" class="sub-text" style="font-weight: 280;">Don't have an account&#63; </label>
                        <a href="signup.php" class="hover-link1 non-style-link" style="color: #d81b60;">Sign Up</a>
                        <br><br><br>
                    </td>
                </tr>
            </table>
        </div>
    </form>
</center>

<!-- Inline JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('userpassword');

        togglePassword.addEventListener('click', function () {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });
</script>
</body>
</html>