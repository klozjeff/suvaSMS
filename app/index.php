<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config-lang.php');
require('core/system.php');
if(isLogged()) {
	header('Location: order');
} else {
	header('Location: login');
}
exit;
