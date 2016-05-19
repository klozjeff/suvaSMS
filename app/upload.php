<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$page['name'] = "Upload Files";
$menu['home'] = 'active';

$page['js'] .= '
<script src="'.$domain.'/vendor/parsleyjs/dist/parsley.min.js"></script>
<script type="text/javascript">$("#_photos").parsley();</script>
<script src="'.$domain.'/vendor/bootstrap-filestyle/src/bootstrap-filestyle.js"></script>
<script>
var c=0;
function addPhoto() {
	c++;
	$.get("'.$domain.'/app/ajax/addPhoto.php?c="+c, function(data) {
		$("#addPhoto").html("Loading...");
		$("#photos").append(data);
		$("#addPhoto").html(\'<i class="fa fa-plus fa-fw"></i> Add File\');
	});
}
</script>
';
$id = $db->real_escape_string($_GET['id']);
if(isset($_POST['upload']) && !empty($_FILES)) {
	foreach($_FILES['photo']['tmp_name'] as $index => $tmpName) {
		if($_FILES['photo']['name'][$index]) {
			$extension = strtolower(end(explode('.', $_FILES['photo']['name'][$index])));
			if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'doc' || $extension == 'pdf' || $extension == 'docx' || $extension == 'xls' || $extension == 'csv') {
				if(!$_FILES['photo']['error'][$index]) {
					$new_file_name = $_FILES['photo']['name'][$index];
					if($_FILES['photo']['size'][$index] > (1024000)) {
						$valid_file = false;
						$error = 'Oops! One of thefiles you uploaded is too large';
					} else {
						$valid_file = true;
					}
					if($valid_file) {
						move_uploaded_file($_FILES['photo']['tmp_name'][$index], 'uploads/'.$new_file_name);
						$uploaded = true;
						$db->query("INSERT INTO order_files (order_id,file_name,file_size,file_type,originator) VALUES ('".$id."','".$new_file_name."','".$_FILES['photo']['size'][$index]."','".$_FILES['photo']['type'][$index]."','Writer')");
					}
				}
				else {
					$error = 'Error occured:  '.$_FILES['photo']['error'][$index];
				}
			}	
		}
	}
	header('Location: '.$domain.'/app/order/'.$id);
	exit;
}

require('inc/top.php');
?>
<section>
<div class="content-wrapper">
<h3> <?php echo "Upload Files"?> </h3>
<div class="container-fluid">

<div class="panel panel-default">
	<div class="panel-body">
		<?php if(isset($error)) { ?> <div class="alert alert-danger"> <?php echo $error?> </div> <?php } ?>
		<form action="" method="post" role="form" enctype="multipart/form-data" id="_photos">

			<div id="photos"></div>

			<a onclick="addPhoto()" class="btn btn-default btn-sm" id="addPhoto"> <i class="fa fa-plus fa-fw"></i> <?php echo "Add File"; ?> </a> 
			<button type="submit" name="upload" class="btn btn-primary"> <?php echo $lang['Upload']?> </button>
		</form>
	</div>
</div>

</div>
</div>
</section>
<?php
require('inc/bottom.php'); 
?>
