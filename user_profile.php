<?php

require 'includes/init.php';
if(isset($_SESSION['user_id']) && isset($_SESSION['email'])){
    if(isset($_GET['id'])){
        $user_data = $user_obj->find_user_by_id($_GET['id']);
        if($user_data===false){
            header('Location: profile.php');
            exit;
        }
        else{
            if($user_data->id == $_SESSION['user_id']){
                header('Location: profile.php');
                exit;
            }
        }
    }

    if(isset($_POST['remind'])){
        $result = $email_obj->sendRemindEmail($user_data->user_email,$_SESSION['email']);
    }



}
else{
    header('Location: logout.php');
    exit;
}

//check friends
$is_already_friends = $friend_obj->is_already_friends($_SESSION['user_id'], $user_data->id);
// if I am the request sender
$check_req_sender = $friend_obj->req_sender($_SESSION['user_id'], $user_data->id);
// if I am the request receiver
$check_req_receiver = $friend_obj->req_receiver($_SESSION['user_id'], $user_data->id);
//total requests
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
    <title><?php echo  $user_data->username;?></title>
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

        <nav>
                <ul>
                    <li><a href="profile.php" rel="noopener noreferrer">Home</a></li>

                    <li><a href="expense.php" rel="noopener noreferrer">Add an Expense</a></li>
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
                        <a class="dropdown-item" href="image_upload.php" rel="noopener noreferrer">Edit Profie</a>
                        <a class="dropdown-item" href="logout.php" rel="noopener noreferrer">Logout</a>
                    </div>
                </ul>
            </nav>
            <div class="actions">
                <?php
                if($is_already_friends){
                    echo '<a href="functions.php?action=unfriend_req&id='.$user_data->id.'" class="req_actionBtn unfriend">Unfriend</a>
                           
                          <form action="" method="POST">
                          <label for="remind"><h4>Send a friendly reminder</h4></label>
                          <input type="submit" name="remind" class="req_actionBtn unfriend" value="Remind"></input>
                          </form>';
                    echo '<div>';
    if(isset($result['errorMessage'])){
        echo '<p class="errorMsg">'.$result['errorMessage'].'</p>';
    }
    if(isset($result['successMessage'])){
        echo '<p class="successMsg">'.$result['successMessage'].'</p>';
    }
    echo '</div>';
                }
                elseif ($check_req_sender){
                    echo '<a href="functions.php?action=cancel_req&id='.$user_data->id.'" class="req_actionBtn cancelRequest">Cancel Request</a>';
                }
                elseif ($check_req_receiver){
                    echo '<a href="functions.php?action=ignore_req&id='.$user_data->id.'" class="req_actionBtn ignoreRequest">Ignore</a>
                        <a href="functions.php?action=accept_req&id='.$user_data->id.'" class="req_actionBtn acceptRequest">Accept</a>';
                }
                else{
                    echo '<a href="functions.php?action=send_req&id='.$user_data->id.'" class="req_actionBtn sendRequest">Send Request</a>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
