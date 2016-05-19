<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$page['name'] = 'Manage Files';
$menu['home'] = 'active';

$page['js'] .= '<script src="'.$domain.'/vendor/parsleyjs/dist/parsley.min.js"></script>';
$page['js'] .= '<script type="text/javascript">$("#_photos").parsley();</script>';
$page['js'] .= '<script src="'.$domain.'/vendor/bootstrap-filestyle/src/bootstrap-filestyle.js"></script>';

$id = $db->real_escape_string($_GET['id']);
if(isset($_GET['delete'])) {
	$id = $_GET['delete'];
	$db->query("DELETE FROM order_files WHERE id='".$id."'");
	header('Location: manage-files/"'.$id.'"');
}

$media = $db->query("SELECT * FROM order_files WHERE order_id='".$id."' AND originator='Writer' LIMIT 20");

require('inc/top.php');
?>
<section>
<div class="content-wrapper">
<h3> <?php echo "Manage Files" ?> </h3>
<div class="container-fluid">

<div class="panel panel-default">
<div class="panel-body">
<?php if($media->num_rows == 0) { ?> <p class="text-center"> <?php echo "You have not uploaded any Files" ?> </p> <?php
} else {
while($photo = $media->fetch_array()) {
	?>
	<div class="col-md-3 pull-left">
		<a href="?delete=<?php echo $photo['id']?>" class="text-danger pull-right"> <i class="fa fa-times fa-fw"></i> </a>
		<a href="uploads/<?php echo $photo['file_name']?>" class="img-responsive img-thumbnail" target="_blank" style="margin-bottom:4px;"><p><?php echo $photo['file_name']?></p></a>
	</div>
	<?php	
} }
?>
</div>
</div>
</div>
</div>
</section>
<?php
require('inc/bottom.php'); 
?>
