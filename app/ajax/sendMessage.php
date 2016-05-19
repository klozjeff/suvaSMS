<?php
session_start();
define('IS_AJAX','true');
require('../core/config/config.php');
require('../core/config/config-lang.php');
require('../core/system.php');

$user1 = $user['id'];
$user2 = $db->real_escape_string($_GET['id']);
$message = $db->real_escape_string($_GET['msg']);

$check = $db->query("SELECT id FROM conversations WHERE (user1='".$user1."' AND user2='".$user2."') OR (user1='".$user2."' AND user2='".$user1."')");
if($check->num_rows == 1) {
  $convers = $check->fetch_array();
  $db->query("INSERT INTO messages (convers_id,message,sender_id,time) VALUES ('".$convers['id']."','".$message."','".$user1."','".time()."')");
  $convers_id = $convers['id'];

} else {
  $db->query("INSERT INTO conversations (user1,user2,time) VALUES ('".$user1."','".$user2."','".time()."')");
  $convers_id = $db->insert_id;
  $db->query("INSERT INTO messages (convers_id,message,sender_id,time) VALUES ('".$convers_id."','".$message."','".$user1."','".time()."')");
}

$db->query("UPDATE conversations SET last_activity='".time()."',last_message='".$message."' WHERE id='".$convers_id."'");

$db->query("UPDATE users SET last_active='".time()."' WHERE id='".$user['id']."'");
$messages = $db->query("SELECT * FROM messages WHERE convers_id='".$convers_id."' AND (sender_id = '".$user1."' OR sender_id='".$user2."') ORDER BY id DESC");
$db->query("INSERT INTO notifications (receiver_id,url,content,icon,time) VALUES ('$user2','app/chat/".$user1."','".sprintf($lang['messaged_you'],$user['full_name'])."','fa fa-comment', '".time()."')");
$receiver_email = $db->query("SELECT email FROM users WHERE id='".$user2."'")->fetch_array();
$receiver_email = $receiver_email['email'];
$clean = str_replace('<b> ', '', $lang['messaged_you']);
$clean = str_replace(' </b>', '', $clean);
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
mail($receiver_email, $site_name.' - '.sprintf($clean,$user['full_name']), '<a href="'.$domain.'/app/chat/'.$user1.'">'.sprintf($lang['messaged_you'],$user['full_name']).'</a>',$headers,'-f info@'.$domain);
while($message = $messages->fetch_array()) {
$sender = $db->query("SELECT id,profile_picture,full_name FROM users WHERE id='".$message['sender_id']."'")->fetch_array();
?>
<!-- START list group item-->
<a href="#" class="list-group-item">
<div class="media-box">
<div class="pull-left">
<img src="<?php echo getProfilePicture($domain,$sender)?>" class="media-box-object img-rounded thumb32">
</div>
<div class="media-box-body clearfix">
<small class="pull-right"><?php echo time_ago($message['time'])?></small>
<strong class="media-box-heading text-primary">
<?php echo $sender['full_name']?></strong>
<p class="mb-sm">
<small><?php echo parseEmoticons($domain,$message['message'])?></small>
</p>
</div>
</div>
</a>
<!-- END list group item-->
<?php	
}
