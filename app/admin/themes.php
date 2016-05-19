<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-ads.php');
require('../core/config/config-lang.php');
require('../core/system.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$page['name'] = 'Admin - Themes';
$menu['themes'] = 'active';

$themes = scandir('../themes');
$themes_list = array();
foreach($themes as $theme) {
  if(file_exists('../themes'.'/'.$theme.'/'.'manifest.json')) {
  	$info = json_decode(trim(file_get_contents('../themes'.'/'.$theme.'/'.'manifest.json')),true);
  	$themes_list[] = array('name' => $info['name'],'author' => $info['author'], 'version' => $info['version'], 'path' => $theme);
  }
}

if(isset($_POST['set_theme'])) {
$theme = $_POST['theme'];
$layout = $_POST['layout'];
$newConfig = "<?php
\$theme = '".$theme."';
\$layout = '".$layout."';
	";
file_put_contents('../core/config/config-theme.php',$newConfig);
header("Location: themes.php");
exit;	
}

require('../core/config/config-theme.php');
require('../inc/admin/top.php');
?>
<section>
<div class="content-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<form action="" method="post">
<div class="panel panel-default">
<div class="panel-heading panel-title"> Themes </div>
<div class="panel-body">
<table class="table table-responsive">
<tr>
<td> <b>Layout</b> </td>
<td> 
<select name="layout" class="form-control">
<option value="full" <?php if($layout=='full') { echo 'selected'; } ?>> Full-Width </option>
<option value="boxed" <?php if($layout=='boxed') { echo 'selected'; } ?>> Boxed </option>
</select>
</td>
</tr>
<tr>
<td> <b>Theme</b> </td>
<td> 
<select name="theme" class="form-control">
<option value="default" <?php if($theme=='default') { echo 'selected'; } ?>> Default </option>
<?php
if(!empty($themes_list)) {
foreach($themes_list as $_theme) {
if($_theme['path'] == $theme) {
echo '<option value="'.$_theme['path'].'" selected> '.$_theme['name'].' </option>';	
} else {
echo '<option value="'.$_theme['path'].'"> '.$_theme['name'].' </option>';		
}
}	
}
?>
</select>
</td>
</tr>
</table>
<?php 
if(!empty($themes_list)) { 
echo '<input type="submit" name="set_theme" class="btn btn-danger" value="Save">';
} else {
echo '<input type="submit" class="btn btn-danger" value="Save" disabled>';
}
?>
</div>
</div>
</form>
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