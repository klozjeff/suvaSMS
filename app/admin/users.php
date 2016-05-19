<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-theme.php');
require('../core/config/config-lang.php');
require('../core/system.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$page['name'] = 'Admin - Manage Users';
$menu['users'] = 'active';

$per_page = 10;
$count = $db->query("SELECT * FROM users")->num_rows;
$last_page = ceil($count/$per_page);
if(isset($_GET['p'])) { $p = $_GET['p']; } else { $p = 1; }
if($p < 1) { $p = 1; } elseif($p > $last_page) { $p = $last_page; }
$limit = 'LIMIT ' .($p - 1) * $per_page .',' .$per_page;
$users = $db->query("SELECT * FROM users ORDER BY id DESC $limit");

$page['css'] = '<style> th,td { text-align:center !important; vertical-align:middle !important; } </style>';

require('../inc/admin/top.php');
?>
<section>
<div class="content-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
<div class="panel-heading panel-title">Users</div>
<div class="panel-body" style="padding-top:0px;">
<?php if($users->num_rows >= 1) { ?>
<table class="table table-responsive" style="background:#fff;">
<thead>
<th> # </th>
<th> Profile Picture </th>
<th> Full Name </th>
<th> Email </th>
<th> Actions </th>
</thead>
<tbody>
<?php while($user = $users->fetch_array()) { ?>
<tr>
<td> <?php echo $user['id']?> </td>
<td> <img src="<?php echo getProfilePicture($domain,$user)?>" class="img-responsive img-circle center-block" height="64" width="64"> </td>
<td> <?php echo $user['full_name']?> </td>
<td> <?php echo $user['email']?> </td>
<td> <a class="btn btn-primary" href="<?php echo $domain?>/app/admin/edit-user.php?id=<?php echo $user['id']?>"> Edit </a> <a class="btn btn-danger" href="<?php echo $domain?>/app/admin/delete-user.php?id=<?php echo $user['id']?>"> <i class="fa fa-trash"></i> </a> </td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } else { echo 'No users to show'; } ?>
</div>
</div>
<div class="row">
<ul class="pagination pagination-sm m0 pull-left">
<?php
for($i=1; $i<=$last_page; $i++) {
if($i == $p) {
?>
<li class="active"> <a href="<?php echo $domain?>/app/admin/users.php?p=<?php echo $i?>"> <?php echo $i; ?> </a> </li>
<?php
} else {
?>
<li> <a href="<?php echo $domain?>/app/admin/users.php?p=<?php echo $i?>"> <?php echo $i; ?> </a> </li>
<?php   
}
}
?>
</ul>
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