<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');
require('core/dom.php');

$id = $db->real_escape_string($_GET['id']);

if(isset($_POST['upload_file'])) {

	$order_id = $_POST['order_id'];

	if($_FILES['order_file']['name']) {
		$extension = strtolower(end(explode('.', $_FILES['order_file']['name'])));
		if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg' || $extension == 'doc' || $extension == 'pdf' || $extension == 'docx' || $extension == 'xls' || $extension == 'csv') {
			if(!$_FILES['profile_picture']['error']) {
				$new_file_name =$_FILES['order_file']['name'];
				if($_FILES['order_file']['size'] > (1024000)) {
					$valid_file = false;
					$error = 'Oops!  Your profile picture\'s size is to large.';
				}
				$valid_file = true;
				if($valid_file) {
					move_uploaded_file($_FILES['order_file']['tmp_name'],'uploads/'.$new_file_name);
					$uploaded = true;
				}
			}
			else {
				$error = 'Error occured:  '.$_FILES['order_file']['error'];
			}
		}
	}


	if(isset($uploaded)) {
			$db->query("INSERT INTO order_files (order_id,file_name,file_size,file_type,status,originator) VALUES ('".$order_id."','".$new_file_name."','".$_FILES['order_file']['size']."','".$_FILES['order_file']['type']."','1','Customer')");

	}



} 

if(isset($_POST['report_user'])) {
  $reason = $db->real_escape_string(strip_tags($_POST['reason']));
  $db->query("INSERT INTO reports (reported_id,reporter_id,reason,time) VALUES ('".$id."','".$user['id']."','".$reason."','".time()."')");
  header('Location: profile/'.$id);
  exit;
}

if(isset($_POST['release_payment'])) {
	 $orderid= $db->real_escape_string(strip_tags($_POST['order_id']));
  $orderpay= $db->real_escape_string(strip_tags($_POST['order_pay']));
    $orderprice= $db->real_escape_string(strip_tags($_POST['order_price']));
	$balance=$orderprice-$orderpay;
  $db->query("UPDATE order_price SET paid=paid+'".$orderpay."',balance='".$balance."' WHERE order_id='".$orderid."'");
  $db->query("UPDATE users SET energy=energy-'".$orderpay."' WHERE id='".$user['id']."'");
  //header('Location: order/'.$id);
 // exit;
}

function getInstagramPhotos($username) {
  $images = array();
  $html = file_get_html('http://websta.me/n/'.$username);
  if($html) {
    foreach($html->find('a[class=mainimg]') as $element) {
      $result = $element->innertext;
      $result = str_replace('src', '', $result);
      $result = str_replace('"', '', $result);
      $result = str_replace('img', '', $result);
      $result = str_replace('width', '', $result);
      $result = str_replace('height', '', $result);
      $result = str_replace('>', '', $result);
      $result = str_replace('<', '', $result);
      $result = str_replace('=', '', $result);
      $result = str_replace('306 heigh306', '', $result);
      $result = str_replace('s320x320/', '', $result);
      $images[] = trim($result);
    }
  }
  return $images;
}

$orderDetails = $db->query("SELECT order_details.*,users.email,users.full_name,users.profile_picture,users.last_active,users.bio,users.is_verified,users.id as cid FROM order_details INNER JOIN users ON order_details.customer_id=users.id WHERE order_details.id='$id'");
if($orderDetails->num_rows >= 1) {
 $orderDetails = $orderDetails->fetch_array();
} else {
 header('Location:../orders');
 exit;
}

$split_name = splitName($orderDetails['full_name']);

$page['name'] = sprintf($lang['sProfile'], $split_name['first_name']);
$menu['home'] = 'active';

$profile_picture = $domain.'/app/uploads/'.$orderDetails['profile_picture'];

$page['css'] .= '<link rel="stylesheet" href="'.$domain.'/vendor/lightbox/ekko-lightbox.min.css"><style> @media (min-width:750px){ .ekko-lightbox .modal-dialog { height:40% !important; width:40% !important; } } .glyphicon-chevron-right,.glyphicon-chevron-left { text-decoration:none !important; } </style>';
$page['js'] .= '
<script src="'.$domain.'/vendor/slimScroll/jquery.slimscroll.min.js"></script>
<script src="'.$domain.'/vendor/lightbox/ekko-lightbox.min.js"></script>
<script>
$(document).delegate(\'*[data-toggle="lightbox"]\', \'click\', function(event) {
  event.preventDefault();
  $(this).ekkoLightbox();
});
</script>
';


