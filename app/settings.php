<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$page['name'] = $lang['Settings'];
$menu['settings'] = 'active';

$page['js'] .= '<script src="'.$domain.'/vendor/parsleyjs/dist/parsley.min.js"></script>';
$page['js'] .= '<script type="text/javascript">$("#settings").parsley();</script>';
$page['js'] .= '<script src="'.$domain.'/vendor/bootstrap-filestyle/src/bootstrap-filestyle.js"></script>';

if($user['updated_name'] == 0) {
	$can_update_name = '';
} else {
	$can_update_name = 'disabled';
}

if($user['private_profile'] == 0) {
	$private_profile = '';
} else {
	$private_profile = 'checked';
}

if(isset($_POST['save'])) {

	$email = $_POST['email'];
	$password = trim($_POST['password']);
	$instagram_username = $_POST['instagram_username'];
	$bio = $_POST['bio'];

	if($_FILES['profile_picture']['name']) {
		$extension = strtolower(end(explode('.', $_FILES['profile_picture']['name'])));
		if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {
			if(!$_FILES['profile_picture']['error']) {
				$new_file_name = md5(mt_rand()).$_FILES['profile_picture']['name'];
				if($_FILES['profile_picture']['size'] > (1024000)) {
					$valid_file = false;
					$error = 'Oops!  Your profile picture\'s size is to large.';
				}
				$valid_file = true;
				if($valid_file) {
					move_uploaded_file($_FILES['profile_picture']['tmp_name'], 'uploads/'.$new_file_name);
					$uploaded = true;
				}
			}
			else {
				$error = 'Error occured:  '.$_FILES['profile_picture']['error'];
			}
		}
	}

	$db->query("UPDATE users SET email='$email',bio='$bio',profile_username='$instagram_username' WHERE id='".$user['id']."'");

	if(isset($uploaded)) {
		$db->query("UPDATE users SET profile_picture='$new_file_name' WHERE id='".$user['id']."'");
	}

	if(!empty($password)) {
		$db->query("UPDATE users SET password='"._hash($password)."' WHERE id='".$user['id']."'");
	}

	if($user['updated_name'] == 0) {
		$full_name = strip_tags($_POST['full_name']);
		if($full_name != $user['full_name']) {
			$db->query("UPDATE users SET full_name='$full_name' WHERE id='".$user['id']."'");
			$db->query("UPDATE users SET updated_name='1' WHERE id='".$user['id']."'");
		}
	}

	setcookie('updatedSettings', 'true', time()+6);
	header("Location: settings");
	exit;

} 
/*
if(isset($_POST['privacy_save'])) {
	if(isset($_POST['private_profile'])) {
		$private_profile = 1;
	} else {
		$private_profile = 0;
	}
	$db->query("UPDATE users SET private_profile='$private_profile' WHERE id='".$user['id']."'");
	setcookie('updatedSettings', 'true', time()+6);
	header("Location: settings");
	exit;
}
*/

require('inc/top.php');
?>
<section>
<div class="content-wrapper">
<div class="container-fluid">
<div class="col-md-12">

<div class="row">
<!-- START panel-->
<div class="col-md-8">
	<div class="panel panel-default">
		<div class="panel-heading"><?php echo $lang['Account_Settings']?></div>
		<div class="panel-body">
			<?php if(isset($_COOKIE['updatedSettings'])) { ?> <div class="alert alert-success bg-success-light"> <i class="fa fa-check"></i> &nbsp <?php echo $lang['Updated_Account_Settings']?> </div> <?php } ?>
			<form action="" method="post" role="form" enctype="multipart/form-data" id="settings">
				<div class="form-group has-feedback">
					<label for="profile_picture"><?php echo $lang['Profile_Picture']?></label>
					<input type="file" name="profile_picture" data-classbutton="btn btn-default" data-classinput="form-control inline" class="form-control filestyle">
				</div>
				<div class="form-group has-feedback">
					<label for="full_name"><?php echo $lang['Full_Name']?></label>
					<input type="text" name="full_name" class="form-control" value="<?php echo $user['full_name']?>" autocomplete="off" <?php echo $can_update_name?>>
					<small class="text-muted"> <?php echo $lang['Full_Name_Update_Notice']?> </small>
				</div>
				<div class="form-group has-feedback">
					<label for="email"><?php echo $lang['Email']?></label>
					<input type="email" name="email" class="form-control" value="<?php echo $user['email']?>" autocomplete="off" required>
				</div>
				<div class="form-group has-feedback">
					<label for="password"><?php echo $lang['New_Password']?></label>
					<input type="password" name="password" class="form-control" placeholder="Enter your new password" autocomplete="off">
				</div>
				<div class="form-group has-feedback">
					<label for="city"><?php echo $lang['Bio']?></label>
					<textarea name="bio" class="form-control" value="<?php echo $user['bio']?>" placeholder="Tell the world about yourself..." autocomplete="off" data-parsley-trigger="keyup" data-parsley-minlength="10" data-parsley-maxlength="150" data-parsley-validation-threshold="10" data-parsley-minlength-message = "<?php echo $lang['Come_On_You_Can_Do_Better']?>" required><?php echo $user['bio']?></textarea>
				</div>
				<div class="form-group has-feedback">
					<label for="city"> <?php echo $lang['Profile_Username']?></label>
					<input type="text" name="instagram_username" class="form-control" placeholder="Enter your profile username" value="<?php echo $user['profile_username']?>" autocomplete="off">
					<small class="text-muted"> <?php echo $lang['Nickname_Notice']?> </small>
				</div>
				<input type="submit" name="save" class="btn btn-primary" value="<?php echo $lang['Save']?>">
			</form>
		</div>
	</div>
</div>
<!-- END panel-->
<!-- START panel-->
<div class="col-md-4">
	<div class="panel panel-default">
		<div class="panel-heading"><?php echo $lang['Profile_Picture']?></div>
		<div class="panel-body">
			<img src="<?php echo getProfilePicture($domain,$user)?>" class="center-block img-responsive img-thumbnail" height="128" width="128">
		</div>
	</div>
</div>
<!-- END panel-->
<!-- START panel
<div class="col-md-4">
	<div class="panel panel-default">
		<div class="panel-heading"><?php echo $lang['Privacy']?></div>
		<div class="panel-body">
			<form action="" method="post">
				<div class="form-group">
					<label><?php echo $lang['Private_Profile']?></label>
					<div class="checkbox c-checkbox">
						<label>
							<input type="checkbox" name="private_profile" <?php echo $private_profile?> value="">
							<span class="fa fa-check"></span></label> <?php echo $lang['Check_To_Enable']?>
						</div>
						<p class="text-muted" style="display:inline;"><?php echo $lang['Private_Profile_Explanation']?></p>
					</div>
					<input type="submit" name="privacy_save" class="btn btn-danger" value="<?php echo $lang['Save']?>">
				</form>
			</div>
		</div>
	</div>
	<!-- END panel-->
</div>

</div>
</div>
</div>
</section>
<?php
require('inc/bottom.php'); 
?>
