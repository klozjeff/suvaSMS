<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$id = $db->real_escape_string($_GET['id']);

$__pages = $db->query("SELECT * FROM pages WHERE id='".$id."'");
if($__pages->num_rows >= 1) {
	$__page = $__pages->fetch_array();
} else {
	header('Location: home.php');
	exit;
}

$page['name'] = $__page['page_title'];

require('inc/top.php');
?>
<section>
	<div class="content-wrapper">
		<h3><?php echo $__page['page_title']?></h3>
		<div class="container-fluid">
			<div class="panel panel-default">
				<div class="panel-body">
					<?php echo $__page['content']?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
require('inc/bottom.php'); 
?>
