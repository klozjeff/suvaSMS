<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$_SESSION['confirmation'] = true;

$page['name'] = $lang["New Campaign"];
$menu['home'] = 'active';

$page['js'] .= '<script src="'.$domain.'/vendor/parsleyjs/dist/parsley.min.js"></script>';
$page['js'] .= '<script type="text/javascript">$("#preferences").parsley();</script>';
$page['js'] .= '<script src="'.$domain.'/vendor/seiyria-bootstrap-slider/dist/bootstrap-slider.min.js"></script>';
$page['js'] .= '<script>$("[data-ui-slider]").slider();</script>';
$page['css'] .= '<link rel="stylesheet" href="'.$domain.'/vendor/seiyria-bootstrap-slider/dist/css/bootstrap-slider.min.css">';

$countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");
$specialization=array("Communication & Literature","Business & Accounting","Technical Papers & Projects","Philosophy & Psychology","Management & Marketing","Nursing & Medicine","Education","Law","Art & Architecture","Problem Solution");

$status=array("Draft","Ready","Archive");

//if($user['local_dating'] == 1) {
 // $local_dating = 'checked';
//}//

//$local_range = $user['local_range'];

if(isset($_POST['save'])) {
  $name = ucfirst(strip_tags($_POST['name']));
  $group = $_POST['group'];
  $list = $_POST['list'];
  $status = $_POST['status'];
  $template = $_POST['template'];
   $new_template = strip_tags($_POST['new_template']);
   $now=time();
   $user_id=$user['unique_id'];
if(isset($new_template) && $new_template!='')
{
	$temp_name="Untitled";
$unique_id2=rand(1,99999);


$db->query("INSERT INTO sms_template(template_uid,customer_id,name,content,date_added) VALUES('".$unique_id2."','".$user_id."','".$temp_name."','".$new_template."','".$now."')");
$tempid=$db->insert_id;	
	
}
else
{
	$tempid=$template;
	
}
$unique_id=rand(1,99999);
$db->query("INSERT INTO campaign(campaign_uid,customer_id,list_id,group_id,temp_id,name,status,date_added) VALUES('".$unique_id."','".$user_id."','".$list."','".$group."','".$tempid."','".$name."','".$status."','".$now."')");
$campaignid=$db->insert_id;	

  if($status=='Ready') {

    $page['js'] .= '
    <script>
    swal({ 
      title: "'.$lang['Ready_Set_Go'].'",
      text: "'.$lang['Updated_Preferences_Success'].'",
      imageUrl: "'.$domain.'/app/img/thumbs-up.png",
      confirmButtonText: "Broadcast",
      confirmButtonColor: "#3a3f51",
      closeOnConfirm: false,
      closeOnCancel: false
    },
    function(){
      window.location.href = "campaign/'.$campaignid.'/send";
    });
</script>
';


} else {

  header("Location:campaign/".$campaignid);
  exit; 
}

} 

require('inc/top.php');
?>
<style>
.nav-tabs > li > a::before {
  -moz-transition: -moz-transform 0.3s;
  -webkit-transition: -webkit-transform 0.3s;
  background: #566473;
  content: '';
  height: 3px;
  left: 0;
  position: absolute;
  top: 40px;
  transition: transform 0.3s;
  width: 100%;
  -moz-transform: scale(0);
  -o-transform: scale(0);
  -webkit-transform: scale(0);
  transform: scale(0);
}
.nav-tabs.nav-primary > li > a::before {
  background-color: #F5F7FA;
}
.nav-tabs.nav-blue > li > a::before {
  background-color: #F5F7FA;
}
.nav-tabs.nav-red > li > a::before {
  background-color: #c75757;
}
.nav-tabs.nav-green > li > a::before {
  background-color: #F5F7FA;
}
.nav-tabs.nav-yellow > li > a::before {
  background-color: #ff9122;
}
.nav-tabs .active a::before {
  width: 100%;
  -moz-transform: scale(1);
  -o-transform: scale(1);
  -webkit-transform: scale(1);
  transform: scale(1);
  border: 0px solid #dfdfdf;
  
}
.nav-tabs a:hover::before {
  width: 100%;
  -moz-transform: scale(1);
  -o-transform: scale(1);
  -webkit-transform: scale(1);
  transform: scale(1);
  border: 0px solid #dfdfdf;
}
.nav-tabs a:focus::before {
  width: 100%;
  -moz-transform: scale(1);
  -o-transform: scale(1);
  -webkit-transform: scale(1);
  transform: scale(1);
  border: 0px solid #dfdfdf;
}
.nav-tabs:after {
  bottom: 3px;
  position: relative;
  width: 100%;
  z-index: 120;
   background-color: #F5F7FA;
   
}

.nav.nav-tabs > li > a {
  background-color: #F5F7FA;
  border: none;
  color: #939393;
}
.nav.nav-tabs > li > a i {
  padding-right: 8px;
}
.nav.nav-tabs > li.active > a {
  background-color: #F5F7FA;
 border: 0px solid #F5F7FA;
  color: #121212;
}
.nav.nav-tabs > li:hover > a {
  background-color: #F5F7FA;
  color: #121212;
}
.tab-content {
  background-color: #F5F7FA;
  border: 0px solid #F5F7FA;
  padding: 15px;
}
</style>
<section>
<div class="content-wrapper">
<h3>
<?php echo $lang["New Campaign"]?> 
<span class="pull-right"> 
<a class="btn btn-primary" data-toggle="modal" data-target="#newGroup"> <i class="fa fa-plus"></i> <?php echo $lang['New Campaign Group']?> </a> 

