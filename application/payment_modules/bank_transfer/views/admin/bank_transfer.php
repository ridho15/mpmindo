<div class="form-action">
	<a class="btn btn-default" href="<?=admin_url('payment_modules')?>"><span class="fa fa-reply"></span> <?=lang('button_back')?></a> <button id="submit" class="btn btn-primary"><span class="fa fa-check"></span> <?=lang('button_save')?></button>
</div>
<?=form_open($action, 'id="form" class="form-horizontal" role="form"')?>
	<div class="form-group">
		<label class="col-sm-4 control-label"><?=lang('label_title')?><br><span class="label-help"><?=lang('label_title_help')?></span></label>
		<div class="col-sm-8">
			<input type="text" name="title" value="<?=$title?>" class="form-control">
		</div>
	</div><hr>
	<div class="form-group">
		<label class="col-sm-4 control-label"><?=lang('label_instruction')?><br><span class="label-help"><?=lang('label_instruction_help')?></span></label>
		<div class="col-sm-8">
			<textarea class="form-control" name="instruction" rows="5"><?=$instruction?></textarea>
		</div>
	</div><hr>
	<div class="form-group">
		<label class="control-label col-sm-4"><?=lang('label_unique_code')?><br><span class="label-help"><?=lang('label_unique_code_help')?></span></label>
		<div class="toggle lg col-sm-4">
			<label style="margin-top:5px;">
			<?php if ($unique_code) {?>
			<input type="checkbox" name="unique_code" value="1" checked="checked">
			<?php } else {?>
			<input type="checkbox" name="unique_code" value="1">
			<?php }?>
			<span class="button-indecator"></span>
			</label>
		</div>
	</div>
	<hr>
	<div class="form-group">
		<label class="control-label col-sm-4"><?=lang('label_confirmation')?><br><span class="label-help"><?=lang('label_confirmation_help')?></span></label>
		<div class="toggle lg col-sm-4">
			<label style="margin-top:5px;">
			<?php if ($confirmation) {?>
			<input type="checkbox" name="confirmation" value="1" checked="checked">
			<?php } else {?>
			<input type="checkbox" name="confirmation" value="1">
			<?php }?>
			<span class="button-indecator"></span>
			</label>
		</div>
	</div><hr>
	<div class="form-group">
		<label class="col-sm-4 control-label"><?=lang('label_bank_account')?><br><span class="label-help"><?=lang('label_bank_account_help')?></span></label>
		<div class="col-sm-8">
			<table class="table table-hover">
				<?php foreach ($bank_accounts as $bank_account) {?>
				<tr>
					<td style="width:10px;">
						<?php if (in_array($bank_account['bank_account_id'], $bank_account_ids)) {?>
						<input type="checkbox" name="bank_accounts[]" value="<?=$bank_account['bank_account_id']?>" checked="checked">
						<?php } else {?>
						<input type="checkbox" name="bank_accounts[]" value="<?=$bank_account['bank_account_id']?>">
						<?php }?>
					</td>
					<td><?=$bank_account['title']?><br><small class="text-muted"><?=$bank_account['name']?> / <?=$bank_account['account_name']?> / <?=$bank_account['account_number']?></small></td>
				</tr>
				<?php }?>
			</table>
		</div>
	</div>
</form>
<script type="text/javascript">
	$('#submit').bind('click', function() {
		var $btn = $(this);
		$.ajax({
			url: $('#form').attr('action'),
			data: $('#form').serialize(),
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				$btn.button('loading');
			},
			complete: function() {
				$btn.button('reset');
			},
			success: function(json) {
				$('.form-group').removeClass('has-error');
				$('.text-danger').remove();
				if (json['error']) {
					for (i in json['error']) {
						$('input[name=\''+i+'\']').parent().addClass('has-error');
						$('input[name=\''+i+'\']').after('<span class="text-danger">' + json['error'][i] + '</span>');	
						$('textarea[name=\''+i+'\']').parent().addClass('has-error');
						$('textarea[name=\''+i+'\']').after('<span class="text-danger">' + json['error'][i] + '</span>');	
					}
				} else if (json['success']){
					window.location = "<?=admin_url('payment_modules')?>";
				}
			}
		});
	});
</script>