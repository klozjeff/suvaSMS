<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$page['name'] = $lang['Templates'];
$menu['template'] = 'active';
if(isset($_POST['create_temp'])) 
{ 
$temp_name = $_POST['temp_name'];
$new_template= $_POST['new_template'];
$unique_id=rand(1,99999);
$user_id=$user['unique_id'];
$now=time();
$create=$db->query("INSERT INTO sms_template(template_uid,customer_id,name,content,date_added) VALUES('".$unique_id."','".$user_id."','".$temp_name."','".$new_template."','".$now."')");
$tempid=$db->insert_id;	
 if($create) {

    $page['js'] .= '
    <script>
    swal({ 
      title: "'.$lang['Ready_Set_Go'].'",
      text: "'.$lang['Template_Success'].'",
      imageUrl: "'.$domain.'/app/img/thumbs-up.png",
      confirmButtonText: "Ok",
      confirmButtonColor: "#3a3f51",
      closeOnConfirm: false,
      closeOnCancel: false
    },
    function(){
      window.location.href = "";
    });
</script>
';
 }
 else
 { 
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

}
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
   $sms_template = $db->query("SELECT sms_template.* FROM sms_template WHERE template_id!='' $sort_by");
}
if($user['is_admin'] == 2)
{
$sms_template=$db->query("SELECT sms_template.* FROM sms_template WHERE customer_id=".$user['unique_id']."  $sort_by");
//echo "SELECT * FROM order_details WHERE customer_id =".$user['id']." AND (order_page >= $min_page AND order_page <= $max_page) $sort_by";
}


/*
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
*/

if(isset($_POST['delete_conversation'])) {
$delete = $_POST['delete'];
foreach($delete as $k=>$v) {
$db->query("UPDATE sms_template SET status='Archive' WHERE list_id='".$v."'");
}
header('Location: '.$domain.'/app/list');
exit;
}


require('inc/top.php');
$source=array("Web","API","Import");

?>

<section>
<div class="content-wrapper">
<h3>
<?php echo $lang['Templates'];?> 
<span class="pull-right"> 
<button type="submit" name="delete_conversation" class="btn btn-danger"> <i class="fa fa-trash fa-fw"></i> Archive </button>
<a data-toggle="modal" data-target="#newTemp"><button type="" name="" class="btn btn-success"> <i class="fa fa-plus fa-fw"></i> New Template</button></a>
</span> 
</h3>
<div class="container-fluid">
<div class="table-grid table-grid-desktop">
<div class="col">
<div class="clearfix mb">
<div class="btn-group pull-left">
</div>
</div>
<?php if($sms_template->num_rows >= 1) { ?>
<form action="" method="post">

<br>
<div class="panel panel-default">
<div class="panel-body">
<span class="pull-left"> 
<a class="btn btn-primary" data-toggle="modal" data-target="#filterResults"> <i class="fa fa-sliders fa-fw"></i> <?php echo $lang['Filter']?> </a> 
<?php if(isset($_GET['filter'])) { ?>
<a href="templates?filter=true&sort_by=<?php echo $_GET['sort_by']?>&page_range=<?php echo $_GET['page_range']?>" class="btn btn-default"> <i class="fa fa-refresh fa-fw"></i> Refresh </a> 
<?php } else { ?>
<a href="templates" class="btn btn-default"> <i class="fa fa-refresh fa-fw"></i> <?php echo $lang['Refresh']?> </a> 
<?php } ?>
</span> 
</form>
	<table class="table table-responsive mb-mails">
	   <thead>
	   <th style="text-align:center;">  </th>
	   <th style="text-align:left;"></th>
                    <th style="text-align:left;"> </th>
					  <th style="text-align:left;"></th>
					   <th style="text-align:left;"></th>
                    <th style="text-align:left;"> </th>
					  <th style="text-align:left;">	<div class="pull-left"></div></th>
                   
                </thead>
		<tbody>
			<?php while($details = $sms_template->fetch_array()) {
				
				//$count=$db->query("SELECT COUNT(*) AS subscribers FROM list_leads WHERE list_leads.status='Active' AND //list_leads.list_id='".$details['list_id']."'")->fetch_array();
				?>
				<tr style="border-bottom:0px solid #eee;">
				<td style="width:40px;text-align:center;">
	                <div class="checkbox c-checkbox">
	                   <label>
	                      <input name="delete[]" type="checkbox" value="<?php echo $details['template_id']?>">
	                      <span class="fa fa-check"></span>
	                   </label>
	                </div>
             	</td>
				<td >
	                <a href="template/<?php echo $details['template_id']?>" style="text-decoration:none;">
							
							
							
							<div class="mb-mail-meta">
								<div class="pull-left">
									<div class="mb-mail-preview">#<?php echo $details['template_uid']?></div>
								</div>
								
							</div>

						</a>
             	</td>
					
				
					<td>
						<a href="template/<?php echo $details['template_id']?>" style="text-decoration:none;">
							
						
							<div class="mb-mail-meta">
								<div class="pull-left">
									<div class="mb-mail-subject" style="color:#515253;"><?php echo $details['name']?></div>
								</div>
								<div class="mb-mail-preview"><strong style="color:#000">Last Edited on</strong>
								
								<?php  
								echo date(' D, M j, Y g:i a', $details['date_added']);
								
								/*time_ago2(date('M jS, Y g:i:s a', $details['date_added'])*/?></div>
							
							
							</div>

						</a>
					</td>
					<td align="center" class="act">
					<span class="pull-right"> 
<a data-toggle="modal" data-target="#editTemp<?php echo $details['template_id']?>" class="btn btn-default" > <i class="fa fa-eye fa-fw"></i> <?php echo "View" ?> </a> 

<a  data-toggle="modal" data-target="#editTemp<?php echo $details['template_id']?>" class="btn btn-default"> <i class="fa fa-pencil fa-fw"></i> <?php echo "Edit" ?> </a> 

</span> </td>
						
					
				</tr>
				<tr>
				<div class="modal fade" id="editTemp<?php echo $details['template_id']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<?php 
$sms_templates=$db->query("SELECT sms_template.* FROM sms_template WHERE template_id='".$details['template_id']."'")->fetch_array();
?>
  <div class="modal-dialog" role="document">
	<form action="" method="post" role="form" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Template</h4>
      </div>
      <div class="modal-body">
	  
	  	<div class="panel-body">
		<div class="col-md-12">
<div class="row">
  <div class="form-group has-feedback">
					<label>Name</label>
					 <input name="temp_name" value="<?php echo $sms_templates['name'];?>" class="form-control">
					 
 
				
				</div>
				
				
				</div>
				
<div class="row">
  <div class="form-group has-feedback">
					<label>Description</label>
					 <textarea name="new_template" class="form-control">
					 <?php echo $sms_templates['content'];?>
</textarea> 
				
				</div>
				
				
				</div>
				</div>
   
      </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang['Close']?></button>
        <button type="submit" name="create_temp" class="btn btn-success">Save</button>
      </div>
    </div>
 
  </form>
  </div>
</div>
</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>

<?php } else { echo $lang['NoList'].' <a data-toggle="modal" data-target="#newTemp">New Template</a>'; } ?>
</div>
</div>
</div>
</div>
</section>
<?php
require('inc/bottom.php'); 
?>