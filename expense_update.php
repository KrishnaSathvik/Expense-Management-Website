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

//expense retrieval
$get_expense = $expenses_obj->expenseRetrieval($_GET['id']);

if($get_expense->user2 === $get_expense->paid_by){
    $share_with1 = $get_expense->user2;
}
elseif($get_expense->user1 === $get_expense->paid_by){
    $share_with1 = $get_expense->user1;
}

//getting share with person email
foreach ($all_users as $row){
    if($row->username === $share_with1){
        $share_email = $row->user_email;
    }
    else{
        $share_email = $user_data->user_email;
    }
}

//requesting notification number
$get_req_num = $friend_obj->req_notification($_SESSION['user_id'], false);
//total friends
$get_frnd_num = $friend_obj->get_all_friends($_SESSION['user_id'], false);

//get_all_friends
$get_all_friends = $friend_obj->get_all_friends($_SESSION['user_id'], true);


$mysqli = new mysqli("localhost", "root", "", "easyroommate"); 
										if ($mysqli ==false) 
										{ 
											die("ERROR: Could not connect. ".$mysqli->connect_error); 
										}
										
											
											
											
								
								if(isset($_POST['settleup']))
									{
									$user2= $_POST['user2'];
											$user1= $_POST['user1'];
											$exp_id= $_POST['exp_id'];
										$settle1 = mysqli_query($mysqli, "DELETE FROM expense WHERE id='$exp_id'");
										//$settle2 = mysqli_query($mysqli, "DELETE FROM expense WHERE user1='$username' and user2='$user2'");
										
										 header('Location: activity.php');
									}
										
								
				?>
<?php

if(isset($_POST['submit'])) {
//    if(isset($_POST['description'])){
//        echo 'description';
//    }
//    if(isset($_POST['amount'])){
//        echo 'amount';
//    }
//    if(isset($_POST['share_with'])){
//        echo 'share_with';
//    }
//    if(isset($_POST['split'])){
//        echo 'split';
//    }
    if (isset($_POST['description'])  && isset($_POST['amount']) && isset($_POST['split'])) {


												$exp_id= $_GET['id'];
										        $result = mysqli_query($mysqli, "SELECT * FROM expense WHERE id='$exp_id'");
											
												$exp_name=$get_expense->exp_name;
												$amount=$_POST['amount'];
												$split=$_POST['split'];
												if($split=="you_equally")
												{
														$paid_by = $user_data->username;
														$user1 = $user_data->username;
														$user2 = $share_with1;
														$amount1=$amount/2;
														$amount2=$amount/2;
												}
												if($split=="them_equally")
												{
														$paid_by = $share_with1;
														$user1 = $share_with1;
														$user2 = $user_data->username;
														$amount1=$amount/2;
														$amount2=$amount/2;
												}
												if ($split=="they_owe")
												{
													$paid_by = $user_data->username;
													$user1 = $user_data->username;
													$user2 = $share_with1;
													$amount2=0;
													$amount1=$amount;
												}
												if ($split=="you_owe")
												{
													$paid_by = $share_with1;
													$user1 = $share_with1;
													$user2 = $user_data->username;
													$amount1=0;
													$amount2=$amount;
												}
                                                $settle1 = mysqli_query($mysqli, "UPDATE expense SET exp_name = '$exp_name', amount1 = '$amount1', amount2 = '$amount2', user1='$user1', user2='$user2',total_amount='$amount', paid_by='$paid_by' WHERE id='$exp_id'");
												if($settle1){
                                                    $successMessage =  'Expense updated successfully.';


                                                } else {
                                                $errorMessage = 'Problem with update.';
												}
												}
    else{
        $errorMessage = 'Please fill the fields.';
    }


}


// header('Location: expense_update.php');
									
									
									

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

        <div class="add">
             UPDATE AN EXPENSE
            <br></br>
            <div class="container-fluid" style="border:1px solid #cecece;">
                <br>
                <form action="" method="POST">
                    <label for="description">
                        Description:
                    </label>
                    <?php
                    echo '<input type="text" name="description"  value="'.$get_expense->exp_name.'"/> <br>
                    <label for="share_with">
                        With you and: </label>';
                    if($get_expense->user2 === $get_expense->paid_by){
                        echo '<input type="text" name="share_with" value="'.$get_expense->user2.'" disabled/> <br>';
                    }
                    elseif($get_expense->user1 === $get_expense->paid_by){
                        echo '
                        <input type="text" name="share_with" value="'.$get_expense->user1.'" disabled/> <br>';
                    }

                   ?>
                    <label for="amount">
                            Amount:
                    </label>
					
                    <input type="text" name='amount' required />
					
                <br>
				<label for="split_type">Split Type: </label><br>
				<input type="radio" id="you_equally" name="split" value="you_equally">
				<label for="you_equally">Paid by you and Share Equally</label><br>
				<input type="radio" id="them_equally" name="split" value="them_equally">
				<label for="them_equally">Paid by them and Share Equally</label><br>
				<input type="radio" id="they_owe" name="split" value="they_owe">
				<label for="they_owe">They owe you completely</label><br>
				<input type="radio" id="you_owe" name="split" value="you_owe">
				<label for="you_owe">you owe them completely</label>


                    <input type="submit" name="submit" value="Update" />

                    <br>
                </form>

            </div>

            <div class="user_box">
                <?php
                if(isset($errorMessage)){
                    echo '<p class="errorMsg">'.$errorMessage.'</p>';
                    echo '<a href="activity.php" class="btn btn-primary">Go to activity</a>    ';
                }
                if(isset($successMessage)){
                    $sendUpdateMail = $email_obj->sendExpenseUpdatedMail($share_email,$user_data->username);
                    echo '<p class="successMsg">'.$successMessage.'</p>';
                    echo '<p class="successMsg">'.$sendUpdateMail['emailSuccessMessage'].'</p>';
                    echo '<a href="activity.php" class="btn btn-primary">Go to activity</a>    ';
                }
                ?>
            </div>


        </div>


    </div>
	</div>
</body>
</html>

							



							