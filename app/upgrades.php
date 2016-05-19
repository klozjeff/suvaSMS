<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

if(!isActivePlugin('plugins','monthly_subscriptions')) { 
$page['name'] = $lang['Upgrades'];
} else {
 $page['name'] = $config['subscription_name'];;   
}
$menu['upgrades'] = 'active';

if(isset($_GET['enable'])) {
$feature = $_GET['enable'];

if($feature == 1) {
if($user['energy'] >= 50) {
$db->query("UPDATE users SET is_incognito='1' WHERE id='".$user['id']."'");
$db->query("UPDATE users SET energy=energy-50 WHERE id='".$user['id']."'");
header('Location: '.$domain.'/app/upgrades');
exit;
} else {
$error = 'Not enough energy!';
}
}

if($feature == 2) {
if($user['energy'] >= 100) {
$db->query("UPDATE users SET is_verified='1' WHERE id='".$user['id']."'");
$db->query("UPDATE users SET energy=energy-100 WHERE id='".$user['id']."'");	
header('Location: '.$domain.'/app/upgrades');
exit;
} else {
$error = 'Not enough energy!';
}
}

if($feature == 3) {
if($user['energy'] >= 200) {
$db->query("UPDATE users SET has_disabled_ads='1' WHERE id='".$user['id']."'");  
$db->query("UPDATE users SET energy=energy-200 WHERE id='".$user['id']."'");    
header('Location: '.$domain.'/app/upgrades');
exit;
} else {
$error = 'Not enough energy!';
}
}


}

// Process Payment
if(isset($_POST['continue'])) {

$payment_method = $_POST['payment_method'];
$price=$_POST['energy_amount'];

if($payment_method == 'paypal') {

// Prepare GET data
$query = array();
$query['notify_url'] = $domain.'/app/paypal.php';
$query['cmd'] = '_xclick';
$query['business'] = $paypal_email;
$query['currency_code'] = 'USD';
$query['custom'] = $_POST['energy_amount'].'/'.$user['id'];
$query['return'] = $domain.'/app/recharge.php?price='.$price.'&customer='.$user['id'];
$query['item_name'] = $_POST['energy_amount'].' '.$lang['Funds'];
$query['quantity'] = 1;
$query['amount'] = $price;
$query['cancel_return'] = $domain.'/app/upgrades';
$query['cancel_url'] = $domain.'/app/upgrades';
// Prepare query string
$query_string = http_build_query($query);
header('Location: https://www.sandbox.paypal.com/cgi-bin/webscr?' . $query_string);


} elseif($payment_method == 'stripe') {

$token = md5(time());
$name = $_POST['energy_amount'].' '.$lang['Funds'];
$db->query("INSERT INTO transactions(transaction_amount,transaction_name,status,user_id,token,energy_to_add,method) VALUES ('".$price."','".$name."','0','".$user['id']."','".$token."','".$_POST['energy_amount']."','2')");
header("Location: process/".$token);

} elseif($payment_method == 'sms') {

$token = md5(time());
$name = $_POST['energy_amount'].' '.$lang['Energy'];
$db->query("INSERT INTO transactions(transaction_amount,transaction_name,status,user_id,token,energy_to_add,method) VALUES ('".$price."','".$name."','0','".$user['id']."','".$token."','".$_POST['energy_amount']."','3')");
header("Location: process/".$token);

}

}

if(isset($_POST['subscribe'])) {
// Prepare GET data
$query = array();
$query['notify_url'] = $domain.'/app/paypal-subscribe.php';
$query['cmd'] = '_xclick';
$query['business'] = $paypal_email;
$query['currency_code'] = 'USD';
$query['custom'] = $user['id'];
$query['return'] = $domain.'/app/upgrades';
$query['item_name'] = $config['subscription_name'];
$query['quantity'] = 1;
$query['amount'] = $config['subscription_price'];
// Prepare query string
$query_string = http_build_query($query);
header('Location: https://www.sandbox.paypal.com/cgi-bin/webscr?' . $query_string);
}

if(isset($_POST['unsubscribe'])) {
$db->query("UPDATE users SET is_incognito='0',is_verified='0',has_disabled_ads='0',subscription_expire='' WHERE id='".$user['id']."'");
header('Location: '.$domain.'/app/upgrades');
exit;
}

$page['css'] .= '<style> td,th { vertical-align:middle; text-align:center; } </style>';

