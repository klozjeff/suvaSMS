<?php
session_set_cookie_params(172800);
session_start();
if(!isset($_SESSION['mobileapp'])) {
	session_destroy();
	header("Location: ../index.php");	
} else {
	session_destroy();
	header("Location: login?mobileapp");
}
exit;
