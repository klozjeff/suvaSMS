<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-lang.php');
require('core/system.php');
require('core/fb-sdk/src/facebook.php');

// Geolocation
$geo = geoinfo();
$ip = $_SERVER['REMOTE_ADDR'];
$latitude = $geo['geoplugin_latitude'];
$longitude = $geo['geoplugin_longitude'];
$country = $geo['geoplugin_countryName'];

$app_id = $fb_app_id;
$app_secret = $fb_secret_key;

$facebook = new Facebook(array(
  'appId'  => $app_id,
  'secret' => $app_secret,
  ));

// Getting User ID
$user = $facebook->getUser();

// Get Access token
$access_token = $facebook->getAccessToken();

if ($user) {
  try {

    //$user_friendList = $facebook->api('/me/friends?access_token='.$access_token);
    $user_profile = $facebook->api('/me?fields=id,name,email,gender,birthday','GET');
    $email = $user_profile['email'];
    $full_name = $user_profile['name'];
    $id = $user_profile['id'];
    $birthday = $user_profile['birthday'];
    if(strlen($birthday) == 4) {
      $age = date('Y')-$birthday;
    } else {
      $birthday = explode('/',$birthday);
      $birthday = $birthday[2];
      $age = date('Y')-$birthday;
    }
    if($age > 100) {
      $age = ''; 
    }
    $gender = ucfirst($user_profile['gender']);
    $time = time();
    $profile_picture = 'http://graph.facebook.com/'.$id.'/picture?type=large';

    $check = $db->query("SELECT * FROM users WHERE email='$email'")->num_rows;

    if($check >= 1) {

     // Account exists

     $db->query("UPDATE users SET last_login=UNIX_TIMESTAMP(),latitude='$latitude',longitude='$longitude',ip='$ip' WHERE email='$email'");

     $_SESSION['auth'] = true;
     $_SESSION['email'] = '$email';
     $_SESSION['full_name'] = $full_name;

   } else {

     // Create account

     $db->query("INSERT INTO users(profile_picture,full_name,email,registered,country,energy,ip,age,gender,latitude,longitude,local_range,last_login) VALUES ('$profile_picture','$full_name','$email','$time','$country','10','$ip','$age','$gender','$latitude','$longitude','10,500',UNIX_TIMESTAMP())");

     $_SESSION['auth'] = true;
     $_SESSION['email'] = $email;
     $_SESSION['full_name'] = $full_name;

   }
   
 } catch (FacebookApiException $e) {
  error_log($e);
  $user = null;
}

} 

if ($user) {
  
  $user_profile = $facebook->api('/me?fields=id,name,email,gender','GET');
  $email = $user_profile['email'];

  $myuser = $db->query("SELECT * FROM users WHERE email='$email'");
  $myuser = $myuser->fetch_array();

  $db->query("UPDATE users SET last_login=UNIX_TIMESTAMP(),latitude='$latitude',longitude='$longitude',ip='$ip' WHERE email='$email'");

  $_SESSION['auth'] = true;
  $_SESSION['email'] = $myuser['email'];
  $_SESSION['full_name'] = $myuser['full_name'];

  header('Location: home.php');

} else {
  $loginUrl = $facebook->getLoginUrl(array('scope' => 'email,user_birthday,user_about_me,user_location'));
  header('Location: '.$loginUrl);
}
