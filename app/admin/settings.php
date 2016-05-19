<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-ads.php');
require('../core/config/config-theme.php');
require('../core/config/config-lang.php');
require('../core/system.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$page['name'] = 'Admin - Settings';
$menu['settings'] = 'active';

if(isset($_POST['save'])) {
	$site_name = $_POST['site_name'];
	$keywords = $_POST['meta_keywords'];
	$description = $_POST['meta_description'];
	$paygol_service_id = $_POST['paygol_service_id'];
	$secret_key = $_POST['secret_key'];
	$publishable_key = $_POST['publishable_key'];
	$paypal_email = $_POST['paypal_email'];
	$fb_app_id = $_POST['fb_app_id'];
	$fb_secret_key = $_POST['fb_secret_key'];
	$newConfig = "<?php
\$domain = '".$domain."';

// Database Configuration
\$_db['host'] = '".$_db['host']."';
\$_db['user'] = '".$_db['user']."';
\$_db['pass'] = '".$_db['pass']."';
\$_db['name'] = '".$_db['name']."';

\$site_name = '".$site_name."';
\$meta['keywords'] = '".$keywords."';
\$meta['description'] = '".$description."';

// PayPal Configuration
\$paypal_email = '".$paypal_email."'; // Email of PayPal Account to receive money

// PayGol Configuration (SMS Payments)
\$paygol_service_id = '".$paygol_service_id."';

// Stripe Configuration
\$secret_key = '".$secret_key."'; // Stripe API Secret Key
\$publishable_key = '".$publishable_key."'; // Stripe API Publishable Key

// Facebook Login Configuration
\$fb_app_id = '".$fb_app_id."'; 
\$fb_secret_key = '".$fb_secret_key."'; 

\$db = new mysqli(\$_db['host'], \$_db['user'], \$_db['pass'], \$_db['name']) or die('MySQL Error');
	";
	file_put_contents('../core/config/config.php',$newConfig);
	header("Location: settings.php");
	exit;
}

require('../inc/admin/top.php');
?>
<section>
<div class="content-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<form action="" method="post">
<div class="panel panel-default">
<div class="panel-heading panel-title"> General Settings </div>
<div class="panel-body">
<table class="table table-responsive">
<tr>
<td> <b>Website Name</b> </td>
<td> <input type="text" name="site_name" value="<?php echo $site_name?>" class="form-control"> </td>
</tr>
<tr>
<td> <b>SEO Keywords</b> </td>
<td> <textarea name="meta_keywords" class="form-control"><?php echo $meta['keywords']?></textarea> </td>
</tr>
<tr>
<td> <b>SEO Description</b> </td>
<td> <textarea name="meta_description" class="form-control"><?php echo $meta['description']?></textarea> </td>
</tr>
</table>
</div>
</div>
<div class="panel panel-default">
<div class="panel-heading panel-title"> Other Settings </div>
<div class="panel-body">
<table class="table table-responsive">
<tr>
<td> <b>Fortumo Service ID</b> </td>
<td> <input type="text" name="paygol_service_id" value="<?php echo $paygol_service_id?>" class="form-control"> </td>
</tr>
<tr>
<td> <b>PayPal Account</b> </td>
<td> <input type="text" name="paypal_email" value="<?php echo $paypal_email?>" class="form-control"> </td>
</tr>
<tr>
<td> <b>Stripe Secret Key</b> </td>
<td> <input type="text" name="secret_key" value="<?php echo $secret_key?>" class="form-control"> </td>
</tr>
<tr>
<td> <b>Stripe Publishable Key</b> </td>
<td> <input type="text" name="publishable_key" value="<?php echo $publishable_key?>" class="form-control"> </td>
</tr>
<tr>
<td> <b>Facebook App ID</b> <small>  </td>
<td> <input type="text" name="fb_app_id" value="<?php echo $fb_app_id?>" class="form-control"> </td>
</tr>
<tr>
<td> <b>Facebook App Secret Key</b> </td>
<td> <input type="text" name="fb_secret_key" value="<?php echo $fb_secret_key?>" class="form-control"> </td>
</tr>
</table>
</div>
</div>
<input type="submit" name="save" class="btn btn-danger" value="Save">
</form>
</div>
</div>
</div>
</div>
</section>
<script src="<?php echo $domain?>/vendor/modernizr/modernizr.js"></script>
<script src="<?php echo $domain?>/vendor/jquery/dist/jquery.js"></script>
<script src="<?php echo $domain?>/vendor/bootstrap/dist/js/bootstrap.js"></script>
<script src="<?php echo $domain?>/vendor/jQuery-Storage-API/jquery.storageapi.js"></script>
<script src="<?php echo $domain?>/vendor/animo.js/animo.js"></script>
<script src="<?php echo $domain?>/vendor/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?php echo $domain?>/app/js/app.js"></script>
</body>
</html>