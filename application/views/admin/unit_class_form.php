<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<?=form_open($action, 'id="form" role="form" class="form-horizontal"')?>
				<input type="hidden" name="unit_class_id" value="<?=$unit_class_id?>">
				<div class="form-group">
					<label class="control-label col-sm-6"><?=lang('label_title')?><br><span class="label-help"><?=lang('label_title_help')?></span></label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="title" value="<?=$title?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-6"><?=lang('label_unit')?><br><span class="label-help"><?=lang('label_unit_help')?></span></label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="unit" value="<?=$unit?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-6"><?=lang('label_value')?><br><span class="label-help"><?=lang('label_value_help')?></span></label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="value" value="<?=$value?>">
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> <?=lang('button_cancel')?></button>
			<button type="button" class="btn btn-primary" id="submit"><i class="fa fa-check"></i> <?=lang('button_save')?></button>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#submit').bind('click', function() {
		$.ajax({
			url: $('#form').attr('action'),
			data: $('#form').serialize(),
			type: 'post',
			dataType: 'json',
			success: function(json) {
				$('.form-group').removeClass('has-error');
				$('.text-danger').remove();
				if (json['error']) {
					for (i in json['error']) {
						$('input[name=\''+i+'\']').parent().addClass('has-error');
						$('input[name=\''+i+'\']').after('<span class="text-danger">' + json['error'][i] + '</span>');	
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