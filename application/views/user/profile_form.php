<div class="ps-breadcrumb">
	<div class="ps-container">
		<ul class="breadcrumb">
			<?php foreach ($template['breadcrumbs'] as $key => $breadcrumb) {?>
			<?php if (count($breadcrumb) == $key+1) {?>
			<li><?=$breadcrumb['text']?></li>
			<?php } else {?>
			<li><a href="<?=$breadcrumb['href']?>"><?=$breadcrumb['text']?></a></li>
			<?php }?>
			<?php }?>
		</ul>
	</div>
</div>
<div class="ps-page--product">
	<div class="ps-container">
		<div class="ps-page__container">
			<div class="ps-page__left">
				<div class="row">
					<div class="col-md-12">		
						<div id="message"></div>
						<?=form_open($action, 'id="form" class="form"')?>
							<input class="form-control" type="hidden" name="user_id" value="<?=$user_id?>">
							<ul class="nav nav-tabs">
								<li class="nav-item"><a class="nav-link active" href="#tab-1" data-toggle="tab" id="default">Biodata</a></li>
								<li class="nav-item"><a class="nav-link" href="#tab-2" data-toggle="tab">Alamat</a></li>
								<li class="nav-item"><a class="nav-link" href="#tab-3" data-toggle="tab">Password</a></li>
							</ul>
							<div class="tab-content" style="padding-top:20px;">
								<div class="tab-pane active" id="tab-1">
									<div class="row">
										<div class="col-md-6 col-12">
											<div class="form-group">
												<label>Email:</label>
												<input class="form-control" type="text" name="email" value="<?=$email?>" placeholder="Email" readonly>
											</div>
											<div class="form-group">
												<label>Nama Lengkap:</label>
												<input class="form-control" type="text" name="name" value="<?=$name?>" placeholder="Nama Lengkap" maxlength="64">
											</div>
											<div class="form-group">
												<label>No. Telepon / Handphone:</label>
												<input class="form-control" type="text" name="telephone" value="<?=$telephone?>" placeholder="No. Telepon / Handphone" maxlength="32">
											</div>
											<div class="form-group">
												<label>Tempat Kerja:</label>
												<input class="form-control" type="text" name="job" value="<?=$job?>" placeholder="Pekerjaan" maxlength="64">
											</div>
											<div class="form-group">
												<label>Status Kerja:</label>
												<select class="form-control" name="job_status">
													<option value="Barber" <?php if($job_status == "Barber") echo 'selected';?>>Barber</option>
													<option value="Barber Owner" <?php if($job_status == "Barber Owner") echo 'selected';?>>Barber Owner</option>
													<option value="Lainnya" <?php if($job_status == "Lainnya") echo 'selected';?>>Lainnya</option>
												</select>
											</div>
										</div>
										<div class="col-md-6 col-12">
											<div class="form-group">
												<label>Tanggal Lahir:</label>
												<input type="text" name="dob" value="<?php echo date('d-m-Y',strtotime($dob));?>" class="form-control" data-date-format="dd-mm-yyyy" readonly/>
											</div>
											<div class="form-group">
												<label>Jenis Kelamin:</label>
												<div class="radio">
													<label class="radio-inline"><input type="radio" name="gender" value="m" <?php if($gender == 'm') echo 'checked';?>> Pria</label>
													<label class="radio-inline"><input type="radio" name="gender" value="f" <?php if($gender == 'f') echo 'checked';?>> Wanita</label>
												</div>
											</div>
											<div class="form-group">
												<img id="image" src="<?=$image?>" class="img-thumbnail">
												<div class="caption mt-3">
													<button type="button" class="ps-btn ps-btn--outline ps-btn--sm" id="upload">Ubah Foto</button>
													<?php if($mobile) echo '<br><br><i>*) Jika mengubah foto dengan kamera, gunakan posisi landscape pada kamera</i>';?>
												</div>
											</div>
										</div>
									</div>
									<br><br><br>
								</div>
								<div class="tab-pane" id="tab-2">
									<button type="button" onclick="addressForm();" class="ps-btn ps-btn--sm mb-3"><i class="fa fa-plus-circle"></i> Tambah Alamat</button>
									<div id="address-list"></div><br><br><br><br><br><br>
								</div>
								<div class="tab-pane" id="tab-3">
									<div class="alert alert-info">Biarkan password kosong jika tidak ingin mengganti password</div>
									<div class="row">
									<div class="col-lg-12 col-12">
										<div class="form-group">
											<label>Password:</label>
											<input class="form-control" type="password" name="password" value="" maxlength="25">
										</div>
									</div>
									<div class="col-lg-12 col-12">
										<div class="form-group">
											<label>Konfirmasi Password:</label>
											<input class="form-control" type="password" name="confirm" value="" maxlength="25">
										</div>
									</div>
									</div>
								</div>
							</div>
						</form>
						<hr>
						<div class="mb-5">
							<button onclick="window.location='<?=user_url()?>';" class="ps-btn ps-btn--outline"><i class="fa fa-reply"></i> Kembali</button>
							<button id="submit" class="ps-btn"><i class="fa fa-check"></i> Simpan</button>
						</div>
					</div>
				</div>
			</div>
			<div class="ps-page__right">
				<aside class="widget widget_features">
					<ul class="list-unstyled">
						<li class="mb-3"><a href="<?=user_url()?>"><i class="icon-network"></i> Dashboard</a></li>
						<li class="mb-3"><a href="<?=user_url('profile/edit')?>"><i class="icon-user"></i> Edit Profil</a></li>
						<li class="mb-3"><a href="<?=user_url('purchase')?>"><i class="icon-bag"></i> Pesanan Saya</a></li>
						<li class="mb-3"><a href="<?=user_url('reg_sale_offline')?>"><i class="fa fa-edit"></i> Registrasi Produk</a></li>
						<li class="mb-3"><a href="<?=user_url('warranty')?>"><i class="fa fa-medkit"></i> Klaim Garansi</a></li>
						<li><a href="<?=user_url('logout')?>"><i class="icon-user"></i> Logout</a></li>
					</ul>
				</aside>
			</div>
		</div>
	</div>
