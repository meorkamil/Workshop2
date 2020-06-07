<?php
 header("Access-Control-Allow-Origin: *");
 

   include("db.php");
  // session_start();

  // if(isset($_POST['email'])){
      // username and password sent from form
     // $email = mysqli_real_escape_string($con,$_POST['email']);
    // $password = mysqli_real_escape_string($con,$_POST['password']);
    if(isset($_POST['email'])){

    $email = $_POST['email'];
    $password = $_POST['password'];
      $sql = "SELECT * FROM vendor WHERE Email = '$email' AND Password = '$password'";
      $result = mysqli_query($con,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

      $count = mysqli_num_rows($result);
 
      // If result matched $myusername and $mypassword, table row must be 1 row

      if($count == 1) {
        //Set up session

        // $_SESSION['username']= $username;
        // $_SESSION['password']= $password;
        // session_id($username);
        // session_start();
        if($row['Verified'] == "Decline"){
          $data = [ 'result' => 'verified', 'id' => $row['VendorId'] ];
        }elseif($row['Verified'] == "To Be Determine"){
          $data = [ 'result' => 'verified', 'id' => $row['VendorId'] ];
        }else{
          $data = [ 'result' => 'success', 'id' => $row['VendorId'] ];
        }
   
      }else {
          $data = [ 'result' => 'error', 'id' => null ];
      }
      header('Content-type: application/json');
      echo json_encode( $data );
}
?>
