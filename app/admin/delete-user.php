<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-lang.php');
require('../core/system.php');
require('../core/auth.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$auth = new Auth;
$auth->db = $db;
$auth->user = $user;

if($auth->isLogged() && $auth->isAdmin()) {
	
  $id = $_GET['id'];

  $db->query("DELETE FROM users WHERE id='".$id."'");
  $db->query("DELETE FROM conversations WHERE (user1='".$id."' OR user2='".$id."')");
  $db->query("DELETE FROM friend_requests WHERE (user1='".$id."' OR user2='".$id."')");
  $db->query("DELETE FROM notifications WHERE receiver_id='".$id."'");
  $db->query("DELETE FROM profile_views WHERE viewer_id='".$id."'");
  $db->query("DELETE FROM profile_likes WHERE viewer_id='".$id."'");

  header('Location: users.php');
  exit;
} else {
  header('Location: ../index.php');	
  exit;
}