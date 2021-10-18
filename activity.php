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
                <li><a href="profile.php" rel="noopener noreferrer"  >Home</a></li>

                <li><a href="expense.php" rel="noopener noreferrer">Add an Expense</a></li>
                <li><a href="activity.php" rel="noopener noreferrer" class="active">Activity</a></li>
                <li><a href="groups_create.php" rel="noopener noreferrer">Groups</a></li>

                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Edit
                </button>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="notifications.php" rel="noopener noreferrer">Requests<span class="badge <?php
                        if($get_req_num > 0){
                            echo 'redBadge';
                        }
                        ?>"><?php echo $get_req_num;?></span></a>
                    <a class="dropdown-item" href="friends.php" rel="noopener noreferrer">Friends<span class="badge"><?php echo $get_frnd_num;?></span></a>
                    <a class="dropdown-item" href="image_upload.php" rel="noopener noreferrer">Change Pic</a>
                    <a class="dropdown-item" href="logout.php" rel="noopener noreferrer">Logout</a>
                </div>
            </ul>
        </nav>
		<hr>



        <div class="all_users">
	
            <h3>Activity</h3>
			<?php $mysqli = new mysqli("localhost", "root", "", "easyroommate"); 
					if ($mysqli ==false) 
					{ 
						die("ERROR: Could not connect. ".$mysqli->connect_error); 
					}
			?> 
            <div class="usersWrapper">
                <?php
                

							
//						echo $user_data->username;
                        $user_1 = $user_data->username;
						//$user2=$row->username;
						$ex1 = mysqli_query($mysqli, "SELECT * FROM expense WHERE user1='$user_1' or user2='$user_1'  ");
						if ($ex1) {

							while ($row = $ex1->fetch_assoc()) {				
						//$row1 = @mysqli_fetch_assoc($ex1); 
						$exp_name = $row['exp_name'];
						$exp_id = $row['id'];

						$total_amount = $row['total_amount'];
						$paid_by = $row['paid_by'];
						$user2 = $row['user2'];
						$amount1 = $row['amount1'];
						$amount2 = $row['amount2'];


				
						$share=$total_amount/2;

                          
						
						if($amount1>0 and $amount2>0){
							if($paid_by==$user_data->username)
							{
							 echo '<div class="user_box">';
							 
						
								echo '<div class="user_info">' .' You added $'.$total_amount.' for '.$exp_name.' shared with '.$user2.' and you get back $' .$share .' </div>';
								
                                echo '&nbsp <form action="expense_update.php" method="post" enctype="multipart/form-data">
											
											<a href="expense_update.php?id='.$exp_id.'" type="button" class="btn btn-success">Update</a>
											<input type="submit" name="settleup" value="Delete" class="btn btn-danger">
											 <input type="hidden" name="user2" value='.$user2.'>
											 <input type="hidden" name="exp_id" value='.$exp_id.'>
											 <input type="hidden" name="user1" value='.$user_1.'>
											 <br><br></form><br>';
											
									

							}
							else
							{
								echo '<div class="user_box">';
							 
						
								echo '<div class="user_info">' .$paid_by.' added $'.$total_amount.' for '.$exp_name.' shared with you and You owe $' .$share .' </div>';
								echo '&nbsp <form action="expense_update.php" method="post" enctype="multipart/form-data">
											
											<a href="expense_update.php?id='.$exp_id.'" type="button" class="btn btn-success">Update</a>
											<input type="submit" name="settleup" value="Delete" class="btn btn-danger">
											 <input type="hidden" name="user2" value='.$user2.'>
											 <input type="hidden" name="exp_id" value='.$exp_id.'>
											 <input type="hidden" name="user1" value='.$user_1.'><br><br></form><br>';

							}
							echo '</div>';
						}
						if($amount1==0 or $amount2==0)
						{
							if($paid_by==$user_data->username)
							{
								
							echo '<div class="user_box">';
						
								echo '<div class="user_info">'.' You added $'.$total_amount.' for '.$exp_name.' and '.$user2.' owes you $' .$total_amount .' </div>';
								echo '&nbsp <form action="expense_update.php" method="post" enctype="multipart/form-data">
											<a href="expense_update.php?id='.$exp_id.'" type="button" class="btn btn-success">Update</a>
											<input type="submit" name="settleup" value="Delete" class="btn btn-danger">
											 <input type="hidden" name="user2" value='.$user2.'>
											 <input type="hidden" name="exp_id" value='.$exp_id.'>
											 <input type="hidden" name="user1" value='.$user_1.'><br><br></form><br>';
								//echo '</div>';
							}
							else
							{
							echo '<div class="user_box">';
						
								echo '<div class="user_info">'.$paid_by.' added $'.$total_amount.' for '.$exp_name.' and you owe $' .$total_amount .'</div>';
								echo '&nbsp <form action="expense_update.php" method="post" enctype="multipart/form-data">
											<a href="expense_update.php?id='.$exp_id.'" type="button" class="btn btn-success">Update</a>
											<input type="submit" name="settleup" value="Delete" class="btn btn-danger">
											 <input type="hidden" name="user2" value='.$user2.'>
											 <input type="hidden" name="exp_id" value='.$exp_id.'>
											 <input type="hidden" name="user1" value='.$user_1.'><br><br></form><br>';
								//echo '</div>';
							}
							echo '</div>';
						}
						
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

