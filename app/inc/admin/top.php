<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="description" content="<?php echo $meta['description']?>">
<meta name="keywords" content="<?php echo $meta['keywords']?>">
<title><?php echo $page['name']?></title>
<link rel="stylesheet" href="../../vendor/fontawesome/css/font-awesome.min.css">
<link rel="stylesheet" href="../../vendor/simple-line-icons/css/simple-line-icons.css">
<link rel="stylesheet" href="../../vendor/animate.css/animate.min.css">
<link rel="stylesheet" href="../../vendor/loaders.css/loaders.css">
<link rel="stylesheet" href="../../app/css/bootstrap.css">
<link rel="stylesheet" href="../../vendor/sweetalert/dist/sweetalert.css">
<link rel="stylesheet" href="../../app/css/app.css" id="maincss">
<?php 
if($theme == 'default') { echo '<link rel="stylesheet" href="../css/theme-red.css">'; } 
else { echo '<link rel="stylesheet" href="../themes/'.$theme.'/theme.css">'; }
?>
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,600,700' rel='stylesheet' type='text/css'>
<?php echo $page['css']?>
<link rel="stylesheet" href="../../css/custom.css">
<style>

</style>
</head>

<body class="layout-<?php echo $layout?>">
<div class="wrapper">
<!-- top navbar-->
<header class="topnavbar-wrapper">
<!-- START Top Navbar-->
<nav role="navigation" class="navbar topnavbar">
<!-- START navbar header-->
<div class="navbar-header">
<a href="#/" class="navbar-brand">
<div class="brand-logo">
<img src="../img/logo.png" alt="Logo" class="img-responsive">
</div>
<div class="brand-logo-collapsed">
<!--<img src="img/logo-single.png" alt="Logo" class="img-responsive">-->
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
<a href="#" data-toggle-state="aside-toggled" data-no-persist="true" class="visible-xs sidebar-toggle">
<em class="fa fa-navicon"></em>
</a>
</li>
</ul>
<!-- END Left navbar-->
<!-- START Right Navbar-->
<ul class="nav navbar-nav navbar-right">
<li>
<a href="../../app/campaigns"> <i class="icon-arrow-left"></i> &nbsp Back </a>
</li>
</ul>
<!-- END Right Navbar-->
</div>
<!-- END Nav wrapper-->
</nav>
<!-- END Top Navbar-->
</header>
<!-- sidebar-->
<aside class="aside">
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
<a href="../../app/profile/<?php echo $user['id']?>">
<img src="../../app/uploads/<?php echo $user['profile_picture']?>" alt="Avatar" width="60" height="60" class="img-thumbnail img-circle user-picture">
</a>
<div class="circle circle-success circle-lg"></div>
</div>
</div>
<!-- Name and Job-->
<div class="user-block-info">
<span class="user-block-name">Hello, <?php echo $first_name?></span>
<span class="user-block-role">Admin </span>
</div>
</div>
</div>
</li>

<li class="<?php echo $menu['dashboard']?>">
<a href="dashboard.php">
<em class="icon-speedometer"></em>
<span>Dashboard</span>
</a>
</li>

<li class="<?php echo $menu['users']?>">
<a href="users.php">
<em class="icon-users"></em>
<span>Users</span>
</a>
</li>

<li class="<?php echo $menu['pages']?>">
<a href="pages.php">
<em class="icon-layers"></em>
<span>Pages</span>
</a>
</li>

<li class="<?php echo $menu['ads']?>">
<a href="ads.php">
<em class="icon-picture"></em>
<span>Advertisements</span>
</a>
</li>

<li class="<?php echo $menu['generator']?>">
<a href="generator.php">
<em class="icon-magic-wand"></em>
<span>User Generator</span>
</a>
</li>

<li class="<?php echo $menu['payments']?>">
<a href="payments.php">
<em class="icon-credit-card"></em>
<span>Payments</span>
</a>
</li>

<li class="<?php echo $menu['reports']?>">
<a href="reports.php">
<em class="icon-flag"></em>
<span>Reports</span>
</a>
</li>

<li class="<?php echo $menu['themes']?>">
<a href="themes.php">
<em class="icon-drop"></em>
<span>Themes</span>
</a>
</li>

<li class="<?php echo $menu['plugins']?>">
<a href="plugins.php">
<em class="icon-puzzle"></em>
<span>Plugins</span>
</a>
</li>

<li class="<?php echo $menu['languages']?>">
<a href="languages.php">
<em class="icon-globe"></em>
<span>Languages</span>
</a>
</li>

<li class="<?php echo $menu['global-notification']?>">
<a href="global-notification.php">
<em class="icon-bell"></em>
<span>Global Notification</span>
</a>
</li>

<li class="<?php echo $menu['settings']?>">
<a href="settings.php">
<em class="icon-settings"></em>
<span>Settings</span>
</a>
</li>

</ul>
<!-- END sidebar nav-->
</nav>
</div>
<!-- END Sidebar (left)-->
</aside>
