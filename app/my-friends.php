<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$page['name'] = $lang['Friends'];
$menu['friends'] = 'active';

$per_page = 4;
$count = $db->query("SELECT id FROM friend_requests WHERE accepted='1' AND (user1='".$user['id']."' OR user2='".$user['id']."')")->num_rows;
$last_page = ceil($count/$per_page);
if(isset($_GET['page'])) { $p = $_GET['page']; } else { $p = 1; }
if($p < 1) { $p = 1; } elseif($p > $last_page) { $p = $last_page; }
$limit = 'LIMIT ' .($p - 1) * $per_page .',' .$per_page;

$friend_requests = $db->query("SELECT * FROM friend_requests WHERE accepted='0' AND user2='".$user['id']."' ORDER BY RAND() LIMIT 4");
$friends = $db->query("SELECT * FROM friend_requests WHERE accepted='1' AND (user1='".$user['id']."' OR user2='".$user['id']."') ORDER BY time DESC");

if(isset($_GET['accept'])) {
	$id = $_GET['id'];
	$db->query("UPDATE friend_requests SET accepted='1' WHERE user1='".$id."'");
	header('Location: my-friends');
	exit;
}

if(isset($_GET['decline'])) {
	$id = $_GET['id'];
	$db->query("DELETE FROM friend_requests WHERE user1='".$id."'");
	header('Location: my-friends');
	exit;
}

if(isset($_GET['unfriend'])) {
	$id = $_GET['id'];
	$db->query("DELETE FROM friend_requests WHERE (user1='".$id."' OR user2='".$id."')");
	header('Location: my-friends');
	exit;
}

$page['css'] .= '<style> .panel-body a { text-decoration: none !important; } </style>';

require('inc/top.php');
?>
<section>
<div class="content-wrapper">
<h3><?php echo $lang['Friends']?></h3>
<div class="container-fluid">
<div class="panel panel-default">
<div class="panel-heading"><?php echo $lang['Friend_Requests']?></div>
<div class="panel-body text-center" style="padding-left:30px;">
<div class="row">
<?php if($friend_requests->num_rows >= 1) { while($request = $friend_requests->fetch_array()) { ?>
<?php $requester = $db->query("SELECT * FROM users WHERE id='".$request['user1']."'")->fetch_array(); $split_name = splitName($requester['full_name']); ?>
<div class="col-md-3 pull-left" id="userWidget">
	<div class="panel widget" style="box-shadow: 1px 1px 1px 1px rgba(0,0,0,.05);">
		<div class="panel-heading panel-title">  </div>
		<div class="panel-body text-center">
			<a href="<?php echo $domain?>/app/profile/<?php echo $requester['id']?>" class="linkblk">
				<img src="<?php echo getProfilePicture($domain,$requester)?>" alt="<?php echo $requester['full_name']?>" class="center-block img-circle img-thumbnail thumb96"> <br>
				<p style="font-size:17px;"><b> <?php echo $split_name['first_name']?> </b></p>
			</a>
			<a href="?accept=true&id=<?php echo $requester['id']?>" class="btn btn-default btn-sm pull-left text-success"> <i class="fa fa-check fa-fw fa-lg"></i> <?php echo $lang['Accept']?> </a> <a href="?decline=true&id=<?php echo $requester['id']?>" class="btn btn-default btn-sm pull-right text-danger"> <i class="fa fa-times fa-fw fa-lg"></i> <?php echo $lang['Decline']?> </a> 
		</div>
	</div>
</div>
<?php } } else { echo '<p class="pull-left">'.$lang['No_New_Friend_Requests'].'</p>'; } ?>
</div>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading"><?php echo $lang['Friends']?></div>
<div class="panel-body" style="padding-left:30px;">
<div class="row">
<?php if($friends->num_rows >= 1) { 
	while($friend = $friends->fetch_array()) { 
		$fr = $db->query("SELECT id,profile_picture,age,full_name,last_active FROM users WHERE (id='".$friend['user1']."' OR id='".$friend['user2']."') AND id != '".$user['id']."'")->fetch_array(); 
		?>
		<div class="col-md-3 pull-left" id="userWidget">
			<div class="panel widget" style="box-shadow: 1px 1px 1px 1px rgba(0,0,0,.05);">
				<div class="panel-heading panel-title"><a href="?unfriend=true&id=<?php echo $fr['id']?>" class="text-danger pull-right"> <i class="fa fa-times fa-fw fa-lg"></i> </a></div>
				<div class="panel-body text-center">
					<a href="<?php echo $domain?>/app/profile/<?php echo $fr['id']?>" class="linkblk">
						<img src="<?php echo getProfilePicture($domain,$fr)?>" alt="<?php echo $fr['full_name']?>" class="center-block img-circle img-thumbnail thumb96"> <br>
						<p style="font-size:17px;"><b> <?php echo $fr['full_name']?> </b></p>
					</a>
				</div>
			</div>
		</div>
		<?php }  ?>
	</div>
	<div class="row">
		<div class="col-md-12">
			<ul class="pagination pagination-sm m0 pull-left">
				<?php
				if($last_page < $p) {
					for($i=1; $i<=$last_page; $i++) {
						if($i == $p) {
							?>
							<li class="active"> <a href="<?php echo $domain?>/app/my-friends?page=<?php echo $i?>"> <?php echo $i; ?> </a> </li>
							<?php
						} else {
							?>
							<li> <a href="<?php echo $domain?>/app/my-friends?page=<?php echo $i?>"> <?php echo $i; ?> </a> </li>
							<?php	
						}
					}
				}
				?>
			</ul>
		</div>
	</div>
</div>
</div>
<?php } else { echo '<p class="pull-left">'.$lang['You_Have_Not_Added_Any_Friends'].'</p>'; } ?>
</div>
</div>
</section>
<?php
require('inc/bottom.php'); 
?>
