<?php
require('core/config/config.php');

$users = $db->query("SELECT * FROM users WHERE subscription_expiration IS NOT NULL");
while($user = $users->fetch_array()) {
if($user['subscription_expiration'] < time()) {
$db->query("UPDATE users SET is_incognito='0',is_verified='0',has_disabled_ads='0',subscription_expire='' WHERE id='".$user['id']."'");
}	
}

?>