require('inc/top.php');
?>
<section>
<div class="content-wrapper">
<div class="container-fluid">
    <?php if(isActivePlugin('plugins','monthly_subscriptions')) { ?>
    <div class="panel panel-default">
    <div class="panel-heading panel-title"> Account Top Up </div>
    <div class="panel-body text-center">
    <?php echo sprintf($lang['Current_Energy'],$user['energy'])?> <br> <?php echo $lang['Energy_Gift_Explanation']?> <br><br>
    <a data-toggle="modal" data-target="#submitPayment" class="btn btn-danger btn-lg"><i class="fa fa-shopping-cart fa-fw"></i> <?=$lang['Buy_Energy']?></a> 
    </div>
    </div>
    <? } ?>
    <div class="panel panel-default">
        <div class="panel-body m000" style="padding-bottom:20px;">
           <div class="upgrades-h text-center">
           <p class="upgrades-t"> <?php if(!isActivePlugin('plugins','monthly_subscriptions')) { ?> Account Top Up /Reserve Funds <?php } else { echo $config['subscription_name']; } ?> </p>
           </div>

           <div class="upgrades-b">
           <div class="col-md-12">
           <div class="upgrades-e text-center">
           <?php if(isset($error)) { ?> <div class="alert alert-warning"> <i class="fa fa-warning fa-fw"></i> <?php echo $error; ?> </div> <?php } ?>
           <?php if(!isActivePlugin('plugins','monthly_subscriptions')) { ?>
           <?php echo sprintf($lang['Current_Funds'], $user['energy'])?><br> <?php echo $lang['Reserve_Explanation']?> <br><br>
           <a data-toggle="modal" data-target="#submitPayment" class="btn btn-primary btn-lg"><i class="fa fa-shopping-cart fa-fw"></i> <?=$lang['Reserve_Funds']?></a> 
           </div>
           <table class="table table-responsive">
                <thead>
                    <th style="text-align:center;"> <?php echo $lang['Feature']?> </th>
                    <th style="text-align:center;"> <?php echo $lang['Description']?> </th>
                    <th style="text-align:center;"> <?php echo $lang['Price']?> </th>
                </thead>
                <tbody>
                   <!-- <tr>
                        <td> <b class="pull-left"> <i class="fa fa-eye-slash fa-lg fa-fw"></i> <?php echo $lang['Incognito_Mode']?> </b> </td>
                        <td> <?php echo $lang['Incognito_Mode_Explanation']?> </td>
                        <td> <?php if($user['is_incognito'] == 0) { ?> <a href="?enable=1" class="btn btn-danger"> <?php echo $lang['Enable']?> ($5) </a> <?php } else { ?> <a href="#" class="btn btn-default" disabled> <i class="fa fa-check fa-fw"></i> <?php echo $lang['Active']?> </a> <?php } ?> </td>
                    </tr>-->
                    <tr>
                        <td> <b class="pull-left"> <i class="fa fa-check fa-lg fa-fw"></i> <?php echo $lang['Verified_Badge']?> </b> </td>
                        <td> <?php echo $lang['Verified_Badge_Explanation']?> </td>
                        <td> <?php if($user['is_verified'] == 0) { ?> <a href="?enable=2" class="btn btn-danger"> <?php echo $lang['Enable']?> ($100) </a> <?php } else { ?> <a href="#" class="btn btn-default" disabled> <i class="fa fa-check fa-fw"></i> <?php echo $lang['Active']?> </a> <?php } ?> </td>
                    </tr>
                    <tr>
                        <td> <b class="pull-left"> <i class="fa fa-times fa-lg fa-fw"></i> <?php echo $lang['Disable_Ads']?> </b> </td>
                        <td> <?php echo $lang['Disable_Ads_Explanation']?> </td>
                        <td> <?php if($user['has_disabled_ads'] == 0) { ?> <a href="?enable=3" class="btn btn-danger"> <?php echo $lang['Enable']?> ($15) </a> <?php } else { ?> <a href="#" class="btn btn-default" disabled> <i class="fa fa-check fa-fw"></i> <?php echo $lang['Active']?> </a> <?php } ?> </td>
                    </tr>
                </tbody>
            </table>
            <?php } else { ?>
            <form action="" method="post">
            <table class="table table-responsive">
                <tbody>
                    <tr>
                        <td> <b> <?php echo $lang['Incognito_Mode']?> </b> </td>
                    </tr>
                    <tr>
                        <td> <?php echo $lang['Incognito_Mode_Explanation']?> </td>
                    </tr>
                    <tr>
                        <td> <b> <?php echo $lang['Verified_Badge']?> </b> </td>
                    </tr>
                    <tr>
                        <td> <?php echo $lang['Verified_Badge_Explanation']?> </td>
                    </tr>
                    <tr>
                        <td> <b> <?php echo $lang['Disable_Ads']?> </b> </td>
                    </tr>
                    <tr>
                         <td> <?php echo $lang['Disable_Ads_Explanation']?> </td>
                    </tr>
                </tbody>
            </table>
             <br>
             <?php if(empty($user['subscription_expire'])) { ?>
             <button type="submit" name="subscribe" class="btn btn-danger btn-block"> <i class="fa fa-check fa-fw"></i> Subscribe - $<?php echo $config['subscription_price']?>/<?php echo $lang['month']?></button>
             <? } else { ?>
             <button type="submit" name="unsubscribe" class="btn btn-danger btn-block"> <i class="fa fa-times fa-fw"></i> Cancel Subscription </button>
             <? } ?>
             <br>
             <img src="<?php echo $domain?>/app/img/paypal.png" width="200" height="70">
             </form>
            <? } ?>
            </div>
        </div>

        </div>
    </div>
</div>
</div>
</section>
<?php
require('inc/bottom.php'); 
?>

