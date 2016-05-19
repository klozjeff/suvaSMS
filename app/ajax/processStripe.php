<?php
session_start();
define('IS_AJAX','true');
require("../core/config/config.php");
require("../core/stripe/init.php");

$stripe_token = $_GET['t'];
$transaction_token = $_SESSION['tid'];
$user_id = $_SESSION['tuid'];

$transaction = $db->query("SELECT * FROM transactions WHERE token='".$transaction_token."'");
$transaction = $transaction->fetch_array();

// Set your secret key: remember to change this to your live secret key in production
// See your keys here https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey($secret_key);

// Create the charge on Stripe's servers - this will charge the user's card
try {
$charge = \Stripe\Charge::create(array(
  "amount" => $transaction['transaction_amount']*100, // amount in cents, again
  "currency" => "usd",
  "source" => $stripe_token,
  "description" => $transaction['transaction_name'])
);
echo '<div class="text-success"><i class="fa fa-check fa-fw"></i> Payment Successful</div>';
} catch(\Stripe\Error\Card $e) {
echo '<div class="text-success"><i class="fa fa-times fa-fw"></i> Payment Failed</div>';
}

$db->query("UPDATE transactions SET status='1' WHERE id='".$transaction['id']."'");
$db->query("UPDATE users SET energy=energy+".$transaction['energy_to_add']." WHERE id='".$user_id."'");

?>
