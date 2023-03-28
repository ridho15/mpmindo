<?php $inventory_row = 0; ?>
<script src="<?=base_url('assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.min.js')?>"></script>
<script type="text/javascript">
$('#form select').select2();
	$('#submit').bind('click', function() {
		$.ajax({
			url: $('#form').attr('action'),
			data: $('#form').serialize(),
			type: 'post',
			dataType: 'json',
			success: function(json) {
				$('.form-group').removeClass('has-error');
				$('.text-danger').remove();
				
				if (json['warning']) {
					$('#notification').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+json['warning']+'</div>');
				}
				
				if (json['error']) {
					for (i in json['error']) {
						$('input[name=\''+i+'\'], select[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('input[name=\''+i+'\'], select[name=\''+i+'\']').after('<span class="text-danger">' + json['error'][i] + '</span>');	
						if(i == 'invoice_image') {
							$('#notification').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+json['error']['invoice_image']+'</div>');
						}
						if(i == 'claim_description') {
							$('textarea[name=\''+i+'\'], select[name=\''+i+'\']').closest('.form-group').addClass('has-error');
							$('textarea[name=\''+i+'\'], select[name=\''+i+'\']').after('<span class="text-danger">' + json['error'][i] + '</span>');	
						}
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

	var image_row = 0;

	function addImage(image, thumb) {
		html  = '<div class="col-xs-6 col-md-3">';
		html +=	'	<div class="thumbnail">';
		html += '		<button onclick="deleteImage(this);" style="position:absolute;top:0;left:15px;" class="btn btn-danger" type="button"><span class="fa fa-remove"></span></button>';
		html +=	'		<a href="#" id="thumb-image' + image_row + '" class="thumb-image"><img src="'+thumb+'" class="img-thumbnail"/></a><input type="hidden" name="invoice_image" value="'+image+'" id="invoice_image" />';
		html +=	'	</div>';
		html +=	'</div>';
		
		$('#image-add').append(html);
		
		image_row++;
	}
	
	$('#upload-images').on('click', function() {
		$('#form-upload').remove();
		$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="userfile" value="" multiple="multiple" /></form>');
		$('#form-upload input[name=\'userfile\']').trigger('click');

		if (typeof timer != 'undefined') {
			clearInterval(timer);
		}

		timer = setInterval(function() {
			if ($('#form-upload input[name=\'userfile\']').val() != '') {
				clearInterval(timer);

				$.ajax({
					url: "<?=admin_url('reg_sale_offline/upload_warranty')?>",
					type: 'post',
					dataType: 'json',
					data: new FormData($('#form-upload')[0]),
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function() {
						$('#upload-images i').replaceWith('<i class="fa fa-refresh fa-spin"></i>');
						$('#upload-images').prop('disabled', true);
					},
					complete: function() {
						$('#upload-images i').replaceWith('<i class="fa fa-upload"></i>');
						$('#upload-images').prop('disabled', false);
					},
					success: function(json) {
						if (json['error']) {
							alert(json['error']);
						}

						if (json['success']) {
							addImage(json['image'], json['thumb']);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		}, 500);
	});
	
	function deleteImage(element) {
		if (confirm('Apakah anda yakin?')) {
			element = $(element).parent().parent();
			element.remove();
		}
	}
</script>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<div id="notification"></div>
			<?=form_open($action, 'id="form" role="form" class="form-horizontal"')?>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_product_code')?></label>
					<div class="col-sm-9">
						<input type="text" name="product_code" value="<?=$product_code?>" class="form-control" maxLength="50" readonly/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_product_name')?></label>
					<div class="col-sm-9">
						<input type="text" name="product_name" value="<?=$product_name?>" class="form-control" maxLength="100" readonly/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_reason')?></label>
					<div class="col-sm-9">
						<select name="reason" class="form-control">
							<option value="Mesin Mati/Tidak Nyala">Mesin Mati/Tidak Nyala</option>
							<option value="Mesin Berisik">Mesin Berisik</option>
							<option value="Mesin Berasap">Mesin Berasap</option>
							<option value="Lainnya">Lainnya</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_claim_description')?></label>
					<div class="col-sm-9">
						<textarea name="claim_description" class="form-control"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_warehouse_dest')?></label>
					<div class="col-sm-9">
						<select name="warehouse_destination" class="form-control">
							<?php foreach ($warehouse_froms as $key => $from) {?>
								<option value="<?=$key?>"><?=$from?></option>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_upload_warranty')?></label>
					<div class="col-sm-9">
						<label class="control-label"><a id="upload-images" class="btn btn-default btn-small"><i class="fa fa-upload"></i> Upload Foto</a></label>
						<?php if($mobile) echo '<br><i>*) Jika mengupload foto dengan kamera, gunakan posisi landscape pada kamera</i>';?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3"></div>
					<div class="col-sm-9">
						<div id="image-add"></div>
					</div>
				</div>
				<input type="hidden" name="sale_type" value="<?=$sale_type?>">
				<input type="hidden" name="sale_id" value="<?=$sale_id?>">
				<input type="hidden" name="reg_id" value="<?=$reg_id?>">
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> <?=lang('button_cancel')?></button>
			<button type="button" class="btn btn-primary" id="submit"><i class="fa fa-check"></i> <?=lang('button_save')?></button>
		</div>
	</div>
</div>