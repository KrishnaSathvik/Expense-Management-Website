<?php
require 'includes/init.php';
if(isset($_SESSION['user_id']) && isset($_SESSION['email'])){
    $user_data = $user_obj->find_user_by_id($_SESSION['user_id']);
    if($user_data===false){
        header('Location: logout.php');
    }

    //getting username from URL and retrieving from SQL
    if(isset($_GET['id'])){
        $groupId = $_GET['id'];
        $group_data = $group_obj->find_group_members($_GET['id']);
        $group_expense_data = $group_obj->find_group_expense($_GET['id']);
        $group_name = $group_obj->groupName($_GET['id']);
//        if($group_data===false){
//            header('Location: profile.php');
//            exit;
//        }
    }

    if(isset($_GET['id'])){
        //$group_data = $group_obj->find_group_members($_GET['id']);
        $expense_name = $_GET['id'];
        $group_expense_data = $group_obj->find_group_expenses($_GET['id']);
        $youGet =  $group_expense_data[0]->amount - $group_expense_data[0]->eachPay;

        foreach ($group_expense_data as $row){
            if($row->expense_name === $_GET['id']){
                $group_data = $group_obj->find_group_expense_members($row->expense_name);
                $group_name = $group_obj->groupName($row->group_id);
            }
        }


//        if($group_data===false){
//            header('Location: profile.php');
//            exit;
//        }
    }

    //inserting group expense data

    if( isset($_POST['description']) && isset($_POST['amount']) && isset($_POST['name'])){
        $result = $group_obj->updateGroupExpense($_GET['id'], $_POST['description'],$_POST['amount'],$_POST['name'],$user_data->username);
    }

    if(isset($_POST['expense_name']) && isset($_POST['eachPay'])){
        if($user_data->username === $group_expense_data[0]->paid_by){
            $result = $group_obj->settleGroupExpense($_POST['expense_name'],$group_expense_data[0]->group_id);
        }
        else{
            $result = $group_obj->settleGroupExpenseNonPaid($_POST['expense_name'],$group_data, $user_data->username,$group_expense_data[0]->group_id);

        }
    }



}
else{
    header('Location: logout.php');
    exit;
}

//username
$uname = $user_data->username;

//all users
// fetch all users except me
$all_users = $user_obj->all_users($_SESSION['user_id']);

//requesting notification number
$get_req_num = $friend_obj->req_notification($_SESSION['user_id'], false);
//total friends
$get_frnd_num = $friend_obj->get_all_friends($_SESSION['user_id'], false);


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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
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
        <h1 style="margin-right: 13%;">Group Name: <u><?php echo $group_name->group_name;?></u></h1>

    </div>



    <nav>
        <ul>
            <li><a href="profile.php" rel="noopener noreferrer" class="active">Home</a></li>

            <li><a href="expense.php" rel="noopener noreferrer">Add an Expense</a></li>
            <li><a href="activity.php" rel="noopener noreferrer">Activity</a></li>
            <li><a href="groups_create.php" rel="noopener noreferrer">Groups</a></li>

            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                More
            </button>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="notifications.php" rel="noopener noreferrer">Requests<span class="badge <?php
                    if(
                        $get_req_num > 0){
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
                        <form action="" method="POST" name="add_name" id="add_name">
                            <div class="table-responsive">
                                <table><tr><th>Settle Group Expense</th></tr></table>

                                <table class="table table-bordered" id="dynamic_field">
                                    <?php

                                    foreach ($group_expense_data as $item) {
                                        echo '
                                                                        <tr>
                                                                            <td> <label for="expense_name">Expense name:</label></td>
                                                                            <td><input type="text" name="expense_name" value="'.$item->expense_name.'" hidden> '.$item->expense_name.' </td>
                                                                        </tr>';

                                        if ($user_data->username === $item->paid_by) {
                                            echo '<tr>
                                                                            <td><label for="eachPay">Amount You Get: </label></td>
                                                                            <td><input type="text" name="eachPay" value="'.$youGet.'" hidden>'.$youGet.'</td>
                                                                        </tr>';
                                        }
                                        else{
                                            echo '
                                                                        <tr>
                                                                            <td><label for="eachPay">Amount you owe:</label></td>
                                                                            <td><input type="text" name="eachPay" value="'.$item->eachPay.'" hidden>'.$item->eachPay.'</td>
                                                                        </tr>';
                                        }
                                    }
                                    ?>
                                </table>

                            </div>
                            <input type="submit" name="submit" id="submit" class="btn btn-info" value="Settle Up" />
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
            <div class="col">


                <div class="all_users">
                    <h4>Current Expense</h4>
                    <?php
                    if($group_expense_data) {
                        foreach ($group_expense_data as $item) {
                            echo '<div class="user_box">
                                 <div class="user_info"><span>' . $item->expense_name . '</span></div>
                                 <span><a href="group_expense_profile.php?id=' . $item->expense_name . '" class="see_profileBtn">View</a></span></div>';
                        }
                    }
                    else{
                        echo '<div class="user_box">
                                 <div class="user_info"><span>There are no expenses</span></div>
                                 
                               </div>';
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>