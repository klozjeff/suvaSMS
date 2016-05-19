<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-theme.php');
require('../core/config/config-lang.php');
require('../core/system.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$page['name'] = 'Admin - Plugins';
$menu['plugins'] = 'active';

$page['css'] = '<style> td,th { text-align:center !important; vertical-align:middle !important; } </style>';

$plugins = scandir('../plugins');
$plugin_list = array();
foreach($plugins as $plugin) {
  if(file_exists('../plugins'.'/'.$plugin.'/'.'manifest.json')) {
  	$info = json_decode(trim(file_get_contents('../plugins'.'/'.$plugin.'/'.'manifest.json')),true);
  	if(file_exists('../plugins'.'/'.$plugin.'/'.'status.lock')) { $status = 1; } else { $status = 0; }
  	if(file_exists('../plugins'.'/'.$plugin.'/'.'config.json')) { $config = 1; } else { $config = 0; }
  	$plugin_list[] = array('name' => $info['name'],'author' => $info['author'], 'version' => $info['version'], 'path' => $plugin, 'status' => $status, 'config' => $config);
  }
}

if(isset($_GET['activate'])) {
$path = $_GET['path'];
fopen('../plugins'.'/'.$path.'/'.'status.lock','w+');
header('Location: plugins.php');
exit;
}

if(isset($_GET['deactivate'])) {
$path = $_GET['path'];
unlink('../plugins'.'/'.$path.'/'.'status.lock');
header('Location: plugins.php');
exit;
}

require('../inc/admin/top.php');
?>
<section>
<div class="content-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
<div class="panel-heading panel-title"> Plugins </div>
<div class="panel-body">
Below is the list of the currently installed plugins <br>
</div>
</div>
<table class="table table-responsive table-hover" style="background:#fff;">
<thead>
<th> Name </th>
<th> Author </th>
<th> Version </th>
<th> Actions </th>
</thead>
<tbody>
<?php foreach($plugin_list as $item) { ?>
<tr>
<td> <b><?php echo $item['name']?></b> </td>
<td> <b><?php echo $item['author']?></b> </td>
<td> <b><?php echo $item['version']?></b> </td>
<td> 
<?php if($item['status'] == 0) { echo '<a href="?activate=true&path='.$item['path'].'" class="btn btn-danger"> Activate </a>'; } else { echo '<a href="?deactivate=true&path='.$item['path'].'" class="btn btn-default"> Deactivate </a>'; } ?> 
<?php if($item['config'] == 1) { echo '<a href="configurator.php?id='.base64_encode($item['path']).'" class="btn btn-danger"> <i class="fa fa-cog fa-fw"></i> Configure </a>'; } else { echo ''; } ?>
</td>
</tr>
<? } ?>
</tbody>
</table>
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