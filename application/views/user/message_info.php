<div class="page-title">
	<div>
		<h1><?=$subject?></h1>
	</div>
	<div class="btn-group">
		<button class="btn btn-default" onclick="window.location = '<?=user_url('message')?>';"><i class="fa fa-chevron-left"></i> Back</button>
		<?php if ($sender_id != $this->user->user_id()) {?>
		<button class="btn btn-success" onclick="getForm(<?=$message_id?>, '/message/reply');"><i class="fa fa-reply"></i> Reply</button>
		<?php }?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div id="message"></div>
				<dl>
					<dt>Sender</dt>
					<?php if ($sender_id == $this->user->user_id()) {?>
					<dd>You</dd>
					<?php } else {?>
					<dd><?=$sender?></dd>
					<?php }?>
					<dt>Recipient</dt>
					<?php if ($recipient_id == $this->user->user_id()) {?>
					<dd>You</dd>
					<?php } else {?>
					<dd><?=$recipient?></dd>
					<?php }?>
					<dt>Sent At</dt>
					<dd><?=format_date($date_added, true)?></dd>
					<dt>Subject</dt>
					<dd><?=$subject?></dd>
					<dt>Message</dt>
					<dd><?=$body?></dd>
				</dl>
			</div>
		</div>
	</div>
</div>
<div id="modal"></div>
<script type="text/javascript">
	function getForm(message_id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'message_id='+message_id,
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
				} else if (json['content']) {
					$('#modal').html('<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">'+json['content']+'</div>');
					$('#form-modal').modal('show');
				}
			}
		});
	}
</script>