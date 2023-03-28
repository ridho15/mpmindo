<?php $inventory_row = 0; ?>
<script src="<?=base_url('assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.min.js')?>"></script>
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
				
				if (json['warning']) {
					$('#notification').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+json['warning']+'</div>');
				}
				
				if (json['error']) {
					for (i in json['error']) {
						$('input[name=\''+i+'\'], select[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('input[name=\''+i+'\'], select[name=\''+i+'\']').after('<span class="text-danger">' + json['error'][i] + '</span>');	
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
					url: "<?=admin_url('reg_sale_offline/upload')?>",
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

	var inventory_row = "<?=$inventory_row?>";
	
	var listname = "<?=$listname?>";

	function addInventory() {
		html  = '<tr id="inventory-row'+inventory_row+'">';
		html += '<td><input type="text" name="inventories['+inventory_row+'][code]" value="W" class="form-control" maxlength="50"></td>';
		html += '<td><select name="inventories['+inventory_row+'][name]" class="form-control">'+listname+'</select></td>';
		html += '<td class="text-right"><a onclick="$(\'#inventory-row'+inventory_row+'\').remove();" class="btn btn-danger btn-flat"><i class="fa fa-remove"></i></a></td>';
		html += '</tr>';
		
		$('#inventory-add').before(html);
		inventory_row++;
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
					<label class="control-label col-sm-3"><?=lang('column_shop_name')?></label>
					<div class="col-sm-9">
						<input type="text" name="shop_name" class="form-control" maxlength="150"/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_notes')?></label>
					<div class="col-sm-9">
						<textarea name="notes" class="form-control"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_upload_foto')?></label>
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
				<table class="table table-bordered">
					<thead>
						<tr>
							<th><?=lang('column_product_code')?></th>
							<th><?=lang('column_product_name')?></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr id="inventory-add">
							<td colspan="2"></td>
							<td class="text-right"><a onclick="addInventory();" class="btn btn-success btn-flat"><i class="fa fa-plus"></i></a></td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> <?=lang('button_cancel')?></button>
			<button type="button" class="btn btn-primary" id="submit"><i class="fa fa-check"></i> <?=lang('button_save')?></button>
		</div>
	</div>
</div>