if($user['is_incognito'] == 0) {
// Record profile view
  $check = $db->query("SELECT id FROM profile_views WHERE
   viewer_id='".$user['id']."' AND profile_id='".$orderDetails['cid']."' LIMIT 1");
  if($check->num_rows == 0) {
    $time = time();
    $db->query("INSERT INTO profile_views (profile_id,viewer_id,time) VALUES ('$id','".$user['id']."','".$time."')");
  }
}
// Check profile likes
$check = $db->query("SELECT id FROM profile_likes WHERE 
  viewer_id='".$user['id']."' AND profile_id='".$orderDetails['cid']."' LIMIT 1");
if($check->num_rows == 0) {
  $can_like = 1;
} else {
  $can_like = 0;
}
// Check friend status
$check = $db->query("SELECT id FROM friend_requests WHERE 
  (user1='".$user['id']."' AND user2='".$orderDetails['cid']."') OR (user1='".$orderDetails['cid']."' AND user2='".$user['id']."') LIMIT 1");
if($check->num_rows == 0) {
  $send_request = 1;
} else {
  $send_request = 0;
}
$check = $db->query("SELECT id FROM friend_requests WHERE
 (user1='".$user['id']."' AND user2='".$profile['id']."' AND 
  accepted='1') OR (user1='".$profile['id']."' AND user2='".$user['id']."' AND accepted='1')");
if($check->num_rows == 0) {
  $is_friend = 0;
} else {
  $is_friend = 1;
}
// Get profile views
$profile_views = $db->query("SELECT * FROM profile_views WHERE profile_id='".$orderDetails['cid']."'");
$profile_views = $profile_views->num_rows;
// Get profile likes
$profile_likes = $db->query("SELECT * FROM profile_likes WHERE profile_id='".$orderDetails['cid']."'");
$profile_likes = $profile_likes->num_rows;
// Get friends
$friends = $db->query("SELECT * FROM friend_requests WHERE accepted='1' AND (user2='".$orderDetails['cid']."' OR user1='".$orderDetails['cid']."')");
$friend_count= $friends->num_rows;
$friends = $db->query("SELECT * FROM friend_requests WHERE accepted='1' AND (user2='".$orderDetails['cid']."' OR user1='".$orderDetails['cid']."') LIMIT 4");
// Get gifts
$gifts = $db->query("SELECT * FROM gifts WHERE user2='".$orderDetails['cid']."' LIMIT 4");

$page['js'] .= '
<script>
function likeProfile(id) {
  $.get("'.$domain.'/app/ajax/likeProfile.php?id="+id, function(data) {
    $("#like").html(data);
    var current_likes = parseInt("'.$profile_likes.'");
    $("#likes_number").text(current_likes+1);
  });
}
</script>
';

$media = $db->query("SELECT * FROM order_files WHERE order_id='".$orderDetails['id']."' AND originator='Customer'");
//echo $orderDetails['customer_id'];
//echo $user['id'];
/**CHAT INFROMATION PLEASE**/
if($user['id']==$orderDetails['customer_id'])
{
	//echo "SELECT id FROM conversations WHERE (user1='20' AND user2='".$orderDetails['cid']."') OR (user1='".$orderDetails['cid']."' AND user2='20'";
$convers = $db->query("SELECT id FROM conversations WHERE (user1='20' AND user2='".$orderDetails['cid']."') OR (user1='".$orderDetails['cid']."' AND user2='20')");
//echo "SELECT id FROM conversations WHERE (user1='".$user['id']."' AND user2='".$orderDetails['cid']."') OR (user1='".$orderDetails['cid']."' AND user2='".$user['id']."')";
if($convers->num_rows==0) {
  $db->query("INSERT INTO conversations (user1,user2,time) VALUES ('".$user['id']."','20','".time()."')");
  $convers_id = $db->insert_id;
} else {
  $convers = $convers->fetch_array();
  $convers_id = $convers['id'];
}
$receiver='20';
}

else
{
$convers = $db->query("SELECT id FROM conversations WHERE (user1='".$user['id']."' AND user2='".$orderDetails['cid']."') OR (user1='".$orderDetails['cid']."' AND user2='".$user['id']."')");
//echo "SELECT id FROM conversations WHERE (user1='".$user['id']."' AND user2='".$orderDetails['cid']."') OR (user1='".$orderDetails['cid']."' AND user2='".$user['id']."')";
if($convers->num_rows==0) {
  $db->query("INSERT INTO conversations (user1,user2,time) VALUES ('".$user['id']."','".$orderDetails['cid']."','".time()."')");
  $convers_id = $db->insert_id;
} else {
  $convers = $convers->fetch_array();
  $convers_id = $convers['id'];
}
	
$receiver=$orderDetails['cid'];	
}

 if($orderDetails['cid']!= $user['id']) 
	{
$split_name = splitName($_user['full_name']);
$convs_name= $split_name['first_name'];
	}
	else
	{
	$convs_name= 'Support';	
	}

$page['js'] = '
<script src="'.$domain.'/vendor/slimScroll/jquery.slimscroll.min.js"></script>

<script>

function refreshChat() {
 var id = "'.$convers_id.'";
 var receiver = "'.$convs_name.'";
 $.get("'.$domain.'/app/ajax/refreshChat.php?id="+id+"&receiver="+receiver, function(data) {
    $("#messages").html(data);
  });
}

window.setInterval(function(){
  refreshChat();
}, 1000);

function sendMessage() {
  var user2 = "'.$receiver.'";
  var message = $("#message");
    if(message.val() != "" && message.val() != " ") {
    $.get("'.$domain.'/app/ajax/sendMessage.php?id="+user2+"&msg="+encodeURIComponent(message.val()), function(data) {
    $("#messages").html(data);
    message.val("");
    });
    }
}

$(document).keypress(function(e) {
    if(e.which == 13) {
        sendMessage();
    }
});

function appendToMessage(str) {
var message = $("#message");
message.val(message.val()+" "+str);
$("#emoticonList").modal("hide");
}

</script>
';

//echo $convers_id;
$messages = $db->query("SELECT * FROM messages WHERE convers_id='".$convers_id."' AND (sender_id = '20' OR sender_id='".$orderDetails['cid']."') ORDER BY id DESC");



//echo "SELECT * FROM messages WHERE convers_id='".$convers_id."' AND (sender_id = '".$user['id']."' OR sender_id='".$orderDetails['cid']."') ORDER BY id DESC";






/**END OF CHAT DETAILS**/

require('inc/top.php');
?>

  <section>
  <div class="content-wrapper">
  <div class="unwrap">
  <div class="container-fluid">
    <div style="background-image: url(../img/profile-bg.png); background-repeat: repeat; background-size: inherit;" class="bg-cover">
      <div class="p-xl text-left text-white">
	  <ul class="nav navbar-nav navbar-left" style="margin-right:200px;margin-top:20px;padding:5px">
<li>
<span style="color:#27A1DA;font-weight:bold;"> No of Pages:</span> <?php echo $orderDetails['order_pages']?> Pages
</li>

<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>

<li>
<span style="color:#27A1DA;font-weight:bold;"> Order Status: </span> <?php echo $orderDetails['status']?>
</li>
<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
<li>
<i class="icon-clock"></i><span style="color:#27A1DA;font-weight:bold;"> Deadline:  </span> <?php echo time_ago($orderDetails['order_deadline'])?>
</li>



</ul>
	 <!-- <ul class="nav navbar-nav navbar-left" style="margin-right:400px;margin-top:10px;padding:5px">
<li>
<span style="color:#27A1DA;font-weight:bold;"> Type of Paper: </span> <?php echo $orderDetails['order_type']?>
</li>

<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>

<li>
<span style="color:#27A1DA;font-weight:bold;"> Academic Level: </span> <?php echo $orderDetails['academic_level']?>
</li>
<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
<li>
<span style="color:#27A1DA;font-weight:bold;"> Citation/Format: </span>  <?php echo $orderDetails['citation_style']?>
</li>



</ul>-->
        <?php if(!isGraph(getProfilePicture($domain,$profile))) { ?>
        <a href="<?php echo getProfilePicture($domain,$profile)?>" data-toggle="lightbox">
          <img src="<?php echo $domain;?>/app/<?php echo getProfilePicture($domain,$orderDetails)?>" alt="<?php echo $orderDetails['full_name']?>" width="50" height="80" class="img-thumbnail img-circle user-picture">
        </a>
        <? } else { ?>
        <img src="<?php echo $domain;?>/app/<?php echo getProfilePicture($domain,$orderDetails)?>" alt="<?php echo $orderDetails['full_name']?>" width="50" height="80" class="img-thumbnail img-circle user-picture">
        <? } ?>
        <h3 class="m0"><?php echo $orderDetails['full_name']?> <?php if(time()-$orderDetails['last_active'] <= 300) { ?> <span class="circle circle-success circle-lg" style="margin:0px;"></span> <?php } else { ?> <span class="circle circle-danger circle-lg" style="margin:0px;"></span> <?php } ?></h3> <?php if($orderDetails['is_verified'] == 1) { ?> <div class="label label-success" style="margin-right:5px;"> <i class="fa fa-check"></i> <?php echo $lang['Verified']?> </div> <?php } ?>
        <small><?php echo parseEmoticons($domain,$orderDetails['bio'])?></small>
     
	 </div>
	  
	  
    </div>

    <?php if($orderDetails['cid']!= $user['id']) 
	{ 
?>
    <div class="p-lg">
      <div class="row">
        <div class="col-md-12">
       <div class="pull-left">
         <h3><?php echo $orderDetails['order_topic']?></h3>
          </div>
          <div class="pull-right">
		  	  
		    <button class="btn btn-primary" onclick="savePaperDetails()"> <i class="fa fa-cloud fa-fw"></i> <?php echo $lang['Save']?> </button> 
          
		   <span id="addFriend">
              <?php if($orderDetails['status'] =='Completed') { ?>
              <button class="btn btn-warning" disabled> <i class="fa fa-user-plus fa-fw"></i> <?php echo $lang['Completed']?> </button>
              <?php }  elseif($orderDetails['status'] =='Active') { ?>
              <button class="btn btn-primary" onclick="markCompleted(<?php echo $orderDetails['cid']?>,<?php echo $id?>)"> <i class="fa fa-user-plus fa-fw"></i> <?php echo $lang['Completed_Mark']?> </button>
              <?php } ?>
            </span>
            <a href="#" data-toggle-state="offsidebar-open" data-no-persist="true" class="btn btn-primary"> <i class="fa fa fa-comment fa-fw"></i> <?php echo $lang['Chat']?> </a> 
       
          </div>
        </div>
      </div>
    </div>
    <?php 
	
	} 
	
	else
	{
		?>
		
	   <div class="p-lg">
      <div class="row">
        <div class="col-md-12">
     <div class="pull-left">
             <h3><?php echo $orderDetails['order_topic']?></h3>
          </div>
          <div class="pull-right">
		  	  
		   <a data-toggle="modal" data-target="#releasePayment<?php echo $id;?>"> <button class="btn btn-danger"> <i class="fa fa-dollar fa-fw"></i> <?php echo "Release Payment"?> </button> </a>
          
		   <span id="addFriend">
              <?php if($orderDetails['status'] =='Completed') { ?>
              <button class="btn btn-danger" disabled> <i class="fa fa-user-plus fa-fw"></i> <?php echo $lang['Completed']?> </button>
              <?php }  elseif($orderDetails['status'] =='Active') { ?>
              <button class="btn btn-danger"> <i class="fa fa-user-plus fa-fw"></i> <?php echo "In Progress" ?> </button>
              <?php } ?>
            </span>
            <a href="#" data-toggle-state="offsidebar-open" data-no-persist="true" class="btn btn-danger"> <i class="fa fa fa-comment fa-fw"></i> <?php echo $lang['Chat']?> </a> 
       
          </div>
        </div>
      </div>
    </div>

<?php	
	}
		?>
  
    <div class="p-lg">
      <div class="row">
        <div class="col-md-9">

          <div class="panel panel-default" id="details">
          <div class="panel-heading panel-title"> 
		  Paper Details<div class="pull-right">
		  
		     <?php if($orderDetails['cid']!== $user['id']) 
	{ 
?>
		  <a href="<?php echo $domain;?>/app/upload/<?php echo $id?>" class="btn btn-danger btn-xs"> 
		  <i class="fa fa-upload fa-fw"></i> <?php echo $lang['Upload']?> </a> 
		  <a href="<?php echo $domain;?>/app/manage-files/<?php echo $id?>" class="btn btn-danger btn-xs">  <i class="fa fa-gear fa-fw"></i> <?php echo $lang['Manage']?> </a> </div></div>
           <?php
	}
	
	else
	{
		?>
		
		
		  <a href="<?php echo $domain;?>/app/manage-files/<?php echo $id?>" class="btn btn-danger btn-xs">  <i class="fa fa-gear fa-fw"></i>Paper Uploads </a> </div></div>
         
		<?php
	}
	?>

		   <hr>
			<div class="panel-body">
           <textarea cols="105" rows="17" id="detailsr"><?php echo $orderDetails['paper_details'];?></textarea>
          </div>
        </div>
    
    </div>
    <div class="col-md-3">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="text-center">
           Paper Instructions
          </div>
          <hr>
          <ul class="list-unstyled ph-xl">
            <li> <?php echo $orderDetails['order_details']?>  </li>
           
          </ul>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">
          <?php echo $lang['Attachments']?><span class="pull-right" style="cursor:pointer"> <a data-toggle="modal" data-target="#uploadFile<?php echo $id;?>"><i class="fa fa-cloud"></i> New File</a></span></div>
        <hr>
		<div class="nav-wrapper">
	<ul style="list-style:none;float:left" class="">
              <?php 
              if($media->num_rows == 0) { echo '<p>'.$lang['Nothing_To_Show'].'</p>';
              } else {
              while($file = $media->fetch_array()) {
                ?>
               <li >
                  <a href="uploads/<?php echo $file['file_name']?>" ><?php echo $file['file_name']?></a>
                </li>
                <?  
              }
            } ?>
			</ul>
          </div>
        </div>
      <?php
        // Ad Slot
        require("core/config/config-ads.php");
        if($ads && $user['has_disabled_ads'] == 0) {
          echo '<div class="img-responsive hidden-xs hidden-sm">'.$ad_side.'</div>';
        }
        ?>
      </div>
    </div>
    <?php
    // Ad Slot
    if($ads && $user['has_disabled_ads'] == 0) {
      echo '<div class="img-responsive centered-block hidden-xs hidden-sm">'.$ad_bottom.'</div>';
    }
    ?>
  </div>

  </div>
  </div>
  </div>
  </section> 
  
  <aside class="offsidebar hide">
<!-- START Off Sidebar (right)-->
<nav>
<div role="tabpanel">
<!-- Nav tabs-->
<ul role="tablist" class="nav nav-tabs nav-justified">

</ul>
<!-- Tab panes-->
<div class="tab-content">
<div id="app-chat" role="tabpanel" class="tab-pane fade active in">
<h3 class="text-center text-thin"><?php echo $lang['Chat']?></h3>
<div data-height="500" data-scrollable="" class="list-group">
<div id="messages">
<?php 
if($messages->num_rows >= 1) { 
while($message = $messages->fetch_array()) { 
$sender=$db->query("SELECT id,profile_picture,full_name FROM users WHERE id='".$message['sender_id']."'")->fetch_array(); 
?>
<a href="#" class="list-group-item">
<div class="media-box">
<div class="pull-left">
  <img src="<?php echo $domain;?>/app/<?php echo getProfilePicture($domain,$sender)?>" class="media-box-object img-rounded thumb32">

  </div>
<div class="media-box-body clearfix">
  <small class="pull-right"><?php echo time_ago($message['time'])?></small>
  <strong class="media-box-heading text-primary">
    <?php echo $sender['full_name']?></strong>
    <p class="mb-sm">
      <small><?php echo parseEmoticons($domain,$message['message'])?></small>
    </p>
  </div>
</div>
</a>
<?php 
} 
} else {
	 if($orderDetails['cid']!== $user['id']) 
	{
$split_name = splitName($_user['full_name']);
$convs_name= $split_name['first_name'];
	}
	else
	{
	$convs_name= 'Support Team';	
	}
echo '<p class="text-center" style="height:90px;line-height:90px"> '.$lang['Start_Conversation'].' <b>'.$convs_name.'</b></p>'; 
    
} 
?>
</div>
</div>
<div class="panel-footer clearfix has-feedback">
<div class="input-group">
<input type="text" id="message" placeholder="<?php echo $lang['Enter_Message']?>" class="form-control input-sm" required>
<span class="input-group-btn">
<button class="btn btn-default btn-sm" onclick="sendMessage()"><i class="fa fa-send-o"></i></button>
<a id="emoticonsShow" data-toggle="modal" data-target="#emoticonList" class="btn btn-default btn-sm"><i class="fa fa-smile-o"></i></a>

</span>
</div>
</div>
</div>
</div>
</div>
</nav>
<!-- END Off Sidebar (right)-->
</aside>
<?php
require('inc/bottom.php'); 
?>
