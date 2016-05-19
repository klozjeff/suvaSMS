<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-theme.php');
require('../core/config/config-lang.php');
require('../core/system.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$page['name'] = 'Admin - Edit User';
$menu['users'] = 'active';

$id = $_GET['id'];
$_user = $db->query("SELECT * FROM users WHERE id='".$id."'")->fetch_array();

if(isset($_POST['save'])) {
$email = $_POST['email'];
$full_name = $_POST['full_name'];
$bio = $_POST['bio'];
if(!empty($_POST['password'])) {
	$password = $_POST['password'];
} else {
	$password = $user['password'];
}
$gender= $_POST['gender'];
$energy = $_POST['energy'];
$city = $_POST['city'];
$country = $_POST['country'];
if(empty($energy) || !is_numeric($energy)) {
	$energy = 0;	
}
$db->query("UPDATE users SET email='".$email."',full_name='".$full_name."',bio='".$bio."',password='"._hash($password)."',gender='".$gender."',energy='".$energy."',city='".$city."',country='".$country."' WHERE id='".$id."'");
header('Location: users.php');
exit;
}

if(isset($_POST['make_admin'])) {
$db->query("UPDATE users SET is_admin='1' WHERE id='".$id."'");
header('Location: edit-user.php?id='.$id);
exit;
}

if(isset($_POST['remove_admin'])) {
$db->query("UPDATE users SET is_admin='0' WHERE id='".$id."'");
header('Location: edit-user.php?id='.$id);
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
<div class="panel-body">
<table class="table table-responsive">
<tr>
<td> <b>Email</b> </td>
<td> <input type="text" name="email" value="<?php echo $_user['email']?>" class="form-control"> </td>
</tr>
<tr>
<td> <b>Full Name</b> </td>
<td> <input type="text" name="full_name" value="<?php echo $_user['full_name']?>" class="form-control"> </td>
</tr>
<tr>
<td> <b>Bio</b> </td>
<td> <textarea name="bio" class="form-control"><?php echo $_user['bio']?></textarea> </td>
</tr>
<tr>
<td> <b>New Password</b> </td>
<td> <input type="password" name="password" class="form-control" placeholder="Enter a new password for this user..."> </td>
</tr>
<tr>
<td> <b>Gender</b> </td>
<td> 
<select name="gender" class="form-control">
<option value="Male" <?php if($_user['gender'] == 'Male') { echo 'selected'; } ?>> Male </option>
<option value="Female" <?php if($_user['gender'] == 'Female') { echo 'selected'; } ?>> Female </option>
</select>
</td>
</tr>
<tr>
<td> <b>City</b> </td>
<td> <input type="text" name="city" value="<?php echo $_user['city']?>" class="form-control"> </td>
</tr>
<tr>
<td> <b>Country</b> </td>
<td> <input type="text" name="country" value="<?php echo $_user['country']?>" class="form-control"> </td>
</tr>
<tr>
<td> <b>Energy</b> </td>
<td> 
<input type="text" name="energy" value="<?php echo $_user['energy']?>" class="form-control">
</td>
</tr>
</table>
<br>
<input type="submit" name="save" class="btn btn-danger" value="Save">
<?php if($_user['is_admin'] == 0) { ?>
<button type="submit" name="make_admin" class="btn btn-default"> <i class="fa fa-check-circle fa-fw"></i> Make Admin </button>
<? } else { ?>
<button type="submit" name="remove_admin" class="btn btn-default"> <i class="fa fa-times-circle fa-fw"></i> Remove Admin </button>
<?php } ?>
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