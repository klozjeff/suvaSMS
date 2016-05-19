<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-theme.php');
require('../core/config/config-lang.php');
require('../core/system.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$page['name'] = 'Admin - Dashboard';
$menu['dashboard'] = 'active';

$users = $db->query("SELECT id FROM users")->num_rows;
$purchases = $db->query("SELECT * FROM transactions WHERE status='1'")->num_rows;

require('../inc/admin/top.php');
?>
<section>
<div class="content-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-lg-3 col-sm-6">
<div class="panel widget bg-primary">
<div class="row row-table">
<div class="col-xs-4 text-center bg-primary-dark pv-lg">
<em class="icon-user fa-3x"></em>
</div>
<div class="col-xs-8 pv-lg">
<div class="h2 mt0"><?php echo $users?></div>
<div class="text-uppercase">Users</div>
</div>
</div>
</div>
</div>
<div class="col-lg-3 col-sm-6">
<div class="panel widget bg-purple">
<div class="row row-table">
<div class="col-xs-4 text-center bg-purple-dark pv-lg">
<em class="icon-credit-card fa-3x"></em>
</div>
<div class="col-xs-8 pv-lg">
<div class="h2 mt0"><?php echo $purchases?></div>
<div class="text-uppercase">Purchases</div>
</div>
</div>
</div>
</div>
<div class="col-lg-3 col-md-6 col-sm-12">
<div class="panel widget bg-green">
<div class="row row-table">
<div class="col-xs-4 text-center bg-green-dark pv-lg">
<em class="icon-info fa-3x"></em>
</div>
<div class="col-xs-8 pv-lg">
<div class="h2 mt0">1.8</div>
<div class="text-uppercase">Version</div>
</div>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
<div class="panel-heading panel-title"> Welcome </div>
<div class="panel-body">
<div class="col-md-4">
<b>First Steps</b>
<ul>
<li style="list-style:none;"> <a href="settings.php"> Configure the script </a> </li>
<li style="list-style:none;"> <a href="generator.php"> Generate your first users </a> </li>
<li style="list-style:none;"> <a href="add-page.php"> Create a custom page </a> </li>
</ul>
</div>
<div class="col-md-4">
<b>Get Help</b>
<ul>
<li style="list-style:none;"> <a href="http://codecanyon.net/item/matchme-complete-dating-script/12494116/support"> Item Support </a> </li>
<li style="list-style:none;"> <a href="http://condor5.com/docs/matchme/"> Online Documentation </a> </li>
</ul>
</div>
<div class="col-md-4">
<b>Other</b>
<ul>
<li style="list-style:none;"> <a href="http://codecanyon.net/item/theme-pack-for-matchme/12946496"> Purchase Themes </a> </li>
<li style="list-style:none;"> <a href="http://codecanyon.net/search?utf8=%E2%9C%93&term=matchme+plugins&view=list&sort=&date=&category=php-scripts&price_min=&price_max=&sales=&rating_min="> Purchase Plugins </a> </li>
</ul>
</div>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
<div class="panel-heading panel-title"> System Status </div>
<div class="panel-body">
<p>Verifying the <b>Geolocation API</b>... <span class="text-success">success</span></p>
<p>Retrieving pictures from <b>Instagram</b>... <span class="text-success">success</span></p>
<p>Verifying file and folder permissions... <span class="text-success">success</span></p>
<p>Cleaning junk files and cache... <span class="text-success">success</span></p>
</div>
</div>
</div>
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