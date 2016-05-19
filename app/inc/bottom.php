<!-- Page footer-->
<footer>
  <span>&copy; <?php echo date("Y")?> - <?php echo $site_name?></span>
  <span class="pull-right">
    <?php $c=0; while($_page = $_pages->fetch_array()) {
      $c++;
      if($c < $_pages->num_rows) {
        echo '<a href="'.$domain.'/app/page/'.$_page['id'].'" style="color:#656565;">'.$_page['page_title'].'</a> - ';
      } else {
        echo '<a href="'.$domain.'/app/page/'.$_page['id'].'" style="color:#656565;">'.$_page['page_title'].'</a>';  
      }
    }
    ?>
  </span>
</footer>
</div>
<script src="<?php echo $domain;?>/vendor/modernizr/modernizr.js"></script>
<script src="<?php echo $domain;?>/vendor/jquery/dist/jquery.js"></script>
<script src="<?php echo $domain;?>/vendor/bootstrap/dist/js/bootstrap.js"></script>
<script src="<?php echo $domain;?>/vendor/jQuery-Storage-API/jquery.storageapi.js"></script>
<script src="<?php echo $domain;?>/vendor/animo.js/animo.js"></script>
<script src="<?php echo $domain;?>/vendor/sweetalert/dist/sweetalert.min.js"></script>

<script src="<?php echo $domain;?>/app/js/app.js"></script>
<?php require('pre.js.php'); ?>
<?php echo $page['js']; ?>
<?php if(basename($_SERVER['PHP_SELF']) == 'upgrades.php') { ?> 
<form action="" method="POST" id="submitPaymentForm">
  <div class="modal fade" id="submitPayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Reserve Funds</h4>
        </div>
        <div class="modal-body">
          <div class="payment-errors text-danger"></div>
          <div id="energy">
            <?php echo $lang['Funds']?> <br>
            <input name="energy_amount" class="form-control">
             <br>
          </div>
          <?php echo $lang['Payment_Method']?> <br>
          <select name="payment_method" id="payment_method" class="form-control" onchange="changemethod()">
            <option value="paypal"> PayPal </option>
         <!--   <option value="stripe"> <?php echo $lang['Credit_Card']?> </option>
            <option value="sms"> SMS </option>-->
          </select>
        </div>
        <div class="modal-footer" id="formFooter">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']?></button>
          <button type="submit" name="continue" class="btn btn-danger"><?php echo $lang['Continue']?></button>
        </div>
      </div>
    </div>
  </div>
</form>
<? } ?>
<?php if(basename($_SERVER['PHP_SELF']) == 'home.php') { ?> 
<div class="modal fade" id="filterResults" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <form action="" method="GET">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Filter Orders</h4>
      </div>
      <div class="modal-body">
      <input type="hidden" name="filter" value="true">
      <?php echo $lang['Sort_By']?>:
      <select name="sort_by" class="form-control">
      <option value="1" <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 1) { echo 'selected'; } ?>> Newest first </option> 
      <option value="2" <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 2) { echo 'selected'; } ?>> Oldest first </option>
      <option value="3" <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 3) { echo 'selected'; } ?>> Last online </option> 
      </select>
      <br>
     Page Range:
      <br>
      <input data-ui-slider="" name="page_range" type="text" value="[<?php if(isset($_GET['page_range'])) { echo $_GET['page_range']; } else { echo '1,100'; } ?>]" data-slider-min="1" data-slider-max="100" data-slider-step="1" data-slider-value="[<?php if(isset($_GET['page_range'])) { echo $_GET['page_range']; } else { echo '1,100'; } ?>]" class="slider">
      <br><br>
      <!--<i class="fa fa-info-circle fa-fw"></i> You can set your <b>country, city</b> and <b>sexual</b> interest from your <a href="preferences.php" style="color:#000;text-decoration:underline;"> Preferences </a>-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']?></button>
        <button type="submit" class="btn btn-danger"><?php echo $lang['Filter']?></button>
      </div>
    </div>
  </form>
  </div>
