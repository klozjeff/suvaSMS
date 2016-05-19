<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$page['name'] = $lang['Campaigns'];
$menu['home'] = 'active';

if(isset($_GET['filter'])) {
if(isset($_GET['sort_by'])) {
$sort_by = $_GET['sort_by'];
switch ($sort_by) {
   case 1:
     $sort_by = 'ORDER BY id DESC';
     break;
   case 2:
     $sort_by = 'ORDER BY id ASC';
     break;
   case 3:
     $sort_by = 'ORDER BY last_active DESC';
     break;
   default:
     $sort_by = 'ORDER BY RAND()';
     break;
 } 
}
if(isset($_GET['page_range'])) {
$page_range = $_GET['page_range'];
$page_range = explode(',',$page_range);
$min_page = $page_range[0];
$max_page = $page_range[1];
}
} else {
$sort_by = 'ORDER BY RAND()';
$min_page = 1;
$max_page = 100;
}

if($user['is_admin'] == 1)
{
   $campaign = $db->query("SELECT campaign.*,list.name AS list_name,campaign_group.name AS group_name,sms_template.name AS temp_name  FROM campaign INNER JOIN list ON campaign.list_id=list.list_id INNER JOIN campaign_group ON campaign.group_id=campaign_group.group_id INNER JOIN sms_template ON campaign.temp_id=sms_template.template_id WHERE campaign_id!='' $sort_by");
}
if($user['is_admin'] == 2)
{
	$campaign = $db->query("SELECT campaign.*,list.name,campaign_group.name,sms_template.name FROM campaign INNER JOIN list ON campaign.list_id=list.list_id INNER JOIN campaign_group ON campaign.group_id=campaign_group.group_id INNER JOIN sms_template ON campaign.temp_id=sms_template.template_id WHERE campaign.campaign_id!='' AND campaign.customer_id=".$user['id']." $sort_by");
//$campaign=$db->query("SELECT campaign.* FROM campaign WHERE customer_id=".$user['id']."  $sort_by");
//echo "SELECT * FROM order_details WHERE customer_id =".$user['id']." AND (order_page >= $min_page AND order_page <= $max_page) $sort_by";
}



if($user['updated_preferences'] == 0) {
  $page['js'] .= '
  <script>
  swal({ 
    title: "'.$lang['Welcome_Window_Title'].'",
    text: "'.$lang['Welcome_Window_Text'].'",
    imageUrl: "img/salute.png",
    confirmButtonText: "'.$lang['OK'].'",
    confirmButtonColor: "#3a3f51",
    closeOnConfirm: false,
    closeOnCancel: false
  },
  function(){
    window.location.href = "preferences.php";
  });
</script>
';
} else {
  $page['js'] .= '
  <script>
  function clickHeart(id) {
    heart = $("#heart-"+id);
    $.get("ajax/clickHeart.php?id="+id, function(data) {
      $("#user-"+id).html(data);
    });
    heart.css("color", "#f05050");
    heart.animo( { animation: ["tada"], duration: 2 } );
  }
  </script>
';
}

if(isset($_POST['delete_conversation'])) {
$delete = $_POST['delete'];
foreach($delete as $k=>$v) {
$db->query("UPDATE order_details SET status='Archive' WHERE id='".$v."'");
}
header('Location: '.$domain.'/app/chats');
exit;
}


