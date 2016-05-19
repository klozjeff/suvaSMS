<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$page['name'] = $lang['People'];
$menu['home'] = 'active';

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
if(isset($_GET['age_range'])) {
$age_range = $_GET['age_range'];
$age_range = explode(',',$age_range);
$min_age = $age_range[0];
$max_age = $age_range[1];
}
} else {
$sort_by = 'ORDER BY RAND()';
$min_age = 16;
$max_age = 100;
}

if(!isLocalDating($user)) {
  if($user['sexual_interest'] == 1) {
    $users = $db->query("SELECT * FROM users WHERE id != ".$user['id']." AND gender='Male' AND (age >= $min_age AND age <= $max_age) $sort_by LIMIT 8");
  } elseif($user['sexual_interest'] == 2) {
    $users = $db->query("SELECT * FROM users WHERE id != ".$user['id']." AND gender='Female' AND (age >= $min_age AND age <= $max_age) $sort_by LIMIT 8");
  } else {
    $users = $db->query("SELECT * FROM users WHERE id != ".$user['id']." AND (age >= $min_age AND age <= $max_age) $sort_by LIMIT 8");
  }
} else {
  if($user['sexual_interest'] == 1) {
    $users = $db->query("SELECT * FROM users WHERE id != ".$user['id']." AND gender='Male' AND country LIKE '%".$user['country']."%' AND city LIKE '%".$user['city']."%' AND (age >= $min_age AND age <= $max_age) $sort_by LIMIT 8");
  } elseif($user['sexual_interest'] == 2) {
    $users = $db->query("SELECT * FROM users WHERE id != ".$user['id']." AND gender='Female' AND country LIKE '%".$user['country']."%' AND city LIKE '%".$user['city']."%' AND (age >= $min_age AND age <= $max_age) $sort_by LIMIT 8");
  } else {
    $users = $db->query("SELECT * FROM users WHERE id != ".$user['id']." AND country LIKE '%".$user['country']."%' AND city LIKE '%".$user['city']."%' AND (age >= $min_age AND age <= $max_age) $sort_by LIMIT 8");
  }
}

if($user['updated_preferences'] == 0) {
  $page['js'] .= '
  <script>
  swal({ 
    title: "'.$lang['Welcome_Window_Title'].'",
    text: "'.$lang['Welcome_Window_Text'].'",
    imageUrl: "img/salute.png",
    confirmButtonText: "'.$lang['OK'].'",
    confirmButtonColor: "#3a3f51",
    closeOnConfirm: false,
    closeOnCancel: false
  },
  function(){
    window.location.href = "preferences.php";
  });
</script>
';
} else {
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

require('inc/top.php');
?>
<section>
<div class="content-wrapper">
<h3>
<?php echo $lang['Suggestions']?> 
<span class="pull-right"> 
<a class="btn btn-danger" data-toggle="modal" data-target="#filterResults"> <i class="fa fa-sliders fa-fw"></i> <?php echo $lang['Filter']?> </a> 
<?php if(isset($_GET['filter'])) { ?>
<a href="people?filter=true&sort_by=<?php echo $_GET['sort_by']?>&age_range=<?php echo $_GET['age_range']?>" class="btn btn-default"> <i class="fa fa-refresh fa-fw"></i> Refresh </a> 
<?php } else { ?>
<a href="people" class="btn btn-default"> <i class="fa fa-refresh fa-fw"></i> <?php echo $lang['Refresh']?> </a> 
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
    <?php $check = $db->query("SELECT id FROM profile_likes WHERE viewer_id='".$user['id']."' AND profile_id='".$match['id']."' LIMIT 1"); ?>
    <?php if($check->num_rows == 0) { ?>
    <i class="fa fa-heart fa-lg" id="heart-<?php echo $match['id']?>"></i> 
    <?php } else { ?>
    <i class="fa fa-heart fa-lg" id="heart-<?php echo $match['id']?>" style="color:#f05050;"></i> 
    <?php } ?>
  </div>
  <div class="panel-body text-center">
    <a href="profile/<?php echo $match['id']?>" style="color:#656565;text-decoration:none;">
      <img src="<?php echo getProfilePicture($domain,$match)?>" alt="<?php echo $match['full_name']?>" class="center-block img-thumbnail img-circle thumb96"> <br>
      <p style="font-size:17px;"><b> <?php echo $split_name['first_name']?> </b></p>
      <p><?php echo $match['age']?>, <?php echo $lang[$match['gender']]?> </p>
    </div>
    <div class="panel-footer text-center">
      <p>
        <i class="fa fa-map-marker fa-fw"></i>
        <?php 
         if(!isLocalDating($user)) {
          if(empty($match['city'])) { echo $match['country']; } else { echo $match['city'].', '.$match['country']; }
         } else {
         if(!empty($km) && $km <= $local_range[1]) { echo $km.' '.$lang['km_away']; } 
         else { if(empty($match['city'])) { echo $match['country']; } else { echo $match['city'].', '.$match['country']; } }
         }
      ?> </p>
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