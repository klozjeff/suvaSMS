<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-theme.php');
require('../core/config/config-ads.php');
require('../core/config/config-lang.php');
require('../core/system.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$page['name'] = 'Admin - Global Notification';
$menu['global-notification'] = 'active';

if(isset($_POST['send'])) {
$content = $_POST['content'];
$users = $db->query("SELECT id FROM users");
while($_user = $users->fetch_array()) {
$id = $_user['id'];
$db->query("INSERT INTO notifications (receiver_id,url,content,icon,time,is_read) VALUES ('".$id."','#','".$content."','fa fa-globe', '".time()."', '0')");
$success = true;
}
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
<div class="panel-heading panel-title"> Global Notification </div>
<div class="panel-body">
<?php if(isset($success)) { ?> <div class="alert alert-success"> <i class="fa fa-check fa-fw"></i> Notification has been sent </div> <?php } ?>
From here you can send a custom notification to all users of your website <br><br>
<textarea class="form-control" name="content" placeholder="Notification content..."></textarea>
<br>
<input type="submit" name="send" class="btn btn-danger" value="Send">
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