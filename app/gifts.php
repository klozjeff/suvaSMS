<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$page['name'] = $lang['Friends'];
$menu['home'] = 'active';

$id = $db->real_escape_string($_GET['id']);

$per_page = 5;
$count = $db->query("SELECT id FROM gifts WHERE user2='".$id."'")->num_rows;
$last_page = ceil($count/$per_page);
if(isset($_GET['page'])) { $p = $_GET['page']; } else { $p = 1; }
if($p < 1) { $p = 1; } elseif($p > $last_page) { $p = $last_page; }
$limit = 'LIMIT ' .($p - 1) * $per_page .',' .$per_page;

$gifts = $db->query("SELECT * FROM gifts WHERE user2='".$id."' ORDER BY id DESC");
$info = $db->query("SELECT id,full_name FROM users WHERE id='".$id."'");
if($info->num_rows >= 1) {
	$info = $info->fetch_array();
} else {
	header("Location: people");
	exit;
}

$name = splitName($info['full_name']);

$vars = get_defined_vars();

require('inc/top.php');
?>
<section>
<div class="content-wrapper">
<h3><?php echo sprintf($lang["sGifts"],$name['first_name'])?></h3>
<div class="container-fluid">

<div class="panel panel-default">
<div class="panel-body text-center">
<div class="row">
	<?php 
	if($gifts->num_rows >= 1) {
		while($gift= $gifts->fetch_array()) { 
			$sender = $db->query("SELECT full_name FROM users WHERE id='".$gift['user1']."'")->fetch_array();
			?>
			<a href="<?php echo $domain?>/app/profile/<?php echo $gift['user1']?>" style="color:#656565;">
				<div class="col-md-3 pull-left" id="userWidget">
					<div class="panel widget" style="box-shadow: 1px 1px 1px 1px rgba(0,0,0,.05);">
						<div class="panel-body text-center">
							 <img src="<?php echo $domain?>/<?php echo $gift['path']?>" class="giftImageSpecial"> <br>
							 <p style="font-size:17px;"><b> <?php echo $sender['full_name']?> </b></p>
						</div>
					</div>
				</div>
			</a>
			<?php } ?>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<ul class="pagination pagination-sm m0 pull-left">
			<?php
			if($last_page >= $per_page) {
			for($i=1; $i<=$last_page; $i++) {
				if($i == $p) {
					?>
					<li class="active"> <a href="<?php echo $domain?>/app/gifts/<?php echo $id?>?page=<?php echo $i?>"> <?php echo $i; ?> </a> </li>
					<?php
				} else {
					?>
					<li> <a href="<?php echo $domain?>/app/gifts/<?php echo $id?>?page=<?php echo $i?>"> <?php echo $i; ?> </a> </li>
					<?php	
				} } }
			?>
		</ul>
	</div>
</div>
<?php } else { echo $lang['Nothing_To_Show']; } ?>
</div>
</div>
</div>
</section>
<?php
require('inc/bottom.php');
?>