<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<?=form_open($action, 'id="form" role="form" class="form-horizontal"')?>
				<input type="hidden" name="warehouse_id" value="<?=$warehouse_id?>">
				<div class="form-group">
					<label class="control-label col-sm-4"><?=lang('label_code')?></label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="code" value="<?=$code?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4"><?=lang('label_name')?></label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="name" value="<?=$name?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4"><?=lang('label_region')?></label>
					<div class="col-sm-8">
						<select class="form-control" name="region">
							<option value="Aceh" <?php if($region == 'Aceh') echo 'selected';?>>Aceh</option>
							<option value="Sumatera Utara" <?php if($region == 'Sumatera Utara') echo 'selected';?>>Sumatera Utara</option>
							<option value="Sumatera Barat" <?php if($region == 'Sumatera Barat') echo 'selected';?>>Sumatera Barat</option>
							<option value="Riau" <?php if($region == 'Riau') echo 'selected';?>>Riau</option>
							<option value="Kepulauan Riau" <?php if($region == 'Kepulauan Riau') echo 'selected';?>>Kepulauan Riau</option>
							<option value="Jambi" <?php if($region == 'Jambi') echo 'selected';?>>Jambi</option>
							<option value="Sumatera Selatan" <?php if($region == 'Sumatera Selatan') echo 'selected';?>>Sumatera Selatan</option>
							<option value="Kepulauan Bangka Belitung" <?php if($region == 'Kepulauan Bangka Belitung') echo 'selected';?>>Kepulauan Bangka Belitung</option>
							<option value="Bengkulu" <?php if($region == 'Bengkulu') echo 'selected';?>>Bengkulu</option>
							<option value="Lampung" <?php if($region == 'Lampung') echo 'selected';?>>Lampung</option>
							<option value="DKI Jakarta" <?php if($region == 'DKI Jakarta') echo 'selected';?>>DKI Jakarta</option>
							<option value="Banten" <?php if($region == 'Banten') echo 'selected';?>>Banten</option>
							<option value="Jawa Barat" <?php if($region == 'Jawa Barat') echo 'selected';?>>Jawa Barat</option>
							<option value="Jawa Tengah" <?php if($region == 'Jawa Tengah') echo 'selected';?>>Jawa Tengah</option>
							<option value="DI Yogyakarta" <?php if($region == 'DI Yogyakarta') echo 'selected';?>>DI Yogyakarta</option>
							<option value="Jawa Timur" <?php if($region == 'Jawa Timur') echo 'selected';?>>Jawa Timur</option>
							<option value="Bali" <?php if($region == 'Bali') echo 'selected';?>>Bali</option>
							<option value="Nusa Tenggara Barat" <?php if($region == 'Nusa Tenggara Barat') echo 'selected';?>>Nusa Tenggara Barat</option>
							<option value="Nusa Tenggara Timur" <?php if($region == 'Nusa Tenggara Timur') echo 'selected';?>>Nusa Tenggara Timur</option>
							<option value="Kalimantan Barat" <?php if($region == 'Kalimantan Barat') echo 'selected';?>>Kalimantan Barat</option>
							<option value="Kalimantan Tengah" <?php if($region == 'Kalimantan Tengah') echo 'selected';?>>Kalimantan Tengah</option>
							<option value="Kalimantan Selatan" <?php if($region == 'Kalimantan Selatan') echo 'selected';?>>Kalimantan Selatan</option>
							<option value="Kalimantan Timur" <?php if($region == 'Kalimantan Timur') echo 'selected';?>>Kalimantan Timur</option>
							<option value="Kalimantan Utara" <?php if($region == 'Kalimantan Utara') echo 'selected';?>>Kalimantan Utara</option>
							<option value="Sulawesi Utara" <?php if($region == 'Sulawesi Utara') echo 'selected';?>>Sulawesi Utara</option>
							<option value="Gorontalo" <?php if($region == 'Gorontalo') echo 'selected';?>>Gorontalo</option>
							<option value="Sulawesi Tengah" <?php if($region == 'Sulawesi Tengah') echo 'selected';?>>Sulawesi Tengah</option>
							<option value="Sulawesi Barat" <?php if($region == 'Sulawesi Barat') echo 'selected';?>>Sulawesi Barat</option>
							<option value="Sulawesi Selatan" <?php if($region == 'Sulawesi Selatan') echo 'selected';?>>Sulawesi Selatan</option>
							<option value="Sulawesi Tenggara" <?php if($region == 'Sulawesi Tenggara') echo 'selected';?>>Sulawesi Tenggara</option>
							<option value="Maluku" <?php if($region == 'Maluku') echo 'selected';?>>Maluku</option>
							<option value="Maluku Utara" <?php if($region == 'Maluku Utara') echo 'selected';?>>Maluku Utara</option>
							<option value="Papua Barat" <?php if($region == 'Papua Barat') echo 'selected';?>>Papua Barat</option>
							<option value="Papua" <?php if($region == 'Papua') echo 'selected';?>>Papua</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4"><?=lang('label_address')?></label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="address" value="<?=$address?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4"><?=lang('label_telephone')?></label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="telephone" value="<?=$telephone?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4"><?=lang('label_email')?></label>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="email" value="<?=$email?>">
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
</script>