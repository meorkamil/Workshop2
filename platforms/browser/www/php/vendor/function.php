<?php
require 'class.php';


date_default_timezone_set("Asia/Kuala_Lumpur");

function convert_date($date){

    $newDate   =   date("Y-m-d", strtotime($date));

    echo $newDate;
}

function job_validation($RequestId){
  Database::initialize();

 
        $today = date("Y-m-d");                         
        $hour = date("H:i:s");  
     
        $res=mysqli_query(Database::$con,"SELECT * FROM request WHERE RequestId=$RequestId");
        while($row=mysqli_fetch_array($res))
        {
         
          $id = $row['RequestId'];
          $date = $row['Date'];
          $time = $row['Time'];
        }
      
        

            $sql = "UPDATE request SET StatusId = 3 WHERE RequestId = '$RequestId'";
            Database::$con->multi_query($sql);
           
     
    


}
function confirm_payment($RequestId){
    Database::initialize();
  
          $res=mysqli_query(Database::$con,"SELECT r.Duration, r.VendorId , j.Rate FROM request AS r JOIN job AS j ON r.JobId=j.JobId WHERE r.RequestId=$RequestId");
          $row = $res->fetch_assoc();

          $Duration = $row['Duration'];
          $Rate = $row['Rate'];
          $VendorId = $row['VendorId'];

          $total_price = $Rate * $Duration;

          $sql = "UPDATE vendor SET Balance = Balance + $total_price WHERE VendorId = '$VendorId'";
          Database::$con->multi_query($sql);
      
  
  
  }

  
?>