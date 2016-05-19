<?php
session_set_cookie_params(172800);
session_start();
require('core/config/config.php');
require('core/config/config-theme.php');
require('core/config/config-lang.php');
require("core/system.php");

$page['name'] = $lang['Chats'];
$menu['chat'] = 'active';

$conversations = $db->query("SELECT * FROM conversations WHERE (user1='".$user['id']."' OR user2='".$user['id']."') AND last_activity != '' ORDER BY last_activity DESC");

if(isset($_POST['delete_conversation'])) {
$delete = $_POST['delete'];
foreach($delete as $k=>$v) {
$db->query("DELETE FROM conversations WHERE id='".$v."'");
}
header('Location: '.$domain.'/app/chats');
exit;
}

require('inc/top.php');
?>
<section>
<div class="content-wrapper">
<h3><?php echo $lang['Chats']?></h3>
<div class="container-fluid">
<div class="table-grid table-grid-desktop">
<div class="col">
<div class="clearfix mb">
<div class="btn-group pull-left">
</div>
</div>
<?php if($conversations->num_rows >= 1) { ?>
<form action="" method="post">
<div class="row">
<div class="col-md-5">
<!--<button type="submit" name="new" class="btn btn-default"> <i class="fa fa-pencil fa-fw"></i> New Message </button>-->
<button type="submit" name="delete_conversation" class="btn btn-danger"> <i class="fa fa-trash fa-fw"></i> <?php echo $lang['Delete']?> </button>
</div>
</div>
<br>
<div class="panel panel-default">
<div class="panel-body">
	<table class="table mb-mails">
		<tbody>
			<?php while($convers = $conversations->fetch_array()) {
				if($convers['user1'] != $user['id']) { $other_user = $db->query("SELECT id,full_name,profile_picture FROM users WHERE id='".$convers['user1']."'")->fetch_array(); } 
				if($convers['user2'] != $user['id']) { $other_user = $db->query("SELECT id,full_name,profile_picture FROM users WHERE id='".$convers['user2']."'")->fetch_array(); } 
				?>
				<tr>
				<td style="width:40px;">
	                <div class="checkbox c-checkbox">
	                   <label>
	                      <input name="delete[]" type="checkbox" value="<?php echo $convers['id']?>">
	                      <span class="fa fa-check"></span>
	                   </label>
	                </div>
             	</td>
					<td>
						<a href="chat/<?php echo $other_user['id']?>" style="text-decoration:none;">
							<img src="<?php echo getProfilePicture($domain,$other_user)?>" class="mb-mail-avatar pull-left">
							<div class="mb-mail-date pull-right"><?php echo time_ago($convers['last_activity'])?></div>
							<div class="mb-mail-meta">
								<div class="pull-left">
									<div class="mb-mail-subject" style="color:#515253;"><?php echo $other_user['full_name']?></div>
								</div>
								<div class="mb-mail-preview"><?php echo parseEmoticons($domain,trimMessage($convers['last_message'],100))?></div>
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
<?php } else { echo $lang['No_Chats_To_Show']; } ?>
</div>
</div>
</div>
</div>
</section>
<?php
require('inc/bottom.php'); 
?>
