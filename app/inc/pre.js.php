<?php if(basename($_SERVER['PHP_SELF']) == 'process.php') { ?> 
<script>
var handler = StripeCheckout.configure({
  key: '<?php echo $publishable_key?>',
  locale: 'auto',
  token: function(token) {
    $.get("<?php echo $domain;?>/app/ajax/processStripe.php?t="+token.id, function(data) {
    $("#result").html(data);
    });
  }
});

$('#stripe-pay').on('click', function(e) {
  // Open Checkout with further options
  handler.open({
    name: "<?php echo $site_name?>",
    description: "<?php echo $transaction['transaction_name']?>",
    amount: <?php echo $transaction['transaction_amount']*100; ?>
  });
  e.preventDefault();
});

// Close Checkout on page navigation
$(window).on('popstate', function() {
  handler.close();
});
</script>
<? } ?>
<script>
function readNotifications(id) {
$.get("<?php echo $domain;?>/app/ajax/readNotifications.php?id="+id, function(data) {
  var unread = parseInt("<?php echo $ucount?>");
  var willread = parseInt("<?php echo $ncount?>");
  if(unread > willread) {
  $("#notifications_number").text(unread-willread);
  }
});
}
function sendFriendRequest(user1,user2) {
$.get("<?php echo $domain;?>/app/ajax/sendFriendRequest.php?user1="+user1+"&user2="+user2, function(data) {
  $("#addFriend").html(data);
});	
}
function markCompleted(customerid,orderid) {
	 var details = $("#detailsr");
	  if(details.val() != "" && details.val() != " ") {
$.get("<?php echo $domain;?>/app/ajax/markCompleted.php?id1="+customerid+"&id2="+orderid, function(data) {
  $("#addFriend").html(data);
});	
	  }
	  else
	  {
		  
		  alert("Can't mark order as completed while Paper Content field is empty");
	  }
}
function savePaperDetails() 
{
 var orderid = "<?php echo $id;?>";
  var details = $("#detailsr");
    if(details.val()!="" && details.val()!="") {
    $.get("<?php echo $domain;?>/app/ajax/savePaper.php?orderid="+orderid+"&msg="+encodeURIComponent(details.val()), function(data) {
    $("#details").html(data);
    //details.val("");
    });
    }
	else
	{
		
	 alert("Paper Content Field is empty");	
	}
}

<?php if(basename($_SERVER['PHP_SELF']) != 'profile.php') { ?> 
$('.loader-inner').show();

$(window).bind('load', function() {
$('.loader-inner').hide();
$('.container-fluid').fadeIn('slow');
});
<?php } else { ?>
$('.loader-inner').show();

$(window).bind('load', function() {
$('.loader-inner').hide();
$('.profile').fadeIn('slow');
});
<?php } ?>
$('.has-user-block').animo( { animation: 'fadeIn' } );
$('.user-picture').animo( { animation: 'fadeIn' } );
</script>