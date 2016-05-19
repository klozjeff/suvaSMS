<?php
function sendSMS()

{
global $db;
 
 
 
 $phoneNo=$_POST['opt'];
 
 $msg=$_POST['message'];
 
 $fields = $_POST['opt'];
 if (is_array($fields)) {
  $content = '';
  $cont = '';
  for ($i=0;$i<count($fields);$i++) {
   $content = $content . "$fields[$i],";
  }
  $allrecepients = $content;
 }
 
 $myrecepients=substr($allrecepients,0,-1);
 
 
 
 
 require_once('../gateways/AfricasTalkingGateway.php');
 
 // Specify your login credentials
 $username   = "jucan";
 $apikey     = "f43c02b357c10bf41ba100bc69866a6f68ce575ab49be6d08d27d3807b6eba7d";
 
 $ip=$_SERVER['REMOTE_ADDR'];
 
 $actionby=$_SESSION['byte_user'];
 $date_done=date('Y-m-d H:i:s');
 
 
 $query = "INSERT INTO tbl_sms(message,recipient,ip,action_by,date_done) VALUES(?,?,?,?,?)";
 $statement = $db->prepare($query);
 $statement->bind_param('sssss',$message, $myrecepients,$ip,$actionby,$date_done);
 
 $statement->execute();
 
 $statement->close();
 
 foreach($phoneNo as $mycontact)
 
 {
  
  $stmt=$db->prepare("SELECT title,fname,mname,lname,hack,code1,phone1 FROM tbl_alumni WHERE phone1=?");
  
  $stmt->bind_param('s',$mycontact);
  
  $stmt->execute();
  
  $stmt->bind_result($title,$fname,$mname,$lname,$hack,$code1,$phone1);
  
  while($stmt->fetch())
  {
  
  $recipients =$code1.$phone1;
  
  // And of course we want our recipients to know what we really do
  Dear |NAME| 
 $message='Dear ,'.$title.' '.$fname.' '.$mname.' '.$lname.' '.strtolower($msg).' '.$hack;
  echo '<br>';
  
  
  $gateway    = new AfricasTalkingGateway($username, $apikey);
  
  
  try
  {
   // Thats it, hit send and we'll take care of the rest.
   $results = $gateway->sendMessage($recipients, $message);
   /*foreach($results as $result) {
    // Note that only the Status "Success" means the message was sent
    echo $phno=$result->number;
    echo $msg_status=$result->status;
     echo $msg_id=$result->messageId;
    echo $msg_cost=$result->cost;
    
    $mysql=mysqli_query($db,"INSERT INTO tbl_sms(message) VALUES('$message')");
    
   }*/
  }
  catch ( AfricasTalkingGatewayException $e )
  {
   echo "Encountered an error while sending: ".$e->getMessage();
  }
  }//End While Loop
  
 //Close Prepared Statement
 }
 
 
}
?>