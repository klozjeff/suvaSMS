<?php
session_start();
define('IS_AJAX','true');
require('../core/config/config.php');
require('../core/config/config-lang.php');
require('../core/system.php');

$customerid= $db->real_escape_string($_GET['id1']);
$orderid = $db->real_escape_string($_GET['id2']);

$check = $db->query("Update order_details SET status='Completed' WHERE customer_id='".$customerid."' AND id='".$orderid."'");

$db->query("Update order_details SET status='Completed' WHERE customer_id='".$customerid."' AND id='".$orderid."'");

$db->query("INSERT INTO notifications (receiver_id,url,content,icon,time,is_read) VALUES ('".$customerid."','".$domain."'/app/orders/".$orderid."','".sprintf("Completed Paper order",$user['full_name'])."','fa fa-user-plus', '".time()."', '0')");
$receiver_email = $db->query("SELECT email FROM users WHERE id='".$customerid."'")->fetch_array();
$receiver_email = $receiver_email['email'];
$clean = str_replace('<b> ', '', 'Your order is Completed');
$clean = str_replace(' </b>', '', $clean);
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
mail($receiver_email, $site_name.' - '.sprintf($clean,$user['full_name']), '<a href="'.$domain.'/app/order/'.$orderid.'">'.sprintf("Completed Paper order",$user['full_name']).'</a>',$headers,'-f info@'.$domain);

?>
<button class="btn btn-danger" disabled> <i class="fa fa-user-plus" style="margin-right:2px;"></i> Completed </button>
