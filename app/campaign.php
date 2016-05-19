<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');
require_once('plugins/AfricasTalkingGateway.php');
	
	
$id = $db->real_escape_string($_GET['id']);
$action = $db->real_escape_string($_GET['action']);

if(isset($action) && $action=='send')
{
$campaign_data=$db->query("SELECT list_id,temp_id FROM campaign WHERE campaign_id='".$id."'");
$campaign_data=$campaign_data->fetch_array();	
$recipients=$db->query("SELECT * FROM list_leads WHERE list_id='".$campaign_data['list_id']."'");
while($recipient=$recipients->fetch_array())
{
	$receivers[]=$recipient['phone_no'];
	
}
 
$temp_data=$db->query("SELECT content FROM sms_template WHERE template_id='".$campaign_data['temp_id']."'")->fetch_array();		
$message=$temp_data['content'];
foreach($receivers as $receiver)
{
$mycontact=$db->query("SELECT * FROM list_leads WHERE phone_no='".$receiver."'");
while($mypeople=$mycontact->fetch_array())
{
$string=strtolower($message);
$constants=array("@fname", "@lname","@email","@field1","@field2","@field3","@field4","@field5");
$values=array($mypeople['first_name'],$mypeople['second_name'],$mypeople['email'],$mypeople['custom_field1'],$mypeople['custom_field2'],$mypeople['custom_field3'],$mypeople['custom_field4'],$mypeople['custom_field5']);
$newmsg =ucfirst(str_replace($constants, $values, $string));
$now=time();
$mm=$mypeople['phone_no'];

$gateway=new AfricasTalkingGateway($sms_username,$api_key);
		
		try
		{

			$results = $gateway->sendMessage($mm, $newmsg);

			foreach($results as $result) {
				// Note that only the Status "Success" means the message was sent
				$phno=$result->number;
				 $msg_status=$result->status;
				 $msg_id=$result->messageId;
				$msg_cost=$result->cost;
                   $dateTime=date('Y-m-d H:i:s'); 
				 $db->query("INSERT INTO sms_outbox(campaign_id,message,recipient,msg_status,msg_id,msg_cost,date_done) VALUES('".$id."','".$newmsg."','".$phno."','".$msg_status."','".$msg_id."','".$msg_cost."','".$now."')");	
			}

		}
		catch ( AfricasTalkingGatewayException $e )
		{
			echo "Encountered an error while sending: ".$e->getMessage();
		}

}		
}


/*
 if (is_array($receivers)) {
  $content = '';
  $cont = '';
  for ($i=0;$i<count($receivers);$i++) {
  $content = $content . "$receivers[$i],";
  }
 $allrecepients = $content;
  }
  
 $people=substr($allrecepients,0,-1);

	
//$people=implode(',', $output);
   $now=time();
$db->query("INSERT INTO sms_outbox(campaign_id,message,recipient,date_done) VALUES('".$id."','".$message."','".$people."','".$now."')");*/
}
$_SESSION['confirmation'] = true;

$page['name'] = $lang["New Campaign"];
$menu['home'] = 'active';

$page['js'] .= '<script src="'.$domain.'/vendor/parsleyjs/dist/parsley.min.js"></script>';
$page['js'] .= '<script type="text/javascript">$("#preferences").parsley();</script>';
$page['js'] .= '<script src="'.$domain.'/vendor/seiyria-bootstrap-slider/dist/bootstrap-slider.min.js"></script>';
$page['js'] .= '<script>$("[data-ui-slider]").slider();</script>';
$page['css'] .= '<link rel="stylesheet" href="'.$domain.'/vendor/seiyria-bootstrap-slider/dist/css/bootstrap-slider.min.css">';

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
if($user['is_admin'] == 1)

$campaign=$db->query("SELECT campaign.* FROM campaign WHERE customer_id='".$user['id']."'");


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
<a class="btn btn-primary" data-toggle="modal" data-target="#newGroup"> <i class="fa fa-plus"></i> <?php echo $lang['New Campaign']?> </a> 

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

	<div class="col-md-11">
<div class="row">
<div class="form-group has-feedback">

  <div class="">                    
                    <div class="">
                      <ul  class="nav nav-tabs" style="border: 0px solid #F5F7FA;">
                        <li class="active"><a href="#tab1_1" data-toggle="tab">Dashboard</a></li>
						  <li class=""><a href="#tab1_2" data-toggle="tab">List/Subscribers</a></li>
                        <li class=""><a href="#tab1_3" data-toggle="tab">Template</a></li>
                    
                      </ul>
					    <div class="tab-content">
					   <div class="tab-pane fade active in" id="tab1_1">
					
					   </div>
					   
					      <div class="tab-pane fade in" id="tab1_2">
					      <textarea type="text" name="new_template" class="form-control" value=""></textarea>
					   </div>
					     <div class="tab-pane fade in" id="tab1_3">
					      <textarea type="text" name="new_template" class="form-control" value=""></textarea>
					   </div>
					  </div></div></div>
  
  

  </div>
  

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