<?php
session_start();
define('IS_AJAX','true');
require('../core/config/config.php');
require('../core/config/config-lang.php');
require('../core/system.php');

$id = $db->real_escape_string($_GET['id']);
$receiver = $db->real_escape_string($_GET['receiver']);
$convers = $db->query("SELECT * FROM conversations WHERE id='".$id."'");

$convers = $convers->fetch_array();
$messages = $db->query("SELECT * FROM messages WHERE convers_id='".$id."' ORDER BY id DESC"); 
if($messages->num_rows >= 1) {
while($message = $messages->fetch_array()) {
$sender = $db->query("SELECT id,profile_picture,full_name FROM users WHERE id='".$message['sender_id']."'")->fetch_array(); ?>
<!-- START list group item-->
<a href="#" class="list-group-item">
<div class="media-box">
<div class="pull-left">
<img src="<?php echo $domain;?>/app/<?php echo getProfilePicture($domain, $sender)?>" class="media-box-object img-rounded thumb32">
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
} else { echo '<p class="text-center" style="height:90px;line-height:90px"> '.$lang['Start_Conversation'].' <b>'.$receiver.'</b></p>'; }
?>
