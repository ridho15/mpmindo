<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<?=form_open($action, 'id="form" role="form" class="form-horizontal"')?>
			<input type="hidden" name="list_distributor_id" value="<?=$list_distributor_id?>">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab-1" data-toggle="tab" id="default"><?=lang('tab_general')?></a></li>
				<li><a href="#tab-4" data-toggle="tab"><?=lang('tab_business')?></a></li>
				<li><a href="#tab-2" data-toggle="tab"><?=lang('tab_address')?></a></li>
				<li><a href="#tab-3" data-toggle="tab"><?=lang('tab_others')?></a></li>
			</ul>
			<div class="tab-content" style="padding-top:20px;">
				<div class="tab-pane active" id="tab-1">
					<div class="form-group">
						<label class="control-label col-sm-4"><?=lang('entry_company_name')?></label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="company_name" value="<?=$company_name?>" maxlength="64">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4"><?=lang('entry_shop_name')?></label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="shop_name" value="<?=$shop_name?>" maxlength="64">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4"><?=lang('entry_number')?></label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="number_phone" value="<?=$number_phone?>" maxlength="64">
						</div>
					</div>
					
				</div>
				<div class="tab-pane" id="tab-4">
					<div class="form-group">
						<label class="control-label col-sm-4">Category</label>
						<div class="col-sm-8">
							<select class="form-control border" name="category">
								<option value="" selected disabled>-- Pilih Kategori --</option>
								<option value="Human Professional" <?php if ('Human Professional'==$category): ?>
								<?= 'selected' ?>
								<?php endif ?>>Human Professional
							</option>

							<option value="Human Consumer" <?php if ('Human Consumer'==$category): ?>
							<?= 'selected' ?>
							<?php endif ?>>Human Consumer
						</option>

						<option value="Animal Professional" <?php if ('Animal Professional'==$category): ?>
						<?= 'selected' ?>
						<?php endif ?>>Animal Professional
					</option>

					<option value="Animal Consumer" <?php if ('Animal Consumer'==$business): ?>
					<?= 'selected' ?>
					<?php endif ?>>Animal Consumer
				</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Business</label>
		<div class="col-sm-8">
			<select class="form-control border" name="business">
				<option value="" selected disabled>-- Pilih Kategori --</option>
				<option value="Distributor" <?php if ('Distributor'==$business): ?>
				<?= 'selected' ?>
				<?php endif ?>>Distributor</option>

				<option value="Wholesale" <?php if ('Wholesale'==$business): ?>
				<?= 'selected' ?>
				<?php endif ?>>Wholesale</option>

				<option value="Reseller" <?php if ('Reseller'==$business): ?>
				<?= 'selected' ?>
				<?php endif ?>>Reseller</option>

				<option value="Retailer" <?php if ('Retailer'==$business): ?>
				<?= 'selected' ?>
				<?php endif ?>>Retailler</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Haircut</label>
		<div class="col-sm-8">
			<select class="form-control border" name="haircut">
				<option value="" selected disabled>-- Haircut? --</option>
				<option value="1" <?php if (1==$haircut): ?>
				<?= 'selected' ?>
				<?php endif ?>>Ya</option>
				<option value="0" <?php if (0==$haircut): ?>
				<?= 'selected' ?>
				<?php endif ?>>Tidak</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Sales</label>
		<div class="col-sm-8">
			<select class="form-control border" name="sales">
				<option value="" selected disabled>-- Sales? --</option>
				<option value="1" <?php if (1==$sales): ?>
				<?= 'selected' ?>
				<?php endif ?>>Ya</option>
				<option value="0" <?php if (0==$sales): ?>
				<?= 'selected' ?>
				<?php endif ?>>Tidak</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Repair</label>
		<div class="col-sm-8">
			<select class="form-control border" name="repair">
				<option value="" selected disabled>-- Repair? --</option>
				<option value="1" <?php if (1==$repair): ?>
				<?= 'selected' ?>
				<?php endif ?>>Ya</option>
				<option value="0" <?php if (0==$repair): ?>
				<?= 'selected' ?>
				<?php endif ?>>Tidak</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Spare Part</label>
		<div class="col-sm-8">
			<select class="form-control border" name="sparepart">
				<option value="" selected disabled>-- Spare Part? --</option>
				<option value="1" <?php if (1==$spare_part): ?>
				<?= 'selected' ?>
				<?php endif ?>>Ya</option>
				<option value="0" <?php if (0==$spare_part): ?>
				<?= 'selected' ?>
				<?php endif ?>>Tidak</option>
			</select>
		</div>
	</div>
</div>
<div class="tab-pane" id="tab-2">
	<div class="form-group">
		<label class="control-label col-sm-3">Nama Alamat</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" name="address_name" placeholder="Contoh: Rumah, Kantor, dsb..." maxlength="64" value="<?=$address_name?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">Alamat</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" name="address" maxlength="128" value="<?=$address?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">Kode Pos</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" name="address_postcode" maxlength="5" value="<?= $post_code ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">Provinsi</label>
		<div class="col-sm-9">
			<select name="address_province_id" class="form-control">
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
			<select name="address_city_id" class="form-control"></select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">Kecamatan</label>
		<div class="col-sm-9">
			<select name="address_subdistrict_id" class="form-control"></select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-3">Map Coordinates</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" name="lat" placeholder="Latitude" maxlength="64" value="<?= $latitude ?>">
		</div>
		<div class="col-sm-5">
			<input type="text" class="form-control" name="long" placeholder="Longitude" maxlength="64" value="<?= $longitude ?>">
		</div>
	</div>
</div>
<div class="tab-pane" id="tab-3">
	<div class="form-group">
		<label class="control-label col-sm-4">Facebook</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="fb" placeholder="Facebook" maxlength="32" value="<?= $facebook ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Twiter</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="tw" placeholder="Twitter" maxlength="32" value="<?= $twiter ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Instagram</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="ig" placeholder="Instagram" maxlength="32" value="<?= $instagram ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Youtube</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="yb" placeholder="Youtube" maxlength="32" value="<?= $youtube ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Linkedin</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="linkedin" placeholder="Linkedin" maxlength="32"  value="<?= $linkedin ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Tiktok</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="tiktok" placeholder="Tiktok" maxlength="32" value="<?= $tiktok ?>">
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4">Website/ Market Place</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="website" placeholder="Website/ Market Place" maxlength="32" value="<?= $website ?>">
		</div>
	</div>
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
						if (json['cities'][i]['city_id'] == '<?php echo $city; ?>') {
							html += ' selected="selected"';
						}
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

						if (json['subdistricts'][i]['subdistrict_id'] == '<?php echo $sub_district; ?>') {
							html += ' selected="selected"';
						}

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