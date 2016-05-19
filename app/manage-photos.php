<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$page['name'] = $lang['Manage_Photos'];
$menu['home'] = 'active';

$page['js'] .= '<script src="'.$domain.'/vendor/parsleyjs/dist/parsley.min.js"></script>';
$page['js'] .= '<script type="text/javascript">$("#_photos").parsley();</script>';
$page['js'] .= '<script src="'.$domain.'/vendor/bootstrap-filestyle/src/bootstrap-filestyle.js"></script>';

if(isset($_GET['delete'])) {
	$id = $_GET['delete'];
	$db->query("DELETE FROM media WHERE id='".$id."'");
	header('Location: manage-photos');
}

$media = $db->query("SELECT * FROM media WHERE user_id='".$user['id']."' LIMIT 20");

require('inc/top.php');
?>
<section>
<div class="content-wrapper">
<h3> <?php echo $lang['Manage_Photos']?> </h3>
<div class="container-fluid">

<div class="panel panel-default">
<div class="panel-body">
<?php if($media->num_rows == 0) { ?> <p class="text-center"> <?php echo $lang['You_Have_Not_Uploaded_Any_Photos']?> </p> <?php
} else {
while($photo = $media->fetch_array()) {
	?>
	<div class="col-md-3 pull-left">
		<a href="?delete=<?php echo $photo['id']?>" class="text-danger pull-right"> <i class="fa fa-times fa-fw"></i> </a>
		<img src="uploads/<?php echo $photo['path']?>" class="img-responsive img-thumbnail" style="margin-bottom:4px;">
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
