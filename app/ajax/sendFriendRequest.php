<?php
session_start();
define('IS_AJAX','true');
require('../core/config/config.php');
require('../core/config/config-lang.php');
require('../core/system.php');

$user1 = $db->real_escape_string($_GET['user1']);
$user2 = $db->real_escape_string($_GET['user2']);

$check = $db->query("SELECT * FROM friend_requests WHERE user1='".$user1."' AND user2='".$user2."' LIMIT 1");
if($check->num_rows == 0) {
$db->query("INSERT INTO friend_requests (user1,user2,time,accepted) VALUES ('".$user1."','".$user2."','".time()."','0')");
$db->query("INSERT INTO notifications (receiver_id,url,content,icon,time,is_read) VALUES ('".$user2."','app/my-friends','".sprintf($lang['sent_you_a_friend_request'],$user['full_name'])."','fa fa-user-plus', '".time()."', '0')");
$receiver_email = $db->query("SELECT email FROM users WHERE id='".$user2."'")->fetch_array();
$receiver_email = $receiver_email['email'];
$clean = str_replace('<b> ', '', $lang['sent_you_a_friend_request']);
$clean = str_replace(' </b>', '', $clean);
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
mail($receiver_email, $site_name.' - '.sprintf($clean,$user['full_name']), '<a href="'.$domain.'/app/my-friends">'.sprintf($lang['sent_you_a_friend_request'],$user['full_name']).'</a>',$headers,'-f info@'.$domain);
}
?>
<button class="btn btn-danger" disabled> <i class="fa fa-user-plus" style="margin-right:2px;"></i> <?php echo $lang['Friend_Request_Sent']?> </button>
