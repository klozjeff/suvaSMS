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

         // Geolocation
         $geo = geoinfo();
         $ip = $_SERVER['REMOTE_ADDR'];
         $latitude = $geo['geoplugin_latitude'];
         $longitude = $geo['geoplugin_longitude'];

         $db->query("UPDATE users SET last_login=UNIX_TIMESTAMP(),latitude='$latitude',longitude='$longitude',ip='$ip' WHERE email='$email'");

         header("Location: orders");

      } else {
         $error = 'Invalid Cridentials';
      }

   } else {
      $error = 'Invalid Cridentials';
   }
}

foreach ($lang as $l => $v) {
   $lang[$l] = utf8_encode($v);
}

foreach ($lang as $l => $v) {
   $lang[$l] = utf8_decode($v);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta http-equiv="content-type" content="text/html; charset=utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <meta name="description" content="">
   <meta name="keywords" content="">
   <title><?php echo $lang['Login']?></title>
   <link rel="stylesheet" href="../vendor/fontawesome/css/font-awesome.min.css">
   <link rel="stylesheet" href="../vendor/simple-line-icons/css/simple-line-icons.css">
   <link rel="stylesheet" href="css/bootstrap.css">
   <link rel="stylesheet" href="css/app.css">
   <link rel="stylesheet" href="vendor/bootstrap-social/bootstrap-social.css">
   <link rel="stylesheet" href="css/custom.css">
   <?php 
   if($theme == 'default') { echo '<link rel="stylesheet" href="css/theme-red.css">'; } 
   else { echo '<link rel="stylesheet" href="themes/'.$theme.'/theme.css">'; }
   ?>
   <style>
   body {
      background-image: url('vendor/landing/bg-header.jpg');  
      background-position: 50% 0px;
      position: relative;
      background-size: cover;
   }
   body:after {
    background: #271c3e;
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    transform: rotate(0deg);
    left: 0;
    width: 100%;
    min-width: 100%;
    height: 100%;
    opacity: 0.7;
    content: "";
    position: absolute;
    z-index: -999;
    top: 0;
   }
   .panel-heading {
    border-radius:0px; 
   }
   </style>
</head>

<body>
   <div class="wrapper">
      <div class="block-center col-md-6" style="margin:0 auto;float:none;padding:30px;">
         <!-- START panel-->
         <div class="panel panel-dark panel-flat">
            <div class="panel-heading text-center" <?php if($mobileapp == true) { echo 'style="display:none !important;"'; } ?>>
               <a href="index.php">
                  <img src="img/logo.png" class="block-center img-rounded">
               </a>
            </div>
            <div class="panel-body">
               <p class="text-center pv"><?php echo $lang['Log_In_To_Continue']?></p>
               <?php if(isset($_COOKIE['justRegistered'])) { ?> <div class="alert alert-success bg-success-light"> <i class="fa fa-check"></i> &nbsp <?php echo $lang['You_Can_Now_Log_In']?> </div> <?php } ?>
               <?php if(isset($error)) { ?> <div class="alert alert-danger bg-danger-light"> <i class="fa fa-warning"></i> &nbsp <?php echo $error?> </div> <?php } ?>
               <form action="fb-login.php" method="post">
                  <button type="submit" name="fb-login" class="btn btn-block btn-social btn-lg btn-facebook"><i class="fa fa-facebook"></i><?php echo $lang['Log_In_With_Facebook']?></button> <br>
               </form>
               <form action="" method="post" role="form" data-parsley-validate="" novalidate="" class="mb-lg">
                  <div class="form-group has-feedback">
                     <input id="exampleInputEmail1" type="email" name="email" placeholder="<?php echo $lang['Enter_Email']?>" autocomplete="off" required class="form-control" value="<?php echo $email?>">
                     <span class="fa fa-envelope form-control-feedback text-muted"></span>
                  </div>
                  <div class="form-group has-feedback">
                     <input id="exampleInputPassword1" type="password" name="password" placeholder="<?php echo $lang['Enter_Password']?>" required class="form-control">
                     <span class="fa fa-lock form-control-feedback text-muted"></span>
                  </div>
                  <div class="clearfix">
                     <div class="checkbox c-checkbox pull-left mt0">
                        <label>
                           <input type="checkbox" value="" name="remember" <?php echo $remember?>>
                           <span class="fa fa-check"></span><?php echo $lang['Remember_Me']?></label>
                        </div>
                        <div class="pull-right"><a href="app/recover" class="text-muted"><?php echo $lang['Forgot_Your_Password']?></a>
                        </div>
                     </div>
                     <button type="submit" name="login" class="btn btn-block btn-danger mt-lg"><?php echo $lang['Login']?></button>
                  </form>
                  <p class="pt-lg text-center"><?php echo $lang['No_Account']?></p><a href="register" class="btn btn-block btn-default"><?php echo $lang['Register']?></a>
               </div>
            </div>
         </div>
      </div>
      <script src="../vendor/modernizr/modernizr.js"></script>
      <script src="../vendor/jquery/dist/jquery.js"></script>
      <script src="../vendor/bootstrap/dist/js/bootstrap.js"></script>
      <script src="../vendor/jQuery-Storage-API/jquery.storageapi.js"></script>
      <script src="../vendor/parsleyjs/dist/parsley.min.js"></script>
      <script src="js/app.js"></script>
   </body>
   </html>
