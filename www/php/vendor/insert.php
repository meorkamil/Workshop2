<?php
 header("Access-Control-Allow-Origin: *");
include "db.php";
error_reporting(0);
 
if($_SERVER["REQUEST_METHOD"] == "POST")
{

	$data = json_decode(file_get_contents("php://input"));
  $file = addslashes(file_get_contents($_FILES["image"]["tmp_name"]));

  $Email=$_POST['Email']; 
  $Password=$_POST['Password'];
  $passwordconfirm = $_POST['passwordconfirm'];
  $Name=$_POST['Name'];
  $PhoneNo=$_POST['PhoneNo'];



 $q=mysqli_query($con,"INSERT INTO vendor (Email,Password,Name,PhoneNo, Balance,ProfileImage, Verified) VALUES ('$Email','$Password','$Name','$PhoneNo','0.00','$file', 'To Be Determine')");
//$q=mysqli_query($con,"INSERT INTO vendor (Email,Password,Name,PhoneNo, ProfileImage, Verified) VALUES ('test','test','test','012', '$file', 'To Be Determine')");
if($q){
  $data = [ 'result' => 'success'];
}else{
  $data = [ 'result' => 'error'];
}

header('Content-type: application/json');
echo json_encode($data);
}

?>
