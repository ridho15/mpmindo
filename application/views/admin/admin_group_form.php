<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<?=form_open($action, 'id="form" role="form" class="form-horizontal"')?>
				<input type="hidden" name="admin_group_id" value="<?=$admin_group_id?>">
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('entry_name')?></label>
					<div class=" col-sm-9">
						<input type="text" class="form-control" name="name" value="<?=$name?>">
					</div>
				</div>
				<div class="form-group" id="permission">
					<label class="control-label col-sm-3"><?=lang('entry_permission')?></label>
					<div class=" col-sm-9">
						<table class="table table-fixed" style="border:2px solid #ccc;">
							<thead>
								<tr>
									<th style="width:59.9%;"><?=lang('text_module_path')?></th>
									<th style="width:9.7%;" class="text-center"><a id="select-all-index" data-toggle="tooltip" data-placement="top" title="<?=lang('text_permit_read')?>"><i class="fa fa-eye"></i></a></th>
									<th style="width:9.6%;" class="text-center"><a id="select-all-create" data-toggle="tooltip" data-placement="top" title="<?=lang('text_permit_create')?>"><i class="fa fa-plus"></i></a></th>
									<th style="width:9.5%;" class="text-center"><a id="select-all-edit" data-toggle="tooltip" data-placement="top" title="<?=lang('text_permit_update')?>"><i class="fa fa-pencil"></i></a></th>
									<th style="width:9.6%;" class="text-center"><a id="select-all-delete" data-toggle="tooltip" data-placement="top" title="<?=lang('text_permit_delete')?>"><i class="fa fa-trash"></i></a></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($permissions as $path) {?>
								<tr>
									<td style="width:60%;"><?=lang($path)?></td>
									<td style="width:10%;" class="text-center">
										<?php if (isset($actions[$path]) && in_array('index', $actions[$path])) {?>
										<?php if (isset($permission[$path]) && in_array('index', $permission[$path])) {?>
										<input type="checkbox" name="permission[<?=$path?>][index]" class="check-index" value="1" checked="checked"/>
										<?php } else {?>
										<input type="checkbox" name="permission[<?=$path?>][index]" class="check-index" value="1" />
										<?php }?>
										<?php }?>
									</td>
									<td style="width:10%;" class="text-center">
										<?php if (isset($actions[$path]) && in_array('create', $actions[$path])) {?>
										<?php if (isset($permission[$path]) && in_array('create', $permission[$path])) {?>
										<input type="checkbox" name="permission[<?=$path?>][create]" class="check-create" value="1" checked="checked"/>
										<?php } else {?>
										<input type="checkbox" name="permission[<?=$path?>][create]" class="check-create" value="1" />
										<?php }?>
										<?php }?>
									</td>
									<td style="width:10%;" class="text-center">
										<?php if (isset($actions[$path]) && in_array('edit', $actions[$path])) {?>
										<?php if (isset($permission[$path]) && in_array('edit', $permission[$path])) {?>
										<input type="checkbox" name="permission[<?=$path?>][edit]" class="check-edit" value="1" checked="checked"/>
										<?php } else {?>
										<input type="checkbox" name="permission[<?=$path?>][edit]" class="check-edit" value="1" />
										<?php }?>
										<?php }?>
									</td>
									<td style="width:10%;" class="text-center">
										<?php if (isset($actions[$path]) && in_array('delete', $actions[$path])) {?>
										<?php if (isset($permission[$path]) && in_array('delete', $permission[$path])) {?>
										<input type="checkbox" name="permission[<?=$path?>][delete]" class="check-delete" value="1" checked="checked"/>
										<?php } else {?>
										<input type="checkbox" name="permission[<?=$path?>][delete]" class="check-delete" value="1" />
										<?php }?>
										<?php }?>
									</td>
								</tr>
								<?php }?>
							</tbody>
						</table>
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-primary" id="submit"><i class="fa fa-check"></i> <?=lang('button_save')?></button>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('a').tooltip();
		var checks = ['index', 'create', 'edit', 'delete'];
		for (i in checks) {
			toggleCheck(checks[i]);
		}
	});
	
	function toggleCheck(x) {
		$('#select-all-' + x).click(function() {
			var checkBoxes = $('.check-' + x);
			checkBoxes.prop('checked', !checkBoxes.prop('checked'));
		});
	}
	
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
						$('input[name=\''+i+'\']').closest('.form-group').addClass('has-error');
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