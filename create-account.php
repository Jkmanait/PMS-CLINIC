<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <title>Create Account</title>
    <style>
        .container {
            animation: transitionIn-X 0.5s;
        }
        .input-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .input-text {
            flex: 1;
        }
        .checkmark {
            display: none;
            color: green;
            font-size: 20px;
            margin-left: 10px;
        }
        .checkmark.valid {
            display: inline;
        }
        .wrongmark {
            display: none;
            color: red;
            font-size: 20px;
            margin-left: 10px;
        }
        .wrongmark.invalid {
            display: inline;
        }
        .agreement {
            margin: 20px 0;
        }
        #terms {
            display: none; 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background-color: rgba(0, 0, 0, 0.5); 
            z-index: 1000;
            padding: 20px;
            overflow: auto;
        }
        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
    </style>
</head>
<body>
<?php
session_start();
$_SESSION["user"] = "";
$_SESSION["usertype"] = "";
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');
$_SESSION["date"] = $date;
include("configuration/config.php");

if ($_POST) {
    $result = $mysqli->query("SELECT * FROM webuser");
    $fname = $_SESSION['personal']['fname'];
    $lname = $_SESSION['personal']['lname'];
    $name = $fname . " " . $lname;
    $address = $_SESSION['personal']['address'];
    
    $dob = $_SESSION['personal']['dob'];
    $email = $_POST['newemail'];
    $tele = $_POST['tele'];
    $newpassword = $_POST['newpassword'];
    $cpassword = $_POST['cpassword'];
    
    if ($newpassword == $cpassword && isset($_POST['agreement'])) {
        $sqlmain = "SELECT * FROM webuser WHERE email=?;";
        $stmt = $mysqli->prepare($sqlmain);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
        } else {
            $mysqli->query("INSERT INTO patient(pemail,pname,ppassword, paddress, pnic,pdob,ptel) VALUES('$email','$name','$newpassword','$address','$nic','$dob','$tele');");
            $mysqli->query("INSERT INTO webuser VALUES('$email','p')");
            $_SESSION["user"] = $email;
            $_SESSION["usertype"] = "p";
            $_SESSION["username"] = $fname;
            header('Location: login.php');
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>';
        }
    } else {
        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Confirmation Error or Agreement not accepted!</label>';
    }
} else {
    $error = '<label for="promter" class="form-label"></label>';
}
?>

<center>
    <div class="container">
        <table border="0" style="width: 69%;">
            <tr>
                <td colspan="2">
                    <p class="header-text">Let's Get Started</p>
                    <p class="sub-text">It's Okay, Now Create User Account.</p>
                </td>
            </tr>
            <form action="" method="POST">
            <tr>
                <td class="label-td" colspan="2">
                    <label for="newemail" class="form-label">Email: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <div class="input-container">
                        <input type="text" name="newemail" id="newemail" class="input-text" placeholder="Email Address" required oninput="validateEmail()">
                        <i id="newemail-check" class="checkmark fas fa-check-circle"></i>
                        <i id="newemail-wrong" class="wrongmark fas fa-times-circle"></i>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="tele" class="form-label">Mobile Number: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <div class="input-container">
                        <input type="text" name="tele" id="tele" class="input-text" placeholder="ex: 09123456789" pattern="09[0-9]{9}" oninput="validateTele()">
                        <i id="tele-check" class="checkmark fas fa-check-circle"></i>
                        <i id="tele-wrong" class="wrongmark fas fa-times-circle"></i>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="newpassword" class="form-label">Create Password: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="password" name="newpassword" id="newpassword" class="input-text" placeholder="New Password" required onkeyup="checkPasswordStrength()">
                    <span id="password-strength" style="display: block; font-size: 0.9em; margin-top: 5px;"></span>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="cpassword" class="form-label">Confirm Password: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <div class="input-container">
                        <input type="password" name="cpassword" id="cpassword" class="input-text" placeholder="Confirm Password" required oninput="validateConfirmPassword()">
                        <i id="cpassword-check" class="checkmark fas fa-check-circle"></i>
                        <i id="cpassword-wrong" class="wrongmark fas fa-times-circle"></i>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span id="error-message" style="color: red;"><?php echo $error; ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="agreement">
                    <input type="checkbox" name="agreement" id="agreement" required>
                    <label for="agreement">I have read and agree to the <a href="javascript:void(0);" onclick="showModal()" style="text-decoration: underline;">User Agreement</a>.</label>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn">
                </td>
                <td>
                    <input type="submit" value="Sign Up" class="login-btn btn-primary btn">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br>
                    <label for="" class="sub-text" style="font-weight: 280;">Already have an account&#63; </label>
                    <a href="login.php" class="hover-link1 non-style-link">Login</a>
                    <br><br><br>
                </td>
            </tr>
            </form>
        </table>
    </div>

    <!-- User Agreement Modal -->
