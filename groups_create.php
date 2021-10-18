<?php
require 'includes/init.php';
if(isset($_SESSION['user_id']) && isset($_SESSION['email'])){
    $user_data = $user_obj->find_user_by_id($_SESSION['user_id']);
    if($user_data===false){
        header('Location: logout.php');
    }

    // fetch all users except me
    $all_users = $user_obj->all_users($_SESSION['user_id']);
}
else{
    header('Location: logout.php');
    exit;
}


//groups
//creating groups
if(isset($_POST["group_name"]) && isset($_POST["description"])){
    $result = $group_obj->createGroups($_POST['group_name'], $_POST['description'],$_POST['name'], $user_data->username);
}

//retrieving groups which user is present in
$retResult = $group_obj->retrieveGroups($user_data->username, true);

////retrieving groupId's which user is present in
$retResultId = $group_obj->retrieveGroups($user_data->username, false);

//retrieving all groups
$retAllGroups = $group_obj->allGroups($user_data->username);

//username
$uname = $user_data->username;

$s_id = '';
if(isset($_POST['fetch_btn'])) {
    $s_id = $_POST['get_email'];
}

$email_id = '';
if(isset($_POST['add'])) {
    $email_id = $_POST['name[]'];
}


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
    </div>



    <nav>
        <ul>
            <li><a href="profile.php" rel="noopener noreferrer" >Home</a></li>

            <li><a href="expense.php" rel="noopener noreferrer">Add an Expense</a></li>
            <li><a href="activity.php" rel="noopener noreferrer">Activity</a></li>
            <li><a href="groups_create.php" rel="noopener noreferrer" class="active">Groups</a></li>

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
                        <form action="" method="POST" name="add_name" id="add_name">

                                <div class="table-responsive">
                                    <table><tr><th>Create Groups</th></tr></table>

                                    <table class="table table-bordered" id="dynamic_field">

                                        <tr>
                                            <td> <label for="group_name">Group Name:</label></td>
                                            <td><input type="text" name="group_name" placeholder="Enter Group Name"></td>
                                        </tr>
                                        <tr>
                                            <td><label for="description">Description: </label></td>
                                            <td><input type="text" name="description" placeholder="Expense Description"></td>
                                        </tr>

                                        <tr><td><label for="group_name">Add Group Members: </label></td></tr>
                                        <tr>
                                            <td><input type="text" name="name[]" placeholder="Enter your Name" class="form-control name_list" required></td>
                                            <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>
                                        </tr>
                                    </table>
<!--                                    <label for="amount">Amount:-->
<!--                                        <input type="text" name="amount" placeholder="Enter amount">-->
<!--                                    </label>-->
<!--                                    <label for="split_type">Split type:-->
<!--                                        <input type="text" name="Split Type" placeholder="Enter Group Name">-->
<!--                                    </label>-->
                                    <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />
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

                    <script>
                        $(document).ready(function(){
                            var i=1;
                            $('#add').click(function(){
                                i++;
                                $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="name[]" placeholder="Enter your Name" class="form-control name_list" required /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
                            });
                            $(document).on('click', '.btn_remove', function(){
                                var button_id = $(this).attr("id");
                                $('#row'+button_id+'').remove();
                            });
                            $('#submit').click(function(){
                                $.ajax({
                                    url:"",
                                    method:"POST",
                                    data:$('#add_name').serialize(),
                                    success:function(data)
                                    {
                                        //alert(data);
                                        $('#add_name')[0].reset();
                                    }
                                });
                            });
                        });
                    </script>
                </div>

            </div>
            <div class="col">
                <div class="profile_container">
                <div class="all_users">
                    <h1>Group Names</h1>
                <?php

                if($retResult){
                    foreach ($retResultId as $id_num){
                        foreach ($retResult as $item) {
                            foreach ($retAllGroups as $row){
                                if($row->id === $id_num and $row->group_name === $item){
                                    echo '<div class="user_box">
                                 <div class="user_info"><span>'.$item.'</span></div>
                                 <span><a href="groups_expense.php?id='.$id_num.'" class="see_profileBtn">Add an Expense</a></span>
                               </div>';
                                }
                            }
                        }

                    }
                }
                else{
                    echo '<div class="user_box">
                                 <div class="user_info"><span>There are no Groups</span></div>
                                 
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