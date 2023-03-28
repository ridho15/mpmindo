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
	</div><hr>
	<div class="form-group">
		<label class="col-sm-4 control-label"><?=lang('label_qrcode')?></label>
		<div class="col-sm-4">
			<img id="qrcode-image" src="<?=$thumb_detail?>" class="img-thumbnail" data-placeholder="<?=$thumb_detail?>">
			<div class="caption" style="margin-top:10px;">
			<button type="button" class="btn btn-default" id="upload-qrcode"><span class="fa fa-upload"></span> <?=lang('button_upload')?></button>
			</div>
			<input type="hidden" id="input-qrcode" name="qrcode" value="<?=$qrcode?>">
		</div>
	</div><hr>
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
	</div>
</form>
<script src="<?=base_url('assets/js/ajaxupload.js')?>" type="text/javascript"></script>
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
	
	new AjaxUpload('#upload-qrcode', {
		action: "<?=admin_url('payment_modules/upload');?>",
		name: 'userfile',
		autoSubmit: false,
		responseType: 'json',
		onChange: function(file, extension) {
			this.submit();
		},
		onSubmit: function(file, extension) {
			$('#upload-qrcode').button('loading');
		},
		onComplete: function(file, json) {
			$('#upload-qrcode').button('reset');
			
			if (json.success) { 
				$('#qrcode-image').attr('src', json.thumb);
				$('#input-qrcode').val(json.image);
			}
			
			if (json.error) {
				$.notify({
					title: '<strong><?=lang('text_error')?></strong><br>',
					message: json.error,
					icon: 'fa fa-info' 
				},{
					type: "danger"
				});
			}
		}
	});
</script>