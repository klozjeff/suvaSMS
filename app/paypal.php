<?php
ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/ipn_errors.log');
require('core/config/config.php');
require('libs/paypal-ipn.php');

$listener = new IpnListener();

$listener->use_sandbox = false;

try {
    $listener->requirePostMethod();
    $verified = $listener->processIpn();
} catch (Exception $e) {
    error_log($e->getMessage());
    exit(0);
}

if($verified) {

$custom	= $_POST['custom'];
$custom = explode('/', $custom);
$energy = $custom[0];
$user = $custom[1];

$db->query("UPDATE users SET energy=energy+".$energy." WHERE id='".$user."'");

} else {
    
    // Invalid IPN

}

?>