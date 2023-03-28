<div class="page-title">
	<div>
		<h1><?=$this->admin->name()?></h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="form-action">
					<button id="submit" class="btn btn-success"><i class="fa fa-lg fa-check"></i> <?=lang('button_save')?></button>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-3">
						<div class="thumbnail" style="border:none; text-align:center;">
							<img id="image" src="<?=$image?>" class="img-circle">
							<div class="caption">
								<button type="button" class="btn btn-default btn-block" id="upload">Ganti Foto</button>
								<?php if($mobile) echo '<i>*) Jika mengganti foto dengan kamera, gunakan posisi landscape pada kamera</i>';?>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-9">
						<?=form_open($action, 'role="form" class="form-horizontal" id="form"')?>
							<div class="form-group">
								<label class="col-sm-3 control-label">Nama</label>
								<div class="col-sm-6">
									<input type="text" name="name" class="form-control" value="<?=$name?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Grup/Divisi</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" value="<?=$group?>" disabled>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Username</label>
								<div class="col-sm-6">
									<input type="text" name="username" class="form-control" value="<?=$username?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email</label>
								<div class="col-sm-6">
									<input type="text" name="email" class="form-control" value="<?=$email?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"></label>
								<div class="col-sm-6">
									<div class="alert alert-info">Biarkan password kosong jika Anda tidak ingin mengganti password</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Password</label>
								<div class="col-sm-6">
									<input type="password" name="password" class="form-control" value="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Konfirmasi Password</label>
								<div class="col-sm-6">
									<input type="password" name="confirm" class="form-control" value="">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?=base_url('assets/js/ajaxupload.js')?>" type="text/javascript"></script>
<script type="text/javascript">
	new AjaxUpload('#upload', {
		action: "<?=admin_url('profile/upload');?>",
		name: 'userfile',
		autoSubmit: false,
		responseType: 'json',
		onChange: function(file, extension) {
			this.submit();
		},
		onSubmit: function(file, extension) {
			$('#upload').append('<span class="wait"><i class="fa fa-refresh"></i></span>');
		},
		onComplete: function(file, json) {
			if (json['success']) {
				$('.wait').remove();
				$('#image').attr('src', json['image']);
			}
			if (json['error']) {
				alert(json['error']);
			}
			$('.loading').remove();	
		}
	});
	
	$('#submit').bind('click', function() {
		$.ajax({
			url: $('#form').attr('action'),
			data: $('#form').serialize(),
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				$('#submit').attr('disabled', true);
				$('#submit').append('<span class="wait"> <i class="fa fa-refresh fa-spin"></i></span>');
			},
			complete: function() {
				$('#submit').attr('disabled', false);
				$('.wait').remove();
			},
			success: function(json) {
				$('.form-group').removeClass('has-error');
				$('.text-danger').remove();
				$('.alert-danger').remove();
				
				if (json['errors']) {
					for (i in json['errors']) {
						$('input[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('select[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('input[name=\''+i+'\']').after('<span class="text-danger">' + json['errors'][i] + '</span>');
						$('select[name=\''+i+'\']').after('<span class="text-danger">' + json['errors'][i] + '</span>');	
					}
				} else if (json['success']) {
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