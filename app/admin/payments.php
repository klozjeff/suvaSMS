<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-theme.php');
require('../core/config/config-ads.php');
require('../core/config/config-lang.php');
require('../core/system.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$page['name'] = 'Admin - User Payments';
$menu['payments'] = 'active';

$payments = $db->query("SELECT * FROM transactions WHERE status='1' ORDER BY id DESC");

$page['css'] = '<style> th,td { vertical-align:middle; text-align:center; } </style>';

require('../inc/admin/top.php');
?>
<section>
<div class="content-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
<div class="panel-heading panel-title"> Payments </div>
<?php if($payments->num_rows >= 1) { ?>
<table class="table table-responsive table-hover">
<thead>
<th> Item </th>
<th> Price </th>
<th> Payment Method </th>
</thead>
<tbody>
<?php while($payment = $payments->fetch_array()) { ?>
<tr>
<td> <?php echo $payment['transaction_name']?> </td>
<td> <?php echo $payment['transaction_amount']?> USD </td>
<td> <?php if($payment['method'] == 1) { echo 'PayPal'; } elseif($payment['method'] == 2) { echo 'Credit Card'; } else { echo 'SMS'; } ?> </td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } else { echo '<p class="text-center">No payments to show</p>'; } ?>
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