<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-theme.php');
require('../core/config/config-lang.php');
require('../core/system.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$page['name'] = 'Admin - Edit Page';
$menu['pages'] = 'active';

$id = $_GET['id'];
$page = $db->query("SELECT * FROM pages WHERE id='".$id."'")->fetch_array();

if(isset($_POST['save'])) {
	$page_title = $_POST['page_title'];
	$content = $db->real_escape_string($_POST['content']);
	$db->query("UPDATE pages SET page_title='".$page_title."',content='".$content."' WHERE id='".$id."'");
	header("Location: pages.php");
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
<td> <b>Page Title</b> </td>
<td> <input type="text" name="page_title" value="<?php echo $page['page_title']?>" class="form-control"> </td>
</tr>
<tr>
<td> <b>Content</b> </td>
<td> <textarea name="content" class="textarea form-control"><?php echo $page['content']?></textarea> </td>
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
<script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>
<script>tinymce.init({
	plugins: "image link visualblocks ",
	toolbar: [
        "undo redo bold italic underline styleselect formatselect fontsizeselect bullist numlist removeformat link image visualblocks"
    ],
    menubar: false,
    selector:'.textarea'});</script>
</body>
</html>