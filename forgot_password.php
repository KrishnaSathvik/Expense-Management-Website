<?php
require 'includes/init.php';

//if user is making a sign up request
if(isset($_POST['email'])){
    $result = $user_obj->email_in_db($_POST['email']);
    $email_result = $email_obj->sendForgotPassEmail($_POST['email'],$result);
}

//user logged in already
if(isset($_SESSION['email'])){
    header('Location: profile.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-eqiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
</head>
<body>
<div class="main_container login_signup_container">
    <h1>Forgot Password?</h1>
    <form action="" method="POST" novalidate>
        <h5>Please enter you registered email ID, a password reset link will be sent to your email address</h5>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" spellcheck="false" placeholder="Enter your email address" required>
        <input type="submit" name="sendMail" value="Send link">
        <a href="index.php" class="form_link">Login</a>
    </form>
    <div>
        <?php
        if(isset($email_result['errorMessage'])){
            echo '<p class="errorMsg">'.$email_result['errorMessage'].'</p>';
        }
        if(isset($email_result['successMessage'])){
            echo '<p class="successMsg">'.$email_result['successMessage'].'</p>';
        }
        ?>
    </div>
</div>
</body>
</html>
