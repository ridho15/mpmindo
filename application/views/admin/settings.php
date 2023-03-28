<div class="page-title">
	<div>
		<h1><?=lang('heading_title')?></h1>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="form-action">
					<button id="submit" class="btn btn-primary"><i class="fa fa-lg fa-check"></i> <?=lang('button_save')?></button>
				</div>
				<?=form_open($action, 'id="form" class="form-horizontal"')?>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-1" data-toggle="tab" id="default"><?=lang('tab_general')?></a></li>
					<li><a href="#tab-2" data-toggle="tab"><?=lang('tab_seo')?></a></li>
					<li><a href="#tab-4" data-toggle="tab"><?=lang('tab_shipping')?></a></li>
					<!-- <li><a href="#tab-5" data-toggle="tab"><?=lang('tab_order_status')?></a></li> -->
					<li><a href="#tab-6" data-toggle="tab"><?=lang('tab_option')?></a></li>
					<!-- <li><a href="#tab-7" data-toggle="tab">Social Media</a></li> -->
					<li><a href="#tab-8" data-toggle="tab">Klaim Garansi</a></li>
				</ul>
				<div class="tab-content" style="padding-top:20px;">
					<div class="tab-pane active" id="tab-1">
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('label_company')?></label>
							<div class="col-sm-8">
								<input type="text" name="company" value="<?=isset($company) ? $company : ''?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4">No. NPWP</label>
							<div class="col-sm-8">
								<input type="text" name="tax_id" value="<?=isset($tax_id) ? $tax_id : ''?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('label_address')?></label>
							<div class="col-sm-8">
								<textarea name="address" rows="5" class="form-control"><?=isset($address) ? $address : ''?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('label_telephone')?></label>
							<div class="col-sm-8">
								<input type="text" name="telephone" value="<?=isset($telephone) ? $telephone : ''?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('label_whatsapp')?></label>
							<div class="col-sm-8">
								<input type="text" name="whatsapp" value="<?=isset($whatsapp) ? $whatsapp : ''?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('label_fax')?></label>
							<div class="col-sm-8">
								<input type="text" name="fax" value="<?=isset($fax) ? $fax : ''?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('label_email')?></label>
							<div class="col-sm-8">
								<input type="text" name="email" value="<?=isset($email) ? $email : ''?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('label_logo')?></label>
							<div class="col-sm-4">
								<img id="image-logo" src="<?=$thumb_logo?>" class="img-thumbnail" data-placeholder="<?=$no_image?>">
								<div class="caption" style="margin-top:10px;">
									<button type="button" class="btn btn-default btn-block" id="upload-logo"><span class="fa fa-upload"></span> <?=lang('text_upload_logo')?></button>
								</div>
								<input type="hidden" id="input-logo" name="logo" value="<?=$logo?>">
								<?php if($mobile) echo '<i>*) Jika mengupload logo dengan kamera, gunakan posisi landscape pada kamera</i>';?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('label_favicon')?></label>
							<div class="col-sm-4">
								<img id="image-icon" src="<?=$thumb_icon?>" class="img-thumbnail" data-placeholder="<?=$no_image?>">
								<div class="caption" style="margin-top:10px;">
									<button type="button" class="btn btn-default btn-block" id="upload-icon"><span class="fa fa-upload"></span> <?=lang('text_upload_icon')?></button>
								</div>
								<input type="hidden" id="input-icon" name="icon" value="<?=$icon?>">
								<?php if($mobile) echo '<i>*) Jika mengupload favicon dengan kamera, gunakan posisi landscape pada kamera</i>';?>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-2">
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('label_site_name')?><br><span class="label-help"><?=lang('label_site_name_help')?></span></label>
							<div class="col-sm-8">
								<input type="text" name="site_name" value="<?=isset($site_name) ? $site_name : ''?>" class="form-control">
							</div>
						</div><hr>
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('label_seo_title')?><br><span class="label-help"><?=lang('label_seo_title_help')?></span></label>
							<div class="col-sm-8">
							<input type="text" name="seo_title" value="<?=isset($seo_title) ? $seo_title : ''?>" class="form-control">
							</div>
						</div><hr>
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('label_meta_keyword')?><br><span class="label-help"><?=lang('label_meta_keyword_help')?></span></label>
							<div class="col-sm-8">
							<textarea name="meta_keywords" class="form-control" rows="5"><?=isset($meta_keywords) ? $meta_keywords : ''?></textarea>
							</div>
						</div><hr>
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('label_meta_description')?><br><span class="label-help"><?=lang('label_meta_description_help')?></span></label>
							<div class="col-sm-8">
							<textarea name="meta_description" class="form-control" rows="5"><?=isset($meta_description) ? $meta_description : ''?></textarea>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-4">
						<div class="alert alert-warning"><?=lang('text_shipping')?></div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_rajaongkir_weight_class')?><br><span class="label-help"><?=lang('label_rajaongkir_weight_class_help')?></span></label>
							<div class="col-sm-4">
								<select name="rajaongkir_weight_class_id" class="form-control">
									<?php foreach ($weight_classes as $weight_class) {?>
									<?php if ($weight_class['weight_class_id'] == $rajaongkir_weight_class_id) { ?>
									<option value="<?=$weight_class['weight_class_id']?>" selected="selected"><?=$weight_class['title']?></option>
									<?php } else {?>
									<option value="<?=$weight_class['weight_class_id']?>"><?=$weight_class['title']?></option>
									<?php }?>
									<?php }?>
								</select>
							</div>
						</div><hr>
						<div class="form-group">
							<label class="col-sm-4 control-label">Alamat Asal Pengiriman<br><span class="label-help">Barang dikirim dari mana?</span></label>
							<div class="col-sm-3">
								<select name="rajaongkir_province_id" class="form-control">
									<?php foreach ($provinces as $province) {?>
									<?php if ($province['province_id'] == $rajaongkir_province_id) {?>
									<option value="<?=$province['province_id']?>" selected="selected"><?=$province['name']?></option>
									<?php } else {?>
									<option value="<?=$province['province_id']?>"><?=$province['name']?></option>
									<?php }?>
									<?php }?>
								</select>
							</div>
							<div class="col-sm-3">
								<select name="rajaongkir_city_id" class="form-control"></select>
							</div>
							<div class="col-sm-2">
								<select name="rajaongkir_subdistrict_id" class="form-control"></select>
							</div>
						</div><hr>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_courier')?><br><span class="label-help"><?=lang('label_courier_help')?></span></label>
							<div class="col-sm-8">
								<div>
									<?php foreach ($couriers as $courier_code => $courier) {?>
									<div class="checkbox">
									<label>
									<?php if (in_array($courier_code, $rajaongkir_courier)) { ?>
									<input type="checkbox" name="rajaongkir_courier[]" value="<?php echo $courier_code; ?>" checked="checked" />
									 <?php echo $courier; ?>
									<?php } else { ?>
									<input type="checkbox" name="rajaongkir_courier[]" value="<?php echo $courier_code; ?>" />
									 <?php echo $courier; ?>
									<?php } ?>
									</label>
									</div>
									<?php }?>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-5">
						<div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_order_status')?><br><span class="label-help"><?=lang('label_order_status_help')?></span></label>
							<div class="col-sm-4">
								<select name="order_status_id" class="form-control">
									<?php foreach ($order_statuses as $order_status) {?>
									<?php if ($order_status['order_status_id'] == $order_status_id) { ?>
									<option value="<?=$order_status['order_status_id']?>" selected="selected"><?=$order_status['name']?></option>
									<?php } else {?>
									<option value="<?=$order_status['order_status_id']?>"><?=$order_status['name']?></option>
									<?php }?>
									<?php }?>
								</select>
							</div>
						</div><hr>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_order_cancel_status')?><br><span class="label-help"><?=lang('label_order_cancel_status_help')?></span></label>
							<div class="col-sm-4">
								<select name="order_cancel_status_id" class="form-control">
									<?php foreach ($order_statuses as $order_status) {?>
									<?php if ($order_status['order_status_id'] == $order_cancel_status_id) { ?>
									<option value="<?=$order_status['order_status_id']?>" selected="selected"><?=$order_status['name']?></option>
									<?php } else {?>
									<option value="<?=$order_status['order_status_id']?>"><?=$order_status['name']?></option>
									<?php }?>
									<?php }?>
								</select>
							</div>
						</div><hr>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_order_paid_status')?><br><span class="label-help"><?=lang('label_order_paid_status_help')?></span></label>
							<div class="col-sm-4">
								<select name="order_paid_status_id" class="form-control">
									<?php foreach ($order_statuses as $order_status) {?>
									<?php if ($order_status['order_status_id'] == $order_paid_status_id) { ?>
									<option value="<?=$order_status['order_status_id']?>" selected="selected"><?=$order_status['name']?></option>
									<?php } else {?>
									<option value="<?=$order_status['order_status_id']?>"><?=$order_status['name']?></option>
									<?php }?>
									<?php }?>
								</select>
							</div>
						</div><hr>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_order_process_status')?><br><span class="label-help"><?=lang('label_order_process_status_help')?></span></label>
							<div class="col-sm-4">
								<select name="order_process_status_id" class="form-control">
									<?php foreach ($order_statuses as $order_status) {?>
									<?php if ($order_status['order_status_id'] == $order_process_status_id) { ?>
									<option value="<?=$order_status['order_status_id']?>" selected="selected"><?=$order_status['name']?></option>
									<?php } else {?>
									<option value="<?=$order_status['order_status_id']?>"><?=$order_status['name']?></option>
									<?php }?>
									<?php }?>
								</select>
							</div>
						</div><hr>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_order_delivered_status')?><br><span class="label-help"><?=lang('label_order_delivered_status_help')?></span></label>
							<div class="col-sm-4">
								<select name="order_delivered_status_id" class="form-control">
									<?php foreach ($order_statuses as $order_status) {?>
									<?php if ($order_status['order_status_id'] == $order_delivered_status_id) { ?>
									<option value="<?=$order_status['order_status_id']?>" selected="selected"><?=$order_status['name']?></option>
									<?php } else {?>
									<option value="<?=$order_status['order_status_id']?>"><?=$order_status['name']?></option>
									<?php }?>
									<?php }?>
								</select>
							</div>
						</div><hr>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_order_complete_status')?><br><span class="label-help"><?=lang('label_order_complete_status_help')?></span></label>
							<div class="col-sm-4">
								<select name="order_complete_status_id" class="form-control">
									<?php foreach ($order_statuses as $order_status) {?>
									<?php if ($order_status['order_status_id'] == $order_complete_status_id) { ?>
									<option value="<?=$order_status['order_status_id']?>" selected="selected"><?=$order_status['name']?></option>
									<?php } else {?>
									<option value="<?=$order_status['order_status_id']?>"><?=$order_status['name']?></option>
									<?php }?>
									<?php }?>
								</select>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-6">
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('label_maintenance')?><br><span class="label-help"><?=lang('label_maintenance_help')?></span></label>
							<div class="toggle lg col-sm-4">
								<label style="margin-top:5px;">
								<?php if (!empty($maintenance)) {?>
								<input type="checkbox" name="maintenance" value="1" checked="checked">
								<?php } else {?>
								<input type="checkbox" name="maintenance" value="1">
								<?php }?>
								<span class="button-indecator"></span>
								</label>
							</div>
						</div><hr>
						<div class="form-group">
							<label class="control-label col-sm-4"><?=lang('label_registration')?><br><span class="label-help"><?=lang('label_registration_help')?></span></label>
							<div class="toggle lg col-sm-4">
								<label style="margin-top:5px;">
								<?php if (!empty($registration)) {?>
								<input type="checkbox" name="registration" value="1" checked="checked">
								<?php } else {?>
								<input type="checkbox" name="registration" value="1">
								<?php }?>
								<span class="button-indecator"></span>
								</label>
							</div>
						</div><hr>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_weight_class')?><br><span class="label-help"><?=lang('label_weight_class_help')?></span></label>
							<div class="col-sm-4">
								<select name="weight_class_id" class="form-control">
									<?php foreach ($weight_classes as $weight_class) {?>
									<?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
									<option value="<?=$weight_class['weight_class_id']?>" selected="selected"><?=$weight_class['title']?></option>
									<?php } else {?>
									<option value="<?=$weight_class['weight_class_id']?>"><?=$weight_class['title']?></option>
									<?php }?>
									<?php }?>
								</select>
							</div>
						</div><hr>
						<!-- <div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_store')?><br><span class="label-help"><?=lang('label_store_help')?></span></label>
							<div class="col-sm-4">
								<select name="store_id" class="form-control">
									<?php foreach ($stores as $store) {?>
									<?php if ($store['store_id'] == $store_id) { ?>
									<option value="<?=$store['store_id']?>" selected="selected"><?=$store['code']?> (<?=$store['name']?>)</option>
									<?php } else {?>
									<option value="<?=$store['store_id']?>"><?=$store['code']?> (<?=$store['name']?>)</option>
									<?php }?>
									<?php }?>
								</select>
							</div>
						</div> -->
					</div>
					<div class="tab-pane" id="tab-7">
						<div class="form-group">
							<label class="control-label col-sm-4">Facebook</label>
							<div class="col-sm-8">
								<input type="text" name="facebook_link" value="<?=isset($facebook_link) ? $facebook_link : ''?>" class="form-control">
							</div>
						</div><hr>
						<div class="form-group">
							<label class="control-label col-sm-4">Instagram</label>
							<div class="col-sm-8">
								<input type="text" name="instagram_link" value="<?=isset($instagram_link) ? $instagram_link : ''?>" class="form-control">
							</div>
						</div><hr>
						<div class="form-group">
							<label class="control-label col-sm-4">Youtube</label>
							<div class="col-sm-8">
								<input type="text" name="youtube_link" value="<?=isset($youtube_link) ? $youtube_link : ''?>" class="form-control">
							</div>
						</div><hr>
						<div class="form-group">
							<label class="control-label col-sm-4">Twitter</label>
							<div class="col-sm-8">
								<input type="text" name="twitter_link" value="<?=isset($twitter_link) ? $twitter_link : ''?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-8">
						<div class="form-group">
							<label class="control-label col-sm-4">Masa Klaim Garansi (bulan)</label>
							<div class="col-sm-8">
								<input type="number" name="warranty_period" value="<?=isset($warranty_period) ? $warranty_period : ''?>" class="form-control">
							</div>
						</div><hr>
						<div class="form-group">
							<label class="control-label col-sm-4">Tujuan Transfer Klaim Garansi</label>
							<div class="col-sm-8">
								<input type="text" name="warranty_transfer" value="<?=isset($warranty_transfer) ? $warranty_transfer : ''?>" class="form-control">
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
<script type="text/javascript">
	new AjaxUpload('#upload-logo', {
		action: "<?=admin_url('settings/upload');?>",
		name: 'userfile',
		autoSubmit: false,
		responseType: 'json',
		onChange: function(file, extension) {
			this.submit();
		},
		onSubmit: function(file, extension) {
			$('#upload-logo').button('loading');
		},
		onComplete: function(file, json) {
			$('#upload-logo').button('reset');
			
			if (json.success) { 
				$('#image-logo').attr('src', json.thumb);
				$('#input-logo').val(json.image);
			}
			
			if (json.error) {
				$.notify({
					title: '<strong><?=lang('text_error')?></strong><br>',
					message: json.error,
					icon: 'fa fa-info' 
				},{
					type: "danger"
				});
			}
		}
	});
	
	new AjaxUpload('#upload-icon', {
		action: "<?=admin_url('settings/upload');?>",
		name: 'userfile',
		autoSubmit: false,
		responseType: 'json',
		onChange: function(file, extension) {
			this.submit();
		},
		onSubmit: function(file, extension) {
			$('#upload-icon').button('loading');
		},
		onComplete: function(file, json) {
			$('#upload-icon').button('reset');
			
			if (json.success) { 
				$('#image-icon').attr('src', json.thumb);
				$('#input-icon').val(json.image);
			}
			
			if (json.error) {
				$.notify({
					title: '<strong><?=lang('text_error')?></strong><br>',
					message: json.error,
					icon: 'fa fa-info' 
				},{
					type: "danger"
				});
			}
		}
	});
	
	$('#submit').bind('click', function() {
		$btn = $(this);
		$.ajax({
			url: $('#form').attr('action'),
			data: $('#form').serialize(),
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				$btn.button('loading');
			},
			complete: function() {
				$btn.button('reset');
			},
			success: function(json) {
				$('.error').remove();
				$('.form-group').removeClass('has-error');
				
				if (json['success']) {
					$.notify({
						title: '<strong><?=lang('text_success')?></strong><br>',
						message: json['success'],
						icon: 'fa fa-check' 
					},{
						type: "success"
					});
				} else if (json['error']) {
					$.notify({
						title: '<strong><?=lang('text_error')?></strong><br>',
						message: json['error'],
						icon: 'fa fa-info' 
					},{
						type: "danger"
					});
				} else if (json['errors']) {
					for (i in json['errors']) {
						$('input[name=\''+i+'\']').after('<span class="help-block error">'+json['errors'][i]+'</span>');
						$('input[name=\''+i+'\']').parent().addClass('has-error');
					}
				}
			}
		});
	});
	
	$('select[name=\'rajaongkir_province_id\']').on('change', function() {
		$.ajax({
			url: "<?=site_url('location/province')?>",
			data: 'province_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'rajaongkir_province_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
			},
			complete: function() {
				$('.fa-spin').remove();
			},
			success: function(json) {
				html = '<option value="">-- Pilih --</option>';

				if (json['cities'] && json['cities'] != '') {
					for (i = 0; i < json['cities'].length; i++) {
						html += '<option value="' + json['cities'][i]['city_id'] + '"';

						if (json['cities'][i]['city_id'] == '<?php echo $rajaongkir_city_id; ?>') {
							html += ' selected="selected"';
						}

						html += '>' + json['cities'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0" selected="selected">-- None --</option>';
				}

				$('select[name=\'rajaongkir_city_id\']').html(html);
				$('select[name=\'rajaongkir_city_id\']').trigger('change');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('select[name=\'rajaongkir_city_id\']').on('change', function() {
		$.ajax({
			url: "<?=site_url('location/city')?>",
			data: 'city_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'rajaongkir_city_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
			},
			complete: function() {
				$('.fa-spin').remove();
			},
			success: function(json) {
				html = '<option value="">-- Pilih --</option>';

				if (json['subdistricts'] && json['subdistricts'] != '') {
					for (i = 0; i < json['subdistricts'].length; i++) {
						html += '<option value="' + json['subdistricts'][i]['subdistrict_id'] + '"';

						if (json['subdistricts'][i]['subdistrict_id'] === '<?php echo $rajaongkir_subdistrict_id; ?>') {
							html += ' selected="selected"';
						}

						html += '>' + json['subdistricts'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0" selected="selected">-- None --</option>';
				}

				$('select[name=\'rajaongkir_subdistrict_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('select[name=\'rajaongkir_province_id\']').trigger('change');
</script>