require('inc/top.php');
?>
 <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
 <script>
	$(document).ready(function() {
	$("#lodrop").click(function(){
	
            	if ($("#account_drop").is(':hidden')){
                	$("#account_drop").show();
				}
            	else{
                	$("#account_drop").hide();
            	}
            return false;
       			 });
				  $('#account_drop').click(function(e) {
            		e.stopPropagation();
        			});
        		$(document).click(function() {
					if (!$("#account_drop").is(':hidden')){
            		$('#account_drop').hide();
					}
        			});	
                
});
</script>
<script>
$(document).ready(function() {
$(".s_no_but").click(function(){
	                $('.gridact_drop').hide();
	            
            	if ($("#"+this.id+'l').is(':hidden')){
					$('.ns_drop').hide();
				    $(".s_no_but").removeClass("ns_drop_hand");	
                	$("#"+this.id+'l').show();
					$("#"+this.id).addClass("ns_drop_hand");
					$(".gridact_drop").hide();
					
				}
            	else{
                	$("#"+this.id+'l').hide();
					$("#"+this.id).removeClass("ns_drop_hand");
            	}
            return false;
       			 });
				  $("#"+this.id+'l').click(function(e) {
            		e.stopPropagation();
        			});
        		
});
$(document).click(function() {
					
            		$('.ns_drop').hide();
					$(".s_no_but").removeClass("ns_drop_hand");
					
        			});
</script>

<script>
$(document).ready(function() {
$(".action_but").click(function(){
	                $(".ns_drop").hide();
					$(".s_no_but").removeClass("ns_drop_hand");
	            
				if ($("#"+this.id+'a').is(':hidden')){
					$('.gridact_drop').hide();
					$("#"+this.id+'a').show();
					}
            	else{
                	$("#"+this.id+'a').hide();
					}
            return false;
       			 });
				  $("#"+this.id+'a').click(function(e) {
            		e.stopPropagation();
        			});
        		
});
$(document).click(function() {
					
            		$('.gridact_drop').hide();
					});
