<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-theme.php');
require('../core/config/config-lang.php');
require('../core/system.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$page['name'] = 'Admin - Manage Reports';
$menu['reports'] = 'active';

$reports = $db->query("SELECT * FROM reports ORDER BY id DESC");

$page['css'] = '<style> th,td { vertical-align:middle; text-align:center; } </style>';

require('../inc/admin/top.php');
?>
<section>
<div class="content-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
<div class="panel-heading panel-title">Reports</div>
<div class="panel-body">
<?php if($reports->num_rows >= 1) { ?>
<table class="table table-responsive">
<thead>
<th> Reported User </th>
<th> Reason </th>
<th> Actions </th>
</thead>
<tbody>
<?php while($report = $reports->fetch_array()) { ?>
<tr>
<td> <a href="<?php echo $domain?>/app/profile/<?php echo $report['reported_id']?>"> View </a> </td>
<td> <?php echo $report['reason']?> </td>
<td> <a href="delete-user.php?id=<?php echo $report['reported_id']?>" class="btn btn-danger btn-xs"> <i class="fa fa-user-times fa-fw"></i> Delete User </a> <a href="delete-report.php?id=<?php echo $report['id']?>" class="btn btn-danger btn-xs"> <i class="fa fa-trash fa-fw"></i> Delete Report </a> </td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } else { echo '<p class="text-center">No reports to show</p>'; } ?>
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