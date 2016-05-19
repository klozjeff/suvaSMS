<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

if(isset($_GET['price']))
{
//$custom	= $_POST['custom'];
//$custom = explode('/', $custom);
$energy = $_GET['price'];
$user = $_GET['customer'];


$db->query("UPDATE users SET energy=energy+".$energy." WHERE id='".$user."'");
}

header("Location: orders");
?>
