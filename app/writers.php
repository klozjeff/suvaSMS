<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$page['name'] = $lang['Writers'];
$menu['writers'] = 'active';

if(isset($_GET['filter'])) {
if(isset($_GET['sort_by'])) {
$sort_by = $_GET['sort_by'];
switch ($sort_by) {
   case 1:
     $sort_by = 'ORDER BY id DESC';
     break;
   case 2:
     $sort_by = 'ORDER BY id ASC';
     break;
   case 3:
     $sort_by = 'ORDER BY last_active DESC';
     break;
   default:
     $sort_by = 'ORDER BY RAND()';
     break;
 } 
}
if(isset($_GET['orders_range'])) {
$orders_range = $_GET['orders_range'];
$orders_range = explode(',',$orders_range);
$min_orders = $orders_range[0];
$max_orders = $orders_range[1];
}
} else {
$sort_by = 'ORDER BY RAND()';
$min_orders = 0;
$max_orders = 1000;
}
$users = $db->query("SELECT users.*,(SELECT COUNT(DISTINCT customer_id) FROM order_details WHERE order_details.customer_id=users.id) as totalOrders FROM users WHERE id != ".$user['id']."  AND ((SELECT COUNT(DISTINCT customer_id) FROM order_details WHERE order_details.customer_id=users.id) >= $min_orders AND (SELECT COUNT(DISTINCT customer_id) FROM order_details WHERE order_details.customer_id=users.id) <= $max_orders) $sort_by LIMIT 8");

require('inc/top.php');
?>
<section>
<div class="content-wrapper">
<h3>
<?php echo $lang['Suggestions']?> 
<span class="pull-right"> 
<a class="btn btn-danger" data-toggle="modal" data-target="#filterResults"> <i class="fa fa-sliders fa-fw"></i> <?php echo $lang['Filter']?> </a> 
<?php if(isset($_GET['filter'])) { ?>
<a href="writers?filter=true&sort_by=<?php echo $_GET['sort_by']?>&orders_range=<?php echo $_GET['orders_range']?>" class="btn btn-default"> <i class="fa fa-refresh fa-fw"></i> Refresh </a> 
<?php } else { ?>
<a href="writers" class="btn btn-default"> <i class="fa fa-refresh fa-fw"></i> <?php echo $lang['Refresh']?> </a> 
<?php } ?>
</span> 
</h3>
<div class="container-fluid">
<div class="row">
<?php
$cc=0;
while($match = $users->fetch_array()) { 
$cc++;
$km = round(coordsToKm($user['latitude'],$user['longitude'],$match['latitude'],$match['longitude']));
$local_range = explode(',',$user['local_range']);
$split_name = splitName($match['full_name']);
?>
<div class="col-md-3" id="userWidget">
<div class="panel widget">
  <div class="panel-heading panel-title" onclick="clickHeart(<?php echo $match['id']?>)" id="user-<?php echo $match['id']?>">
    <?php if($match['totalOrders']== 0) { ?>
    <i class="fa fa-heart fa-lg" id="heart-<?php echo $match['id']?>"></i> 
    <?php } else { ?>
    <i class="fa fa-heart fa-lg" id="heart-<?php echo $match['id']?>" style="color:#f05050;"></i> 
    <?php } ?>
  </div>
  <div class="panel-body text-center">
    <a href="profile/<?php echo $match['id']?>" style="color:#656565;text-decoration:none;">
      <img src="<?php echo getProfilePicture($domain,$match)?>" alt="<?php echo $match['full_name']?>" class="center-block img-thumbnail img-circle thumb96"> <br>
      <p style="font-size:17px;"><b> <?php echo $split_name['first_name']?> </b></p>
     <!-- <p>Orders Completed:<?php echo $match['totalOrders']?><br/>Specialization:<?php echo $lang[$match['specialization']]?> </p>-->
  

  </div>
    <div class="panel-footer text-center">
      <p>
      <div title="Customers rating of the order: 10" data-rating-value="9.9" class="rating_view right ">
				<meta content="1" itemprop="worstRating">
				<meta content="10" itemprop="ratingValue">
				<meta content="10" itemprop="bestRating">
				<div class="realrate r100"></div>
			</div>
     </p>
    </div>
  </div>
</div>
</a>
<?php if($cc >= 4) { echo '</div> <div class="row">'; $cc=0; } } ?>
<?php if($users->num_rows == 0) { echo '<p style="padding-left:10px;">'.$lang['No_Suggestions_Found'].'</p>'; } ?> 
</div>
</div>
</div>
</section>
<?php
require('inc/bottom.php'); 
?>