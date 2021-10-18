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

$s_id = '';
if(isset($_POST['fetch_btn'])) {
    $s_id = $_POST['get_email'];
}

//requesting notification number
$get_req_num = $friend_obj->req_notification($_SESSION['user_id'], false);
//total friends
$get_frnd_num = $friend_obj->get_all_friends($_SESSION['user_id'], false);

//image upload
if (isset($_POST['submit'])) {
    move_uploaded_file($_FILES['file']['tmp_name'], "profile_images/" . $_FILES['file']['name']);
    $con = mysqli_connect("localhost", "root", "", "easyroommate");
    $q = mysqli_query($con, "UPDATE users SET user_image = '" . $_FILES['file']['name'] . "' WHERE id = '" . $_SESSION['user_id'] . "'");
//    $upload_image = $user_obj->upload_image($_SESSION['user_id'], false);
    if($q){
        header('Location: image_upload.php');
        exit;
    }
}

if(isset($_POST['submit2']))
{
    $mysqli = new mysqli("localhost", "root", "", "easyroommate");
    if ($mysqli ==false)
    {
        die("ERROR: Could not connect. ".$mysqli->connect_error);
    }
    $userid = $user_data->id;
    $username=$_POST['new_username'];
    $update = mysqli_query($mysqli, "UPDATE users SET username='$username' WHERE id='$userid'");
    if($update){
        header('Location: image_upload.php');
        exit;
    }
}

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



    <nav>
            <ul>
                <li><a href="profile.php" rel="noopener noreferrer" >Home</a></li>

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
                    <a class="dropdown-item" href="image_upload.php" rel="noopener noreferrer">Edit Profile</a>
                    <a class="dropdown-item" href="logout.php" rel="noopener noreferrer">Logout</a>
                </div>
            </ul>
        </nav>


    <div class="inner_profile">

        <label><b> Change Your Username</b></label>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="new_username" placeholder="Enter new username">
            <input type="submit" name="submit2" value="Change"><br><br>
        </form>

    </div>
<div class="inner_profile">
    <label><b> Change User Image</b></label>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="file" placeholder="Upload a file">
        <input type="submit" name="submit" value="Change"><br><br>
		<label> Image dimensions should be less than 600x800 pixels </label>
    </form>

</div>

</div>


</body>
</html>






