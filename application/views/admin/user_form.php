<div class="page-title">
	<div>
		<h1><?=$template['title']?></h1>
	</div>
	<div class="btn-group">
		<a onclick="window.location = '<?=admin_url('users')?>'" class="btn btn-default"><i class="fa fa-reply"></i> Kembali</a>
		<a id="submit" class="btn btn-primary pull-right"><i class="fa fa-check"></i> Simpan</a>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<?=form_open($action, 'id="form" class="form-horizontal"')?>
				<input type="hidden" name="user_id" value="<?=$user_id?>">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-1" data-toggle="tab" id="default">Biodata</a></li>
					<?php if ($user_id) {?>
						<li><a href="#tab-2" data-toggle="tab">Alamat</a></li>
					<?php } else {?>
						<li><a href="#tab-5" data-toggle="tab">Alamat</a></li>
					<?php }?>
					<!-- <li><a href="#tab-3" data-toggle="tab">Rekening Bank</a></li> -->
					<li><a href="#tab-4" data-toggle="tab">Password</a></li>
				</ul>
				<div class="tab-content" style="padding-top:20px;">
					<div class="tab-pane active" id="tab-1">
						<div class="form-group">
							<label class="control-label col-sm-3">Nama Lengkap</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="name" value="<?=$name?>" maxlength="64">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Email</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="email" value="<?=$email?>" <?php if ($user_id) echo 'readonly';?> maxlength="100">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">No. Telepon / Handphone</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="telephone" value="<?=$telephone?>" maxlength="32">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Jenis Kelamin</label>
							<div class="col-sm-9">
								<div class="radio">
										<label class="radio-inline"><input type="radio" name="gender" value="m" <?php if($gender == "m" || $gender == "") echo 'checked';?>> Pria</label>
										<label class="radio-inline"><input type="radio" name="gender" value="f" <?php if($gender == "f") echo 'checked';?>> Wanita</label>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Tanggal Lahir</label>
							<div class="col-md-1">
								<select class="form-control" name="dob_date">
									<?php for ($i = 1; $i <= 31; $i++) {?>
									<option value="<?=$i?>" <?php if($dob && date('d',strtotime($dob)) == $i) echo 'selected';?>><?=$i?></option>
									<?php }?>
								</select>
							</div>
							<div class="col-md-2">
								<select class="form-control" name="dob_month">
									<?php foreach ($months as $key => $value) {?>
									<option value="<?=$key?>" <?php if($dob && date('m',strtotime($dob)) == $key) echo 'selected';?>><?=$value?></option>
									<?php }?>
								</select>
							</div>
							<div class="col-md-1">
								<select class="form-control" name="dob_year">
									<?php for ($i = date('Y')-17; $i >= 1950; $i--) {?>
									<option value="<?=$i?>" <?php if($dob && date('Y',strtotime($dob)) == $i) echo 'selected';?>><?=$i?></option>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Tempat Kerja</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="job" value="<?=$job?>" maxlength="64">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Status Kerja</label>
							<div class="col-sm-9">
								<select class="form-control" name="job_status">
									<option value="Barber" <?php if($job_status == 'Barber') echo 'selected';?>>Barber</option>
									<option value="Barber Owner" <?php if($job_status == 'Barber Owner') echo 'selected';?>>Barber Owner</option>
									<option value="Lainnya" <?php if($job_status == 'Lainnya') echo 'selected';?>>Lainnya</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Foto Profil</label>
							<div class="col-md-3">
								<img id="image" src="<?=$image?>" class="img-thumbnail">
								<div class="caption" style="margin-top:10px;">
									<button type="button" class="btn btn-default" id="upload">Ubah</button>
									<?php if($mobile) echo '<br><i>*) Jika mengubah foto dengan kamera, gunakan posisi landscape pada kamera</i>';?>
								</div>
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
					<?php if ($user_id) {?>
					<div class="tab-pane" id="tab-2">
						<a onclick="addressForm();" class="btn btn-success mb-2"><i class="fa fa-plus-circle"></i> Tambah Alamat</a><br><br>
						<table class="table table-hover" width="100%" id="datatable-address">
							<thead>
								<tr>
									<th>Alamat</th>
									<th>Default</th>
									<th class="text-right"></th>
								</tr>
							</thead>
						</table>
					</div>
					<?php } else {?>
						<div class="tab-pane" id="tab-5">
							<div class="form-group">
								<label class="control-label col-sm-3">Nama Alamat</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="address_name" placeholder="Contoh: Rumah, Kantor, dsb..." maxlength="64">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">Alamat</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="address_address" maxlength="128">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">Kode Pos</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="address_postcode" maxlength="5">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">Provinsi</label>
								<div class="col-sm-9">
									<select name="address_province_id" class="form-control">
									<?php foreach ($provinces as $province) {?>
									<option value="<?=$province['province_id']?>"><?=$province['name']?></option>
									<?php }?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">Kota / Kabupaten</label>
								<div class="col-sm-9">
									<select name="address_city_id" class="form-control"></select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-3">Kecamatan</label>
								<div class="col-sm-9">
									<select name="address_subdistrict_id" class="form-control"></select>
								</div>
							</div>
						</div>
					<?php }?>
					<div class="tab-pane" id="tab-4">
					<?php if ($user_id) {?>
						<div class="alert alert-info">Biarkan password kosong jika tidak ingin mengganti password</div>
					<?php } ?>
						<div class="form-group">
							<label class="control-label col-sm-3">Password</label>
							<div class="col-sm-9">
								<input type="password" class="form-control" name="password" value="" maxlength="25">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Konfirmasi Password</label>
							<div class="col-sm-9">
								<input type="password" class="form-control" name="confirm" value="" maxlength="25">
							</div>
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="modal"></div>
<script src="<?=base_url('assets/js/ajaxupload.js')?>" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
	    var tableAddress = $('#datatable-address').DataTable({
	    	"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('users/get_addresses')?>",
				"type": "POST",
				"data": function(d) {
					d.user_id = "<?=$user_id?>"
				}
			},
			"columns": [
				{"data": "address_id"},
				{"data": "user_id"},
				{"orderable": false, "searchable" : false, "data": "address_id"},
			],
			"sDom":'<t>ip',
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html('<address><b>'+data.name+'</b><br>'+data.address+', '+data.subdistrict+'<br>'+data.city+' - '+data.province+'</address>');
				html  = '<div class="btn-group btn-group-xs">';
				html += '<a onclick="addressForm('+data.address_id+');" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> Edit</a>';
				html += '<a onclick="delRecord('+data.address_id+');" class="btn btn-warning btn-xs"><i class="fa fa-minus-circle"></i> Hapus</a>';
				html += '</div>';
				$('td', row).eq(2).addClass('text-right').html(html);
				
				if (data.is_default === '1') {
					$('td', row).eq(1).html('<i class="fa fa-check" style="color:green;"></i>');
				} else {
					$('td', row).eq(1).html('');
				}
			},
			"order": [[0, 'asc']]
		});
	});
	
	new AjaxUpload('#upload', {
		action: "<?=admin_url('users/upload?user_id='.$user_id);?>",
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
				btn.button('loading');
			},
			complete: function() {
				btn.button('reset');
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
						$('textarea[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('textarea[name=\''+i+'\']').after('<span class="text-danger">' + json['errors'][i] + '</span>');	
					}
				} else if (json['success']) {
					window.location = "<?=admin_url('users')?>";
				}
			}
		});
	});
	
	function delRecord(address_id) {
		swal({
			title: "Apakah anda yakin?",
			text: "Data yang sudah dihapus tidak dapat dikembalikan!",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "Ya",
			cancelButtonText: "Tidak",
			closeOnConfirm: false,
			closeOnCancel: true
		}, function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					url : '<?=admin_url('address/delete')?>',
					type : 'post',
					data: 'address_id='+address_id,
					dataType: 'json',
					success: function(json) {
						if (json['success']) {
							swal("Terhapus!", json['success'], "success");
						} else if (json['error']) {
							swal("Error!", json['error'], "error");
						} else if (json['redirect']) {
							window.location = json['redirect'];
						}
						refreshTable();
					}
				});
			}
		});
	}
	
	function refreshTable() {
		$('#datatable-address').DataTable().ajax.reload();
	}
	
	function addressForm(address_id) {
		if (typeof(address_id) == 'undefined') {
			var _url = "<?=admin_url('address/create')?>";
		} else {
			var _url = "<?=admin_url('address/edit')?>";
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
						refreshTable();
					});	
				}
			}
		});
	}

	$('select[name=\'address_province_id\']').on('change', function() {
		$.ajax({
			url: "<?=site_url('location/province')?>",
			data: 'province_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'address_province_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
			},
			complete: function() {
				$('.fa-spin').remove();
			},
			success: function(json) {
				html = '<option value="">-- Pilih --</option>';

				if (json['cities'] && json['cities'] != '') {
					for (i = 0; i < json['cities'].length; i++) {
						html += '<option value="' + json['cities'][i]['city_id'] + '"';

						html += '>' + json['cities'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="" selected="selected">-- Kosong --</option>';
				}

				$('select[name=\'address_city_id\']').html(html);
				$('select[name=\'address_city_id\']').trigger('change');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('select[name=\'address_city_id\']').on('change', function() {
		$.ajax({
			url: "<?=site_url('location/city')?>",
			data: 'city_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'address_city_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
			},
			complete: function() {
				$('.fa-spin').remove();
			},
			success: function(json) {
				html = '<option value="">-- Pilih --</option>';

				if (json['subdistricts'] && json['subdistricts'] != '') {
					for (i = 0; i < json['subdistricts'].length; i++) {
						html += '<option value="' + json['subdistricts'][i]['subdistrict_id'] + '"';

						html += '>' + json['subdistricts'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="" selected="selected">-- Kosong --</option>';
				}

				$('select[name=\'address_subdistrict_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('select[name=\'address_province_id\']').trigger('change');
</script>