<?php

if(isset($_GET['mobileapp'])) {
   $_SESSION['mobileapp'] = true;
} 

if(isset($_SESSION['mobileapp'])) {
   $mobileapp = true;
} else {
   $mobileapp = false;
}

if(!isLogged()) {
header("Location: login.php");
exit;
}

// Update user activity
$db->query("UPDATE users SET last_active='".time()."' WHERE id='".$user['id']."'");

$notifications=$db->query("SELECT * FROM notifications WHERE receiver_id='".$user['id']."' ORDER BY is_read ASC, id DESC LIMIT 4");
$ncount = $notifications->num_rows;
$ucount = $db->query("SELECT id FROM notifications WHERE receiver_id='".$user['id']."' AND is_read='0'")->num_rows;
$_pages = $db->query("SELECT * FROM pages ORDER BY id ASC");

$chat_friends = $db->query("SELECT * FROM friend_requests WHERE accepted='1' AND (user1='".$user['id']."' OR user2='".$user['id']."') ORDER BY RAND() LIMIT 10");

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="description" content="<?php echo $meta['description']?>">
<meta name="keywords" content="<?php echo $meta['keywords']?>">
<meta charset="utf-8">
<title><?php echo $page['name']?></title>
<link rel="stylesheet" href="<?php echo $domain;?>/vendor/fontawesome/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo $domain;?>/vendor/simple-line-icons/css/simple-line-icons.css">
<link rel="stylesheet" href="<?php echo $domain;?>/vendor/animate.css/animate.min.css">
<link rel="stylesheet" href="<?php echo $domain;?>/vendor/loaders.css/loaders.css">
<link rel="stylesheet" href="<?php echo $domain;?>/app/css/bootstrap.css" id="bscss">
<link rel="stylesheet" href="<?php echo $domain;?>/vendor/sweetalert/dist/sweetalert.css">
<link rel="stylesheet" href="<?php echo $domain;?>/app/css/app.css" id="maincss">
<!--<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ selector:'textarea' });</script>
-->
<?php 

if($theme == 'default') { echo '<link rel="stylesheet" href="'.$domain.'/app/css/theme-red.css">'; } 
else { echo '<link rel="stylesheet" href="'.$domain.'/themes/'.$theme.'/theme.css">'; }
?>
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,600,700' rel='stylesheet' type='text/css'>
<?php echo $page['css']; ?>
<!--<link rel="stylesheet" href="css/custom.css">-->
<?php if($mobileapp == true) { echo '<style> section { margin-left: 0px !important; } </style>'; } ?>
<script>
function changemethod() {
var payment_method = $('#payment_method').val();
var energy_amount = $('#energy');
if(payment_method == 'sms') {
energy_amount.hide();
} else {
energy_amount.show(); 
}
}
function selectGift(id) {
for (var i = 1; i <= 16; i++) {
if(id != i) {
$('#gift'+i).css('background','none');
} else {
$('#gift'+i).css('background','#e5e5e5'); 
}
};
$('#giftValue').val(id);
}
</script>
</head>

<body class="layout-<?php echo $layout?>">
<div class="wrapper">
<!-- top navbar-->
<header class="topnavbar-wrapper">
<!-- START Top Navbar-->
<nav role="navigation" class="navbar topnavbar">
<!-- START navbar header-->
<div class="navbar-header" <?php if($mobileapp == true) { echo 'style="display:none !important;"'; } ?>>
<a href="<?php echo $domain;?>/app/campaigns" class="navbar-brand">
<div class="brand-logo" style="height:20px">
<img src="<?php echo $domain;?>/app/img/logo.png" alt="Logo" class="img-responsive">
</div>
<div class="brand-logo-collapsed">
</div>
</a>
</div>
<!-- END navbar header-->
<!-- START Nav wrapper-->
<div class="nav-wrapper">
<!-- START Left navbar-->
<ul class="nav navbar-nav">
<li>
<!-- Button to show/hide the sidebar on mobile. Visible on mobile only.-->
<a href="#" data-toggle-state="aside-toggled" data-no-persist="true" class="visible-xs sidebar-toggle" <?php if($mobileapp == true) { echo 'style="display:none !important;"'; } ?>>
<em class="fa fa-navicon"></em>
</a>
</li>
</ul>
<!-- END Left navbar-->
<!-- START Right Navbar-->
<ul class="nav navbar-nav navbar-right" >
<li>
<a href="<?php echo $domain;?>/app/upgrades">SMS Balance: KES <?php echo $user['energy']?> </a>
</li>
<?php if($user['is_admin'] == 1 && $mobileapp == false) { ?>
<li>
<a href="<?php echo $domain;?>/app/admin/index.php"> <i class="icon-users"></i> &nbsp Admin </a>
</li>
<?php } ?>

