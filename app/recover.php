<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$db->set_charset('utf8');

if(isset($_POST['reset'])) {
$email = $_POST['email'];
$user = $db->query("SELECT id FROM users WHERE email='".$email."'");
if($user->num_rows >= 1) {
$pass = substr(_hash(time()),0,5);
$db->query("UPDATE users SET password='"._hash($pass)."' WHERE email='".$email."'");
mail($email, $lang['Password_Reset'], sprintf($lang['Password_Reset_Instructions'],$pass),'From: info@'.$domain,'-f info@'.$domain);
$success = true;
} 
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $lang['Password_Reset']?>-A Unique Essay Writing Service. Get Professional Academic Help</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php echo $web['description']; ?>">
		<meta name="author" content="Spreadrr Design">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		
		
		<!-- Stylesheets -->
		   <link rel="stylesheet" href="css/semantic.min.css">
		     <link rel="stylesheet" href="css/packed_global.css">
		     <link rel="stylesheet" href="css/main.css">
		<!-- End Stylesheets -->
	    
		
		<style>
		@media only screen and (max-width: 760px){
		  .login-container {
		    width:80%!important;
			margin:auto!important;
		  }
		}
		
		@media only screen and (min-width: 800px){
		  .login-container {
		    width:25%!important;
			margin:auto!important;
		  }
		}
		</style>
		
</head>
<body>
	<!-- HEADER START -->
	 <div class="canvas__header"  style="background:#FFF;">
            

            <header class="site-header">
			<!--START HEADER  FOR MOBILE-->
                <div class="site-header__mini is-hidden-desktop">
                  <div class="header-mini">
  <div class="header-mini__button--account">
    <a href="#account" class="btn btn--square" data-off-canvas="right" data-view="offCanvasNavToggle">
      <i class="e-icon -icon-person"></i>
      <span class="is-hidden">Account</span>
</a>  </div>

  <div class="header-mini__button--categories">
    <a href="#categories" class="btn btn--square" data-off-canvas="left" data-view="offCanvasNavToggle">
      <i class="e-icon -icon-hamburger"></i>
      <span class="is-hidden">Sites, Search &amp; Categories</span>
</a>  </div>

  <div class="header-mini__logo">
    <a href="index"><img src="<?php echo $domain;?>assets/images/logo2.png" width="157px" alt=""></a>
  </div>
</div>

                </div>
				<!--END HEADER FOR MOBILE-->
<!--START HEADER FOR DESKTOP-->
              <div class="site-header__logo-and-account is-hidden-tablet-and-below">
                <div class="header-logo-account" style="background-color:#FFF;">
  <div class="grid-container">
    <div class="header-logo-account__container">
      <a class="header-logo-account__logo" href="<?php echo $domain;?>" style="margin-top:5px">
       <img src="<?php echo $domain;?>/assets/images/logo2.png" width="157px" alt="">
      </a>
      <nav class="header-logo-account__right">
          <ul class="header-logo-account__sundry">
          
              <li class="header-logo-account__sundry-item">
                  <a href="<?php echo $domain;?>/how" class="header-logo-account__sundry-main-link" data-dropdown-target=".js-sundry-5-dropdown" data-view="touchOnlyDropdown">Help Center</a>

                  <div class="header-logo-account__sundry-dropdown js-sundry-5-dropdown">
                    <ul class="hub-header-dropdown">
                     
                        <li>
                          <a href="<?php echo $domain;?>/how" class="header-logo-account__sundry-sub-link">How it Works</a>
                        </li>
                        <li>
                          <a href="<?php echo $domain;?>/faq" class="header-logo-account__sundry-sub-link">FAQ</a>
                        </li>
                     <li>
                          <a href="<?php echo $domain;?>/pricing" class="header-logo-account__sundry-sub-link">Pricing</a>
                        </li>
                   
                    </ul>
                  </div>
              </li>
          </ul>

        <div class="header-logo-account__user-nav">

            <div class="header-logo-account__user-nav-item">
  <strong>
    <a href="<?php echo $domain;?>/order" class="header-logo-account__user-nav-main-link--alpha" data-view=""><span>Place Your Order</span></a>
  </strong>
</div>
<div class="header-logo-account__user-nav-item">
  <a href="<?php echo $domain;?>/app/login" class="header-logo-account__user-nav-main-link--omega" data-view="" rel="nofollow">Sign In</a>
</div>

        </div>
      </nav>
    </div>
  </div>
</div>

              </div>

             <!--HEADER HEADER FOR DESKTOP-->
            </header>
          </div>
	<!-- HEADER END -->
	<!-- BODY START -->
	<div class="body">
	<div class="body-content">
	<div id="login" style="padding-top: 8%;" class="content auth">
    <div class="ui header"><?php echo $lang['Password_Reset']?></div>

               <?php if(isset($success)) { ?> <div class="alert alert-success"> <i class="fa fa-check fa-fw"></i> Success </div> <?php } ?>
	
	<div class="login-container"></div>
    <form action="" method="post" accept-charset="utf-8" class="ui form login-container">
  
		
		<div class="field">
             <input id="resetInputEmail1" type="email" name="email" placeholder="<?php echo $lang['Enter_Email']?>" autocomplete="off" class="form-control">
                 <span class="fa fa-envelope form-control-feedback text-muted"></span>
		</div>
        <div class="field center aligned">
          <button type="submit" name="reset" class="ui green button fluid submit">Reset</button>
        </div>
		
		<div class="ui horizontal divider">NEW CUSTOMER</div>
		
		<div class="field">
          <a class="ui fluid twitter button" rel="nofollow" href="<?php echo $domain;?>/order">
			PLACE YOUR ORDER <i class="fa fa-arrow-right"></i>
		  </a>
        </div>
        
    </form>
	</div>
	</div>
	</div>
	<!-- BODY END -->
	<!-- FOOTER CODE -->
	<script type="text/javascript" src="<?php echo $web['url']; ?>static/gen/scripts.min.js"></script>
	<script type="text/javascript" src="<?php echo $web['url']; ?>static/gen/packed_global.js?51b2b110"></script>
	<!-- FOOTER CODE -->
  
</body>
</html>