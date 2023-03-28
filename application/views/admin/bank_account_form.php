<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<?=form_open($action, 'id="bank-account-form" role="form" class="form-horizontal"')?>
				<input type="hidden" name="bank_account_id" value="<?=$bank_account_id?>">
				<div class="form-group">
					<label class="control-label col-sm-4"><?=lang('label_title')?></label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="title" value="<?=$title?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4"><?=lang('label_bank_name')?></label>
					<div class="col-sm-8">
						<select name="bank_id" class="form-control">
							<?php foreach ($banks as $bank) {?>
							<?php if ($bank_id == $bank['bank_id']) {?>
							<option value="<?=$bank['bank_id']?>" selected="selected"><?=$bank['code']?> - <?=$bank['name']?></option>
							<?php } else {?>
							<option value="<?=$bank['bank_id']?>"><?=$bank['code']?> - <?=$bank['name']?></option>
							<?php }?>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4"><?=lang('label_account_name')?></label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="account_name" value="<?=$account_name?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4"><?=lang('label_account_number')?></label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="account_number" value="<?=$account_number?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4"><?=lang('label_status')?></label>
					<div class="toggle lgcol-sm-8">
						<label style="margin-top:5px;">
						<?php if ($active) {?>
						<input type="checkbox" name="active" value="1" checked="checked">
						<?php } else {?>
						<input type="checkbox" name="active" value="1">
						<?php }?>
						<span class="button-indecator"></span>
						</label>
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> <?=lang('button_cancel')?></button>
			<button type="button" class="btn btn-default" id="bank-account-submit"><i class="fa fa-check"></i> <?=lang('button_save')?></button>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#bank-account-submit').bind('click', function() {
		$.ajax({
			url: $('#bank-account-form').attr('action'),
			data: $('#bank-account-form').serialize(),
			type: 'post',
			dataType: 'json',
			success: function(json) {
				$('.form-group').removeClass('has-error');
				$('.error').remove();
				if (json['error']) {
					for (i in json['error']) {
						$('input[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('input[name=\''+i+'\']').after('<span class="error text-danger">' + json['error'][i] + '</span>');	
					}
				} else if (json['success']){
					$('#form-modal').modal('hide');
					$.notify({
						title: '<strong><?=lang('text_success')?></strong><br>',
						message: json['success'],
						icon: 'fa fa-check' 
					},{
						type: "success"
					});
				}
			}
		});
	});
</script>