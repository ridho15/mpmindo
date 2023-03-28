<div class="page-title">
	<div>
		<h1>Metode Transfer BCA</h1>
		<p>Pengaturan metode transfer BCA</p>
	</div>
	<div class="btn-group">
		<a onclick="window.location = '<?=admin_url('setting_payment')?>';" class="btn btn-default"><i class="fa fa-reply"></i> Batal</a>
		<button id="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
	<div class="card">
		<div class="card-body">
			<div id="message"></div>
			<?=form_open($action, 'role="form" id="form" class="form-horizontal"')?>
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab-1" data-toggle="tab" id="default">General</a></li>
				<li><a href="#tab-2" data-toggle="tab">Konfigurasi</a></li>
			</ul>
			<div class="tab-content" style="padding-top:20px;">
				<div class="tab-pane active" id="tab-1">
					<div class="form-group">
						<label class="col-sm-3 control-label">Instruksi</label>
						<div class="col-sm-9">
							<textarea name="instruction" class="form-control" rows="5"><?=$instruction?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Status Order</label>
						<div class="col-sm-4">
							<select name="order_status_id" class="form-control">
								<?php foreach ($order_statuses as $order_status) {?>
								<?php if ($order_status['order_status_id'] == $order_status_id) {?>
								<option value="<?=$order_status['order_status_id']?>" selected="selected"><?=$order_status['name']?></option>
								<?php } else {?>
								<option value="<?=$order_status['order_status_id']?>"><?=$order_status['name']?></option>
								<?php }?>
								<?php }?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Minimal Belanja</label>
						<div class="col-sm-9">
							<input type="text" name="total" value="<?=$total?>" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Urutan</label>
						<div class="col-sm-3">
							<input type="text" name="sort_order" value="<?=$sort_order?>" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Aktif</label>
						<div class="toggle lg col-sm-4">
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
				</div>
				<div class="tab-pane" id="tab-2">
					<div class="form-group">
						<label class="col-sm-3 control-label">Username IB<br><span class="label-help">Username internet banking BCA</span></label>
						<div class="col-sm-9">
							<input type="text" name="ib_username" value="<?=$ib_username?>" class="form-control">
						</div>
					</div><hr>
					<div class="form-group">
						<label class="col-sm-3 control-label">Password IB<br><span class="label-help">Password internet banking BCA</span></label>
						<div class="col-sm-9">
							<input type="password" name="ib_password" value="<?=$ib_password?>" class="form-control">
						</div>
					</div><hr>
					<div class="form-group">
						<label class="col-sm-3 control-label">Auto Check Mutasi</label>
						<div class="toggle lg col-sm-4">
							<label style="margin-top:5px;">
							<?php if ($auto_check) {?>
							<input type="checkbox" name="auto_check" value="1" checked="checked">
							<?php } else {?>
							<input type="checkbox" name="auto_check" value="1">
							<?php }?>
							<span class="button-indecator"></span>
							</label>
						</div>
					</div><hr>
					<div class="form-group">
						<label class="col-sm-3 control-label">Periode Check Mutasi<br><span class="label-help">Periode auto check mutasi oleh system dalam menit (minimal 10 menit)</span></label>
						<div class="col-sm-2">
							<input type="text" name="auto_check_period" value="<?=$auto_check_period?>" class="form-control">
						</div>
					</div>
				</div>
			</div>
		</div>
		</form>
	</div>
	</div>
</div>
<script type="text/javascript">
	$('#submit').bind('click', function() {
		$this = $(this);
		$.ajax({
			url: $('#form').attr('action'),
			data: $('#form').serialize(),
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				$($this).attr('disabled', true);
				$($this).append('<span class="wait"> <i class="fa fa-refresh fa-spin"></i></span>');
			},
			complete: function() {
				$($this).attr('disabled', false);
				$('.wait').remove();
			},
			success: function(json) {
				$('.alert, .error').remove();
				$('.form-group').removeClass('has-error');
				if (json['redirect']) {
					window.location = json['redirect'];
				} else if (json['error']) {
					$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> '+json.error+'</div>');
					$('html, body').animate({ scrollTop: 0 }, 'slow'); 
				} else if (json['errors']) {
					for (i in json['errors']) {
						$('input[name=\''+i+'\']').after('<span class="help-block error">'+json['errors'][i]+'</span>');
						$('input[name=\''+i+'\']').parent().addClass('has-error');
					}
				}
			}
		});
	});	
</script>