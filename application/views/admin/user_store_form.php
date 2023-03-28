<div class="page-title">
	<div>
		<h1><?=$template['title']?></h1>
	</div>
	<div class="btn-group">
		<a onclick="window.location = '<?=admin_url('user_store')?>'" class="btn btn-default"><i class="fa fa-reply"></i> Kembali</a>
		<a id="submit" class="btn btn-primary pull-right"><i class="fa fa-check"></i> Simpan</a>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<?=form_open($action, 'id="form" class="form-horizontal"')?>
				<input type="hidden" name="store_id" value="<?=$store_id?>">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-1" data-toggle="tab" id="default">General</a></li>
					<li><a href="#tab-2" data-toggle="tab">Pengiriman</a></li>
					<li><a href="#tab-3" data-toggle="tab">Catatan</a></li>
				</ul>
				<div class="tab-content" style="padding-top:20px;">
					<div class="tab-pane active" id="tab-1">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-province">Membership</label>
							<div class="col-sm-4">
								<select name="store_group_id" class="form-control">
									<?php foreach ($store_groups as $store_group) {?>
									<?php if ($store_group['store_group_id'] == $store_group_id) {?>
									<option value="<?=$store_group['store_group_id']?>" selected="selected"><?=$store_group['name']?></option>
									<?php } else {?>
									<option value="<?=$store_group['store_group_id']?>"><?=$store_group['name']?></option>
									<?php }?>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">Nama Toko</label>
							<div class="col-sm-10">
								<input type="text" name="name" value="<?=$name?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">Domain</label>
							<div class="col-sm-10">
								<div class="input-group">
									<span class="input-group-addon"><?=site_url('store')?>/</span>
									<input type="text" name="domain" value="<?=$domain?>" class="form-control">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">Slogan</label>
							<div class="col-sm-10">
								<input type="text" name="tagline" value="<?=$tagline?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">Deskripsi</label>
							<div class="col-sm-10">
								<textarea name="description" class="form-control" rows="8"><?=$description?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Logo</label>
							<div class="col-md-3">
								<img id="logo" src="<?=$logo?>" class="img-thumbnail">
								<div class="caption" style="margin-top:10px;">
									<button type="button" class="btn btn-default" id="upload">Ubah</button>
								</div>
							</div>
						</div><hr>
						<div class="form-group">
							<label class="col-sm-2 control-label">Gambar Cover</label>
							<div class="col-sm-6">
								<div class="thumbnail">
									<div id="image"><img src="<?=$image?>" class="img-thumbnail"></div>
									<div class="caption" style="margin-top:10px;">
										<button type="button" class="btn btn-default" id="upload2">Ubah</button>&nbsp;<small class="text-muted"><i>Ukuran ideal 1200 x 300 pixels</i></small>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Owner</label>
							<div class="col-sm-6">
								<input type="hidden" name="user_id" value="<?=$user_id?>">
								<input type="text" name="user" value="<?=$user?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Aktif</label>
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
							<label class="col-sm-2 control-label" for="input-province">Provinsi</label>
							<div class="col-sm-6">
								<select name="province_id" class="form-control">
									<?php foreach ($provinces as $province) {?>
									<?php if ($province['province_id'] == $province_id) {?>
									<option value="<?=$province['province_id']?>" selected="selected"><?=$province['name']?></option>
									<?php } else {?>
									<option value="<?=$province['province_id']?>"><?=$province['name']?></option>
									<?php }?>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-city">Kota/Kabupaten</label>
							<div class="col-sm-6">
								<select name="city_id" class="form-control"></select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-subdistrict">Kecamatan</label>
							<div class="col-sm-6">
								<select name="subdistrict_id" class="form-control"></select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">Kodepos</label>
							<div class="col-sm-3">
								<input type="text" name="postcode" value="<?=$postcode?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">No. Telephone</label>
							<div class="col-sm-3">
								<input type="text" name="telephone" value="<?=$telephone?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">Kurir</label>
							<div class="col-sm-10">
								<div class="well well-sm" style="height: 320px; overflow: auto;">
									<?php foreach ($couriers as $courier_code => $courier) {?>
									<div class="checkbox">
									<label>
									<?php if (array_key_exists($courier_code, $store_shipping)) { ?>
									<input type="checkbox" name="store_shipping[]" value="<?php echo $courier_code; ?>" checked="checked" />
									 <?php echo $courier; ?>
									<?php } else { ?>
									<input type="checkbox" name="store_shipping[]" value="<?php echo $courier_code; ?>" />
									 <?php echo $courier; ?>
									<?php } ?>
									</label>
									</div>
									<?php }?>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-3">
						<div class="form-group">
							<label class="control-label col-sm-2">Catatan Penjual</label>
							<div class="col-sm-10">
								<textarea name="seller_note" class="form-control" rows="8"><?=$seller_note?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2">Kebijakan Pengembalian Barang</label>
							<div class="col-sm-10">
								<textarea name="return_policy" class="form-control" rows="8"><?=$return_policy?></textarea>
							</div>
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="<?=base_url('assets/js/ajaxupload.js')?>" type="text/javascript"></script>
<script src="<?=site_url('assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.js')?>"></script>
<script type="text/javascript">
	new AjaxUpload('#upload', {
		action: "<?=admin_url('user_store/upload?type=logo&store_id='.$store_id);?>",
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
				$('#logo').attr('src', json.image);
			}
			if (json.error) {
				alert(json.error);
			}
			$('.loading').remove();	
		}
	});
	
	new AjaxUpload('#upload2', {
		action: "<?=admin_url('user_store/upload?type=cover&store_id='.$store_id);?>",
		name: 'userfile',
		autoSubmit: false,
		responseType: 'json',
		onChange: function(file, extension) {
			this.submit();
		},
		onSubmit: function(file, extension) {
			$('#upload2').append('<span class="wait"><i class="fa fa-refresh"></i></span>');
		},
		onComplete: function(file, json) {
			if (json.success) {
				$('.wait').remove();
				$('#image img').attr('src', json.image);
			}
			if (json.error) {
				alert(json.error);
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
					window.location = "<?=admin_url('user_store')?>";
				}
			}
		});
	});

	$('select[name=\'province_id\']').on('change', function() {
		$.ajax({
			url: "<?=site_url('location/province')?>",
			data: 'province_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'province_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
			},
			complete: function() {
				$('.fa-spin').remove();
			},
			success: function(json) {
				html = '<option value="">-- Pilih --</option>';

				if (json['cities'] && json['cities'] != '') {
					for (i = 0; i < json['cities'].length; i++) {
						html += '<option value="' + json['cities'][i]['city_id'] + '"';

						if (json['cities'][i]['city_id'] == '<?php echo $city_id; ?>') {
							html += ' selected="selected"';
						}

						html += '>' + json['cities'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0" selected="selected">-- None --</option>';
				}

				$('select[name=\'city_id\']').html(html);
				$('select[name=\'city_id\']').trigger('change');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('select[name=\'city_id\']').on('change', function() {
		$.ajax({
			url: "<?=site_url('location/city')?>",
			data: 'city_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'city_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
			},
			complete: function() {
				$('.fa-spin').remove();
			},
			success: function(json) {
				html = '<option value="">-- Pilih --</option>';

				if (json['subdistricts'] && json['subdistricts'] != '') {
					for (i = 0; i < json['subdistricts'].length; i++) {
						html += '<option value="' + json['subdistricts'][i]['subdistrict_id'] + '"';

						if (json['subdistricts'][i]['subdistrict_id'] == '<?php echo $subdistrict_id; ?>') {
							html += ' selected="selected"';
						}

						html += '>' + json['subdistricts'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0" selected="selected">-- None --</option>';
				}

				$('select[name=\'subdistrict_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('select[name=\'province_id\']').trigger('change');
	
	$('input[name=\'user\']').typeahead({
		source: function(query, process) {
			$.ajax({
				url: "<?=admin_url('users/autocomplete')?>",
				data: 'name=' + query,
				type: 'get',
				dataType: 'json',
				beforeSend: function() {
					$('span.form-control-feedback').removeClass('hide');
				},
				complete: function(){
					$('span.form-control-feedback').addClass('hide');
				},
				success: function(json) {
					users = [];
					map = {};
					$.each(json, function(i, user) {
						map[user.name] = user;
						users.push(user.name);
					});
					
					process(users);
				}
			});
		},
		updater: function (item) {
			$('input[name=\'user\']').val(map[item].name);
			$('input[name=\'user_id\']').val(map[item].user_id);
			
			return map[item].name;
		},
		minLength: 1
	});
</script>