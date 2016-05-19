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

  $db->query("DELETE FROM reports WHERE id='".$id."'");

  header('Location: reports.php');
  exit;
} else {
  header('Location: ../index.php');	
  exit;
}