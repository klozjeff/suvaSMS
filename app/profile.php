<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');
require('core/dom.php');

$id = $db->real_escape_string($_GET['id']);

if(isset($_POST['request_writer'])) 
{ 

$writer_id = $_POST['writer_id'];
$order_id = $_POST['order_id'];

$db->query("UPDATE order_details SET writer_preference='".$writer_id."' WHERE id='".$order_id."'");

}

if(isset($_POST['report_user'])) {
  $reason = $db->real_escape_string(strip_tags($_POST['reason']));
  $db->query("INSERT INTO reports (reported_id,reporter_id,reason,time) VALUES ('".$id."','".$user['id']."','".$reason."','".time()."')");
  header('Location: profile/'.$id);
  exit;
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

$profile = $db->query("SELECT users.*,(SELECT COUNT(DISTINCT writer_preference) FROM order_details WHERE order_details.writer_preference='".$id."' AND order_details.status='Completed') as totalOrders,(SELECT COUNT(DISTINCT writer_preference) FROM order_details WHERE order_details.writer_preference='".$id."' AND order_details.status='Active') as ActiveOrders FROM users WHERE id='$id'");
if($profile->num_rows >= 1) {
 $profile = $profile->fetch_array();
} else {
 header('Location:writers');
 exit;
}

$split_name = splitName($profile['full_name']);

$page['name'] = sprintf($lang['sProfile'], $split_name['first_name']);
$menu['writers'] = 'active';

$profile_picture = 'uploads/'.$profile['profile_picture'];

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
   viewer_id='".$user['id']."' AND profile_id='".$profile['id']."' LIMIT 1");
  if($check->num_rows == 0) {
    $time = time();
    $db->query("INSERT INTO profile_views (profile_id,viewer_id,time) VALUES ('$id','".$user['id']."','".$time."')");
  }
}
// Check profile likes
$check = $db->query("SELECT id FROM profile_likes WHERE 
  viewer_id='".$user['id']."' AND profile_id='".$profile['id']."' LIMIT 1");
if($check->num_rows == 0) {
  $can_like = 1;
} else {
  $can_like = 0;
}
// Check friend status
$check = $db->query("SELECT id FROM friend_requests WHERE 
  (user1='".$user['id']."' AND user2='".$profile['id']."') OR (user1='".$profile['id']."' AND user2='".$user['id']."') LIMIT 1");
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
$profile_views = $db->query("SELECT * FROM profile_views WHERE profile_id='".$id."'");
$profile_views = $profile_views->num_rows;
// Get profile likes
$profile_likes = $db->query("SELECT * FROM profile_likes WHERE profile_id='".$id."'");
$profile_likes = $profile_likes->num_rows;
// Get friends
$friends = $db->query("SELECT * FROM friend_requests WHERE accepted='1' AND (user2='".$profile['id']."' OR user1='".$profile['id']."')");
$friend_count= $friends->num_rows;
$friends = $db->query("SELECT * FROM friend_requests WHERE accepted='1' AND (user2='".$profile['id']."' OR user1='".$profile['id']."') LIMIT 4");
// Get gifts
$gifts = $db->query("SELECT * FROM gifts WHERE user2='".$profile['id']."' LIMIT 4");

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

$media= $db->query("SELECT * FROM media WHERE user_id='".$profile['id']."' LIMIT 20");
$orders = $db->query("SELECT * FROM order_details WHERE (customer_id='".$profile['id']."' OR writer_preference='".$profile['id']."') ORDER BY id DESC LIMIT 20");
/**CHAT INFROMATION PLEASE**/

$convers = $db->query("SELECT id FROM conversations WHERE (user1='".$user['id']."' AND user2='".$profile['id']."') OR (user1='".$profile['id']."' AND user2='".$user['id']."')");
//echo "SELECT id FROM conversations WHERE (user1='".$user['id']."' AND user2='".$orderDetails['cid']."') OR (user1='".$orderDetails['cid']."' AND user2='".$user['id']."')";
if($convers->num_rows==0) {
  $db->query("INSERT INTO conversations (user1,user2,time) VALUES ('".$user['id']."','".$profile['id']."','".time()."')");
  $convers_id = $db->insert_id;
} else {
  $convers = $convers->fetch_array();
  $convers_id = $convers['id'];
}


$page['js'] = '
<script src="'.$domain.'/vendor/slimScroll/jquery.slimscroll.min.js"></script>

<script>

function refreshChat() {
 var id = "'.$convers_id.'";
 var receiver = "'.$split_name['first_name'].'";
 $.get("'.$domain.'/app/ajax/refreshChat.php?id="+id+"&receiver="+receiver, function(data) {
    $("#messages").html(data);
  });
}

window.setInterval(function(){
  refreshChat();
}, 1000);

function sendMessage() {
  var user2 = "'.$profile['id'].'";
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


$messages = $db->query("SELECT * FROM messages WHERE convers_id='".$convers_id."' AND (sender_id = '".$user['id']."' OR sender_id='".$orderDetails['cid']."') ORDER BY id DESC");



//echo "SELECT * FROM messages WHERE convers_id='".$convers_id."' AND (sender_id = '".$user['id']."' OR sender_id='".$orderDetails['cid']."') ORDER BY id DESC";






/**END OF CHAT DETAILS**/
require('inc/top.php');
?>

  <section>
  <div class="content-wrapper">
  <div class="unwrap">
  <div class="container-fluid">
    <div style="background-image: url(../img/profile-bg.png); background-repeat: repeat; background-size: inherit;" class="bg-cover">
      <div class="p-xl text-center text-white">
        <?php if(!isGraph(getProfilePicture($domain,$profile))) { ?>
        <a href="<?php echo getProfilePicture($domain,$profile)?>" data-toggle="lightbox">
          <img src="<?php echo $domain;?>/app/<?php echo getProfilePicture($domain,$profile)?>" alt="<?php echo $profile['full_name']?>" class="img-thumbnail img-circle thumb128">
        </a>
        <? } else { ?>
        <img src="<?php echo $domain;?>/app/<?php echo getProfilePicture($domain,$profile)?>" alt="<?php echo $profile['full_name']?>" class="img-thumbnail img-circle thumb128">
        <? } ?>
        <h3 class="m0"><?php echo $profile['full_name']?> <?php if(time()-$profile['last_active'] <= 300) { ?> <span class="circle circle-success circle-lg" style="margin:0px;"></span> <?php } else { ?> <span class="circle circle-danger circle-lg" style="margin:0px;"></span> <?php } ?></h3> <?php if($profile['is_verified'] == 1) { ?> <div class="label label-success" style="margin-right:5px;"> <i class="fa fa-check"></i> <?php echo $lang['Verified']?> </div> <?php } ?>
        <small><?php echo parseEmoticons($domain,$profile['bio'])?></small>
      </div>
    </div>
    <div class="text-center bg-gray-dark p-lg mb-xl">
      <div class="row row-table">
       
        <div class="col-xs-4 br">
         <!-- <h3 class="m0" id="likes_number">4.5/5</h3>-->
		 <p> <div title="Customers rating of the order: 10" data-rating-value="5" class="rating_view right ">
				<meta content="1" itemprop="worstRating">
				<meta content="10" itemprop="ratingValue">
				<meta content="10" itemprop="bestRating">
				<div class="realrate r100"></div>
			</div></p>
        <!--  <p class="m0"><?php echo $lang['Rating']?></p>-->
        </div>
		 <div class="col-xs-4 br">
          <h3 class="m0"><?php echo $profile['totalOrders']+300?></h3>
          <p class="m0">
            <span class="hidden-xs">Orders Completed</span>
           
          </p>
        </div>
        <div class="col-xs-4">
          <h3 class="m0"><?php echo $profile['ActiveOrders']+5?></h3>
          <p class="m0">Pending Orders</p>
        </div>
      </div>
    </div>
    <?php if($profile['id'] !== $user['id']) { ?>
    <div class="p-lg">
      <div class="row">
        <div class="col-md-12">
          <div class="pull-left">
            <span id="like">
              <?php if($can_like == 1) { ?>
              <button class="btn btn-danger" onclick="likeProfile(<?php echo $id?>)"> <i class="fa fa-heart fa-fw"></i> <?php echo $lang['Rate']?> </button>
              <?php } else { ?>
              <button class="btn btn-danger" disabled> <i class="fa fa-heart fa-fw"></i> <?php echo $lang['Liked']?> </button>
              <?php } ?>
            </span>
          </div>
          <div class="pull-right" style="margin-right:100px">
           <a href="#" data-toggle-state="offsidebar-open" data-no-persist="true" class="btn btn-primary"> <i class="fa fa fa-comment fa-fw"></i> <?php echo $lang['Chat']?> </a> 
     <?php
	 if($user['is_admin'] == 1 || $user['is_admin'] == 2)
{
	?>
           <a data-toggle="modal" data-target="#requestWriter<?php echo $profile['id'];?>"> 
              <button class="btn btn-primary" > <i class="fa fa-user-plus fa-fw"></i> Request Writer</button>
            </a>
			
			<?php
}
?>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
    <?php if($profile['private_profile'] == 0 || $profile['id'] == $user['id'] || $is_friend == 1) { ?>
    <div class="p-lg">
      <div class="row">
        <div class="col-md-12">

          <div class="panel panel-default">
            <div class="panel-heading panel-title">Latest Orders  </div>
			<hr>
            <div class="panel-body">
              <table class="table mb-mails">
		<tbody>
			<?php while($details = $orders->fetch_array()) {
			?>
				<tr>
				<td style="width:40px;">
	                <div class="checkbox c-checkbox">
	                   <label>
	                      <input name="delete[]" disabled type="checkbox" value="<?php echo $details['id']?>">
	                      <span class="fa fa-check"></span>
	                   </label>
	                </div>
             	</td>
					
					
					<td>
						<a href="" style="text-decoration:none;">
								<div class="mb-mail-date pull-right"><?php echo time_ago($details['time'])?></div>
						
							<div class="mb-mail-meta">
								<div class="pull-left">
									<div class="mb-mail-subject" style="color:#515253;"><?php echo $details['order_topic']?></div>
								</div>
								<div class="mb-mail-preview"><?php echo $details['order_type']?></div>
							
							
							</div>

						</a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
          </div>
        </div>
 
    </div>
   <!-- <div class="col-md-3">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="text-center">
            <h3 class="mt0"><?php echo $lang['About']?></h3>
          </div>
          <hr>
          <ul class="list-unstyled ph-xl">
            <li> <i class="fa fa-map-marker fa-fw"></i> <?php echo $profile['city']?> <?php if(!empty($profile['city'])) { echo ','; } ?> <?php echo $profile['country']?> </li>
            <li> <i class="fa fa-birthday-cake fa-fw"></i> <?php echo $profile['age']?> <?php echo $lang['years_old']?> </li>
            <li> <i class="fa fa-venus-mars fa-fw"></i> <?php echo $lang[$profile['gender']]?> </li>
          </ul>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">
          <?php echo $lang['Friends']?></div>
          <?php if($friends->num_rows >= 1) { ?>
          <div class="list-group">
            <?php while($friend = $friends->fetch_array()) { $fr = $db->query("SELECT id,profile_picture,age,full_name FROM users WHERE (id='".$friend['user1']."' OR id='".$friend['user2']."') AND id != '".$profile['id']."'")->fetch_array(); ?>
            <a href="profile/<?php echo $fr['id']?>" class="media p mt0 list-group-item">
              <span class="pull-left">
                <img src="<?php echo getProfilePicture($domain,$fr)?>" alt="<?php echo $fr['full_name']?>" class="media-object img-circle thumb32">
              </span>
              <span class="media-body">
                <span class="media-heading">
                  <strong><?php echo $fr['full_name']?></strong>
                  <br>
                  <small class="text-muted"><?php echo $fr['age']?> <?php echo $lang['years_old']?></small>
                </span>
              </span>
            </a>
            <?php } ?>
            <a href="friends/<?php echo $id?>" class="media p mt0 list-group-item text-center text-muted"><?php echo $lang['View_All_Friends']?></a>
          </div>
          <?php } else { echo '<p class="text-center pb">'.$lang['Nothing_To_Show'].'</p>'; } ?>
        </div>
        <div class="panel panel-default">
        <div class="panel-heading">
          <?php echo $lang['Gifts']?></div>
          <?php if($gifts->num_rows >= 1) { ?>
          <div class="list-group">
            <?php while($gift = $gifts->fetch_array()) { $sender = $db->query("SELECT full_name FROM users WHERE id='".$gift['user1']."'")->fetch_array(); ?>
            <a href="gifts/<?php echo $profile['id']?>" class="media p mt0 list-group-item">
              <span class="pull-left">
                <img src="<?php echo $domain?>/<?php echo $gift['path']?>" class="media-object img-circle thumb32">
              </span>
              <span class="media-body">
                <span class="media-heading">
                  <strong><?php echo $sender['full_name']?></strong>
                  <br>
                  <small class="text-muted"><?php echo time_ago($gift['time'])?></small>
                </span>
              </span>
            </a>
            <?php } ?>
            <a href="gifts/<?php echo $id?>" class="media p mt0 list-group-item text-center text-muted"><?php echo $lang['View_All_Gifts']?></a>
          </div>
          <?php } else { echo '<p class="text-center pb">'.$lang['Nothing_To_Show'].'</p>'; } ?>
        </div>
       <a data-toggle="modal" data-target="#reportUser" class="btn btn-danger btn-block"> <i class="fa fa-flag fa-fw"></i> <?php echo $lang['Report']?> </a>
        <?php
        // Ad Slot
        require("core/config/config-ads.php");
        if($ads && $user['has_disabled_ads'] == 0) {
          echo '<div class="img-responsive hidden-xs hidden-sm">'.$ad_side.'</div>';
        }
        ?>
      </div>-->
    </div>
    <?php
    // Ad Slot
    if($ads && $user['has_disabled_ads'] == 0) {
      echo '<div class="img-responsive centered-block hidden-xs hidden-sm">'.$ad_bottom.'</div>';
    }
    ?>
  </div>
  <?php } else { ?>
  <p class="text-center"> <i class="fa fa-lock" style="margin-right:2px;height:90px;line-height:90px;"></i> <?php echo $lang['This_Profile_Is_Private']?> </p>
  <?php } ?>
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
$split_name = splitName($_user['full_name']); 
echo '<p class="text-center" style="height:90px;line-height:90px"> '.$lang['Start_Conversation'].' <b>'.$split_name['first_name'].'</b></p>'; 
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