<a href="orders" class="btn btn-default"> <i class="icon-arrow-left"></i> Back to <?php echo $lang['Campaign']?> </a> 

</span> 
</h3>
<div class="container-fluid">
<div class="table-grid table-grid-desktop">
<div class="col">
<div class="clearfix mb">
<div class="btn-group pull-left">
</div>
</div>

<form action="" method="post" role="form" id="preferences">
<div class="col-md-4">
<div class="row">
<div class="form-group has-feedback">
    <label for="sexual_interest">Campaign Name</label>
  
     <input type="text" name="name" class="form-control" value="">
  

  </div>
   <div class="form-group has-feedback">
    <label for="city"><?php echo $lang["Campaign Group"]?></label>
     <select name="group" class="form-control">
	 <option value="">Select Value</option>
      <?php 
	  $group=$db->query("SELECT * FROM campaign_group");
	  while($row=$group->fetch_array()) { 
      
          echo '<option value="'.$row['group_id'].'">'.$row['name'].'</option>';
       
      } ?>
    </select>
  </div>
  <div class="form-group">
    <label>Contact <?php echo $lang['List']?>/<?php echo $lang['Leads']?></label>
    <select name="list" class="form-control">
      <option value="">Select Value</option>
      <?php 
	if($user['is_admin'] == 1)
	  $lists=$db->query("SELECT * FROM list WHERE status='active'");
  if($user['is_admin'] == 2)
	  $lists=$db->query("SELECT * FROM list WHERE status='active' AND customer_id=".$user['unique_id']."");
	  while($list=$lists->fetch_array()) { 
      
          echo '<option value="'.$list['list_id'].'">'.$list['name'].'</option>';
       
      } ?>
    </select>
  </div>
 
  <div class="form-group">
    <label>Status</label>
     <select name="status" class="form-control">
	 <option value="">Select Value</option>
      <?php foreach($status as $state) { 
      
          echo '<option value="'.$state.'">'.$state.'</option>';
        }
       ?>
    </select>
  </div>

	</div></div>
	<div class="col-md-1">
	</div>
	<div class="col-md-4">
<div class="row">
<div class="form-group has-feedback">
    <label for="sexual_interest">Message Template</label>
  <div class="">                    
                    <div class="">
                      <ul  class="nav nav-tabs" style="border: 0px solid #F5F7FA;">
                        <li class="active"><a href="#tab1_1" data-toggle="tab">Existing</a></li>
                        <li class=""><a href="#tab1_2" data-toggle="tab">New</a></li>
                    
                      </ul>
					    <div class="tab-content">
					   <div class="tab-pane fade active in" id="tab1_1">
					    <select name="template" class="form-control">
					 <option value="">Select Value</option>
      <?php 
	if($user['is_admin'] == 1)
	  $lists=$db->query("SELECT * FROM sms_template");
  if($user['is_admin'] == 2)
	  $lists=$db->query("SELECT * FROM sms_template WHERE customer_id=".$user['unique_id']."");
	  while($list=$lists->fetch_array()) { 
      
          echo '<option value="'.$list['template_id'].'">#'.$list['template_uid'].'-'.$list['name'].'</option>';
       
      } ?>
    </select>
					   </div>
					   
					      <div class="tab-pane fade in" id="tab1_2">
					      <textarea type="text" name="new_template" class="form-control" value=""></textarea>
					   </div>
					  </div></div></div>
  
  

  </div>
   <!--<div class="form-group has-feedback">
    <label for="city"><?php echo $lang["Campaign Group"]?></label>
     <select name="specialization" class="form-control">
      <?php foreach($specialization as $special) { 
        if($special == $user['specialization']) {
          echo '<option value="'.$special.'" selected>'.$special.'</option>';
        } else {
          echo '<option value="'.$special.'">'.$special.'</option>';
        }
      } ?>
    </select>
  </div>
  <div class="form-group">
    <label><?php echo $lang['List']?>/<?php echo $lang['Leads']?></label>
    <select name="country" class="form-control">
      <?php foreach($countries as $country) { 
        if($country == $user['country']) {
          echo '<option value="'.$country.'" selected>'.$country.'</option>';
        } else {
          echo '<option value="'.$country.'">'.$country.'</option>';
        }
      } ?>
    </select>
  </div>
 
  <div class="form-group">
    <label>Status</label>
     <select name="country" class="form-control">
      <?php foreach($status as $state) { 
      
          echo '<option value="'.$state.'">'.$state.'</option>';
        }
       ?>
    </select>
  </div>

<input type="submit" name="schedule" class="btn btn-primary pull-left" value="<?php echo $lang['Schedule']?>">
-->

&nbsp;&nbsp;&nbsp;
    <input type="submit" name="save" class="btn btn-success pull-right" value="<?php echo $lang['Save']?>">
	</div></div>
  </form>
<div class="col-md-3" style="min-length:100%;border-left:1px solid red">
<div class="row">

   

	</div></div>
</div>
</div>
</div>
</div>
</section>
<?php
require('inc/bottom.php'); 
?>