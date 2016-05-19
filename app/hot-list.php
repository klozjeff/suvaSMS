<?php
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$page['name'] = $lang['Hot_List'];
$menu['hot-list'] = 'active';

// Daily Hottest
$daily_hottest = $db->query("SELECT * FROM profile_likes GROUP BY profile_id ORDER BY COUNT(*) DESC LIMIT 10");

require('inc/top.php');
?>
<section>
<div class="content-wrapper">
<h3> <?php echo $lang['Hot_List']?> </h3>
<div class="container-fluid">
<div class="col-md-12">
<div class="panel panel-default">
<div class="panel-heading panel-title"> <?php echo $lang['Hottest_Users']?> </div>
<div class="panel-body">
<ul class="list-group">
  <?php while($dhottest = $daily_hottest->fetch_array()) { 
  $dhottest = $db->query("SELECT id,full_name,profile_picture,gender,age FROM users WHERE id='".$dhottest['profile_id']."'")->fetch_array();
  ?>
  	<a href="<?php echo $domain?>/app/profile/<?php echo $dhottest['id']?>" class="list-group-item">
    <div class="media-box">
	<div class="pull-left">
	<img src="<?php echo getProfilePicture($domain, $dhottest)?>" class="media-box-object img-rounded thumb32">
	</div>
	<div class="media-box-body clearfix">
	<strong class="media-box-heading text-primary">
	<span class="linkblk"><?php echo $dhottest['full_name']?></span> <span class="label label-danger pull-left" style="margin-right:5px;"> <?php echo $lang['HOT']?></span> </strong>
	<p class="mb-sm">
	<small><?php echo $dhottest['age']?>, <?php echo $lang[$dhottest['gender']]?></small>
	</p>
	</div>
	</div>
	</a>
  <?php } ?>
</ul>
</div>
</div>
</div>
</div>
</div>
</section>
<?php
require('inc/bottom.php'); 
?>