<!--Language selector-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
<li class="dropdown dropdown-list">
<a href="#" data-toggle="dropdown">
<span class="lang-selected">
   <img class="lang-flag" src="<?php echo $icon?>">
   <!--<span class="lang-name"></span>-->
</span>
</a>

<!--Language selector menu
<ul class="dropdown-menu animated flipInX">
   </li>
   <?php
   $lang_dir = scandir("languages");
   $c = 0;
   foreach($lang_dir as $file) { 
   if($file != $language) {
   if(file_exists("languages/".$file."/language.php")) {
   $c++;
   ?>
   <li>
      <!--English
      <a href="?set-lang=<?php echo $file?>" style="padding-bottom:10px;padding-top:10px;">
         <img class="lang-flag" src="languages/<?php echo $file?>/icon.png">
         <span class="lang-name"><?php echo mb_convert_case($file, MB_CASE_TITLE, 'UTF-8')?></span>
      </a>
   </li>
   <?php } } } if($c == 0) { echo '<div style="padding:10px;font-style:500;"> No other languages are available </div>'; } ?>
</ul>
</li>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End language selector-->

<!-- START Alert menu-->
<li class="dropdown dropdown-list" onclick="readNotifications(<?php echo $user['id']?>)">
<a href="#" data-toggle="dropdown">
<em class="icon-bell"></em>
<div class="label label-danger" id="notifications_number"><?php echo $ucount?></div>
</a>

<!-- START Dropdown menu-->
<ul class="dropdown-menu animated flipInX">
<li>
<!-- START list group-->
<div class="list-group">
<?php if($ncount >= 1) { ?>
<?php while($notification = $notifications->fetch_array()) { ?>
<!-- list item-->
<a href="<?php if($notification['url'] != '#') { echo $domain.'/'.$notification['url']; } else { echo '#'; } ?>" class="list-group-item">
<div class="media-box">
<div class="pull-left">
   <em class="<?php echo $notification['icon']?> fa-2x"></em>
</div>
<div class="media-box-body clearfix">
   <p class="m0"><?php echo $notification['content']?></p>
   <p class="m0 text-muted">
      <small><?php echo time_ago($notification['time'])?></small>
   </p>
</div>
</div>
</a>
<?php } ?>
<?php } else { ?> <p style="padding-top:15px;padding-left:15px;padding-right:15px;padding-bottom:5px;"> <?php echo $lang['No_New_Notifications']?> </p><?php } ?>
<?php if($ucount >= 4) { ?>
<!-- last list item -->
<a href="#" class="list-group-item">
<small><?php echo $lang['More_Notifications']?></small>
<span class="label label-danger pull-right"><?php echo $ucount-4?></span>
</a>
<?php } ?>
</div>
<!-- END list group-->
</li>
</ul>
<!-- END Dropdown menu-->
</li>
<!-- END Alert menu-->
<li>
<a href="<?php echo $domain;?>/app/logout.php">
<em class="icon-logout"></em>
</a>
</li>
<!-- START Offsidebar button-->
<?php if($mobileapp == false) { ?>
<li>
<a href="#" data-toggle-state="offsidebar-open" data-no-persist="true">
<em class="icon-bubbles"></em>
</a>
</li>
<?php } ?>
<!-- END Offsidebar menu-->
</ul>
<!-- END Right Navbar-->
</div>
<!-- END Nav wrapper-->
</nav>
<!-- END Top Navbar-->
</header>
<!-- sidebar-->
<aside class="aside" <?php if($mobileapp == true) { echo 'style="display:none;"'; } ?>>
<!-- START Sidebar (left)-->
<div class="aside-inner">
<nav data-sidebar-anyclick-close="" class="sidebar">
<!-- START sidebar nav-->
<ul class="nav">
<!-- START user info-->
<li class="has-user-block">
<div id="user-block" class="collapse in" aria-expanded="true">
<div class="item user-block">
<!-- User picture-->
<div class="user-block-picture">
<div class="user-block-status">
<a href="<?php echo $domain;?>/app/profile/<?php echo $user['id']?>">
<img src="<?php echo $domain;?>/app/<?php echo getProfilePicture($domain,$user)?>" width="60" height="60" class="img-thumbnail img-circle user-picture">
</a>
<div class="circle circle-success circle-lg"></div>
</div>
</div>
<!-- Name and Job-->
<div class="user-block-info">
<span class="user-block-name"><?php echo $lang['Hello']?>, <?php echo $first_name?></span>
<span class="user-block-role"><?php echo $lang['Logged_In']?> <?php echo time_ago($user['last_login'])?> </span>
</div>
</div>
</div>
</li>