<div id="terms" style="display: none;">
    <div class="modal-content" style="max-width: 800px; margin: auto; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); padding: 40px;">
        <h2 style="text-align: center; color: #333;">User Agreement</h2>
        <p style="color: #555;">By creating an account, you agree to the following terms and conditions:</p>
        <ul style="margin: 10px 0 20px 20px; color: #555; list-style-type: disc; padding-left: 20px;">
            <li><strong>Eligibility:</strong> You must be at least 18 years old or have parental consent.</li>
            <br>
            <li><strong>Account Security:</strong> You are responsible for maintaining your account's confidentiality.</li>
            <br>
            <li><strong>Personal Information:</strong> You agree to provide accurate information.</li>
            <br>
            <li><strong>Use of Services:</strong> Use the services for lawful purposes only.</li>
            <br>
            <li><strong>Appointment Policies:</strong> Follow our appointment policies.</li>
            <br>
            <li><strong>Medical Records Access:</strong> Use your medical records responsibly.</li>
            <br>
            <li><strong>Limitation of Liability:</strong> Dr. Bolong Pedia Clinic is not liable for indirect damages.</li>
            <br>
            <li><strong>Modification of Agreement:</strong> We may modify terms and notify you.</li>
            <br>
            <li><strong>Governing Law:</strong> This agreement is governed by the Philippines law.</li>
        </ul>
        <div style="text-align: center;">
            <button onclick="document.getElementById('terms').style.display='none'" style="background-color: #007BFF; color: white; border: none; border-radius: 5px; padding: 10px 20px; cursor: pointer;">Close</button>
        </div>
    </div>
</div>

    <script>
        function showModal() {
            document.getElementById('terms').style.display = 'block';
        }

        function checkPasswordStrength() {
            const password = document.getElementById('newpassword').value;
            const strengthIndicator = document.getElementById('password-strength');
            const specialCharPattern = /[!@#$%^&*(),.?":{}|<>]/;
            const numberPattern = /[0-9]/;
            const letterPattern = /[a-zA-Z]/;
            const minLength = 8;

            let strength = 'Weak';
            let color = 'red';

            if (password.length >= minLength && 
                letterPattern.test(password) && 
                numberPattern.test(password) &&
                specialCharPattern.test(password)) {
                strength = 'Strong';
                color = 'green';
            } else if (password.length >= minLength && 
                       (letterPattern.test(password) || numberPattern.test(password)) && 
                       (specialCharPattern.test(password) || numberPattern.test(password))) {
                strength = 'Moderate';
                color = 'orange';
            }

            strengthIndicator.textContent = `Password Strength: ${strength}`;
            strengthIndicator.style.color = color;
        }

        function validateEmail() {
            const email = document.getElementById('newemail').value;
            const checkmark = document.getElementById('newemail-check');
            const wrongmark = document.getElementById('newemail-wrong');
            
            const isValid = email.endsWith('@gmail.com');

            if (isValid) {
                checkmark.classList.add('valid');
                wrongmark.classList.remove('invalid');
            } else {
                checkmark.classList.remove('valid');
                wrongmark.classList.add('invalid');
            }
        }

        function validateTele() {
            const tele = document.getElementById('tele').value;
            const checkmark = document.getElementById('tele-check');
            const wrongmark = document.getElementById('tele-wrong');
            
            const isValid = tele.length === 11 && tele.match(/^09[0-9]{9}$/);

            if (isValid) {
                checkmark.classList.add('valid');
                wrongmark.classList.remove('invalid');
            } else {
                checkmark.classList.remove('valid');
                wrongmark.classList.add('invalid');
            }
        }

        function validateConfirmPassword() {
            const password = document.getElementById('newpassword').value;
            const confirmPassword = document.getElementById('cpassword').value;
            const checkmark = document.getElementById('cpassword-check');
            const wrongmark = document.getElementById('cpassword-wrong');
            
            if (password === confirmPassword) {
                checkmark.classList.add('valid');
                wrongmark.classList.remove('invalid');
            } else {
                checkmark.classList.remove('valid');
                wrongmark.classList.add('invalid');
            }
        }

        function validateForm() {
            const password = document.getElementById('newpassword').value;
            const confirmPassword = document.getElementById('cpassword').value;
            const errorMessage = document.getElementById('error-message');

            const specialCharPattern = /[!@#$%^&*(),.?":{}|<>]/;

            if (!specialCharPattern.test(password)) {
                errorMessage.textContent = 'Password must contain at least one special character.';
                return false; // Prevent form submission
            }

            if (password !== confirmPassword) {
                errorMessage.textContent = 'Passwords do not match.';
                return false; // Prevent form submission
            }

            errorMessage.textContent = '';
            return true; // Allow form submission
        }
    </script>

</body>
</html>
