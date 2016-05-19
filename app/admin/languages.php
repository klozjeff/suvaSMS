<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-theme.php');
require('../core/config/config-lang.php');
require('../core/system.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$page['name'] = 'Admin - Languages';
$menu['languages'] = 'active';

$page['css'] = '<style> td,th { text-align:center !important; vertical-align:middle !important; } </style>';

$languages = scandir('../languages');
$list = array();
foreach($languages as $language) {
if(file_exists('../languages/'.$language.'/language.php')) {
 $list[] = array('icon' => $domain.'/app/languages/'.$language.'/icon.png', 'path' => '../languages/'.$language.'/'.'language.php', 'name' => ucfirst($language));
}	
}

if(isset($_GET['set-default'])) {
$lang = $_GET['set-default'];
$newConfig = "<?php
\$default = '".$lang."';
	";
	file_put_contents('../core/config/config-lang.php',$newConfig);
	header("Location: languages.php");
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
<div class="panel-heading panel-title"> Languages </div>
<div class="panel-body">
Below is the list of the currently available languages <br>
Instructions on how to add a new language can be found in the /languages/ folder
</div>
</div>
<table class="table table-responsive table-hover" style="background:#fff;">
<thead>
<th> Flag </th>
<th> Language </th>
<th> Action </th>
</thead>
<tbody>
<?php foreach($list as $item) { ?>
<tr>
<td> <img src="<?=$item['icon']?>"> </td>
<td> <b><?php echo $item['name']?></b> </td>
<td> <?php if($default==strtolower($item['name'])) { echo '<a href="#" class="btn btn-default btn-sm" disabled> Default </a>'; } else { echo '<a href="?set-default='.strtolower($item['name']).'" class="btn btn-danger btn-sm"> Make Default </a>'; } ?> </td>
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