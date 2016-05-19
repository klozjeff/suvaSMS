<?php
session_start();
define('IS_AJAX','true');
require('../core/config/config.php');
require('../core/config/config-lang.php');
require('../core/system.php');

$user1 = $user['id'];
$orderid = $db->real_escape_string($_GET['orderid']);
$message = $db->real_escape_string($_GET['msg']);

$db->query("UPDATE order_details SET paper_details='".$message."' WHERE id='".$orderid."'");

$db->query("UPDATE users SET last_active='".time()."' WHERE id='".$user['id']."'");

$messages = $db->query("SELECT paper_details FROM order_details WHERE id='".$orderid."'");
$db->query("INSERT INTO notifications (receiver_id,url,content,icon,time) VALUES ('$orderid','app/orders/".$orderid."','".sprintf($lang['messaged_you'],$user['full_name'])."','fa fa-comment', '".time()."')");
//$receiver_email = $db->query("SELECT email FROM users WHERE id='".$user2."'")->fetch_array();
//$receiver_email = $receiver_email['email'];
//$clean = str_replace('<b> ', '', $lang['messaged_you']);
//$clean = str_replace(' </b>', '', $clean);
//$headers  = 'MIME-Version: 1.0' . "\r\n";
//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//mail($receiver_email, $site_name.' - '.sprintf($clean,$user['full_name']), '<a href="'.$domain.'/app/chat/'.$user1.'">'.sprintf($lang['messaged_you'],$user['full_name']).'</a>',$headers,'-f info@'.$domain);
while($message = $messages->fetch_array()) {
//$sender = $db->query("SELECT id,profile_picture,full_name FROM users WHERE id='".$message['sender_id']."'")->fetch_array();
?>
<!-- START list group item
 <div class="panel panel-default" id="details">
          <div class="panel-heading panel-title"> <i class="fa fa-book"></i> Paper Content<?php if($profile['id'] == $user['id']) { ?><div class="pull-right"> <a href="upload" class="btn btn-danger btn-xs">  <i class="fa fa-upload fa-fw"></i> <?php echo $lang['Upload']?> </a> <a href="manage-photos" class="btn btn-danger btn-xs">  <i class="fa fa-gear fa-fw"></i> <?php echo $lang['Manage']?> </a> </div><?php } ?></div>
            <div class="panel-body">
           <textarea cols="105" rows="17" id="detailsr"><?php echo $message['paper_details'];?></textarea>
          </div>
        </div>
		-->
		 <div class="panel panel-default" id="details">
          <div class="panel-heading panel-title"> 
		  Paper Details<div class="pull-right">
		  <a href="<?php echo $domain;?>/app/upload/<?php echo $id?>" class="btn btn-danger btn-xs"> 
		  <i class="fa fa-upload fa-fw"></i> <?php echo $lang['Upload']?> </a> 
		  <a href="<?php echo $domain;?>/app/manage-files/<?php echo $id?>" class="btn btn-danger btn-xs">  <i class="fa fa-gear fa-fw"></i> <?php echo $lang['Manage']?> </a> </div></div>
            <hr>
			<div class="panel-body">
           <textarea cols="105" rows="17" id="detailsr"><?php echo $message['paper_details'];?></textarea>
          </div>
        </div>
<!-- END list group item-->
<?php	
}