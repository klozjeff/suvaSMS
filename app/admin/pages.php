<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-theme.php');
require('../core/config/config-lang.php');
require('../core/system.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$page['name'] = 'Admin - Manage Pages';
$menu['pages'] = 'active';

$pages = $db->query("SELECT * FROM pages ORDER BY id ASC");

$page['css'] = '<style> th,td { text-align:center !important; vertical-align:middle !important; } </style>';

require('../inc/admin/top.php');
?>
<section>
<div class="content-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<a href="<?php echo $domain?>/app/admin/add-page.php" class="btn btn-danger pull-left"> <i class="fa fa-plus fa-fw"></i> Add Page </a> <br><br>
<?php if($pages->num_rows >= 1) { ?>
<table class="table table-responsive" style="background:#fff;">
<thead>
<th> Title </th>
<th> Actions </th>
</thead>
<tbody>
<?php while($page = $pages->fetch_array()) { ?>
<tr>
<td> <?php echo $page['page_title']?> </td>
<td> <a class="btn btn-primary" href="<?php echo $domain?>/app/admin/edit-page.php?id=<?php echo $page['id']?>"> Edit </a> <a class="btn btn-danger" href="<?php echo $domain?>/app/admin/delete-page.php?id=<?php echo $page['id']?>"> <i class="fa fa-trash"></i> </a> </td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } else { echo '<p class="text-center">No pages to show</p>'; } ?>
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