</div>
<script src="<?php echo $domain;?>/vendor/seiyria-bootstrap-slider/dist/bootstrap-slider.min.js"></script>
<script>$("[data-ui-slider]").slider();</script>
<link rel="stylesheet" href="<?php echo $domain;?>/vendor/seiyria-bootstrap-slider/dist/css/bootstrap-slider.min.css">
<?php } ?>

<?php if(basename($_SERVER['PHP_SELF']) == 'writers.php') { ?> 
<div class="modal fade" id="filterResults" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <form action="" method="GET">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Filter Writers</h4>
      </div>
      <div class="modal-body">
      <input type="hidden" name="filter" value="true">
      <?php echo $lang['Sort_By']?>:
      <select name="sort_by" class="form-control">
      <option value="1" <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 1) { echo 'selected'; } ?>> Newest first </option> 
      <option value="2" <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 2) { echo 'selected'; } ?>> Oldest first </option>
      <option value="3" <?php if(isset($_GET['sort_by']) && $_GET['sort_by'] == 3) { echo 'selected'; } ?>> Last online </option> 
      </select>
      <br>
    Orders Completed Range:
      <br>
      <input data-ui-slider="" name="orders_range" type="text" value="[<?php if(isset($_GET['orders_range'])) { echo $_GET['orders_range']; } else { echo '0,1000'; } ?>]" data-slider-min="0" data-slider-max="1000" data-slider-step="1" data-slider-value="[<?php if(isset($_GET['orders_range'])) { echo $_GET['orders_range']; } else { echo '1,100'; } ?>]" class="slider">
      <br><br>
      <!--<i class="fa fa-info-circle fa-fw"></i> You can set your <b>country, city</b> and <b>sexual</b> interest from your <a href="preferences.php" style="color:#000;text-decoration:underline;"> Preferences </a>-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']?></button>
        <button type="submit" class="btn btn-danger"><?php echo $lang['Filter']?></button>
      </div>
    </div>
  </form>
  </div>
</div>
<script src="<?php echo $domain;?>/vendor/seiyria-bootstrap-slider/dist/bootstrap-slider.min.js"></script>
<script>$("[data-ui-slider]").slider();</script>
<link rel="stylesheet" href="<?php echo $domain;?>/vendor/seiyria-bootstrap-slider/dist/css/bootstrap-slider.min.css">
<?php } ?>

