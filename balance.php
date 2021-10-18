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

                <li><a href="expense.php" rel="noopener noreferrer">Add an Expense</a></li>
                li><a href="activity.php" rel="noopener noreferrer">Activity</a></li>
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

        <div class="all_users">
            <h3>BALANCES</h3>
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
                            echo '<div class="user_info">'.$row->username.'<span>Balance: '.$total_balance.'</span></div>';
//						    echo "Balance: ".$total_balance."<br>";
						}
//						else{
//                            echo $row->username;
//							echo " &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp You are settled up";
//						}
								echo '</div><br></br>';
								
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
</body>
</html>

