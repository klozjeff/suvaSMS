<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$page['name'] = $lang['Profile_Views'];
$menu['profile_views'] = 'active';

$per_page = 8;
$count = $db->query("SELECT * FROM profile_views WHERE profile_id='".$user['id']."'")->num_rows;
$last_page = ceil($count/$per_page);
if(isset($_GET['page'])) { $p = $_GET['page']; } else { $p = 1; }
if($p < 1) { $p = 1; } elseif($p > $last_page) { $p = $last_page; }
$limit = 'LIMIT ' .($p - 1) * $per_page .',' .$per_page;

$profile_views = $db->query("SELECT * FROM profile_views WHERE profile_id='".$user['id']."' AND viewer_id != '".$user['id']."' ORDER BY id DESC $limit");

$page['css'] = '<style> table,th,tr,td { border:none !important; } </style>';

require('inc/top.php');
?>
<section>
<div class="content-wrapper">
<h3><?php echo $lang['Profile_Views']?></h3>
<div class="container-fluid">
<div class="col-md-12">
<?php if($profile_views->num_rows >= 1) { ?>
<div class="row">
<div class="panel panel-default">
<table class="table table-responsive table-hover">
<thead>
<tr>
<th style="padding-left:12px;"><?php echo $lang['User']?></th>
<th style="text-align:center;"><?php echo $lang['Time']?></th>
</tr>
</thead>
<tbody>
<?php 
while($profile_view = $profile_views->fetch_array()) {
$profile = $db->query("SELECT * FROM users WHERE id='".$profile_view['viewer_id']."'")->fetch_array(); 
?>
<tr>
<td>
  <div class="media">
     <a href="<?php echo $domain?>/app/profile/<?php echo $profile['id']?>" class="pull-left">
        <img src="<?php echo getProfilePicture($domain,$profile)?>" class="media-object img-responsive img-rounded">
     </a>
     <div class="media-body">
        <a href="<?php echo $domain?>/app/profile/<?php echo $profile['id']?>" style="text-decoration:none;color:#656565;">
        <h4 class="media-heading"><?php echo $profile['full_name']?></h4>
        </a>
        <?php echo $profile['age']?>, <?php echo $lang[$profile['gender']]?>
        </div>
  </div>
</td>
<td style="width:150px;text-align:center;">
<?php echo time_ago($profile_view['time'])?>
</td>
<?php } ?>
</tr>
</tbody>
</table>
</div>
</div>
<div class="row">
<ul class="pagination pagination-sm m0 pull-left">
<?php
if($last_page < $p) {
for($i=1; $i<=$last_page; $i++) {
if($i == $p) {
?>
<li class="active"> <a href="<?php echo $domain?>/app/profile_views?page=<?php echo $i?>"> <?php echo $i; ?> </a> </li>
<?php
} else {
?>
<li> <a href="<?php echo $domain?>/app/profile_views?page=<?php echo $i?>"> <?php echo $i; ?> </a> </li>
<?php   
} } }
?>
</ul>
</div>
<?php } else { echo '<i class="fa fa-frown-o fa-fw"></i> '.$lang['None_Viewed_Profile']; } ?>
</div>
</div>
</div>
</section>
<?php
require('inc/bottom.php'); 
?>
