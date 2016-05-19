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

$user	= $_POST['custom'];
$expire = time()+2629743;

$db->query("UPDATE users SET is_incognito='1',is_verified='1',has_disabled_ads='1',subscription_expire='$expire' WHERE id='".$user."'");

} else {
    
    // Invalid IPN

}

?>