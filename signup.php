<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Sign Up</title>
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
        }
        .header-text {
            color: #d81b60; /* Darker pink for header */
            text-align: center;
        }
        .sub-text {
            color: #f06292; /* Lighter pink for subtext */
            text-align: center;
        }
        .form-label {
            color: #d81b60; /* Darker pink for labels */
            
            display: block; /* Make labels block elements */
            margin: 10px 0; /* Space around labels */
        }
        .input-text {
            border: 1px solid #d81b60; /* Pink border for inputs */
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        .login-btn {
            background-color: #d81b60; /* Button color */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            width: 100%; /* Make buttons full width */
        }
        .login-btn:hover {
            background-color: #c2185b; /* Darker shade on hover */
        }
        .input-container {
            display: flex;
            justify-content: center; /* Center the input containers */
            margin-bottom: 15px;
        }
        .checkmark {
            display: none; /* Hidden by default */
            color: green; /* Green checkmark */
            font-size: 20px;
            margin-left: 10px; /* Space between input and checkmark */
        }
        .checkmark.valid {
            display: inline; /* Show when valid */
        }
        .label-td {
            padding: 5px;
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

if ($_POST) {
    $_SESSION["personal"] = array(
        'fname' => $_POST['fname'],
        'lname' => $_POST['lname'],
        'address' => $_POST['address'],
        'dob' => $_POST['dob']
    );
    print_r($_SESSION["personal"]);
    header("location: create-account.php");
}
?>
<div class="container">
    <form action="" method="POST">
        <table border="0" width="100%">
            <tr>
                <td colspan="2">
                    <p class="header-text">Let's Get Started</p>
                    <p class="sub-text">Add Your Personal Details to Continue</p>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="fname" class="form-label">First Name: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <div class="input-container">
                        <input type="text" name="fname" id="fname" class="input-text" placeholder="First Name" required oninput="validateName()">
                        <i id="fname-check" class="checkmark fas fa-check-circle"></i>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="lname" class="form-label">Last Name: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <div class="input-container">
                        <input type="text" name="lname" id="lname" class="input-text" placeholder="Last Name" required oninput="validateName()">
                        <i id="lname-check" class="checkmark fas fa-check-circle"></i>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="address" class="form-label">Address: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <div class="input-container">
                        <input type="text" name="address" id="address" class="input-text" placeholder="Street, Barrangay, Municipality, Province" required oninput="validateAddress()">
                        <i id="address-check" class="checkmark fas fa-check-circle"></i>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="dob" class="form-label">Date of Birth: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <div class="input-container">
                        <input type="date" name="dob" id="dob" class="input-text" required oninput="validateDate()">
                        <i id="dob-check" class="checkmark fas fa-check-circle"></i>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn">
                </td>
                <td>
                    <input type="submit" value="Next" class="login-btn btn-primary btn">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br>
                    <label for="" class="sub-text" style="font-weight: 280;">Already have an account&#63; </label>
                    <a href="login.php" class="hover-link1 non-style-link" style="color: #d81b60;">Login</a>
                    <br><br><br>
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
    function validateName() {
        const fname = document.getElementById('fname').value;
        const lname = document.getElementById('lname').value;
        
        document.getElementById('fname-check').classList.toggle('valid', fname.trim() !== '');
        document.getElementById('lname-check').classList.toggle('valid', lname.trim() !== '');
    }

    function validateAddress() {
        const address = document.getElementById('address').value;
        document.getElementById('address-check').classList.toggle('valid', address.trim() !== '');
    }

    function validateDate() {
        const dob = document.getElementById('dob').value;
        document.getElementById('dob-check').classList.toggle('valid', dob !== '');
    }
</script>

</body>
</html>