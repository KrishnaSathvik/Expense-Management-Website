<?php

$data = [];
$arr = array('hi','me','iam','hi');
foreach ($arr as $i){
  array_push($data, $i);
}

foreach($data as $key => $value){

    $k[] = $key;
    $v[] ="'".$value."'";


}

$k = implode(",", $k);
$v = implode(",", $v);
echo $v;
$conn = mysqli_connect("localhost","root","","easyroommate");
$sql = "INSERT INTO groups (user_one,user_two,user_three,user_four,id) VALUES ($v,)";
$exe = mysqli_query($conn,$sql);

?>