<?php if(basename($_SERVER['PHP_SELF']) == 'chat.php' || basename($_SERVER['PHP_SELF']) == 'order-details.php' || basename($_SERVER['PHP_SELF']) == 'profile.php') { ?> 
<div class="modal fade" id="emoticonList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <form action="" method="post">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $lang['Emoticons']?></h4>
      </div>
      <div class="modal-body">
      <img src="<?php echo $domain;?>/app/img/emoticons/Smile.png" class="emoticon" onclick="appendToMessage(':)')">
      <img src="<?php echo $domain;?>/app/img/emoticons/Wink.png" class="emoticon" onclick="appendToMessage(';)')">
      <img src="<?php echo $domain;?>/app/img/emoticons/Laughing.png" class="emoticon" onclick="appendToMessage(':D')">
      <img src="<?php echo $domain;?>/app/img/emoticons/Heart.png" class="emoticon" onclick="appendToMessage('<3')">
      <img src="<?php echo $domain;?>/app/img/emoticons/Crazy.png" class="emoticon" onclick="appendToMessage(':P')">
      <img src="<?php echo $domain;?>/app/img/emoticons/Money-Mouth.png" class="emoticon" onclick="appendToMessage(':$')">
      <img src="<?php echo $domain;?>/app/img/emoticons/Kiss.png" class="emoticon" onclick="appendToMessage(':*')">
      <img src="<?php echo $domain;?>/app/img/emoticons/Frown.png" class="emoticon" onclick="appendToMessage(':(')">
      <img src="<?php echo $domain;?>/app/img/emoticons/Sealed.png" class="emoticon" onclick="appendToMessage(':X')">
      <img src="<?php echo $domain;?>/app/img/emoticons/Cool.png" class="emoticon" onclick="appendToMessage('8|')">
      <img src="<?php echo $domain;?>/app/img/emoticons/Thumbs-Up.png" class="emoticon" onclick="appendToMessage('(y)')">
      <img src="<?php echo $domain;?>/app/img/emoticons/Innocent.png" class="emoticon" onclick="appendToMessage('O:]')">
      <img src="<?php echo $domain;?>/app/img/emoticons/Gasp.png" class="emoticon" onclick="appendToMessage(':O')">
      <img src="<?php echo $domain;?>/app/img/emoticons/Naughty.png" class="emoticon" onclick="appendToMessage('3:]')">
      <img src="<?php echo $domain;?>/app/img/emoticons/Nerd.png" class="emoticon" onclick="appendToMessage('8-)')">
      <img src="<?php echo $domain;?>/app/img/emoticons/HeartEyes.png" class="emoticon" onclick="appendToMessage('V_V')">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']?></button>
      </div>
    </div>
  </form>
  </div>
</div>
<?php } ?>
<?php if(basename($_SERVER['PHP_SELF']) == 'profile.php' || basename($_SERVER['PHP_SELF']) == 'chat.php') { 
if($user['energy'] >= 50) {
$send_gift = ''; 
} else {
$send_gift = 'disabled';  
}
?> 
<div class="modal fade" id="sendGift" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <form action="" method="post">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $lang['Send_Gift']?></h4>
      </div>
      <div class="modal-body">
      <?php if($send_gift == 'disabled') { ?> <div class="alert alert-danger"> <i class="fa fa-warning fa-fw"></i> <a href="upgrades" style="color:#fff;"> You need at least <b>50 Energy</b> to send a gift </a> </div> <?php } else { ?>
      <div class="alert alert-warning"> <i class="fa fa-info-circle fa-fw"></i> <a href="upgrades" style="color:#fff;"> Each gift costs <b>50 Energy</b> </a> </div>
      <? } ?>
      <div id="giftSelection" data-height="200" data-scrollable="" >
      <div class="col-md-12">
      <div class="row">
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/1.png" id="gift1" class="giftImage img-responsive" onclick="selectGift(1)"></div>
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/2.png" id="gift2" class="giftImage img-responsive" onclick="selectGift(2)"></div>
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/3.png" id="gift3" class="giftImage img-responsive" onclick="selectGift(3)"></div>
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/4.png" id="gift4" class="giftImage img-responsive" onclick="selectGift(4)"></div>
      </div>
      <div class="row">
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/5.png" id="gift5" class="giftImage img-responsive" onclick="selectGift(5)"></div>
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/6.png" id="gift6" class="giftImage img-responsive" onclick="selectGift(6)"></div>
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/7.png" id="gift7" class="giftImage img-responsive" onclick="selectGift(7)"></div>
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/8.png" id="gift8" class="giftImage img-responsive" onclick="selectGift(8)"></div>
      </div>
      <div class="row">
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/9.png" id="gift9" class="giftImage img-responsive" onclick="selectGift(9)"></div>
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/10.png" id="gift10" class="giftImage img-responsive" onclick="selectGift(10)"></div>
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/11.png" id="gift11" class="giftImage img-responsive" onclick="selectGift(11)"></div>
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/12.png" id="gift12" class="giftImage img-responsive" onclick="selectGift(12)"></div>
      </div>
      <div class="row">
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/13.png" id="gift13" class="giftImage img-responsive" onclick="selectGift(13)"></div>
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/14.png" id="gift14" class="giftImage img-responsive" onclick="selectGift(14)"></div>
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/15.png" id="gift15" class="giftImage img-responsive" onclick="selectGift(15)"></div>
      <div class="col-md-3"><img src="<?php echo $domain;?>/app/img/gifts/16.png" id="gift16" class="giftImage img-responsive" onclick="selectGift(16)"></div>
      </div>
      </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']?></button>
        <button type="submit" name="send_gift" class="btn btn-danger" <?php echo $send_gift?>><?php echo $lang['Continue']?></button>
      </div>
    </div>
    <input type="hidden" id="giftValue" name="giftValue">
  </form>
  </div>
</div>
<?php if(isset($_GET['gift'])) { ?>
<script type="text/javascript">
$(window).load(function(){
    $('#sendGift').modal('show');
});
</script>
<?php } ?>
<div class="modal fade" id="reportUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <form action="" method="post">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo sprintf($lang['Report_User'],$split_name['first_name'])?>
      </div>
      <div class="modal-body">
      <textarea name="reason" class="form-control" placeholder="<?php echo $lang['Report_Reason']?>"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']?></button>
        <button type="submit" name="report_user" class="btn btn-danger"><?php echo $lang['Report']?></button>
      </div>
    </div>
  </form>
  </div>
</div>
<?php } ?>


