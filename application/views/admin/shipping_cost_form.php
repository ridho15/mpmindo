<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<?=form_open($action, 'id="form" role="form" class="form-horizontal"')?>
				<input type="hidden" name="shipping_cost_id" value="<?=$shipping_cost_id?>">
				<div class="form-group">
					<label class="col-sm-4 control-label"><?=lang('label_province')?></label>
					<div class="col-sm-8">
						<select name="province_id" class="form-control" id="province_id">
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
					<label class="col-sm-4 control-label"><?=lang('label_city')?></label>
					<div class="col-sm-8">
						<select name="city_id" class="form-control" id="city_id"></select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"><?=lang('label_subdistrict')?></label>
					<div class="col-sm-8">
						<select name="subdistrict_id" class="form-control" id="subdistrict_id"></select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"><?=lang('label_cost')?></label>
					<div class="col-sm-8">
						<input type="text" name="cost" value="<?=$cost?>" class="form-control">
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> <?=lang('button_cancel')?></button>
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

						if (json['cities'][i]['city_id'] == '<?=$city_id?>') {
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

						if (json['subdistricts'][i]['subdistrict_id'] == '<?=$subdistrict_id?>') {
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
</script>