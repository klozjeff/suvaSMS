<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-theme.php');
require('../core/config/config-lang.php');
require('../core/system.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$page['name'] = 'Admin - Plugin Configurator';
$menu['plugins'] = 'active';

$id = base64_decode($_GET['id']);
$config = json_decode(file_get_contents('../plugins'.'/'.$id.'/'.'config.json'),true);

if(isset($_POST['save'])) {
$all = array();
foreach($_POST as $var=>$val) {
if($var != 'save') {
$all[$var] = $val;
}
}
$all = json_encode($all);
file_put_contents('../plugins'.'/'.$id.'/'.'config.json', $all);
header('Location: configurator.php?id='.$_GET['id']);
exit;
}

require('../inc/admin/top.php');
?>
<section>
<div class="content-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
<div class="panel-heading panel-title"> Plugin Configurator </div>
<div class="panel-body">
<form action="" method="post">
<table class="table table-responsive">
<tbody>
<?php foreach($config as $variable => $value) { ?>
<tr>
<td style="width:200px;"> <b><?php echo ucwords(str_replace('_', ' ', $variable))?></b> </td>
<td> <input type="text" name="<?php echo $variable?>" value="<?php echo $value?>" class="form-control"> </td>
</tr>
<? } ?>
</tbody>
</table>
<button type="submit" name="save" class="btn btn-danger"> Save </button>
</div>
</form>
</div>
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