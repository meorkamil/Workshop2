<?php
header("Access-Control-Allow-Origin: *");
error_reporting(0);
// View Profile
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST"){

	// Get id from session
	// $householdId=$_POST['HouseholdId'];
	$data = json_decode(file_get_contents("php://input"));
	$VendorId= $data->id;


	$sql="SELECT Email, Password, Name,  PhoneNo, ProfileImage FROM vendor WHERE VendorId=".$VendorId;

	$result = mysqli_query($con, $sql);


	    while($row = mysqli_fetch_assoc($result)) {

	    	$jsonData['Password']=$row["Password"];
				$jsonData['passwordconfirm']=$row["Password"];
	    	$jsonData['Email']=$row["Email"];
	    	$jsonData['Name']=$row["Name"];
	    	$jsonData['PhoneNo']=$row["PhoneNo"];
					$jsonData['ProfileImage']= base64_encode($row["ProfileImage"]);


	    }
  header('Content-type: application/json');
	    echo json_encode($jsonData);


}

?>