<div class="modal fade" id="uploadFile<?php echo $id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<form action="" method="post" role="form" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Upload File</h4>
      </div>
      <div class="modal-body">
	  
	  	<div class="panel-body">
  <div class="form-group has-feedback">
					<label>Order File</label>
					<input type="file" name="order_file" data-classbutton="btn btn-default" data-classinput="form-control inline" class="form-control filestyle">
				<input type="hidden" name="order_id" value="<?php echo $id;?>" data-classbutton="btn btn-default" data-classinput="form-control inline" class="form-control filestyle">
				
				</div>
   
      </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']?></button>
        <button type="submit" name="upload_file" class="btn btn-danger" <?php echo $send_gift?>>Upload</button>
      </div>
    </div>
    <input type="hidden" id="giftValue" name="giftValue">
  </form>
  </div>
</div>


<div class="modal fade" id="releasePayment<?php echo $id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<form action="" method="post" role="form" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Release Payment</h4>
      </div>
	  <?php 
	  
	  $orders_price = $db->query("SELECT * FROM order_price WHERE order_id='".$id."'");
	  $value=$orders_price->fetch_array();
	  ?>
      <div class="modal-body">
	  
	  	<div class="panel-body">
  <div class="form-group has-feedback">
					<label>Amount to Release($)</label>
					<input type="text" name="order_pay"  data-classinput="form-control inline" class="form-control" value="<?php echo $value['balance'];?>">
					<input type="hidden" name="order_price"  data-classinput="form-control inline" class="form-control" value="<?php echo $value['balance'];?>">
				<input type="hidden" name="order_id" value="<?php echo $id;?>" data-classbutton="btn btn-default" data-classinput="form-control inline" class="form-control filestyle">
				
				</div>
   
      </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']?></button>
        <button type="submit" name="release_payment" class="btn btn-danger">Release Payment</button>
      </div>
    </div>
    <input type="hidden" id="giftValue" name="giftValue">
  </form>
  </div>
