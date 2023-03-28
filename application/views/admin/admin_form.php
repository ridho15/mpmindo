<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<?=form_open($action, 'id="form" role="form" class="form-horizontal"')?>
				<input type="hidden" name="admin_id" value="<?=$admin_id?>">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-1" data-toggle="tab" id="default"><?=lang('tab_general')?></a></li>
					<li><a href="#tab-2" data-toggle="tab"><?=lang('tab_auth')?></a></li>
					<li><a href="#tab-3" data-toggle="tab"><?=lang('tab_option')?></a></li>
				</ul>
				<div class="tab-content" style="padding-top:20px;">
					<div class="tab-pane active" id="tab-1">
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('entry_name')?></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="name" value="<?=$name?>" maxlength="64">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('entry_email')?></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="64">
							</div>
						</div>
						<!-- <div class="form-group">
							<label class="control-label col-sm-4"><?=lang('entry_admin_group')?></label>
							<div class="col-sm-8">
								<?php if ($admin_id >= 0) {?>
								<select name="admin_group_id" class="form-control">
								<?php foreach ($admin_groups as $admin_group) {?>
								<?php if ($admin_group['admin_group_id'] == $admin_group_id) {?>
								<option value="<?=$admin_group['admin_group_id']?>" selected="selected"><?=$admin_group['name']?></option>
								<?php } else {?>
								<option value="<?=$admin_group['admin_group_id']?>"><?=$admin_group['name']?></option>
								<?php }?>
								<?php }?>
								</select>
								<?php } else {?>
								<input type="text" class="form-control" value="Administrator" disabled="disabled">
								<?php } ?>
							</div>
						</div> -->
					</div>
					<div class="tab-pane" id="tab-2">
						<?php if ($admin_id) {?>
							<div class="alert alert-info">Biarkan password kosong jika tidak ingin mengganti password</div>
						<?php } ?>
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('entry_username')?></label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="username" value="<?=$username?>" maxlength="32">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('entry_password')?></label>
							<div class="col-sm-8">
								<input type="password" class="form-control" name="password" value="" maxlength="25">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('entry_confirm')?></label>
							<div class="col-sm-8">
								<input type="password" class="form-control" name="confirm" value="" maxlength="25">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-3">
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('entry_status')?></label>
							<div class="col-sm-8">
								<div class="checkbox">
									<?php if ($admin_id < 1 && $this->admin->admin_id() != $admin_id) {?>
									<label>
										<?php if ($active) {?>
										<input type="checkbox" name="active" value="1" checked="checked"> <?=lang('text_active')?>
										<?php } else {?>
										<input type="checkbox" name="active" value="1"> <?=lang('text_active')?>
										<?php }?>
									</label>
									<?php } else {?>
									<input name="active" value="<?=$active?>" type="hidden">
									<label>
										<?php if ($active) {?>
										<input type="checkbox" value="1" checked="checked" disabled="disabled"> <?=lang('text_active')?>
										<?php } else {?>
										<input type="checkbox" value="1" disabled="disabled"> <?=lang('text_active')?>
										<?php }?>
									</label>
									<?php }?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="is_distributor" value="0">
			</form>
		</div>
		<div class="modal-footer">
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