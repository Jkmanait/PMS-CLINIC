<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Create Account</title>
    <style>
        body {
            background-color: #ffe4e1; /* Light pink background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            margin: 0; /* Remove default margin */
        }
        .container {
            background-color: #ffebee; /* Light pink container */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 60%;
            max-width: 600px; /* Limit max width for smaller screens */
            animation: transitionIn-X 0.5s;
        }
        .header-text, .sub-text, .form-label {
            color: #d81b60; /* Darker pink */
            text-align: center;
        }
        .input-text {
            border: 1px solid #d81b60; /* Pink border for inputs */
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        .login-btn {
            background-color: #d81b60; /* Button color */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            width: 100%;
            margin: 5px 0;
        }
        .login-btn:hover {
            background-color: #c2185b; /* Darker shade on hover */
        }
        .input-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .checkmark, .wrongmark {
            display: none;
            font-size: 20px;
            margin-left: 10px;
        }
        .checkmark.valid { display: inline; color: green; }
        .wrongmark.invalid { display: inline; color: red; }
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

$error = '';
if ($_POST) {
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
        $sqlmain = "SELECT * FROM webuser WHERE email=?";
        $stmt = $mysqli->prepare($sqlmain);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
        } else {
            $mysqli->query("INSERT INTO patient(pemail,pname,ppassword, paddress, pdob, ptel) VALUES('$email','$name','$newpassword','$address','$pdob','$tele');");
            $mysqli->query("INSERT INTO webuser VALUES('$email','p')");
            $_SESSION["user"] = $email;
            $_SESSION["usertype"] = "p";
            $_SESSION["username"] = $fname;
            header('Location: login.php');
        }
    } else {
        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Confirmation Error or Agreement not accepted!</label>';
    }
}
?>

<div class="container">
    <form action="" method="POST">
        <p class="header-text">Let's Get Started</p>
        <p class="sub-text">It's Okay, Now Create User Account.</p>
        <label for="newemail" class="form-label">Email: </label>
        <div class="input-container">
            <input type="text" name="newemail" id="newemail" class="input-text" placeholder="Email Address" required oninput="validateEmail()">
            <i id="newemail-check" class="checkmark fas fa-check-circle"></i>
            <i id="newemail-wrong" class="wrongmark fas fa-times-circle"></i>
        </div>
        <label for="tele" class="form-label">Mobile Number: </label>
        <div class="input-container">
            <input type="text" name="tele" id="tele" class="input-text" placeholder="ex: 09123456789" pattern="09[0-9]{9}" oninput="validateTele()">
            <i id="tele-check" class="checkmark fas fa-check-circle"></i>
            <i id="tele-wrong" class="wrongmark fas fa-times-circle"></i>
        </div>
        <label for="newpassword" class="form-label">Create Password: </label>
        <input type="password" name="newpassword" id="newpassword" class="input-text" placeholder="New Password" required onkeyup="checkPasswordStrength()">
        <span id="password-strength" style="display: block; font-size: 0.9em; margin-top: 5px;"></span>
        <label for="cpassword" class="form-label">Confirm Password: </label>
        <div class="input-container">
            <input type="password" name="cpassword" id="cpassword" class="input-text" placeholder="Confirm Password" required oninput="validateConfirmPassword()">
            <i id="cpassword-check" class="checkmark fas fa-check-circle"></i>
            <i id="cpassword-wrong" class="wrongmark fas fa-times-circle"></i>
        </div>
        <span id="error-message" style="color: red;"><?php echo $error; ?></span>
        <div class="agreement">
            <input type="checkbox" name="agreement" id="agreement" required>
            <label for="agreement">I have read and agree to the <a href="javascript:void(0);" onclick="showModal()" style="text-decoration: underline;">User Agreement</a>.</label>
        </div>
        <input type="button" value="Reset" class="login-btn btn-primary-soft btn" onclick="resetForm()">
        <input type="submit" value="Sign Up" class="login-btn btn-primary btn">
        <p class="sub-text" style="font-weight: 280;">Already have an account&#63; <a href="login.php" class="hover-link1 non-style-link" style="color: #d81b60;">Login</a></p>
    </form>
</div>

<!-- User Agreement Modal -->
<div id="terms">
    <div class="modal-content">
        <h2 style="text-align: center; color: #333;">User Agreement</h2>
        <p style="color: #555;">By creating an account, you agree to the following terms and conditions:</p>
        <ul style="margin: 10px 0 20px 20px; color: #555; list-style-type: disc; padding-left: 20px;">
            <li><strong>Eligibility:</strong> You must be at least 18 years old or have parental consent.</li>
            <li><strong>Account Security:</strong> You are responsible for maintaining your account's confidentiality.</li>
            <li><strong>Personal Information:</strong> You agree to provide accurate information.</li>
            <li><strong>Use of Services:</strong> Use the services for lawful purposes only.</li>
            <li><strong>Appointment Policies:</strong> Follow our appointment policies.</li>
            <li><strong>Medical Records Access:</strong> Use your medical records responsibly.</li>
            <li><strong>Limitation of Liability:</strong> Dr. Bolong Pedia Clinic is not liable for indirect damages.</li>
            <li><strong>Modification of Agreement:</strong> We may modify terms and notify you.</li>
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

    function resetForm() {
        window.location.href = 'signup.php'; // Replace with your sign-up page filename
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
</script>

</body>
</html>