</div>
<div id="modal"></div>
<script src="<?=base_url('assets/js/ajaxupload.js')?>" type="text/javascript"></script>
<script src="<?=base_url('assets/js/plugins/bootstrap-datepicker.min.js')?>"></script>
<script src="<?=base_url('assets/js/plugins/bootstrap-datepicker.id.js')?>"></script>
<script type="text/javascript">
	$('input[name=\'dob\']').datepicker({language:'id', autoclose:true});

	loadAddress();

	function loadAddress() {
		$.ajax({
			url: "<?=user_url('profile/get_addresses')?>",
			data: 'user_id=<?=$user_id?>',
			dataType: 'json',
			success: function(json) {
				html = '';
				
				for (i in json) {
					html += '<div class="card mb-3">';
					html += '	<div class="card-body">';
					html += '		<div class="row">';
					html += '			<div class="col-xs-12 col-md-6">';
					html += '				<h5 class="card-title">'+json[i].name+'</h5>';
					html += '				<p class="card-text">'+json[i].address+', '+json[i].subdistrict+'<br>'+json[i].city+' - '+json[i].province+' - '+json[i].postcode+'</p>';
					html += '			</div>';
					html += '			<div class="col-xs-12 col-md-6 text-right">';
					html += '				<button type="button" class="ps-btn ps-btn--outline ps-btn--sm" onclick="addressForm('+json[i].address_id+')"><span class="fa fa-pencil"></span> Edit</button> <button type="button" class="ps-btn ps-btn--outline ps-btn--sm" onclick="delRecord('+json[i].address_id+')"><span class="fa fa-remove"></span> Hapus</button>';
					html += '			</div>';
					html += '		</div>';
					html += '	</div>';
					html += '</div>';
				}
				
				$('#address-list').html(html);
			}
		});
	}
	
	new AjaxUpload('#upload', {
		action: "<?=user_url('profile/upload?user_id='.$user_id);?>",
		name: 'userfile',
		autoSubmit: false,
		responseType: 'json',
		onChange: function(file, extension) {
			this.submit();
		},
		onSubmit: function(file, extension) {
			$('#upload').append(' <span class="wait"><i class="fa fa-refresh"></i></span>');
			$('#upload').attr("disabled", true);
		},
		onComplete: function(file, json) {
			$('#upload').removeAttr("disabled");
			if (json.success) { 
				$('.wait').remove();
				$('#image').attr('src', json.image);
			}
			if (json.error) {
				alert(json.error);
			}
			$('.loading').remove();	
		}
	});
	
	$('#submit').bind('click', function() {
		var btn = $(this);
		$.ajax({
			url: $('#form').attr('action'),
			data: $('#form').serialize(),
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				btn.html('<i class="fa fa-refresh"></i> Sedang Diproses...');
				btn.attr("disabled", true);
				//btn.button('loading');
			},
			complete: function() {
				btn.html('<i class="fa fa-check"></i> Simpan');
				btn.removeAttr("disabled");
				//btn.button('reset');
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
						if(i == 'user_address') {
							$('textarea[name=\''+i+'\']').closest('.form-group').addClass('has-error');
							$('textarea[name=\''+i+'\']').after('<span class="text-danger">' + json['errors'][i] + '</span>');
						}
					}
				} else if (json['success']) {
					window.location = "<?=user_url()?>";
				}
			}
		});
	});
	
	function delRecord(address_id) {
		// swal({
		// 	title: "Apakah anda yakin?",
		// 	text: "Data yang sudah dihapus tidak dapat dikembalikan!",
		// 	type: "warning",
		// 	showCancelButton: true,
		// 	confirmButtonText: "Ya",
		// 	cancelButtonText: "Tidak",
		// 	closeOnConfirm: false,
		// 	closeOnCancel: true
		// }, function(isConfirm) {
		// 	if (isConfirm) {
		// 		$.ajax({
		// 			url : '<?=user_url('address/delete')?>',
		// 			type : 'post',
		// 			data: 'address_id='+address_id,
		// 			dataType: 'json',
		// 			success: function(json) {
		// 				if (json['success']) {
		// 					swal("Terhapus!", json['success'], "success");
		// 				} else if (json['error']) {
		// 					swal("Error!", json['error'], "error");
		// 				} else if (json['redirect']) {
		// 					window.location = json['redirect'];
		// 				}
		// 				loadAddress();
		// 			}
		// 		});
		// 	}
		// });
		if(window.confirm('Apa anda yakin ingin menghapus?')) {
			$.ajax({
			url : '<?=user_url('address/delete')?>',
			type : 'post',
			data: 'address_id='+address_id,
			dataType: 'json',
			success: function(json) {
				if (json['success']) {
					//swal("Terhapus!", json['success'], "success");
					$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['success']+'</div>');
				} else if (json['error']) {
					//swal("Error!", json['error'], "error");
					$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
				} else if (json['redirect']) {
					window.location = json['redirect'];
				}
				loadAddress();
			}
			});
		}
	}
	
	function addressForm(address_id) {
		if (typeof(address_id) == 'undefined') {
			var _url = "<?=user_url('address/create')?>";
		} else {
			var _url = "<?=user_url('address/edit')?>";
		}
		
		$.ajax({
			url : _url,
			data: 'address_id='+address_id+'&user_id=<?=$user_id?>',
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
				} else if (json['content']) {
					$('#modal').html('<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">'+json['content']+'</div>');
					$('#form-modal').modal('show');
					$('#form-modal').on('hidden.bs.modal', function (e) {
						loadAddress();
					});	
				}
			}
		});
	}
</script>