<?php
require 'includes/init.php';

//if user is making a sign up request
if( isset($_POST['new_password']) && isset($_POST['confirm_password']) && isset($_GET['id'])){
    $result = $user_obj->resetPassword($_GET['id'],$_POST['new_password'], $_POST['confirm_password']);
}

//user logged in already
if(isset($_SESSION['email'])){
    header('Location: profile.php');
}

if(isset($_GET['id'])){
    $uemail = $_GET['id'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-eqiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
</head>
<body>
<div class="main_container login_signup_container">
    <h1>Password Reset</h1>
    <form action="" method="POST" novalidate>
        <h3>Hello <span style="color: green"> <?php echo $uemail?> </span>!!</h3>
        <h5>Please enter your new password and confirm your password</h5>
        <label for="password">New Password</label>
        <input type="password" id="new_password" name="new_password" placeholder="Enter your password" required>
        <label for="password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Enter your password" required>
        <input type="submit" name="sendMail" value="Reset Password">
        <a href="index.php" class="form_link">Login</a>
    </form>
    <div>
        <?php
        if(isset($result['errorMessage'])){
            echo '<p class="errorMsg">'.$result['errorMessage'].'</p>';
        }
        if(isset($result['successMessage'])){
            echo '<p class="successMsg">'.$result['successMessage'].'</p>';
        }
        ?>
    </div>
</div>
</body>
</html>
