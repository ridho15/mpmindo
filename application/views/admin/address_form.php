<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<?=form_open($action, 'id="form-address" role="form" class="form-horizontal"')?>
				<input type="hidden" name="address_id" value="<?=$address_id?>">
				<input type="hidden" name="user_id" value="<?=$user_id?>">
		        <div class="form-group">
					<label class="control-label col-sm-3">Nama</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="name" value="<?=$name?>" placeholder="Contoh: Rumah, Kantor, dsb...">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3">Alamat</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="address" value="<?=$address?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3">Kode Pos</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="postcode" value="<?=$postcode?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3">Provinsi</label>
					<div class="col-sm-9">
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
					<label class="control-label col-sm-3">Kota / Kabupaten</label>
					<div class="col-sm-9">
						<select name="city_id" class="form-control"></select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3">Kecamatan</label>
					<div class="col-sm-9">
						<select name="subdistrict_id" class="form-control"></select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"></label>
					<div class="col-sm-9">
						<div class="checkbox">
							<label>
								<?php if ($default) {?>
								<input type="checkbox" name="default" value="1" checked="checked"> Alamat Default
								<?php } else {?>
								<input type="checkbox" name="default" value="1"> Alamat Default
								<?php }?>
							</label>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> Batal</button>
			<button type="button" class="btn btn-success" id="submit-address"><i class="fa fa-check"></i> Simpan</button>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#submit-address').bind('click', function() {
		$.ajax({
			url: $('#form-address').attr('action'),
			data: $('#form-address').serialize(),
			type: 'post',
			dataType: 'json',
			success: function(json) {
				$('.form-group').removeClass('has-error');
				$('.text-danger').remove();
				if (json['errors']) {
					for (i in json['errors']) {
						$('input[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('input[name=\''+i+'\']').after('<span class="text-danger">' + json['errors'][i] + '</span>');	
						$('select[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('select[name=\''+i+'\']').after('<span class="text-danger">' + json['errors'][i] + '</span>');	
					}
				} else if (json['success']){
					$('#form-modal').modal('hide');
					parent.$('#message').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> '+json['success']+'</div>');
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
					html += '<option value="" selected="selected">-- Kosong --</option>';
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
					html += '<option value="" selected="selected">-- Kosong --</option>';
				}

				$('select[name=\'subdistrict_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('select[name=\'province_id\']').trigger('change');
</script>