</div>
<?php if(basename($_SERVER['PHP_SELF']) == 'list_contacts.php') { ?> 
<div class="modal fade" id="newContact<?php echo $id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<form action="" method="post" role="form" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add New Contact </h4>
      </div>
      <div class="modal-body">
	  
	  	<div class="panel-body">
		<div class="col-md-5">
<div class="row">
  <div class="form-group has-feedback">
					<label>First Name</label>
					 <input name="first_name" class="form-control">
					 
 
				
				</div>
				
				  <div class="form-group has-feedback">
					<label>Phone No</label>
					 <input name="phone_no" class="form-control">
					 
				
				</div>
				
				  <div class="form-group has-feedback">
					<label>Custom Field 1</label>
					 <input name="custom_field1" class="form-control">
					 
				
				</div>
				
				 <div class="form-group has-feedback">
					<label>Custom Field 3</label>
					 <input name="custom_field3" class="form-control">
					 
				
				</div>
				 <div class="form-group has-feedback">
					<label>Custom Field 5</label>
					 <input name="custom_field5" class="form-control">
					 
				
				</div>
				</div>
				</div>
				<div class="col-md-1">
				</div>
				<div class="col-md-6">
<div class="row">
  <div class="form-group has-feedback">
						<label>Last Name</label>
					 <input name="last_name" class="form-control">
				
				</div>
				  <div class="form-group has-feedback">
					<label>Email Address</label>
					 <input name="email" class="form-control">
					 
				
				</div>
				 <div class="form-group has-feedback">
					<label>Custom Field 2</label>
					 <input name="custom_field2" class="form-control">
					 
				
				</div>
				
				 <div class="form-group has-feedback">
					<label>Custom Field 4</label>
					 <input name="custom_field4" class="form-control">
					 
				
				</div>
				
				 <div class="form-group has-feedback">
					<label>Status</label>
					  <select name="status" class="form-control">
      <?php foreach($status as $statuss) { 
      
          echo '<option value="'.$statuss.'">'.$statuss.'</option>';
        }
       ?>
    </select>
				
				</div>
				</div>
				</div>
   
      </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']?></button>
        <button type="submit" name="add_contact" class="btn btn-success">Add Contact</button>
      </div>
    </div>
   
  </form>
  </div>
</div>


<div class="modal fade" id="importContact<?php echo $id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<form action="" method="post" role="form" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Import CSV File</h4>
      </div>
      <div class="modal-body">
	  
	  	<div class="panel-body">
		 <div class="form-group has-feedback">
					<label>Download CSV Template <a href="../uploads/template.csv">here</a></label>
					</div>
  <div class="form-group has-feedback">
					<label>Contacts File</label>
					<input type="file" name="contact_file" data-classbutton="btn btn-default" data-classinput="form-control inline" class="form-control filestyle">
				<input type="hidden" name="list_id" value="<?php echo $id;?>" data-classbutton="btn btn-default" data-classinput="form-control inline" class="form-control filestyle">
				
				</div>
   
      </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']?></button>
        <button type="submit" name="import_contact" class="btn btn-success">Upload</button>
      </div>
    </div>
   
  </form>
  </div>
</div>
<?php
}
?>
<div class="modal fade" id="requestWriter<?php echo $profile['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<form action="" method="post" role="form" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Request Writer</h4>
      </div>
      <div class="modal-body">
	  
	  	<div class="panel-body">
  <div class="form-group has-feedback">
					<label>Select Order to Request</label>
					 <select name="order_id" class="form-control">
					 <option value="">Select Order</option>
					<?php
		$orders_select = $db->query("SELECT * FROM order_details WHERE customer_id='".$user['id']."' ORDER BY id DESC LIMIT 20");

   foreach($orders_select as $special) { 
        
          echo '<option value="'.$special['id'].'">#'.$special['unique_no'].'-'.$special['order_topic'].'</option>';
        
      } ?>
    </select>
				
				
				<input type="hidden" name="writer_id" value="<?php echo $profile['id'];?>" data-classbutton="btn btn-default" data-classinput="form-control inline" class="form-control filestyle">
				
				</div>
   
      </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']?></button>
        <button type="submit" name="request_writer" class="btn btn-danger">Request Writer</button>
      </div>
    </div>
    <input type="hidden" id="giftValue" name="giftValue">
  </form>
  </div>
</div>

