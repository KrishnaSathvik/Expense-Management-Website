<?php
require 'includes/init.php';
if(isset($_SESSION['user_id']) && isset($_SESSION['email']) ){

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
    if(isset($_POST['fetch_btn'])){
         $s_id=$_POST['get_id'];
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
    
      <div class="container">
         <div class="row">
           <div class="col-md-7 mt-4">
             <div class="card">
             <div class="card-header">
                <h4 class="card-title">Registration details</h4>

             </div>
                <div class="card-body">
                    <form action="" method="POST">
                    <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                            <input type="text"  name= "get_id" class="form-control" placeholder="Enter Id" required>
                          </div>
                     </div>
                     <div class="col-md-6">
                       <button type="submit" name="fetch_btn" class="btn btn-primary">Fetch By ID</button>
                     </div>
                    </div>
                    </form>
                </div>
        
           </div>
         </div>
<!--         <div class="col-md-5">-->
<!--           <div class="card">-->
<!--             <div class="card-body">-->
<!--                    <div class="form-group">-->
<!--                      <input type="text"  name= "get_id" class="form-control"   value=" --><?php //echo $row['firstname'];  ?><!--"  placeholder="Enter First Name" required>-->
<!--                    </div>-->
<!--                    <div class="form-group">-->
<!--                        <input type="text"  name= "get_id" class="form-control" value=" --><?php //echo $row['lastname'];  ?><!-- "  placeholder="Enter Last Name" required>-->
<!--                      </div>-->
<!--                      <div class="form-group">-->
<!--                          <input type="text"  name= "get_id" class="form-control" value=" --><?php //echo $row['phone'];  ?><!-- "  placeholder="Phone Number" required>-->
<!--                        </div>-->
<!--             </div>-->
<!--           </div>-->
<!--         </div>-->
             <div class="all_users">
                 <h3>Search results</h3>
                 <div class="usersWrapper">
                     <?php

                     if($all_users){
                         foreach($all_users as $row){

                             if($row->id === $s_id){

                                 echo '<div class="user_box">
                                <div class="user_img"><img src="profile_images/'.$row->user_image.'" alt="Profile Image"></div>
                                <div class="user_info"><span>'.$row->username.'</span>
                                
                                <span><a href="user_profile.php?id='.$row->id.'" class="see_profileBtn">See Profile</a></span></div>
                                </div>';
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
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    -->

</body>
</html>

