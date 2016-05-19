<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');
//echo $theme;

$db->set_charset('utf8');

if(isset($_GET['mobileapp'])) {
   $_SESSION['mobileapp'] = true;
} 

if(isset($_SESSION['mobileapp'])) {
   $mobileapp = true;
} else {
   $mobileapp = false;
}

if(isset($_COOKIE['mm-email'])) {
   $email = $_COOKIE['mm-email'];
   $remember = 'checked';
}

// Geolocation
$geo = geoinfo();

if(isset($_POST['login'])) {

   $email = $_POST['email'];
   $password = trim($_POST['password']);

   $check = $db->query("SELECT * FROM users WHERE email='$email'");
   if($check->num_rows >= 1) {
      $user = $check->fetch_array();
      if(_hash($password) == $user['password']) {

         if(isset($_POST['remember'])) {
            setcookie('mm-email',$email,time()+60*60*24*30,'/');
         } else {
            setcookie('mm-email', null, -1, '/');
            $remember = "";
         }

         $_SESSION['auth'] = true;
         $_SESSION['email'] = $email;
         $_SESSION['full_name'] = $user['full_name'];
		 $_SESSION['phone']=$user['contact_no'];
		 

         // Geolocation
         $geo = geoinfo();
         $ip = $_SERVER['REMOTE_ADDR'];
         $latitude = $geo['geoplugin_latitude'];
         $longitude = $geo['geoplugin_longitude'];

         $db->query("UPDATE users SET last_login=UNIX_TIMESTAMP(),latitude='$latitude',longitude='$longitude',ip='$ip' WHERE email='$email'");

         header("Location: campaigns");

      } else {
         $error = 'Invalid Credentials';
      }

   } else {
      $error = 'Invalid Credentials';
   }
}

foreach ($lang as $l => $v) {
   $lang[$l] = utf8_encode($v);
}

foreach ($lang as $l => $v) {
   $lang[$l] = utf8_decode($v);
}
if(isLogged()) {
$first_name = explode(' ', $_SESSION['full_name']);
$first_name = $first_name[0];
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $lang['Login']?>-Bulk SMS platform</title>
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
		 <?php if(!isLogged()) { ?>
            <div class="header-logo-account__user-nav-item">
  <strong>
    <a href="<?php echo $domain;?>/order" class="header-logo-account__user-nav-main-link--alpha" data-view=""><span>Place Your Order</span></a>
  </strong>
</div>
<div class="header-logo-account__user-nav-item">
  <a href="" class="header-logo-account__user-nav-main-link--omega" data-view="" rel="nofollow">Sign In</a>
</div>
<?php
		 }
		 ?>
		 
		   <?php if(isLogged()) { ?>
	   
        
		  <div class="header-logo-account__user-nav-item" >
  <a href="<?php echo $domain;?>/app/orders" class="ui fluid twitter button" style="height:38px" data-view="" rel="nofollow">My Account &#8594;</a>
</div>
	 
	  <?php } ?>
	 

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
    <!--<div class="ui header"><img src="<?php echo $domain;?>/assets/images/logo2.png" width="157px" height="1057px" alt=""></div>-->
	<div class="login-container"></div>
    <form action="" method="post" accept-charset="utf-8" class="ui form login-container">
        <div class="field">
          <input id="exampleInputEmail1" type="email" name="email" placeholder="<?php echo $lang['Enter_Email']?>" autocomplete="off" required value="<?php echo $email?>" >
        <!-- <span class="fa fa-envelope form-control-feedback text-muted"></span>-->
		</div>
		
        <div class="field">
          <input id="exampleInputPassword1" type="password" name="password" placeholder="<?php echo $lang['Enter_Password']?>" required >
      <!--<span class="fa fa-lock form-control-feedback text-muted"></span>-->
	  </div>
         <label>
                           <input type="checkbox" value="" name="remember" <?php echo $remember?>>
                          <!-- <span class="fa fa-check"></span>--><?php echo $lang['Remember_Me']?></label>
        <div style="" class="reset field">
		
         <a href="recover" class="reset text-muted"><?php echo $lang['Forgot_Your_Password']?></a>
        </div>

        <div class="field center aligned">
          <button type="submit" name="login" class="ui green button fluid submit">Log in</button>
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