<?php
$domain = 'http://localhost/suva';

// Database Configuration
$_db['host'] = 'localhost';
$_db['user'] = 'root';
$_db['pass'] = '';
$_db['name'] = 'bulkSMS';

$site_name = 'Unikorn Bulk SMS Services';
$meta['keywords'] = 'Bulk SMS,SMS, Communication via Bulk SMS,SMS campaigns,SMS';
$meta['description'] = 'A reliable  Bulk SMS to reach your customer.Dynamic analysis and tracking of your SMS,Email Conversation wit your customers ';

// PayPal Configuration
$paypal_email = 'klozjeff@gmail.com'; // Email of PayPal Account to receive money

// PayGol Configuration (SMS Payments)
$paygol_service_id = '';

// Stripe Configuration
$secret_key = ''; // Stripe API Secret Key
$publishable_key = ''; // Stripe API Publishable Key

// Facebook Login Configuration
$fb_app_id = ''; 
$fb_secret_key = ''; 

$sms_username="klozjeff";
$api_key="1298cf4f1be6a4fc24832ed9472de7e9230431273991f1c8017d32af75c2afcb";

$db = new mysqli($_db['host'], $_db['user'], $_db['pass'], $_db['name']) or die('MySQL Error');
	