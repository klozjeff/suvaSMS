<?php
session_start();
define('IS_AJAX','true');
require('../core/config/config.php');

$id = $db->real_escape_string($_GET['id']);

$unread = $db->query("SELECT * FROM notifications WHERE receiver_id='$id' AND is_read='0' ORDER BY id DESC LIMIT 4");
while($unrd = $unread->fetch_array()) {
$db->query("UPDATE notifications SET is_read='1' WHERE id='".$unrd['id']."'");
}
