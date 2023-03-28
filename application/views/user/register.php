<div class="ps-breadcrumb">
	<div class="ps-container">
		<ul class="breadcrumb">
			<?php foreach ($template['breadcrumbs'] as $key => $breadcrumb) {?>
			<?php if (count($breadcrumb) == $key) {?>
			<li><?=$breadcrumb['text']?></li>
			<?php } else {?>
			<li><a href="<?=$breadcrumb['href']?>"><?=$breadcrumb['text']?></a></li>
			<?php }?>
			<?php }?>
		</ul>
	</div>
</div>
<div class="ps-page--my-account">
	<div class="ps-my-account-2">
		<div class="ps-container">
			<div class="ps-form--account ps-tab-root">
				<div class="ps-form__content">
					<?=form_open($action, 'id="form-register" class="form"')?>
						<h3><?=lang('heading_title')?></h3>
						<p><?=$text_account_already?></p>
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									<input class="form-control" type="text" name="name" required="required" placeholder="<?=lang('entry_name')?>" autofocus maxlength="64">
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label>Jenis Kelamin:</label>
									<div class="radio">
										<label class="radio-inline"><input type="radio" name="gender" value="m" checked="checked"> <?=lang('entry_male')?></label>
										<label class="radio-inline"><input type="radio" name="gender" value="f"> <?=lang('entry_female')?></label>
									</div>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label><?=lang('entry_dob')?></label>
									<div class="row">
										<div class="col-md-3">
											<select class="form-control" name="dob_date">
												<?php for ($i = 1; $i <= 31; $i++) {?>
												<option value="<?=$i?>"><?=$i?></option>
												<?php }?>
											</select>
										</div>
										<div class="col-md-5">
											<select class="form-control" name="dob_month">
												<?php foreach ($months as $key => $value) {?>
												<option value="<?=$key?>"><?=$value?></option>
												<?php }?>
											</select>
										</div>
										<div class="col-md-4">
											<select class="form-control" name="dob_year">
												<?php for ($i = date('Y')-17; $i >= 1950; $i--) {?>
												<option value="<?=$i?>"><?=$i?></option>
												<?php }?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<input class="form-control" type="text" name="telephone" placeholder="<?=lang('entry_telephone')?>" maxlength="32">
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<input class="form-control" type="text" name="address" placeholder="<?=lang('entry_address')?>" maxlength="100">
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<input class="form-control" type="text" name="postcode" placeholder="<?=lang('entry_postcode')?>" maxlength="5">
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<select name="province_id" class="form-control">
									<?php foreach ($provinces as $province) {?>
									<option value="<?=$province['province_id']?>"><?=$province['name']?></option>
									<?php }?>
									</select>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<select name="city_id" class="form-control"></select>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<select name="subdistrict_id" class="form-control"></select>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<input class="form-control" type="text" name="job" placeholder="<?=lang('entry_job')?>" maxlength="64">
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<select class="form-control" name="job_status">
										<option value="">- Status Kerja -</option>
										<option value="Barber">Barber</option>
										<option value="Barber Owner">Barber Owner</option>
										<option value="Lainnya">Lainnya</option>
									</select>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<input class="form-control" type="text" name="email" placeholder="<?=lang('entry_email')?>" maxlength="64">
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<input class="form-control" type="password" name="password" placeholder="<?=lang('entry_password')?>" maxlength="25">
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<input class="form-control" type="password" name="confirm" placeholder="<?=lang('entry_confirm_password')?>" maxlength="25">
								</div>
							</div>
							<div class="col-12">
								<div class="form-group create-account">
									<label><?=$text_agree?></label>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<button class="ps-btn" type="button" id="button-register"><i class="fa fa-pencil"></i> <?=lang('button_register')?></button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).delegate('#button-register', 'click', function(e) {
		e.preventDefault();
		var btn = $(this);
		$.ajax({
			url: $('#form-register').attr('action'),
			data: $('#form-register').serialize(),
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				btn.html('<i class="fa fa-refresh"></i> Sedang Diproses...');
				btn.attr("disabled", true);
				//btn.button('loading');
			},
			complete: function() {
				btn.html('<i class="fa fa-pencil"></i> Daftar');
				btn.removeAttr("disabled");
				//btn.button('reset');
			},
			success: function(json) {
				$('.alert, .error').remove();
				$('.form-group').removeClass('has-error');
				if (json['redirect']) {
					window.location = json['redirect'];
				} else if (json['error']) {
					for (i in json['error']) {
						$('input[name=\''+i+'\']').after('<small class="help-block error"><i>'+json['error'][i]+'</i></small>');
						$('input[name=\''+i+'\']').parent().addClass('has-error');
						$('textarea[name=\''+i+'\']').after('<small class="help-block error"><i>'+json['error'][i]+'</i></small>');
						$('textarea[name=\''+i+'\']').parent().addClass('has-error');
						$('select[name=\''+i+'\']').after('<small class="help-block error"><i>'+json['error'][i]+'</i></small>');
						$('select[name=\''+i+'\']').parent().addClass('has-error');
					}
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