</script>
<section>
<div class="content-wrapper">
<h3>
<?php echo $lang['Campaign']?> 
<span class="pull-right"> 
<a class="btn btn-primary" data-toggle="modal" data-target="#filterResults"> <i class="fa fa-sliders fa-fw"></i> <?php echo $lang['Filter']?> </a> 
<?php if(isset($_GET['filter'])) { ?>
<a href="orders?filter=true&sort_by=<?php echo $_GET['sort_by']?>&page_range=<?php echo $_GET['page_range']?>" class="btn btn-default"> <i class="fa fa-refresh fa-fw"></i> Refresh </a> 
<?php } else { ?>
<a href="orders" class="btn btn-default"> <i class="fa fa-refresh fa-fw"></i> <?php echo $lang['Refresh']?> </a> 
<?php } ?>
</span> 
</h3>
<div class="container-fluid">
<div class="table-grid table-grid-desktop">
<div class="col">
<div class="clearfix mb">
<div class="btn-group pull-left">
</div>
</div>
<?php if($campaign->num_rows >= 1) { ?>

<div class="row">
<div class="col-md-5">
<!--<button type="submit" name="new" class="btn btn-default"> <i class="fa fa-pencil fa-fw"></i> New Message </button>-->
<button type="submit" name="delete_conversation" class="btn btn-danger"> <i class="fa fa-trash fa-fw"></i> Archive </button>
<a href="new-campaign"><button type="" name="" class="btn btn-success"> <i class="fa fa-plus fa-fw"></i> New Campaign</button></a>
</div>
</div>
<br>
<form action="" method="post">
<div class="panel panel-default">
<div class="panel-body">
	<table class="table table-responsive mb-mails">
	   <thead>
	   <th style="text-align:center;">  </th>
	   <th style="text-align:left;">Campaign ID</th>
                    <th style="text-align:left;"> Campaign Name </th>
					
					   <th style="text-align:left;">Contacts/Leads</th>
					     <th style="text-align:left;">Template</th>
                    <th style="text-align:left;"> Reach </th>
					   <th style="text-align:left;"> Status</th>
					  <th style="text-align:left;">	<div class="pull-right"></div> Date Created</th>
                   
                </thead>
		<tbody>
			<?php while($details = $campaign->fetch_array()) {
				
				//$price=$db->query("SELECT * FROM order_price WHERE order_price.order_id='".$details['id']."'")->fetch_array();
				?>
				<tr>
				<td style="width:40px;text-align:center;">
	                <div class="checkbox c-checkbox">
	                   <label>
	                      <input name="delete[]" type="checkbox" value="<?php echo $details['campaign_id']?>">
	                      <span class="fa fa-check"></span>
	                   </label>
	                </div>
             	</td>
				<td >
	                <a href="campaign/<?php echo $details['campaign_id']?>" style="text-decoration:none;">
							
							
							
							<div class="mb-mail-meta">
								<div class="pull-left">
									<div class="mb-mail-preview">#<?php echo $details['campaign_uid']?></div>
								</div>
								
							</div>

						</a>
             	</td>
					
					<td>
						<a href="campaign/<?php echo $details['campaign_id']?>" style="text-decoration:none;">
							
						
							<div class="mb-mail-meta">
								<div class="pull-left">
									<div class="mb-mail-subject" style="color:#515253;"><?php echo $details['name']?></div>
								</div>
								<div class="mb-mail-preview"><?php echo $details['group_name'].' . '.$details['list_name']?></div>
							
							
							</div>

						</a>
					</td>
					<td>
					<div class="mb-mail-meta">
								<div class="pull-left">
									<?php	$list_no=$db->query("SELECT COUNT(DISTINCT phone_no) AS totalLeads FROM list_leads WHERE list_id='".$details['list_id']."'")->fetch_array();		
echo $count=$list_no['totalLeads']; ?>
								</div>
								<div class="mb-mail-preview"> Contacts</div>
							
							
							</div>
					</td>
						<td><?php echo	$details['temp_name']?></td>
						<td>
						
						<?php	$reach=$db->query("SELECT  COUNT(DISTINCT recipient) AS Total,
COUNT(CASE WHEN msg_status='Success' THEN 1 END) AS Success,COUNT(CASE WHEN msg_status='Invalid Phone Number' THEN 1 END) AS Failed FROM sms_outbox WHERE campaign_id='".$details['campaign_id']."'")->fetch_array();
$success=$reach['Success'];
$failed=$reach['Failed'];
$total=$reach['Total'];
	
 ?>
						<div class="mb-mail-subject" style="color:#515253;"><?php echo round((($success/$total)*100),0) ?>%</div>
						
						
						
						
						</td>
					
					<td>
						<a href="campaign/<?php echo $details['campaign_id']?>" style="text-decoration:none;">
					
							<div class="mb-mail-meta">
							
								<div class="mb-mail-preview"><?php if($details['status']=='Ready'){ echo "Sent";} else {echo $details['status'];}?></div>
							
							
							</div>

						</a>
					</td>
					<td>
						<a href="campaign/<?php echo $details['campaign_id']?>" style="text-decoration:none;">
							
						
							<div class="pull-left">
							<div class="mb-mail-meta">
								
								<div class="mb-mail-preview"><?php echo time_ago2($details['date_added'])?></div>
							
							
							</div>
							</div>

						</a>
					</td>
					<td align="center" class="act">
					<div style="position:relative"><span class="action_but" id="<?php echo $details['campaign_id'];  ?>"></span>
    	<div class="gridact_drop" id="<?php echo $details['campaign_id'];  ?>a">
        	<div class="gridact_arrow"></div>
        	<ul>
            	<li><a href="campaign/<?php echo $details['campaign_id'];?>" class="grview"><?php echo 'View';?></a></li>
					<li><a href="campaign/<?php echo $details['campaign_id'];?>/send" class="grview"><?php echo 'Broadcast';?></a></li>
					<li><a href="view_details.php?id=<?php echo $details['campaign_id'];?>" class="gredit"><?php echo 'Replicate';?></a></li>
             
            </ul>
        </div>
    </div></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
</form>
<?php } else { echo $lang['NoCampaign'].' <a href="new-campaign">New Campaign</a>'; } ?>
</div>
</div>
</div>
</div>
</section>
<?php
require('inc/bottom.php'); 
?>