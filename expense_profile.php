<?php
require 'includes/init.php';
if(isset($_SESSION['user_id']) && isset($_SESSION['email'])){
    $user_data = $user_obj->find_user_by_id($_SESSION['user_id']);

    if($user_data===false){
        header('Location: logout.php');
    }

    // fetch all users except me
    $all_users = $user_obj->all_users($_SESSION['user_id']);

    //fetching expenses
    $all_expenses = $expenses_obj->expensesRetrieval($user_data->username);

}
else{
    header('Location: logout.php');
    exit;
}

//expense button
if(isset($_POST['submit'])) {
    if (isset($_POST['description'])  && isset($_POST['share_with']) && isset($_POST['amount']) && isset($_POST['split'])) {
        $result = $expenses_obj->insertExpense($_POST['description'], $user_data->username, $_POST['share_with'], $_POST['amount'], $_POST['split']);

    } else {
        $result['errorMessage'] = 'Fields are filled.';
    }
}

if(isset($_GET['id'])){
    $get_expense = $expenses_obj->expenseRetrieval($_GET['id']);

}



$s_id = '';
if(isset($_POST['fetch_btn'])) {
    $s_id = $_POST['get_email'];
}

//requesting notification number
$get_req_num = $friend_obj->req_notification($_SESSION['user_id'], false);
//total friends
$get_frnd_num = $friend_obj->get_all_friends($_SESSION['user_id'], false);

//get_all_friends
$get_all_friends = $friend_obj->get_all_friends($_SESSION['user_id'], true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo  $user_data->username ;?></title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</head>
<body>
<div class="profile_container">
    <div class="inner_profile">
        <img src="logo.jpeg" alt="Eazy Roommate" align="left" width="120" height="120">
        <div class="img" align="center" style="margin-left: 45%;">


            <img src="profile_images/<?php echo $user_data->user_image;?>" alt="Profile image">
        </div>
        <h1 style="margin-right: 13%;"><?php echo $user_data->username;?></h1>
    </div>

    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


    <nav>
        <ul>
            <li><a href="profile.php" rel="noopener noreferrer" >Home</a></li>

            <li><a href="expense.php" rel="noopener noreferrer" class="active">Add an Expense</a></li>
            <li><a href="activity.php" rel="noopener noreferrer">Activity</a></li>
            <li><a href="groups_create.php" rel="noopener noreferrer">Groups</a></li>

            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                More
            </button>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="notifications.php" rel="noopener noreferrer">Requests<span class="badge <?php
                    if($get_req_num > 0){
                        echo 'redBadge';
                    }
                    ?>"><?php echo $get_req_num;?></span></a>
                <a class="dropdown-item" href="friends.php" rel="noopener noreferrer">Friends<span class="badge"><?php echo $get_frnd_num;?></span></a>
                <a class="dropdown-item" href="image_upload.php" rel="noopener noreferrer">Edit Profile</a>
                <a class="dropdown-item" href="logout.php" rel="noopener noreferrer">Logout</a>
            </div>
        </ul>
    </nav>


    <div class="container">
        <div class="row">
            <div class="col">
                <div class="login_signup_container groups_container" >
                    <div class="form-group">
                        <form action="" method="POST">

                            <div class="table-responsive">
                                <center><table><tr><th>Your Expense Details</th></tr></table></center>

                                <table class="table table-bordered" id="dynamic_field"">

            <?php

            echo'
                                <tr>
                                    <td> <label for="description">Expense Name:</label>
                                        <input type="text" name="description" value="'.$get_expense->exp_name.'" disabled></td>
                                </tr>
                                ';




                if ($get_expense->user1 === $user_data->username) {

                    echo '<tr><td><label for="amount">Amount you get: </label></td></tr>
                          <tr><td><input type="text" value="' . $get_expense->amount1 . '" disabled></td></tr>
                          <tr><td><label for="name">From: </label></td></tr>
                          <tr><td><input type="text" value="' . $get_expense->user2 . '" disabled></td></tr>';

                } elseif ($get_expense->user2 === $user_data->username) {
                    echo '<tr><td><label for="amount">Amount you owe: </label></td></tr>
                          <tr><td><input type="text" value="' . $get_expense->amount2 . '" disabled> </td></tr>
                          <tr><td><label for="name">To: </label></td></tr>
                          <tr><td><input type="text" value="' . $get_expense->user1 . '" disabled></td></tr>';




            }


            echo '<tr>
                                       <td><span><a href="expense.php" class="see_profileBtn">Go Back</a></span>
                                       </td>
                                    </tr>';
            ?>


                                </table>


                            </div>


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

                </div>

            </div>
                </div>
            </div>

        </div>
    </div>


</div>
</div>
</body>
</html>

