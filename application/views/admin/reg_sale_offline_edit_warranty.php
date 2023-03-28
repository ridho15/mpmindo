<?php $inventory_row = 0; ?>
<script src="<?=base_url('assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.min.js')?>"></script>
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
				
				if (json['warning']) {
					$('#notification').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+json['warning']+'</div>');
				}
				
				if (json['error']) {
					for (i in json['error']) {
						$('input[name=\''+i+'\'], select[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('input[name=\''+i+'\'], select[name=\''+i+'\']').after('<span class="text-danger">' + json['error'][i] + '</span>');	
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
	
	$('input[name=\'start_warranty\']').datepicker({language:'id', autoclose:true});
</script>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<div id="notification"></div>
			<?=form_open($action, 'id="form" role="form" class="form-horizontal"')?>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_start_warranty')?></label>
					<div class="col-sm-3">
						<input type="text" name="start_warranty" value="<?=date('d-m-Y',strtotime($start_warranty))?>" class="form-control" data-date-format="dd-mm-yyyy" readonly/>
					</div>
				</div>
				<input type="hidden" name="reg_id" value="<?=$reg_id;?>">
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> <?=lang('button_cancel')?></button>
			<button type="button" class="btn btn-primary" id="submit"><i class="fa fa-check"></i> <?=lang('button_save')?></button>
		</div>
	</div>
</div>