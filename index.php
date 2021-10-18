<?php

require 'includes/init.php';

//if user makes login request
if(isset($_POST['email']) && isset($_POST['password'])){
    $result = $user_obj->login($_POST['email'],$_POST['password']);
}

//if user already logged in
if(isset($_SESSION['email'])){
    header('Location: profile.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang=""en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
</head>

<body>
<div class="profile_container">
<div class="row">
    <div class="col">
        <div class="row">
            <div class="inner_profile">
                <img src="logo.jpeg" alt="Eazy Roommate" align="left" width="120" height="120">
            </div>
        </div>
        <div class="row">
            <div class="main_container login_signup_container">

                <h1>Login</h1>
                <form action="" method="POST">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" spellcheck="false" placeholder="Enter email" required>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <input type="submit" value="Login">

                    <table>
                        <tr>
                            <td><a href="forgot_password.php" class="form_link">Forgot Password?</a></td>
                            <td><a href="signup.php" class="form_link" style="text-align: center">Sign Up  </a></td>
                        </tr>


                    </table>

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
                </form>
            </div>
        </div>
    </div>


</div>


</body>
</html>

