<?php
session_start();
define('IS_AJAX','true');
require('../core/config/config.php');

$user = $db->query("SELECT * FROM users WHERE email='".$_SESSION['email']."'");
$user = $user->fetch_array();

$id = $db->real_escape_string($_GET['id']);

$check = $db->query("SELECT id FROM profile_likes WHERE viewer_id='".$user['id']."' AND profile_id='".$id."' LIMIT 1");
if($check->num_rows == 0) {
$db->query("INSERT INTO profile_likes (profile_id,viewer_id,time) VALUES ('".$id."','".$user['id']."','".time()."')");
$db->query("INSERT INTO notifications (receiver_id,url,content,icon,time) VALUES ('".$id."','app/profile.php?id=".$user['id']."','<b>".$user['full_name']."</b> liked your profile','fa fa-thumbs-up', '".time()."')");
?> <i class="fa fa-heart fa-lg" id="heart-<?php echo $id?>" style="color:#f05050;"></i> <?php
} else {
$db->query("DELETE FROM profile_likes WHERE viewer_id='".$user['id']."' AND profile_id='".$id."'");
$db->query("DELETE FROM notifications WHERE receiver_id='".$id."' AND url='app/profile.php?id=".$user['id']."'");
?> <i class="fa fa-heart fa-lg" id="heart-<?php echo $id?>"></i> <?php
}
