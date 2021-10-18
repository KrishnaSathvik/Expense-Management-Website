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


if(isset($_POST['invite_btn'])) {
    $result = $email_obj->sendInviteMail($_POST['invite']);
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
            <li><a href="profile.php" rel="noopener noreferrer" class="active" >Home</a></li>

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
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="login_signup_container groups_container" >
                    <div class="form-group">
                        <form action="" method="POST" name="add_name" id="add_name">

                            <div class="table-responsive">
                                <table><tr><th>Balances:</th></tr></table>

                                <table class="table table-bordered" id="dynamic_field">

                                    <tr><td>



                                            <?php $mysqli = new mysqli("localhost", "root", "", "easyroommate");
                                            if ($mysqli ==false)
                                            {
                                                die("ERROR: Could not connect. ".$mysqli->connect_error);
                                            }
                                            ?>
                                            <div class="usersWrapper">
                                                <?php
                                                if($all_users){

                                                    foreach($all_users as $row){


//						echo $user_data->username;
                                                        $user_1 = $user_data->username;
                                                        $user2=$row->username;
                                                        $ex1 = mysqli_query($mysqli, "SELECT SUM(amount1) as sum1 FROM expense WHERE user1='$user_1' AND user2='$user2'");
                                                        $row1 = @mysqli_fetch_assoc($ex1);
                                                        $sum1 = $row1['sum1'];
                                                        $ex2 = mysqli_query($mysqli, "SELECT SUM(amount2) as sum2 FROM expense WHERE user1='$user2' AND user2='$user_1'");
                                                        $row2 = @mysqli_fetch_assoc($ex2);
                                                        $sum2 = $row2['sum2'];
                                                        $total_balance=$sum1-$sum2;
                                                        if ($total_balance!=0)
                                                        {
                                                            echo '<div class="user_box">';

                                                            if($total_balance<0)
                                                            {
                                                                echo '<div class="user_info">' .' You owe '.$user2.':  $'. $total_balance*-1;

                                                            }
                                                            if($total_balance>0)
                                                            {
                                                                echo '<div class="user_info">'.$user2 .' owes you:  $'. $total_balance;


                                                            }
															if($total_balance)
								{
									echo '&nbsp <form action="" method="post" enctype="multipart/form-data">
											<br><input type="submit" name="settleup" value="settle up">
											 <input type="hidden" name="user2" value='.$user2.'><br><br>';
											//echo $user2;
											echo '</form>';
										echo'</div></div><br>';	
									
									if(isset($_POST['settleup']))
									{
										$mysqli = new mysqli("localhost", "root", "", "easyroommate"); 
										if ($mysqli ==false) 
										{ 
											die("ERROR: Could not connect. ".$mysqli->connect_error); 
										}
										
											//echo "<script> alert('Expense succesfully settled up'); </script>";
											$username=$user_data->username;
											$user2= $_POST['user2'];
										//echo $user2;
										$settle1 = mysqli_query($mysqli, "DELETE FROM expense WHERE user1='$user2' and user2='$username' ");
										$settle2 = mysqli_query($mysqli, "DELETE FROM expense WHERE user1='$username' and user2='$user2'");


//										if($settle1 && $settle2)
//                                        {
//                                            $sendSettleMail = $email_obj->sendSettleMail()
//                                        }

										//echo "<script> alert('You are succesfully settled up'); </script>";
										 header('Location: profile.php');
									}
								}

//						    echo "Balance: ".$total_balance."<br>";
                                                        }
//						else{
//                            echo $row->username;
//							echo " &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp You are settled up";
//						}
                                                        echo '</div>';

                                                    }
                                                }
                                                else{
                                                    echo '<h4>There is no User!</h4>';
                                                }
                                                ?>
                                </table>
                            </div>
                        </form>
                        <div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>

                    <div class="col">


                        <table class="table table-bordered" id="dynamic_field">
                            <table><tr><th>Find users:</th></tr></table>
                            <tr><td>

                                    <form action="" method="POST">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text"  name= "get_email" class="form-control" placeholder="Enter email ID" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="submit" name="fetch_btn" class="btn btn-primary">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                    <table><tr><th>Invite:</th></tr></table>
                                    <tr><td><form action="" method="POST">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text"  name= "invite" class="form-control" placeholder="Enter email ID" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="submit" name="invite_btn" class="btn btn-primary">Invite</button>
                                            </div>
                                        </div>
                                    </form> </td></tr>

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

                                    <div class="all_users">

                                        <div class="usersWrapper">
                                            <?php

                                            if($all_users){
                                                $flag = 0;
                                                foreach($all_users as $row){

                                                    if($row->user_email === $s_id && $s_id !== ''){
                                                        $flag =1;
                                                        echo '<div class="user_box">
                                <div class="user_img"><img src="profile_images/'.$row->user_image.'" alt="Profile Image"></div>
                                <div class="user_info"><span>'.$row->username.'</span>
                                <span><a href="user_profile.php?id='.$row->id.'" class="see_profileBtn">See Profile</a></span></div>
                                </div>';
                                                    }




                                                }
                                                if($flag=== 0 && $s_id !== ''){
                                                    echo '<h4>User not registered</h4>';
                                                }
                                            }

                                            else{
                                                echo '<h4>There is no User!</h4>';
                                            }
                                            ?>
                                        </div>



                                    </div>

                    </div>
                </div>
            </div>

</body>
</html>