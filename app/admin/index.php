<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-lang.php');
require('../core/system.php');
require('../core/auth.php');

$auth = new Auth;
$auth->db = $db;
$auth->user = $user;

if($auth->isLogged() && $auth->isAdmin()) {
  header('Location: dashboard.php');
  exit;
} else {
  header('Location: ../index.php');	
  exit;
}