<?php
session_start();
define('IS_AJAX','true');
require('../core/config/config.php');
require('../core/config/config-lang.php');
require('../core/system.php');

$id = $db->real_escape_string($_GET['id']);

$check = $db->query("SELECT id FROM profile_likes WHERE viewer_id='".$user['id']."' AND profile_id='".$id."' LIMIT 1");
if($check->num_rows == 0) {
$db->query("INSERT INTO profile_likes (profile_id,viewer_id,time) VALUES ('".$id."','".$user['id']."','".time()."')");
$db->query("INSERT INTO notifications (receiver_id,url,content,icon,time) VALUES ('$id','app/profile.php?id=".$user['id']."','".sprintf($lang['liked_your_profile'],$user['full_name'])."','fa fa-thumbs-up', '".time()."')");
}
?>
<button class="btn btn-danger bg-danger-light" disabled> <i class="fa fa-heart fa-fw"></i> <?php echo $lang['Liked']?> </button>
