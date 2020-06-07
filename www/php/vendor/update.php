<?php
 header("Access-Control-Allow-Origin: *");
error_reporting(0);
// View Profile
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST"){

	// Get id from session
	// $householdId=$_POST['HouseholdId'];
	$data = json_decode(file_get_contents("php://input"));
	$VendorId = $_POST['VendorId'];
	$Password= $_POST['Password'];
	$passwordconfirm = $_POST['passwordconfirm'];
	$Name= $_POST['Name'];
	$PhoneNo= $_POST['PhoneNo'];
	$Email = $_POST['Email'];

	if(!preg_match("/^.*(?=.{8,}).*$/", $Password)) {
	 $data = [ 'result' => 'kid'];
	}
	else if ($_POST['Password']!== $_POST['passwordconfirm']) {
	 $data = [ 'result' => 'mismatch'];
	}
	else if (!preg_match("/^[a-zA-Z ]*$/",$Name)) { 
	  $data = [ 'result' => 'wak'];
	}
	else if (!preg_match("/^[0-9]{11}$/",$PhoneNo)) {
	  $data = [ 'result' => 'duck'];
	}
else { 

 $upload_dir = 'profilepic/'; // upload directory
	$imgExt = strtolower(pathinfo($_FILES['image']['tmp_name'],PATHINFO_EXTENSION)); // get image extension
	// valid image extensions
	$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions

	// rename uploading image
	$pimage = rand(1000,1000000).".".$imgExt;
$img_path = 'profilepic/' . $_FILES['image']['name'];
	if ($_FILES['image'] != null){
// move_uploaded_file($_FILES['image']['tmp_name'], 'profilepic/' . $_FILES['image']['name']);
//*** Read file BINARY ***'
		$fp = fopen($_FILES["image"]["tmp_name"],"r");
		$ReadBinary = fread($fp,filesize($_FILES["image"]["tmp_name"]));
		fclose($fp);
		$FileData = addslashes($ReadBinary);
	$sql="UPDATE vendor SET Email='$Email', Password='$Password', PhoneNo='$PhoneNo', Name='$Name', ProfileImage='$FileData' WHERE VendorId= '$VendorId'";
	$result = mysqli_query($con, $sql);
if ($result)
{
	    $data = [ 'result' => 'success'];
}
else {
$data = [ 'result' => 'error']; 
}
}
else {
	$sql="UPDATE login SET Email='$Email', Password='$Password', PhoneNo='$PhoneNo', Name='$Name' WHERE VendorId= '$VendorId'";
	$result = mysqli_query($con, $sql);
if ($result)
{
	    $data = [ 'result' => 'success'];
}
else {
$data = [ 'result' => 'error'];
}
}






// else {
//}



}
header('Content-type: application/json');
echo json_encode($data);
}

?>
