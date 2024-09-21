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
    <!-- Font Awesome (required for eye icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Patient Login</title>   
</head>
<body>
<div class="preloader" data-preloader>
    <div class="circle"></div>
</div>
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

    $result = $mysqli->query("SELECT * FROM webuser WHERE email='$email'");
    if ($result->num_rows == 1) {
        $utype = $result->fetch_assoc()['usertype'];
        if ($utype == 'p') {
            $checker = $mysqli->query("SELECT * FROM patient WHERE pemail='$email' AND ppassword='$password'");
            if ($checker->num_rows == 1) {
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'p';
                header('Location: patient/index.php');
                exit();
            } else {
                $error = 'Wrong credentials: Invalid email or password';
            }
        } elseif ($utype == 'a') {
            $checker = $mysqli->query("SELECT * FROM admin WHERE aemail='$email' AND apassword='$password'");
            if ($checker->num_rows == 1) {
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'a';
                header('Location: admin/index.php');
                exit();
            } else {
                $error = 'Wrong credentials: Invalid email or password';
            }
        } elseif ($utype == 'd') {
            $checker = $mysqli->query("SELECT * FROM doctor WHERE docemail='$email' AND docpassword='$password'");
            if ($checker->num_rows == 1) {
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'd';
                header('Location: doctor/index.php');
                exit();
            } else {
                $error = 'Wrong credentials: Invalid email or password';
            }
        }
    } else {
        $error = 'We can\'t find any account for this email.';
    }
}
?>

<center>
    <div class="container">
        <table border="0" style="margin: 0;padding: 0;width: 60%;">
            <tr>
                <td>
                    <p class="header-text">Welcome Back!</p>
                </td>
            </tr>
        <div class="form-body">
            <tr>
                <td>
                    <p class="sub-text">Login with your details to continue</p>
                </td>
            </tr>
            <tr>
            <form action="" method="POST" >
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
                                    <input type="submit" value="Login" class="login-btn btn-primary btn">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <label for="" class="sub-text" style="font-weight: 280;">Don't have an account&#63; </label>
                                    <a href="signup.php" class="hover-link1 non-style-link">Sign Up</a>
                                    <br><br><br>
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </td>
        </tr>
    </table>
</div>
</center>

<!-- Inline CSS -->
<style>
    .position-relative {
        position: relative;
    }
    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #666;
    }
    .input-text {
        padding-right: 40px; /* Add space for the eye icon */
    }
</style>

<!-- Inline JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('userpassword');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Toggle the eye icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });
</script>
</body>
</html>