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

$check=$db->query("SELECT *,list.name FROM fields_customized RIGHT JOIN list on fields_customized.list_id=list.list_id WHERE fields_customized.list_id='".$id."'");
$details=$check->fetch_array(); 
if(isset($_POST['save'])) 
{ 
$custom_field1 = $_POST['field1'];
$custom_field2 = $_POST['field2'];
$custom_field3 = $_POST['field3'];
$custom_field4 = $_POST['field4'];
$custom_field5 = $_POST['field5'];
$list_id=$id;
$now=time();
$status='Active';

 if($check->num_rows>= 1)
 { 
	
         $db->query("UPDATE fields_customized SET custom_field1='$custom_field1',custom_field2='$custom_field2',custom_field3='$custom_field3',custom_field4='$custom_field4',custom_field5='$custom_field5' WHERE list_id='$id'"); 
	 
 }
 else
 {


$create=$db->query("INSERT INTO fields_customized(list_id,custom_field1,custom_field2,custom_field3,custom_field4,custom_field5,status,date_added) VALUES('".$list_id."','".$custom_field1."','".$custom_field2."','".$custom_field3."','".$custom_field4."','".$custom_field5."','".$status."','".$now."')");
//$listid=$db->insert_id;
 if($create) {

    $page['js'] .= '
    <script>
    swal({ 
      title: "'.$lang['Ready_Set_Go'].'",
      text: "Customized List Fields Successfully",
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




require('inc/top.php');
$status=array("Active","Inactive");

?>
<section>
<div class="content-wrapper">
<h3>
<?php echo $details['name'].'-'.$lang['List'].' Contacts/Leads';?> 
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

<form action="" method="post" role="form" id="preferences">
<div class="col-md-4">
<div class="row">
<div class="form-group has-feedback">
    <label for="sexual_interest">Custom Field 1</label>
  
     <input type="text" name="field1" class="form-control" value="<?php echo $details['custom_field1'];?>">
  

  </div>
  

<div class="form-group has-feedback">
    <label for="sexual_interest">Custom Field 3</label>
  
   <input type="text" name="field3" class="form-control" value="<?php echo $details['custom_field3'];?>">
  

  </div>
  <div class="form-group has-feedback">
    <label for="sexual_interest">Custom Field 5</label>
  
   <input type="text" name="field5" class="form-control" value="<?php echo $details['custom_field5'];?>">
  

  </div>
  
 
  
	</div></div>
	
	<div class="col-md-1">
	</div>
	<div class="col-md-4">
<div class="row">
<div class="form-group has-feedback">
    <label for="sexual_interest">Custom Field 2</label>
  
     <input type="text" name="field2" class="form-control" value="<?php echo $details['custom_field2'];?>">
  

  </div>
  

<div class="form-group has-feedback">
    <label for="sexual_interest">Custom Field 4</label>
  
   <input type="text" name="field4" class="form-control" value="<?php echo $details['custom_field4'];?>">
  

  </div>
  
 
   <input type="submit" name="save" class="btn btn-success pull-right" value="<?php echo $lang['Save']?>">
	</div></div>
	
  </form>

</div>
</div>
</div>
</div>
</section>
<?php
require('inc/bottom.php'); 
?>