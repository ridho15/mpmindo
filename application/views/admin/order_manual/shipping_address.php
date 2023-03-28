<?=form_open($action, 'class="form-horizontal"')?>
<?php if ($addresses) { ?>
<div class="radio">
	<label>
		<input type="radio" name="shipping_address" value="existing" checked="checked" /> Pilih alamat
	</label>
</div>
<div id="shipping-existing" style="margin:10px 0;">
	<select name="address_id" class="form-control">
		<?php foreach ($addresses as $address) { ?>
		<?php if ($address['address_id'] == $address_id) { ?>
		<option value="<?=$address['address_id']?>" selected="selected"><?=$address['name']?>, <?=$address['address']?>, <?=$address['subdistrict']?>, <?=$address['city']?>, <?=$address['province']?></option>
		<?php } else { ?>
		<option value="<?=$address['address_id']?>"><?=$address['name']?>, <?=$address['address']?>, <?=$address['subdistrict']?>, <?=$address['city']?>, <?=$address['province']?></option>
		<?php } ?>
		<?php } ?>
	</select>
</div>
<div class="radio">
	<label>
		<input type="radio" name="shipping_address" value="new"/> Buat alamat baru
	</label>
</div>
<?php } ?>
<div id="shipping-new" style="display: <?php echo ($addresses ? 'none' : 'block'); ?>; margin:10px 0;" class="">
	<div class="form-group">
		<label class="col-sm-3 control-label"><?=lang('entry_name')?></label>
		<div class="col-sm-9">
			<input type="text" class="form-control" name="name">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><?=lang('entry_address')?></label>
		<div class="col-sm-9">
			<input type="text" class="form-control" name="address">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><?=lang('entry_postcode')?></label>
		<div class="col-sm-3">
			<input type="text" class="form-control" name="postcode">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><?=lang('entry_province')?></label>
		<div class="col-sm-9">
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
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><?=lang('entry_regency')?></label>
		<div class="col-sm-9">
			<select name="city_id" class="form-control">
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><?=lang('entry_district')?></label>
		<div class="col-sm-9">
			<select name="subdistrict_id" class="form-control">
			</select>
		</div>
	</div>
</div>
<hr>
<div class="form-group">
	<div class="col-sm-12">
		<button class="btn btn-primary pull-left" onclick="setSteps(1);"><i class="fa fa-chevron-left"></i> Kembali</button>
		<button class="btn btn-primary pull-right">Lanjut <i class="fa fa-chevron-right"></i></button>
	</div>
</div>
</form>
<script type="text/javascript">	
	$('#tab-3 input[name=\'shipping_address\']').bind('change', function() {
		if (this.value == 'new') {
			$('#shipping-existing').hide();
			$('#shipping-new').show();
			$('#tab-3 select[name=\'province_id\']').trigger('change');
		} else {
			$('#shipping-existing').show();
			$('#shipping-new').hide();
		}
	});
	
	$('#tab-3 select[name=\'province_id\']').on('change', function() {
		$.ajax({
			url: "<?=site_url('location/province')?>",
			data: 'province_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('#tab-3 select[name=\'province_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
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

				$('#tab-3 select[name=\'city_id\']').html(html);
				$('#tab-3 select[name=\'city_id\']').trigger('change');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('#tab-3 select[name=\'city_id\']').on('change', function() {
		$.ajax({
			url: "<?=site_url('location/city')?>",
			data: 'city_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('#tab-3 select[name=\'city_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
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

				$('#tab-3 select[name=\'subdistrict_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('#tab-3 select[name=\'province_id\']').trigger('change');
</script>