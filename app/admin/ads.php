<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-theme.php');
require('../core/config/config-ads.php');
require('../core/config/config-lang.php');
require('../core/system.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$page['name'] = 'Admin - Manage Ads';
$menu['ads'] = 'active';

if(isset($_POST['save'])) {
	$status = $_POST['status'];
	$ad_bottom = $_POST['ad_bottom'];
	$ad_side = $_POST['ad_side'];
	$newConfig = "<?php
\$ads = ".$status.";

\$ad_bottom = '".stripcslashes($ad_bottom)."';
\$ad_side = '".stripcslashes($ad_side)."';
	";
	file_put_contents('../core/config/config-ads.php',$newConfig);
	header("Location: ads.php");
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
<div class="panel-heading panel-title"> Advertisements </div>
<div class="panel-body">
<table class="table table-responsive">
<tr>
<td> <b>Status</b> </td>
<td> 
<select name="status" class="form-control">
<option value="true" <?php if($ads == true) { echo 'selected'; } ?>> Yes, show ads </option>
<option value="false" <?php if($ads == false) { echo 'selected'; } ?>> No, don't show ads </option>
</select>
</td>
</tr>
<tr>
<td> <b>Advertisement 1 (Horizontal)</b> </td>
<td> <textarea name="ad_bottom" class="form-control"><?php echo $ad_bottom?></textarea> </td>
</tr>
<tr>
<td> <b>Advertisement 2 (Vertical)</b> </td>
<td> <textarea name="ad_side" class="form-control"><?php echo $ad_side?></textarea> </td>
</tr>
</table>
<br>
<input type="submit" name="save" class="btn btn-danger" value="Save">
</div>
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