<?php if(basename($_SERVER['PHP_SELF']) == 'list.php') { ?> 
<div class="modal fade" id="newList" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<form action="" method="post" role="form" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">New List</h4>
      </div>
      <div class="modal-body">
	  
	  	<div class="panel-body">
		<div class="col-md-5">
<div class="row">
  <div class="form-group has-feedback">
					<label>Name</label>
					 <input name="list_name" class="form-control">
					 
 
				
				</div>
				
				  <div class="form-group has-feedback">
					<label>Source</label>
					  <select name="list_source" class="form-control">
      <?php foreach($source as $sources) { 
      
          echo '<option value="'.$sources.'">'.$sources.'</option>';
        }
       ?>
    </select>
				
				</div>
				</div>
				</div>
				<div class="col-md-1">
				</div>
				<div class="col-md-6">
<div class="row">
  <div class="form-group has-feedback">
					<label>Description</label>
					 <textarea name="list_description" class="form-control">
					 
</textarea> 
				
				</div>
				
				
				</div>
				</div>
   
      </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']?></button>
        <button type="submit" name="create_list" class="btn btn-success">Create</button>
      </div>
    </div>
 
  </form>
  </div>
</div>
<?php
}

 if(basename($_SERVER['PHP_SELF']) == 'templates.php') { ?> 
<div class="modal fade" id="newTemp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog" role="document">
	<form action="" method="post" role="form" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">New Template</h4>
      </div>
      <div class="modal-body">
	  
	  	<div class="panel-body">
		<div class="col-md-12">
<div class="row">
  <div class="form-group has-feedback">
					<label>Name</label>
					 <input name="temp_name" value="" class="form-control">
					 
 
				
				</div>
				
				
				</div>
				
<div class="row">
  <div class="form-group has-feedback">
					<label>Description</label>
					 <textarea name="new_template" class="form-control">
					 
</textarea> 
				
				</div>
				
				
				</div>
				</div>
   
      </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']?></button>
        <button type="submit" name="create_temp" class="btn btn-success">Save</button>
      </div>
    </div>
 
  </form>
  </div>
</div>


<?php
}
?>
<div class="modal fade" id="newGroup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<form action="" method="post" role="form" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">New Campaign Group</h4>
      </div>
      <div class="modal-body">
	  
	  	<div class="panel-body">
		<div class="col-md-12">
<div class="row">
  <div class="form-group has-feedback">
					<label>Name</label>
					 <input name="group_name" class="form-control">
					 
 
				
				</div>
				
				  <div class="form-group has-feedback">
					<label>Description</label>
					 <textarea name="group_description" class="form-control">
					 
</textarea> 
				
				</div>
				</div>
				</div>
			
      </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']?></button>
        <button type="submit" name="create_group" class="btn btn-success">Create</button>
      </div>
    </div>
 
  </form>
  </div>
</div>
</body>
</html>
<?php
if(isset($_POST['create_group'])) 
{ 
$group_name = $_POST['group_name'];

$group_description= $_POST['group_description'];
$unique_id=rand(1,99999);
$user_id=$user['unique_id'];
$now=time();
$create=$db->query("INSERT INTO campaign_group(group_uid,customer_id,name,description,date_added) VALUES('".$unique_id."','".$user_id."','".$group_name."','".$group_description."','".$now."')");
$listid=$db->insert_id;
 if($create) {

    $page['js'] .= '
    <script>
    swal({ 
      title: "'.$lang['Ready_Set_Go'].'",
      text: "'.$lang['Updated_List_Success'].'",
      imageUrl: "'.$domain.'/app/img/thumbs-up.png",
      confirmButtonText: "Ok",
      confirmButtonColor: "#3a3f51",
      closeOnConfirm: false,
      closeOnCancel: false
    },
    function(){
      window.location.href = "";
    });
</script>
';
 }
 else
 { 
 $page['js'] .= '
  <script>
  function clickHeart(id) {
    heart = $("#heart-"+id);
    $.get("ajax/clickHeart.php?id="+id, function(data) {
      $("#user-"+id).html(data);
    });
    heart.css("color", "#f05050");
    heart.animo( { animation: ["tada"], duration: 2 } );
  }
  </script>
';	 
 }

}
?>