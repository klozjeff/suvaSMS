<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');
require('core/stripe/init.php');

$menu['upgrades'] = 'active';

$token = $_GET['t'];
$transaction = $db->query("SELECT * FROM transactions WHERE token='".$token."'");
if($transaction->num_rows >= 1) {
	$transaction = $transaction->fetch_array();
	if($transaction['status'] == 1) {
		header('Location: upgrades');
	} 
} else {
	header('Location: upgrades');
}

if($transaction['method'] == 2) {
	$page['name'] = $lang['Pay_With_Credit_Card'];
} elseif($transaction['method'] == 3) {
	$page['name'] = $lang['Pay_With_SMS'];
}

$_SESSION['tid'] = $transaction['token'];
$_SESSION['tuid'] = $user['id'];

$geo = geoinfo();

$page['js'] = '
<script>
function checkStatus() {
	var service = '.$paygol_service_id.';
	var id = "'.$create[4].'";   
	var instruction = $("#instruction").html();
	var token = "'.$token.'";
	$.get("ajax/checkStatus.php?service="+service+"&id="+id+"&instruction="+instruction+"&token="+token, function(data) {
		$("#result").html(data);
	});
}
</script>
';

$page['js'] .= '<script src="//fortumo.com/javascripts/fortumopay.js" type="text/javascript"></script>';

require('inc/top.php');
?>
<section>
  <div class="content-wrapper">
    <div class="container-fluid">
      <?php if($transaction['method'] == 2) { ?>
      <div class="col-md-12">
        <div class="panel panel-default"> 
          <div class="panel panel-heading panel-title"> <i class="fa fa-credit-card fa-fw"></i> <?php echo $lang['Pay_With_Credit_Card']?> </div>
          <div class="panel-body text-center">
            <div id="result">
              <form action="" method="POST">

                <table class="table table-tesponsive table-bordered" style="text-align:left;">
                  <tr>
                    <td> <b> <?php echo $lang['User']?> </b> </td>
                    <td> <?php echo $user['full_name']?> </td>
                  </tr>
                  <tr>
                    <td> <b> <?php echo $lang['Item']?> </b> </td>
                    <td> <?php echo $transaction['transaction_name']?> </td>
                  </tr>
                  <tr>
                    <td> <b> <?php echo $lang['Amount']?> </b> </td>
                    <td> $<?php echo $transaction['transaction_amount']?> </td>
                  </tr>
                </table>

                <br>
                <script src="https://checkout.stripe.com/checkout.js"></script>
                <a id="stripe-pay" class="btn btn-success btn-block"> <?php echo $lang['Submit_Payment']?> </a>

              </form>
            </div>
          </div>
        </div>
      </div>
      <?php } elseif($transaction['method'] == 3) { ?>
      <div class="col-md-12">
        <div class="panel panel-default"> 
          <div class="panel panel-heading panel-title"> <i class="fa fa-mobile-phone fa-lg fa-fw"></i> <?php echo $lang['Pay_With_SMS']?> </div>
          <div class="panel-body text-center">
            <form role="form">

              <table class="table table-tesponsive table-bordered" style="text-align:left;">
                <tr>
                  <td> <b> <?php echo $lang['User']?> </b> </td>
                  <td> <?php echo $user['full_name']?> </td>
                </tr>
                <tr>
                  <td> <b> <?php echo $lang['Item']?> </b> </td>
                  <td> <?php echo $lang['Energy']?> </td>
                </tr>
              </table>

              <br>

              <a id="fmp-button" href="#" rel="<?php echo $paygol_service_id?>/<?php echo $user['id']?>" class="btn btn-success btn-block"> <?php echo $lang['Submit_Payment']?> </a>

            </form>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</section>
<?php
require('inc/bottom.php');
?>