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
$count = $db->query("SELECT id FROM friend_requests WHERE accepted='1' AND (user1='".$id."' OR user2='".$id."')")->num_rows;
$last_page = ceil($count/$per_page);
if(isset($_GET['page'])) { $p = $_GET['page']; } else { $p = 1; }
if($p < 1) { $p = 1; } elseif($p > $last_page) { $p = $last_page; }
$limit = 'LIMIT ' .($p - 1) * $per_page .',' .$per_page;

$friends = $db->query("SELECT * FROM friend_requests WHERE accepted='1' AND (user1='".$id."' OR user2='".$id."') ORDER BY id DESC");
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
<h3><?php echo sprintf($lang["sFriends"],$name['first_name'])?></h3>
<div class="container-fluid">

<div class="panel panel-default">
<div class="panel-body text-center">
<div class="row">
	<?php 
	if($friends->num_rows >= 1) {
		while($friend = $friends->fetch_array()) { 
			$fr = $db->query("SELECT id,profile_picture,age,full_name,last_active FROM users WHERE (id='".$friend['user1']."' OR id='".$friend['user2']."') AND id != '".$id."'")->fetch_array(); 
			?>
			<a href="<?php echo $domain?>/app/profile/<?php echo $fr['id']?>" style="color:#656565;">
				<div class="col-md-3 pull-left" id="userWidget">
					<div class="panel widget" style="box-shadow: 1px 1px 1px 1px rgba(0,0,0,.05);">
						<div class="panel-body text-center">
							<img src="<?php echo getProfilePicture($domain,$fr)?>" alt="<?php echo $fr['full_name']?>" class="center-block img-thumbnail img-circle thumb96"> <br>
							<p style="font-size:17px;"><b> <?php echo $fr['full_name']?> <?php if(time()-$fr['last_active'] <= 300) { ?> <span class="circle circle-success circle-lg" style="margin:0px;"></span> <?php } else { ?> <span class="circle circle-danger circle-lg" style="margin:0px;"></span> <?php } ?> </b></p>
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
					<li class="active"> <a href="<?php echo $domain?>/app/friends/<?php echo $id?>?page=<?php echo $i?>"> <?php echo $i; ?> </a> </li>
					<?php
				} else {
					?>
					<li> <a href="<?php echo $domain?>/app/friends/<?php echo $id?>?page=<?php echo $i?>"> <?php echo $i; ?> </a> </li>
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