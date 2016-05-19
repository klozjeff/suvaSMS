<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');
require('core/dom.php');

$id = $db->real_escape_string($_GET['id']);

$page['name'] = $lang['List'];
$menu['list'] = 'active';

if(isset($_POST['import_contact'])) {
//echo $list_id = $_POST['list_id'];
	if($_FILES['contact_file']['name']) {
		$extension = strtolower(end(explode('.', $_FILES['contact_file']['name'])));
		if($extension == 'csv') {
			if(!$_FILES['contact_file']['error']) {
				$new_file_name =$_FILES['contact_file']['name'];
				if($_FILES['contact_file']['size'] > (1024000000)) {
					$valid_file = false;
					$error = 'Oops!  Your file size is to large.';
				}
				$valid_file = true;
				if($valid_file) {
					move_uploaded_file($_FILES['contact_file']['tmp_name'],'uploads/'.$new_file_name);
					$uploaded = true;
				}
			}
			else {
				$error = 'Error occured:  '.$_FILES['contact_file']['error'];
			}
		}
	}


	if(isset($uploaded)) {
		
	$list_id = $_POST['list_id'];
 $now=time();
		
    echo  $file = $_FILES['contact_file']['tmp_name'];
    $handle = fopen($file,"r");
	fgetcsv($handle,1000,",");
	do {
		$data0=$db->real_escape_string($data[0]);
		$data1=$db->real_escape_string($data[1]);
		$data2=$db->real_escape_string($data[2]);
		$data3=$db->real_escape_string($data[3]);
		$data4=$db->real_escape_string($data[4]);
		$data5=$db->real_escape_string($data[5]);
		$data6=$db->real_escape_string($data[6]);
		$data7=$db->real_escape_string($data[7]);
		$data8=$db->real_escape_string($data[8]);
		    $unique_id=rand(1,99999);
			$db->query("INSERT INTO list_leads(lead_uid,list_id,first_name,second_name,phone_no,email,custom_field1,custom_field2,custom_field3,custom_field4,custom_field5,date_added) VALUES('".$unique_id."','".$list_id."','".$data0."','".$data1."','".$data2."','".$data3."','".$data4."','".$data5."','".$data6."','".$data7."','".$data8."','".$now."')");
			
	}while($data=fgetcsv($handle,10000,",")) ;

	}



} 
if(isset($_POST['add_contact'])) 
{ 
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$phone_no = $_POST['phone_no'];
$email = $_POST['email'];
$custom_field1 = $_POST['custom_field1'];
$custom_field2 = $_POST['custom_field2'];
$custom_field3 = $_POST['custom_field3'];
$custom_field4 = $_POST['custom_field4'];
$custom_field5 = $_POST['custom_field5'];
$status = $_POST['status'];
$unique_id=rand(1,99999);
$list_id=$id;
$now=time();
$create=$db->query("INSERT INTO list_leads(lead_uid,list_id,first_name,second_name,phone_no,email,custom_field1,custom_field2,custom_field3,custom_field4,custom_field5,status,date_added) VALUES('".$unique_id."','".$list_id."','".$first_name."','".$last_name."','".$phone_no."','".$email."','".$custom_field1."','".$custom_field2."','".$custom_field3."','".$custom_field4."','".$custom_field5."','".$status."','".$now."')");
$listid=$db->insert_id;
 if($create) {

    $page['js'] .= '
    <script>
    swal({ 
      title: "'.$lang['Ready_Set_Go'].'",
      text: "'.$lang['Updated_List_Success'].'",
      imageUrl: "'.$domain.'/app/img/thumbs-up.png",
      confirmButtonText: "Ok",
      confirmButtonColor: "#3a3f51",
      closeOnConfirm: false,
      closeOnCancel: false
    },
    function(){
      window.location.href = "'.$domain.'/app/list-leads/'.$list_id.'";
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
   $detail = $db->query("SELECT list_leads.*,list.name FROM list_leads INNER JOIN list ON list_leads.list_id=list.list_id WHERE list_leads.list_id!='' AND list_leads.list_id='".$id."' AND list_leads.status='Active' $sort_by");
   $list=$db->query("SELECT * FROM list  WHERE list_id='".$id."'")->fetch_array();
}
if($user['is_admin'] == 2)
{
	 $detail = $db->query("SELECT list_leads.*,list.name FROM list_leads INNER JOIN list ON list_leads.list_id=list.list_id WHERE list_leads.list_id!='' AND list_leads.list_id='".$id."' AND list.customer_id=".$user['unique_id']." AND list_leads.status='Active' $sort_by");
//$list=$db->query("SELECT list.* FROM list WHERE customer_id=".$user['unique_id']."  $sort_by");
 $list=$db->query("SELECT * FROM list WHERE list_id='".$id."' AND customer_id='".$user['unique_id']."'")->fetch_array();
}


if(isset($_POST['delete_conversation'])) {
$delete = $_POST['delete'];
foreach($delete as $k=>$v) {
$db->query("UPDATE list_leads SET status='Archive' WHERE lead_id='".$v."'");
}
header('Location:'.$domain.'/app/list-leads/'.$id);
exit;
}


require('inc/top.php');
$status=array("Active","Inactive");

?>
<section>
<div class="content-wrapper">
<h3>
<?php echo $list['name'].'-'.$lang['List'].' Contacts/Leads';?> 
<span class="pull-right"> 
<a class="btn btn-primary" data-toggle="modal" data-target="#filterResults"> <i class="fa fa-sliders fa-fw"></i> <?php echo $lang['Filter']?> </a> 
<?php if(isset($_GET['filter'])) { ?>
<a href="list-leads?filter=true&sort_by=<?php echo $_GET['sort_by']?>&page_range=<?php echo $_GET['page_range']?>" class="btn btn-default"> <i class="fa fa-refresh fa-fw"></i> Refresh </a> 
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
<?php if($detail->num_rows >= 1) { ?>
<form action="" method="post">
<div class="row">
<div class="col-md-5">
<!--<button type="submit" name="new" class="btn btn-default"> <i class="fa fa-pencil fa-fw"></i> New Message </button>-->
<button type="submit" name="delete_conversation" class="btn btn-danger"> <i class="fa fa-trash fa-fw"></i> Archive </button>
<a data-toggle="modal" data-target="#newContact<?php echo $id;?>"><button type="" name="" class="btn btn-success"> <i class="fa fa-plus fa-fw"></i> New Contact</button></a>
<a data-toggle="modal" data-target="#importContact<?php echo $id;?>"><button type="" name="" class="btn btn-primary"> <i class="fa fa-file fa-fw"></i>Import CSV</button></a>
</div>
</div>
<br>
<div class="panel panel-default">
<div class="panel-body">
	<table class="table table-responsive mb-mails">
	   <thead>
	   <th style="text-align:center;">  </th>
	   <th style="text-align:left;">Contact ID</th>
                    <th style="text-align:left;"> First Name </th>
					  <th style="text-align:left;">Last Name</th>
					   <th style="text-align:left;">Phone No</th>
					    <th style="text-align:left;">Email Address</th>
						<?php
						$check=$db->query("SELECT * FROM fields_customized WHERE fields_customized.list_id='".$id."'");
$details_see=$check->fetch_array(); 
 if($check->num_rows>= 1)
 { 

						echo  '<th style="text-align:left;">'.$details_see['custom_field1'].'</th>
						  <th style="text-align:left;">'.$details_see['custom_field2'].'</th>
						   <th style="text-align:left;">'.$details_see['custom_field3'].'</th>
						    <th style="text-align:left;">'.$details_see['custom_field4'].'</th>
							 <th style="text-align:left;">'.$details_see['custom_field5'].'</th>';
							 
 }
 
 else
 {
	echo 	 '<th style="text-align:left;">[Custom Field 1]</th>
						  <th style="text-align:left;">[Custom Field 2]</th>
						   <th style="text-align:left;">[Custom Field 3]</th>
						    <th style="text-align:left;">[Custom Field 4]</th>
							 <th style="text-align:left;">[Custom Field 5]</th> ';
 }
 ?>
                    <th style="text-align:left;"> Status</th>
					  <th style="text-align:left;">	<div class="pull-right">Created</div></th>
                   
                </thead>
		<tbody>
			<?php while($details = $detail->fetch_array()) {
				
				//$price=$db->query("SELECT * FROM order_price WHERE order_price.order_id='".$details['id']."'")->fetch_array();
				?>
				<tr>
				<td style="width:40px;text-align:center;">
	                <div class="checkbox c-checkbox">
	                   <label>
	                      <input name="delete[]" type="checkbox" value="<?php echo $details['lead_id']?>">
	                      <span class="fa fa-check"></span>
	                   </label>
	                </div>
             	</td>
				<td >
	                <a href="list-contact/<?php echo $details['lead_id']?>" style="text-decoration:none;">
							
							
							
							<div class="mb-mail-meta">
								<div class="pull-left">
									<div class="mb-mail-preview">#<?php echo $details['lead_uid']?></div>
								</div>
								
							</div>

						</a>
             	</td>
					
				
					<td><?php echo	$details['first_name']?></td>
					
					<td><?php echo	$details['second_name']?></td>
					<td><?php echo	$details['phone_no']?></td>
					<td><?php echo	$details['email']?></td>
					<td><?php echo	$details['custom_field1']?></td>
					<td><?php echo	$details['custom_field2']?></td>
					<td><?php echo	$details['custom_field3']?></td>
					<td><?php echo	$details['custom_field4']?></td>
					<td><?php echo	$details['custom_field5']?></td>
				
				
						
					<td>
						<a href="order/<?php echo $details['list_id']?>" style="text-decoration:none;">
					
							<div class="mb-mail-meta">
							
								<div class="mb-mail-preview"><?php echo $details['status']?></div>
							
							
							</div>

						</a>
					</td>
				<td>
						<a href="order/<?php echo $details['id']?>" style="text-decoration:none;">
							
						
							<div class="pull-left">
							<div class="mb-mail-meta">
								
								<div class="mb-mail-preview"><?php echo time_ago2($details['date_added'])?></div>
							
							
							</div>
							</div>

						</a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
</form>
<?php } else { echo $lang['NoList'].' <a data-toggle="modal" data-target="#newContact'.$id.'">New Contact/Lead</a> OR <a data-toggle="modal" data-target="#importContact'.$id.'">Import File(.csv)</a>'; } ?>
</div>
</div>
</div>
</div>
</section>
<?php
require('inc/bottom.php'); 
?>