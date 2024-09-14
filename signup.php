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
    <title>Sign Up</title>
    <style>
        .input-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .input-text {
            flex: 1; /* Make the input field take up remaining space */
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
<center>
    <div class="container">
        <form action="" method="POST">
            <table border="0">
                <tr>
                    <td colspan="2">
                        <p class="header-text">Let's Get Started</p>
                        <p class="sub-text">Add Your Personal Details to Continue</p>
                    </td>
                </tr>
                <tr>
                    <td class="label-td" colspan="2">
                        <label for="name" class="form-label">First Name: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <div class="input-container" >
                            <input type="text" name="fname" id="fname" class="input-text" placeholder="First Name" required oninput="validateName()">
                            <i id="fname-check" class="checkmark fas fa-check-circle"></i>
                        </div>
                    </td>
                    
                    <td class="label-td">
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
                            <input type="text" name="address" id="address" class="input-text" placeholder="Address" required oninput="validateAddress()">
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
                    <td class="label-td" colspan="2">
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
                        <a href="login.php" class="hover-link1 non-style-link">Login</a>
                        <br><br><br>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</center>

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
