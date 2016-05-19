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
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <meta name="description" content="">
   <meta name="keywords" content="">
   <title><?php echo $lang['Password_Reset']?></title>
   <link rel="stylesheet" href="<?php echo $domain?>/vendor/fontawesome/css/font-awesome.min.css">
   <link rel="stylesheet" href="<?php echo $domain?>/vendor/simple-line-icons/css/simple-line-icons.css">
   <link rel="stylesheet" href="<?php echo $domain?>/app/css/bootstrap.css">
   <link rel="stylesheet" href="<?php echo $domain?>/app/css/app.css">
   <link rel="stylesheet" href="<?php echo $domain?>/app/css/custom.css">
   <?php 
   if($theme == 'default') { echo '<link rel="stylesheet" href="'.$domain.'/app/css/theme-red.css">'; } 
   else { echo '<link rel="stylesheet" href="'.$domain.'/app/themes/'.$theme.'/theme.css">'; }
   ?>
   <style>
   body {
      background-image: url('<?php echo $domain?>/vendor/landing/bg-header.jpg');  
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
            <div class="panel-heading text-center">
               <a href="<?php echo $domain?>/index.php">
                  <img src="<?php echo $domain?>/app/img/logo.png" class="block-center img-rounded">
               </a>
            </div>
            <div class="panel-body">
               <p class="text-center pv"><?php echo $lang['Password_Reset']?></p>
               <?php if(isset($success)) { ?> <div class="alert alert-success"> <i class="fa fa-check fa-fw"></i> Success </div> <?php } ?>
               <form action="" method="post" role="form">
                  <div class="form-group has-feedback">
                     <label for="resetInputEmail1" class="text-muted"><?php echo $lang['Email']?></label>
                     <input id="resetInputEmail1" type="email" name="email" placeholder="<?php echo $lang['Enter_Email']?>" autocomplete="off" class="form-control">
                     <span class="fa fa-envelope form-control-feedback text-muted"></span>
                  </div>
                  <button type="submit" name="reset" class="btn btn-danger btn-block"><?php echo $lang['Reset']?></button>
               </form>
            </div>
         </div>
</div>
</div>
<script src="<?php echo $domain?>/vendor/modernizr/modernizr.js"></script>
<script src="<?php echo $domain?>/vendor/jquery/dist/jquery.js"></script>
<script src="<?php echo $domain?>/vendor/bootstrap/dist/js/bootstrap.js"></script>
<script src="<?php echo $domain?>/vendor/jQuery-Storage-API/jquery.storageapi.js"></script>
<script src="<?php echo $domain?>/vendor/parsleyjs/dist/parsley.min.js"></script>
<script src="<?php echo $domain?>/app/js/app.js"></script>
</body>
</html>