<li class="<?php echo $menu['home']?>">
<a href="<?php echo $domain;?>/app/campaigns">
<em class="fa fa-book"></em>
<span><?php echo $lang['Campaign']?></span>
</a>
</li>
<?php if($user['is_admin'] == 1) { ?>
<li class="<?php echo $menu['list']?>">
<a href="<?php echo $domain;?>/app/list">
<em class="icon-users"></em>
<span><?php echo $lang['List']?>/<?php echo $lang['Leads']?></span>
</a>
</li>
<?php }?>

<li class="<?php echo $menu['template']?>">
<a href="<?php echo $domain;?>/app/templates">
<em class="icon-layers"></em>
<span><?php echo $lang["Templates"]?></span>
</a>
</li>
<li class="<?php echo $menu['chat']?>">
<a href="<?php echo $domain;?>/app/chats">
<em class="icon-picture"></em>
<span><?php echo $lang['Report']?></span>
</a>
</li>
<!--
<li class="<?php echo $menu['friends']?>">
<a href="my-friends">
<em class="icon-emoticon-smile"></em>
<span><?php echo $lang['Friends']?></span>
</a>
</li>

<li class="<?php echo $menu['profile_views']?>">
<a href="profile-views">
<em class="icon-eye"></em>
<span><?php echo $lang['Profile_Views']?></span>
</a>
</li>

<li class="<?php echo $menu['profile_likes']?>">
<a href="profile-likes">
<em class="icon-heart"></em>
<span><?php echo $lang['Profile_Likes']?></span>
</a>
</li>

<li class="<?php echo $menu['hot-list']?>">
<a href="hot-list">
<em class="icon-fire"></em>
<span><?php echo $lang['Hot_List']?></span>
</a>
</li>
-->
<li class="<?php echo $menu['upgrades']?>">
<a href="<?php echo $domain;?>/app/upgrades">

<em class="icon-bubbles"></em>
<span><?php echo $lang["Topup"]?></span>

</a>
</li>

<li class="<?php echo $menu['preferences']?>">
<a href="<?php echo $domain;?>/app/preferences">
<em class="icon-magic-wand"></em>
<span><?php echo $lang['Preferences']?></span>
</a>
</li>

<li class="<?php echo $menu['settings']?>">
<a href="<?php echo $domain;?>/app/settings">
<em class="icon-settings"></em>
<span><?php echo $lang['Settings']?></span>
</a>
</li>

</ul>
<!-- END sidebar nav-->
</nav>
</div>
<!-- END Sidebar (left)-->
</aside>
<!-- offsidebar-->
<aside class="offsidebar hide">
<!-- START Off Sidebar (right)-->
<nav>
<div role="tabpanel">
<!-- Nav tabs-->
<ul role="tablist" class="nav nav-tabs nav-justified">

</ul>
<!-- Tab panes-->
<div class="tab-content">
<div id="app-chat" role="tabpanel" class="tab-pane fade active in">
<h3 class="text-center text-thin"><?php echo $lang['Chat']?></h3>
<ul class="nav">
<!-- START list title-->
<li class="p">
<small class="text-muted">My Conversations</small>
</li>
<!-- END list title-->
<li>
<?php if($chat_friends->num_rows >= 1) { ?>
<?php while($chat_friend = $chat_friends->fetch_array()) {  ?>
<?php $chat_fr = $db->query("SELECT id,full_name,profile_picture,country,city,last_active FROM users WHERE (id='".$chat_friend['user1']."' OR id='".$chat_friend['user2']."') AND id != '".$user['id']."'")->fetch_array(); ?>
<!-- START User status-->
<a href="chat/<?php echo $chat_fr['id']?>" class="media-box p mt0" id="match-1">
<span class="pull-right">
<?php if(time()-$chat_fr['last_active'] <= 300) { ?> <span class="circle circle-success circle-lg"></span> <?php } else { ?> <span class="circle circle-danger circle-lg"></span>  <?php } ?>
</span>
<span class="pull-left">
<!-- Contact avatar-->
<img src="<?php echo $domain;?>/app/<?php echo getProfilePicture($domain, $chat_fr)?>" alt="<?php echo $chat_fr['full_name']?>" class="media-box-object img-circle thumb48">
</span>
<!-- Contact info-->
<span class="media-box-body">
<span class="media-box-heading">
<strong><?php echo $chat_fr['full_name']?></strong>
<br>
<small class="text-muted"><i class="fa fa-map-marker" style="margin-right:2px;"></i> <?php echo $chat_fr['city']?>, <?php echo $chat_fr['country']?></small>
</span>
</span>
</a>
<!-- END User status-->
<?php } ?>
<?php } else { echo '<p class="text-center">'.$lang['You_Have_Not_Added_Any_Friends'].'</p>'; } ?>
</li>
</ul>
</div>
</div>
</div>
</nav>
<!-- END Off Sidebar (right)-->
</aside>
