<?php
 header("Access-Control-Allow-Origin: *");
 include("function.php");
$username = 'root';
$password = '';
$connection = new PDO( 'mysql:host=localhost; dbname=octmpyaq_magiclean', $username, $password );

date_default_timezone_set("Asia/Kuala_Lumpur");

if(isset($_POST["action"])) //Check value of $_POST["action"] variable value is set to not
{
 //For Load All Data
 if($_POST['action'] == "login"){
   
  /* username and password sent from form
  $email = mysqli_real_escape_string($connection,$_POST['username']);
  $password = mysqli_real_escape_string($connection,$_POST['password']);

  $sql = "SELECT * FROM login WHERE email = '$email' and password = '$password'";
  $result = mysqli_query($connection,$sql);
  $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

  $count = mysqli_num_rows($result);

  // If result matched $myusername and $mypassword, table row must be 1 row

  if($count == 1) {
    //Set up session

    // $_SESSION['username']= $username;
    // $_SESSION['password']= $password;
    // session_id($username);
    // session_start();
    $data = [ 'result' => 'success', 'id' => $row['VendorId'] ];
  }else {
      $data = [ 'result' => 'error', 'id' => null ];
  }
  header('Content-type: application/json');
  echo json_encode( $data );
  */
  $statement = $connection->prepare("SELECT * FROM login WHERE email = '$email' and password = '$password'");
  $statement->execute();
  $result = $statement->fetchAll();
  $output = '';

  $count = mysqli_num_rows($result);

  $statement = $connection->prepare("
  SELECT * FROM  wallet (HouseholdId, VendorId, Debit) 
  VALUES (:HouseholdId, :VendorId, :Amount)
 ");
 $result = $statement->execute(
  array(
   ':HouseholdId' => NULL,
   ':VendorId' => "1",
   ':Amount' => $_POST["Amount"]

  )
 );
 if(!empty($result))
 {
  echo 'Wallet Deducted';
 }
}

  //For Load All Data
  
  if($_POST["action"] == "") 
  {
    $output = arary();
   $statement = $connection->prepare("SELECT COUNT(`RequestId`) AS Total FROM `request` WHERE StatusId=5 AND VendorId=".$_POST["id"]."");
   $statement->execute();
   $result = $statement->fetchAll();

   if($statement->rowCount() > 0)
   {
    foreach($result as $row)
    {
      $output["Total"] = $row["Total"];
   
    }
   }
   else
   {
   
   }

  echo json_encode($output);
  }

  if($_POST["action"] == "LoadPaymentConfirm")
 {
  $output = array();
  $statement = $connection->prepare(
   "SELECT COUNT(`RequestId`) AS Total FROM `request` WHERE StatusId=5 AND VendorId=".$_POST["id"].""
  );
  $statement->execute();
  $result = $statement->fetchAll();
  foreach($result as $row)
  {
   //$output["Total"] = $row["Total"];

    if($row["Total"] == 0){
      $output["Total"] = "No";
    }else{
      $output["Total"] = $row["Total"];
    }
  }
 
  echo json_encode($output);
 }
  //For Load All Data

  if($_POST["action"] == "Load") 
  {
   $statement = $connection->prepare("SELECT r.RequestId, r.HouseholdId, r.JobId, r.Date, r.Time, r.StatusId, r.Duration,  h.Name, h.Address, h.State, h.Postcode, j.JobType, j.Rate FROM `request` AS r 
   JOIN household AS h ON h.HouseholdId=r.HouseholdId 
   JOIN job AS j ON j.JobId=r.JobId  WHERE r.StatusId=1");
   $statement->execute();
   $result = $statement->fetchAll();
   $output = '';
   $output .= '    ';
   if($statement->rowCount() > 0)
   {
    foreach($result as $row)
    {
    $JobRate = $row["Rate"] * $row["Duration"];
     
    $output .= ' 
    <br>
    <div class="card shadow-sm">
    <div class="card-body">
      <div class="row" style="font-size: smaller;">

        <div class="col-4">
      
        <a id="'.$row["RequestId"].'" href="https://www.google.com/maps/dir/?api=1&destination='.$row["Address"].'  '. $row["Postcode"].'  '. $row["State"].'" class="btn btn-lg btn-success btn-block shadow-sm ">   <i class="fas fa-map-marked-alt fa-2x"></i></a>  
        </div>
        <div class="col-8">
          <p>
         <strong style="font-size: large;" > '.$row["JobType"].'</strong><br>
          <i class="fas fa-user"></i> '.$row["Name"].'<br>
          <i class="fas fa-clock"></i>  '.$row["Date"].' <br>
          RM '.$JobRate.'
          </p>
          
        </div>
       
      </div>
      <button type="button" id="'.$row["RequestId"].'" class="btn btn-sm btn-info btn-block shadow-sm  update"><i class="fas fa-check"></i></button>
    </div>
  </div>
    ';
    }
    
   }
   else
   {
    $output .='<div class="alert alert-danger">
    <strong>No Request. </strong> Wait request from the Household.
  </div>';
   }
;
   echo $output;
  }
  //Load job schedule
  if($_POST["action"] == "LoadJobSchedule") 
  {
  $today = date("Y-m-d h:i:s ");                         
  $hour = date("H:i:s");  
   $statement = $connection->prepare("SELECT r.RequestId, r.HouseholdId, r.JobId, r.Date, r.Time, r.StatusId, r.Duration, h.Name, h.Address, h.State, h.Postcode, j.JobType, j.Rate, s.StatusType, vendor.ProfileImage FROM `request` AS r 
   JOIN household AS h ON h.HouseholdId=r.HouseholdId 
   JOIN vendor ON vendor.VendorId=r.VendorId 
   JOIN job AS j ON j.JobId=r.JobId 
   JOIN status AS s ON r.StatusId=s.StatusId
   WHERE NOT r.StatusId=1 AND r.VendorId=".$_POST["VendorId"]." AND NOT r.StatusId=6 AND NOT r.StatusId=7 ORDER BY r.StatusId ASC");
   $statement->execute();
   $result = $statement->fetchAll();
   $output = '';

   if($statement->rowCount() > 0)
   {
    foreach($result as $row)
    {
      $JobRate = $row["Rate"] * $row["Duration"];
      $date =$row["Date"];
      $time = $row["Time"];
      $StatusId =$row["StatusId"];
      $combinedDT = date('Y-m-d H:i:s', strtotime("$date $time"));

      if( ($StatusId == 2) && ($today  >= $combinedDT)){
        
        
       // $output .= ' '.$today.'  ';
       job_validation($row["RequestId"]);

      }elseif($StatusId == 4){
        $output .= ' 
<br>
      <div class="card shadow-sm ">
      <div class="card-body">
        <div class="row" style="font-size: smaller;">

          <div class="col-4">
      
          <button type="button" id="'.$row["RequestId"].'" class="btn btn-lg btn-danger btn-block shadow-sm " disabled>    <i class="fas fa-exclamation-circle "></i></button>

          <a id="'.$row["RequestId"].'" href="https://www.google.com/maps/dir/?api=1&destination='.$row["Address"].'  '. $row["Postcode"].'  '. $row["State"].'" class="btn btn-lg btn-success btn-block shadow-sm ">   <i class="fas fa-map-marked-alt fa-2x"></i></a>  
          </div>
          <div class="col-8">
            <p>
           <Strong> '.$row["JobType"].'</strong><br>
            <i class="fas fa-user"></i> '.$row["Name"].'<br>
            <i class="fas fa-clock"></i>  '.$row["Date"].'<br>
            <strong class="text-danger">'.$row["StatusType"].'</strong></p>
            RM '.$JobRate.'
          </div>
     
        </div>
      </div>
    </div>
    

        
        ';

      }
      elseif($StatusId == 5){
        $output .= ' 
        <br>
              <div class="card shadow-sm ">
              <div class="card-body">
                <div class="row" style="font-size: smaller;">
        
                  <div class="col-4">
              
                  <button type="button" id="'.$row["RequestId"].'" class="btn btn-lg btn-warning btn-block shadow-sm startjob" >   <i class="fas fa-money-check-alt text-white"></i></button>
        
                  <a id="'.$row["RequestId"].'" href="https://www.google.com/maps/dir/?api=1&destination='.$row["Address"].'  '. $row["Postcode"].'  '. $row["State"].'" class="btn btn-lg btn-success btn-block shadow-sm ">   <i class="fas fa-map-marked-alt fa-2x"></i></a>  
                  </div>
                  <div class="col-8">
                    <p>
                   <Strong> '.$row["JobType"].'</strong><br>
                    <i class="fas fa-user"></i> '.$row["Name"].'<br>
                    <i class="fas fa-clock"></i>  '.$row["Date"].'<br>
                    <strong class="text-danger">'.$row["StatusType"].'</strong></p>
                    RM '.$JobRate.'
                  </div>
             
                </div>
              </div>
            </div>
            
        
                
                ';
      }
      else{

        $output .= ' 
        <br>
        <div class="card shadow-sm">
        <div class="card-body">
          <div class="row" style="font-size: smaller;">
  
            <div class="col-4">
            <button type="button" id="'.$row["RequestId"].'" class="btn btn-lg btn-info btn-block shadow-sm  startjob"><i class="fas fa-arrow-right"></i></button>
            <a id="'.$row["RequestId"].'" href="https://www.google.com/maps/dir/?api=1&destination='.$row["Address"].'  '. $row["Postcode"].'  '. $row["State"].'" class="btn btn-lg btn-success btn-block shadow-sm ">   <i class="fas fa-map-marked-alt fa-2x"></i></a>  
            </div>
            <div class="col-8">
              <p>
             <strong > '.$row["JobType"].'</strong><br>
              <i class="fas fa-user"></i> '.$row["Name"].'<br>
              <i class="fas fa-clock"></i>  '.$row["Date"].' <br>
              <strong class="text-danger">'.$row["StatusType"].'</strong>
              RM '.$JobRate.'
              </p>
            
            </div>
           
          </div>
          
        </div>
      </div>
        ';
      }
 



    }
   }
   else
   {
    $output .='<div class="alert alert-danger">
    <strong>No Job Schedule. </strong>
  </div>';
   }
 
 
   echo $output;
  }

 //For Load All Data (Wallet Transacgion)
 if($_POST["action"] == "LoadJobHistory") 
 {
  $statement = $connection->prepare("SELECT r.RequestId, r.HouseholdId, r.JobId, r.Date, r.Time, r.StatusId, r.Comment, h.Name, h.Address, h.State, h.Postcode, j.JobType, s.StatusType FROM `request` AS r 
  JOIN household AS h ON h.HouseholdId=r.HouseholdId 
  JOIN vendor ON vendor.VendorId=r.VendorId 
  JOIN job AS j ON j.JobId=r.JobId 
  JOIN status AS s ON r.StatusId=s.StatusId
  WHERE r.StatusId=6 AND r.VendorId=".$_POST["VendorId"]."");
  $statement->execute();
  $result = $statement->fetchAll();
  $output = '';

  if($statement->rowCount() > 0)
  {
   foreach($result as $row)
   {


  $output .= ' 
  <br>
  <div class="card shadow-sm">
  <div class="card-body">
    <div class="row" style="font-size: smaller;">

      <div class="col-4 text-center">
    
<i class="fas fa-clipboard-check fa-5x text-success"></i>
<button type="button" id="'.$row["RequestId"].'" class="btn btn-sm btn-info btn-block shadow-sm  startjob"><i class="fas fa-info-circle"></i></button>

      </div>
      <div class="col-8">
        <p>
       <strong style="font-size: large;" > '.$row["JobType"].'</strong><br>
        <i class="fas fa-user"></i> '.$row["Name"].'<br>
        <i class="fas fa-clock"></i>  '.$row["Date"].' <br>
        </p>
    

        </div>
      </div>
     

  </div>
</div>
  ';
  

   
    
    
   }
  }
  else
  {
    $output .='<div class="alert alert-danger">
    <strong>No History . </strong> .
  </div>';
  }
  $output .=' 
 </div>';
  echo $output;
}

 
 //For Load All Data (Wallet Transacgion)
 if($_POST["action"] == "LoadTransaction") 
 {
  $statement = $connection->prepare("SELECT w.WalletId,w.HouseholdId,w.VendorId,w.Debit,w.Credit FROM `wallet` AS w WHERE VendorId =".$_POST["id"]." ");
  $statement->execute();
  $result = $statement->fetchAll();
  $output = '';
/*
  if($statement->rowCount() > 0)
  {*/
   foreach($result as $row)
   {
    $HouseholdHold = $row["HouseholdId"];
     
     if( is_null($HouseholdHold) ){
    

     }else{
      $statement1 = $connection->prepare("SELECT  h.Name FROM `household` AS h WHERE HouseholdId =".$row["HouseholdId"]." ");
      $statement1->execute();
      $result1 = $statement1->fetchAll();
      foreach($result1 as $row1)
      {
        $output .= '
        
        <div class="row">
          <div class="col-6">
          <p class="text-muted">Payment <i class="fas fa-arrow-right"></i><br>
          <i class="far fa-user"></i> '.$row1["Name"].' </p>
          </div>
          <div class="col-6">
         <p class="text-success"><i class="fas fa-plus"></i> RM '.$row["Debit"].'</p>
          </div>
        </div>
        <hr>
    
      
     
    ';
     }

    
 
   
    
   }
 

   }
     /*
  else
  {
  
  }*/
  echo $output;
}
 //For Load All Data (Wallet Transacgion)
 if($_POST["action"] == "LoadWithdrawTransaction") 
 {
  $statement = $connection->prepare("SELECT w.WalletId,w.HouseholdId,w.VendorId,w.Debit,w.Credit FROM `wallet` AS w WHERE VendorId =".$_POST["id"]." ");
  $statement->execute();
  $result = $statement->fetchAll();
  $output = '';
/*
  if($statement->rowCount() > 0)
  {*/
   foreach($result as $row)
   {
    $HouseholdHold = $row["HouseholdId"];
     
     if( is_null($HouseholdHold) ){
        
    $output .= '
   
    <div class="row">
    <div class="col">
    <p class="text-muted">Withdraw <i class="fas fa-arrow-right"></i>  </p>
    </div>
    <div class="col text-danger">
    <i class="fas fa-minus fa-xs"></i> RM '.$row["Credit"].'
    </div>
    </div>
    <hr>
    
   
   
    ';

     }else{
    
   }
 

   }
     /*
  else
  {
  
  }*/
  echo $output;
}
 //For Load All Data (Balance wallet)
 if($_POST["action"] == "LoadBalance") 
 {
  $vendorId = $_POST["id"];
  $statement = $connection->prepare("SELECT Balance FROM vendor WHERE VendorId='".$vendorId."' ");
  $statement->execute();
  $result = $statement->fetchAll();
  $output = '';

  if($statement->rowCount() > 0)
  {
   foreach($result as $row)
   {
     
     $TotalWallet = $row["Balance"];
    $output .= '

   RM '.$TotalWallet.'
   
    ';
    
   }
  }
  else
  {
  
  }
  echo $output;
}
 

 //This code for Create new Records
 if($_POST["action"] == "Create")
 {
  $statement = $connection->prepare("
   INSERT INTO job (JobType, Rate) 
   VALUES (:JobType, :Rate)
  ");
  $result = $statement->execute(
   array(
    ':JobType' => $_POST["JobType"],
    ':Rate' => $_POST["Rate"]
   )
  );
  if(!empty($result))
  {
   echo 'Data Inserted';
  }
 }

 if($_POST["action"] == "Confirm")
 {
  $statement = $connection->prepare("
   INSERT INTO wallet (HouseholdId, VendorId, Credit) 
   VALUES (:HouseholdId, :VendorId, :Amount);

UPDATE vendor SET Balance= Balance - ".$_POST["Amount"]." WHERE VendorId=".$_POST["id"]."
 
  ");
  $result = $statement->execute(
   array(
    ':HouseholdId' => NULL,
    ':VendorId' => $_POST["id"],
    ':Amount' => $_POST["Amount"]

   )
  );
  if(!empty($result))
  {
   echo 'Wallet Deducted';
  }
 }

 if($_POST["action"] == "Select")
 {
  $output = array();
  $statement = $connection->prepare(
   "SELECT r.RequestId, r.HouseholdId, r.JobId, r.Date, r.Time, r.Duration, r.StatusId, h.Name, h.Address, j.JobType FROM `request` AS r 
   JOIN household AS h ON h.HouseholdId=r.HouseholdId 
   JOIN vendor ON vendor.VendorId=r.VendorId 
   JOIN job AS j ON j.JobId=r.JobId
   WHERE r.RequestId = '".$_POST["id"]."' 
   LIMIT 1"
  );
  $statement->execute();
  $result = $statement->fetchAll();
  foreach($result as $row)
  {
   $output["Name"] = $row["Name"];
   $output["Date"] = $row["Date"];
   $output["Address"] = $row["Address"];
   $output["Time"] = $row["Time"];
   $output["Duration"] = $row["Duration"];

  }
  echo json_encode($output);
 }

 if($_POST["action"] == "SelectStart")
 {
  $output = array();
  $statement = $connection->prepare(
   "SELECT r.RequestId, r.HouseholdId, r.JobId, r.Date, r.Time, r.Duration, r.StatusId, r.Comment, h.Name, h.Address, h.State, h.Postcode, j.JobType, s.StatusType FROM `request` AS r 
   JOIN household AS h ON h.HouseholdId=r.HouseholdId 
   JOIN job AS j ON j.JobId=r.JobId
   JOIN status AS s ON r.StatusId=s.StatusId
   WHERE r.RequestId = '".$_POST["id"]."' 
   LIMIT 1"
  );
  $statement->execute();
  $result = $statement->fetchAll();
  foreach($result as $row)
  {
    $address = ''.$row["Address"].'  '. $row["Postcode"].'  '. $row["State"].'';
   $output["Name"] = $row["Name"];
   $output["Date"] = $row["Date"];
   $output["Address"] = $address;
   $output["Time"] = $row["Time"];
   $output["Duration"] = $row["Duration"];
   $output["StatusType"] = $row["StatusType"];
   $output["Type"] = $row["StatusType"];
   $output["Comment"] = $row["Comment"];

  }
  echo json_encode($output);
 }

 if($_POST["action"] == "Update")
 {
 
 
  $statement = $connection->prepare(
   "UPDATE request 
   SET StatusId = :StatusId, VendorId = :VendorId
   WHERE RequestId = :RequestId
   ");
   
  if($_POST["Type"] == "Waiting for Pick Up"){
    $StatusId = 2;
    $result = $statement->execute(
      array(
       ':StatusId' => $StatusId,
       ':RequestId'   => $_POST["id"],
       ':VendorId'   => $_POST["VendorId"]
      )
     );
  }
  elseif($_POST["Type"] == "Pick Up by Vendor"){
    $StatusId = 3;
    $result = $statement->execute(
      array(
       ':StatusId' => $StatusId,
       ':RequestId'   => $_POST["id"],
       ':VendorId'   => $_POST["VendorId"]
      )
     );
  }elseif($_POST["Type"] == "Job In Progress"){
    $StatusId = 4;
    $result = $statement->execute(
      array(
       ':StatusId' => $StatusId,
       ':RequestId'   => $_POST["id"],
       ':VendorId'   => $_POST["VendorId"]
      )
     );
  }
  elseif($_POST["Type"] == "Waiting Customer to do Payment"){


    $StatusId = 5;
    $result = $statement->execute(
      array(
       ':StatusId' => $StatusId,
       ':RequestId'   => $_POST["id"],
       ':VendorId'   => $_POST["VendorId"]
      )
     );
  }
  else{
    
    confirm_payment($_POST["id"]);
    $StatusId = 6;
    $result = $statement->execute(
      array(
       ':StatusId' => $StatusId,
       ':RequestId'   => $_POST["id"],
       ':VendorId'   => $_POST["VendorId"]
      )
     );
  }
 
  if(!empty($result))
  {
   echo 'Progress Updated';
  }
 }

 
 if($_POST["action"] == "Accept")
 {
 
 
  $statement = $connection->prepare(
   "UPDATE request 
   SET StatusId = :StatusId, VendorId = :VendorId
   WHERE RequestId = :RequestId
   ");
   
  if($_POST["Type"] == "Waiting for Pick Up"){
    $StatusId = 2;
    $result = $statement->execute(
      array(
       ':StatusId' => $StatusId,
       ':RequestId'   => $_POST["id"],
       ':VendorId'   => $_POST["VendorId"]
      )
     );
  }
  elseif($_POST["Type"] == "Pick Up by Vendor"){
    $StatusId = 3;
    $result = $statement->execute(
      array(
       ':StatusId' => $StatusId,
       ':RequestId'   => $_POST["id"],
       ':VendorId'   => $_POST["VendorId"]
      )
     );
  }elseif($_POST["Type"] == "Job In Progress"){
    $StatusId = 4;
    $result = $statement->execute(
      array(
       ':StatusId' => $StatusId,
       ':RequestId'   => $_POST["id"],
       ':VendorId'   => $_POST["VendorId"]
      )
     );
  }
  elseif($_POST["Type"] == "Waiting Customer to do Payment"){
    $StatusId = 5;
    $result = $statement->execute(
      array(
       ':StatusId' => $StatusId,
       ':RequestId'   => $_POST["id"],
       ':VendorId'   => $_POST["VendorId"]
      )
     );
  }
  else{
    $StatusId = 6;
    $result = $statement->execute(
      array(
       ':StatusId' => $StatusId,
       ':RequestId'   => $_POST["id"],
       ':VendorId'   => $_POST["VendorId"]
      )
     );
  }
 
  if(!empty($result))
  {
   echo 'Progress Updated';
  }
 }
 if($_POST["action"] == "Delete")
 {
  $statement = $connection->prepare(
   "DELETE FROM customers WHERE id = :id"
  );
  $result = $statement->execute(
   array(
    ':id' => $_POST["id"]
   )
  );
  if(!empty($result))
  {
   echo 'Data Deleted';
  }
 }
}

?>