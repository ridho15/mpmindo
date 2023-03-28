<h5>Biodata &amp; Alamat Pengiriman</h5>
<p>Anda sudah punya akun sebelumnya? Anda bisa mengubah preferensi checkout anda <a href="<?=site_url('checkout/option')?>"><b style="color:red;">di sini</b></a> untuk kemudahan dalam melakukan pemesanan.</p>
<p class="text-muted">Isikan data anda beserta alamat pengiriman dengan lengkap.</p>
<?=form_open($action, 'id="form-sa" style="margin-top:20px"')?>
	<div class="form-group">
		<label><?=lang('entry_name')?></label>
		<input type="text" class="form-control" name="name" value="<?=$name?>" maxlength="100">
	</div>
	<div class="form-group">
		<label><?=lang('entry_address')?></label>
		<textarea class="form-control" name="address" rows="5"><?=$address?></textarea>
	</div>
	<div class="form-row">
		<div class="form-group col-md-4">
			<label><?=lang('entry_postcode')?></label>
			<input type="text" class="form-control" name="postcode" value="<?=$postcode?>" maxlength="5">
		</div>
		<div class="form-group col-md-4">
			<label><?=lang('entry_email')?></label>
			<input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100">
		</div>
		<div class="form-group col-md-4">
			<label><?=lang('entry_telephone')?></label>
			<input type="text" class="form-control" name="telephone" value="<?=$telephone?>" maxlength="32">
		</div>
	</div>
	<div class="form-row">
		<div class="form-group col-md-4">
			<label><?=lang('entry_province')?></label>
			<select name="province_id" class="form-control">
				<?php foreach ($provinces as $province) {?>
				<?php if ($province['province_id'] ==  $province_id) {?>
				<option value="<?=$province['province_id']?>" selected="selected"><?=$province['name']?></option>
				<?php } else {?>
				<option value="<?=$province['province_id']?>"><?=$province['name']?></option>
				<?php }?>
				<?php }?>
			</select>
		</div>
		<div class="form-group col-md-4">
			<label><?=lang('entry_regency')?></label>
			<select name="city_id" class="form-control"></select>
		</div>
		<div class="form-group col-md-4">
			<label><?=lang('entry_district')?></label>
			<select name="subdistrict_id" class="form-control"></select>
		</div>
	</div>
	<?php //if ($has_tax) {?>
	<hr>
	<p class="text-muted">Bagi pembeli yang menginginkan faktur pajak, harap memasukkan <b>nama</b> dan <b>alamat</b> sesuai NPWP</p>
	<div class="form-group">
		<div class="checkbox">
			<label>
				<?php if ($no_tax_id) {?>
				<input type="checkbox" name="no_tax_id" value="1" id="tax-checkbox" checked="checked">
				<?php } else {?>
				<input type="checkbox" name="no_tax_id" value="1" id="tax-checkbox">
				<?php }?> Saya tidak memiliki NPWP
			</label>
		</div>
	</div>
	<div id="tax-box" style="border:1px solid #ddd;padding: 20px;">
		<div class="form-group">
			<label><?=lang('entry_tax_id')?></label>
			<input type="text" class="form-control" id="npwp" name="tax_id" value="<?=$tax_id?>" placeholder="00.000.000.0-000.000">
		</div>
		<div class="form-group">
			<label>Nama WP</label>
			<input type="text" class="form-control" id="npwp_name" name="tax_name" value="<?=$tax_name?>" placeholder="">
		</div>
		<div class="form-group">
			<label>Alamat WP</label>
			<input type="text" class="form-control" id="npwp_addess" name="tax_address" value="<?=$tax_address?>" placeholder="">
		</div>
	</div>
	<?php // }?>
</form>
<hr>
<div>
	<button class="ps-btn pull-right" id="button-sa">Lanjut <i class="fa fa-chevron-right"></i></button>
</div>
<script type="text/javascript">	
	$(document).ready(function() {
		$('#tax-checkbox').on('change', function() {
			if ($(this).prop('checked') == true) {
				$('#tax-box').hide();
			} else {
				$('#tax-box').show();
			}
		});
	});
	
	$('#tax-checkbox').trigger('change');
	
	$('#shipping-address select[name=\'province_id\']').on('change', function() {
		$.ajax({
			url: "<?=site_url('location/province')?>",
			data: 'province_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('#shipping-address select[name=\'province_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
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

				$('#shipping-address select[name=\'city_id\']').html(html);
				$('#shipping-address select[name=\'city_id\']').trigger('change');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('#shipping-address select[name=\'city_id\']').on('change', function() {
		$.ajax({
			url: "<?=site_url('location/city')?>",
			data: 'city_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('#shipping-address select[name=\'city_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
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

				$('#shipping-address select[name=\'subdistrict_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('#shipping-address select[name=\'province_id\']').trigger('change');
	
	if ($('#npwp').length > 0) {
		var element = document.getElementById('npwp');
		var mask = IMask(element, {
			mask: '00.000.000.0-000.000'
		});
	}
</script>