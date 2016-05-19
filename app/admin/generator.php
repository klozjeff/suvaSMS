<?php
session_start();
define('IS_ADMIN','true');
require('../core/config/config.php');
require('../core/config/config-theme.php');
require('../core/config/config-ads.php');
require('../core/config/config-lang.php');
require('../core/system.php');

if(!isLogged() || $user['is_admin'] == 0) { header('Location: ../index.php'); exit; }

$page['name'] = 'Admin - User Generator';
$menu['generator'] = 'active';

if(isset($_POST['generate'])) {
	
	$first_male = array('Stephen','Joe','Mustafa','Martin','Ali','Steve','Hakan','Juan', 'Edward', 'Luiz', 'Anis','James','Mark','Brandon','Campos','Jordan','Alexander','Mateus','Breno');
	$last_male = array('Ali','Muhammad','Jones','Bektas','Johnson', 'Allen','Cavalcanti','Gomez','Khoury','Ray','Bohon','Rasmussen','Friedman','Achen','Hoffmann','Schweizer','Bohm');

	$first_female = array('Giovanna','Larissa','Yasmeen','Salwa','Sarah','Jane','Mia','Lena','Sara','Gabriele','Cordelia','Miranda','Olivie','Annette','Zuhur','Zehra','Haifa');
	$last_female = array('Santos','Castro','Haik','Antar','Hallen','Leslie','Adler','Konig','Lorenzo','Rizzo','Savard','Chabot','Saliba','Amari','Reno','Rhodes');

	$country = $_POST['country'];
	$gender = $_POST['gender'];

		if($gender == 'female') {
		for($i = 1; $i <= 16; $i++) {
		$full_name = $first_female[array_rand($first_female)].' '.$last_female[array_rand($last_female)];
		$time = time();
		$profile_picture = $domain.'/app/img/ug/female/'.$i.'.png';
		$age = mt_rand(20,35);
		$email = 'fake@fake.com';
		$gender = 'Female';
		$city = '';
		$db->query("INSERT INTO users(profile_picture,full_name,email,password,registered,country,city,energy,age,gender,ip,latitude,longitude,local_range) VALUES ('$profile_picture','$full_name','$email','"._hash('123456')."','$time','$country','$city','10','$age','$gender','','60','45','0,150')");
		}
		}
		if($gender == 'male') {
		for($i = 1; $i <= 16; $i++) {
		$full_name = $first_male[array_rand($first_male)].' '.$last_male[array_rand($last_male)];
		$time = time();
		$profile_picture = $domain.'/app/img/ug/male/'.$i.'.png';
		$age = mt_rand(20,35);
		$email = 'fake@fake.com';
		$gender = 'Male';
		$city = '';
		$db->query("INSERT INTO users(profile_picture,full_name,email,password,registered,country,city,energy,age,gender,ip,latitude,longitude,local_range) VALUES ('$profile_picture','$full_name','$email','"._hash('123456')."','$time','$country','$city','10','$age','$gender','','60','45','0,150')");
		}
		}
		if($gender == 'all') {
		for($i = 1; $i <= 16; $i++) {
		$full_name = $first_female[array_rand($first_female)].' '.$last_female[array_rand($last_female)];
		$time = time();
		$profile_picture = $domain.'/app/img/ug/female/'.$i.'.png';
		$age = mt_rand(20,35);
		$email = 'fake@fake.com';
		$gender = 'Female';
		$city = '';
		$db->query("INSERT INTO users(profile_picture,full_name,email,password,registered,country,city,energy,age,gender,ip,latitude,longitude,local_range) VALUES ('$profile_picture','$full_name','$email','"._hash('123456')."','$time','$country','$city','10','$age','$gender','','60','45','0,150')");
		}
		for($i = 1; $i <= 16; $i++) {
		$full_name = $first_male[array_rand($first_male)].' '.$last_male[array_rand($last_male)];
		$time = time();
		$profile_picture = $domain.'/app/img/ug/male/'.$i.'.png';
		$age = mt_rand(20,35);
		$email = 'fake@fake.com';
		$gender = 'Male';
		$city = '';
		$db->query("INSERT INTO users(profile_picture,full_name,email,password,registered,country,city,energy,age,gender,ip,latitude,longitude,local_range) VALUES ('$profile_picture','$full_name','$email','"._hash('123456')."','$time','$country','$city','10','$age','$gender','','60','45','0,150')");
		}
		}

	$success = true;
}

$countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");

require('../inc/admin/top.php');
?>
<section>
<div class="content-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<form action="" method="post">
<div class="panel panel-default">
<div class="panel-heading panel-title"> User Generator </div>
<div class="panel-body">
<?php if(isset($success)) { ?> <div class="alert alert-success"> <i class="fa fa-check fa-fw"></i> Users have been generated succesfully </div> <?php } ?>
A dating website without users is not a dating website. <br> This tool will generate up to 50 unique users, to give your website a solid start. <br><br>
<br>
<select name="gender" class="form-control">
<option value="" disabled selected>Gender</option>
<option value="all"> All </option>
<option value="male"> Male </option>
<option value="female"> Female </option>
</select>
<br>
<select name="country" class="form-control">
<option value="" disabled selected>Country</option>
<?php foreach($countries as $country) { 
  echo '<option value="'.$country.'">'.$country.'</option>';
} ?>
</select>
<br>
<input type="submit" name="generate" class="btn btn-danger" value="Generate">
</div>
</form>
</div>
</div>
</div>
</div>
</section>
<script src="<?php echo $domain?>/vendor/modernizr/modernizr.js"></script>
<script src="<?php echo $domain?>/vendor/jquery/dist/jquery.js"></script>
<script src="<?php echo $domain?>/vendor/bootstrap/dist/js/bootstrap.js"></script>
<script src="<?php echo $domain?>/vendor/jQuery-Storage-API/jquery.storageapi.js"></script>
<script src="<?php echo $domain?>/vendor/animo.js/animo.js"></script>
<script src="<?php echo $domain?>/vendor/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?php echo $domain?>/app/js/app.js"></script>
</body>
</html>