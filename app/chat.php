<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require('core/system.php');

$id = $db->real_escape_string($_GET['id']);

if(isset($_POST['report_user'])) {
  $reason = $db->real_escape_string(strip_tags($_POST['reason']));
  $db->query("INSERT INTO reports (reported_id,reporter_id,reason,time) VALUES ('".$id."','".$user['id']."','".$reason."','".time()."')");
  header('Location: '.$domain.'/app/profile/'.$id);
  exit;
}

if(isset($_POST['send_gift'])) { 

$gift = $_POST['giftValue'];
if(!empty($gift) && $user['energy'] >= 50) {
$gift_path = 'app/img/gifts/'.$gift.'.png';
$db->query("INSERT INTO gifts (user1,user2,path,time) VALUES ('".$user['id']."','".$id."','".$gift_path."','".time()."')");
$db->query("UPDATE users SET energy=energy-50 WHERE id='".$user['id']."'");
header('Location: '.$domain.'/app/gifts/'.$id);
exit;
}

}

$_user = $db->query("SELECT full_name,profile_picture,last_active,id FROM users WHERE id='".$id."'");
if($_user->num_rows >= 1) {
	$_user = $_user->fetch_array();
} else {
	header('Location: people');
	exit;
}

$split_name = splitName($_user['full_name']);

$page['name'] = $lang['Chat_With'].' '.$split_name['first_name'];
$menu['chat'] = 'active';

$user1 = $user['id'];
$user2 = $_GET['id'];

$convers = $db->query("SELECT id FROM conversations WHERE (user1='".$user1."' AND user2='".$user2."') OR (user1='".$user2."' AND user2='".$user1."')");

if($convers->num_rows < 1) {
  $db->query("INSERT INTO conversations (user1,user2,time) VALUES ('".$user1."','".$user2."','".time()."')");
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
  var user2 = "'.$id.'";
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


$messages = $db->query("SELECT * FROM messages WHERE convers_id='".$convers_id."' AND (sender_id = '".$user1."' OR sender_id='".$user2."') ORDER BY id DESC");

require('inc/top.php');
?>
<section>
<div class="content-wrapper">
<h3><a href="../profile.php?id=<?php echo $id?>" style="color:#929292;text-decoration:none;"><?php echo $_user['full_name']?></a> <?php if(time()-$_user['last_active'] <= 300) { ?> <span class="circle circle-success circle-lg pull-right" style="margin:0px;margin-top:4px;"></span> <?php } else { ?> <span class="circle circle-danger circle-lg" style="margin:0px;margin-left:4px;"></span> <?php } ?> <a data-toggle="modal" data-target="#reportUser" class="btn btn-danger pull-right"> <i class="fa fa-flag fa-fw"></i> <?php echo $lang['Report']?> </a> </h3>
<div class="container-fluid">
<div class="col-md-12">
<div class="panel panel-default">
<div class="panel-heading">
<div class="panel-title"><?php echo $lang['Chat']?> </div>
</div>
<div data-height="300" data-scrollable="" class="list-group">
<div id="messages">
<?php 
if($messages->num_rows >= 1) { 
while($message = $messages->fetch_array()) { 
$sender = $db->query("SELECT id,profile_picture,full_name FROM users WHERE id='".$message['sender_id']."'")->fetch_array(); 
?>
<a href="#" class="list-group-item">
<div class="media-box">
<div class="pull-left">
  <img src="<?php echo getProfilePicture($domain,$sender)?>" class="media-box-object img-rounded thumb32">
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
<a data-toggle="modal" data-target="#sendGift" class="btn btn-default btn-sm"><i class="fa fa-gift"></i></a>
</span>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<?php
require('inc/bottom.php');