<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<?=form_open($action, 'id="form" role="form" class="form-horizontal"')?>
				<input type="hidden" name="message_id" value="<?=$message_id?>">
				<div class="form-group">
					<label class="control-label col-sm-3">To</label>
					<div class="col-sm-9">
						<?php if ($message_id || $recipient_id) {?>
						<input type="text" class="form-control" name="recipient" value="<?=$recipient?>" readonly="readonly">
						<?php } else {?>
						<input type="text" class="form-control" name="recipient" value="<?=$recipient?>">
						<?php }?>
						<input type="hidden" name="recipient_id" value="<?=$recipient_id?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3">Subject</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="subject" value="<?=$subject?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3">Message</label>
					<div class="col-sm-9">
						<textarea name="body" class="form-control" rows="5"></textarea>
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
			<button type="button" class="btn btn-success" id="submit"><i class="fa fa-check"></i> Send</button>
		</div>
	</div>
</div>
<script src="<?=base_url('assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.js')?>"></script>
<script type="text/javascript">
	$('input[name=\'recipient\']').typeahead({
			source: function(query, process) {
				$.ajax({
					url: "<?=user_url('message/contacts')?>",
					data: 'name=' + query,
					type: 'get',
					dataType: 'json',
					beforeSend: function() {
						
					},
					complete: function(){
						
					},
					success: function(json) {
						items = [];
						map = {};
						$.each(json, function(i, item) {
							map[item.name+item.company] = item;
							items.push(item.name+item.company);
						});
						process(items);
					}
				});
			},
			highlighter: function(item){
				if (map[item].company) {
					return '<strong>'+map[item].name+'</strong><br>' + map[item].company;
				} else {
					return item;
				}
			},
			updater: function (item) {
				$('input[name=\'recipient_id\']').val(map[item].user_id);
				return map[item].name;
			},
			minLength: 1
		});
		
	$('#submit').bind('click', function() {
		var btn = $(this);
		$.ajax({
			url: $('#form').attr('action'),
			data: $('#form').serialize(),
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				btn.button('loading');
			},
			complete: function() {
				btn.button('reset');
			},
			success: function(json) {
				$('.form-group').removeClass('has-error');
				$('.text-danger').remove();
				if (json['error']) {
					for (i in json['error']) {
						$('input[name=\''+i+'\'], textarea[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('input[name=\''+i+'\'], textarea[name=\''+i+'\']').after('<span class="text-danger">' + json['error'][i] + '</span>');	
					}
				} else if (json['success']){
					$('#form-modal').modal('hide');
					parent.$('#message').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> '+json['success']+'</div>');
				}
			}
		});
	});
</script>