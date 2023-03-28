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
					<label class="control-label col-sm-3"><?=lang('column_claim_date')?></label>
					<div class="col-sm-9">
						<input type="text" name="productcode" value="<?=date('d-m-Y',strtotime($claim['claim_date']))?>" class="form-control" maxLength="100" readonly/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_product_code')?></label>
					<div class="col-sm-9">
						<input type="text" name="product_code" value="<?=$claim['product_code']?>" class="form-control" maxLength="100" readonly/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_product_name')?></label>
					<div class="col-sm-9">
						<input type="text" name="product_name" value="<?=$claim['product_name']?>" class="form-control" maxLength="100" readonly/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_current_status')?></label>
					<div class="col-sm-9">
						<input type="text" name="old_status" value="<?=$claim['status']?>" class="form-control" maxLength="100" readonly/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_new_status')?></label>
					<div class="col-sm-9">
						<select name="status" class="form-control" style="width:100%;">
							<option value="">-- Semua Status --</option>
							<?php if($claim['status'] == 'Klaim Baru') { ?>
								<option value="Barang Belum Diterima Dari Customer">Barang Belum Diterima Dari Customer</option>
								<option value="Barang Sudah Diterima Dari Customer Dan Sedang Diperiksa">Barang Sudah Diterima Dari Customer Dan Sedang Diperiksa</option>
								<option value="Klaim Ditolak">Klaim Ditolak</option>
							<?php } ?>
							<?php if($claim['status'] == 'Barang Belum Diterima Dari Customer') { ?>
								<option value="Barang Sudah Diterima Dari Customer Dan Sedang Diperiksa">Barang Sudah Diterima Dari Customer Dan Sedang Diperiksa</option>
								<option value="Klaim Ditolak">Klaim Ditolak</option>
							<?php } ?>
							<?php if($claim['status'] == 'Barang Sudah Diterima Dari Customer Dan Sedang Diperiksa') { ?>
								<option value="Klaim Disetujui Dan Sedang Diproses">Klaim Disetujui Dan Sedang Diproses</option>
								<option value="Klaim Dikenakan Biaya Dan Menunggu Pembayaran Dari Customer">Klaim Dikenakan Biaya Dan Menunggu Pembayaran Dari Customer</option>
								<option value="Klaim Tidak Disetujui Dan Barang Dikembalikan Ke Customer">Klaim Tidak Disetujui Dan Barang Dikembalikan Ke Customer</option>
							<?php } ?>
							<?php if($claim['status'] == 'Customer Telah Melakukan Pembayaran Dan Menunggu Konfirmasi') { ?>
								<option value="Klaim Disetujui Dan Sedang Diproses">Klaim Disetujui Dan Sedang Diproses</option>
								<option value="Klaim Tidak Disetujui Dan Barang Dikembalikan Ke Customer">Klaim Tidak Disetujui Dan Barang Dikembalikan Ke Customer</option>
							<?php } ?>
							<?php if($claim['status'] == 'Klaim Disetujui Dan Sedang Diproses') { ?>
								<option value="Proses Selesai Dan Barang Dikembalikan Ke Customer">Proses Selesai Dan Barang Dikembalikan Ke Customer</option>
							<?php } ?>
							<?php if($claim['status'] == 'Klaim Tidak Disetujui Dan Barang Dikembalikan Ke Customer') { ?>
								<option value="Barang Sudah Diterima Oleh Customer">Barang Sudah Diterima Oleh Customer</option>
								<option value="Selesai">Selesai</option>
							<?php } ?>
							<?php if($claim['status'] == 'Klaim Tidak Dilanjutkan Customer Dan Barang Dikembalikan Ke Customer') { ?>
								<option value="Barang Sudah Diterima Oleh Customer">Barang Sudah Diterima Oleh Customer</option>
								<option value="Selesai">Selesai</option>
							<?php } ?>
							<?php if($claim['status'] == 'Proses Selesai Dan Barang Dikembalikan Ke Customer') { ?>
								<option value="Barang Sudah Diterima Oleh Customer">Barang Sudah Diterima Oleh Customer</option>
								<option value="Selesai">Selesai</option>
							<?php } ?>	
							<?php if($claim['status'] == 'Barang Sudah Diterima Oleh Customer') { ?>
								<option value="Selesai">Selesai</option>
							<?php } ?>	
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_response')?></label>
					<div class="col-sm-9">
						<textarea name="response_note" class="form-control"><?=$claim['response_note']?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_upload_support_photo')?></label>
					<div class="col-sm-9">
						<label class="control-label"><a id="upload-images" class="btn btn-default btn-small"><i class="fa fa-upload"></i> Upload Gambar</a></label>
					</div>
				</div>
				<?php if($claim['status'] == 'Klaim Disetujui Dan Sedang Diproses') {?>
				<div class="form-group">
					<label class="control-label col-sm-3"><?=lang('column_new_product_code')?></label>
					<div class="col-sm-9">
						<input type="text" name="new_product_code" value="W" class="form-control" maxLength="50"/>
						<small><i>(diisi jika ada pergantian kode serial produk)</i></small>
					</div>
				</div>
				<?php } ?>
				<div class="form-group">
					<div class="col-sm-3"></div>
					<div class="col-sm-9">
						<div id="image-add">
						<?php if($claim['response_photo']) { ?>
							<div class="col-xs-6 col-md-3">
								<div class="thumbnail">
									<button onclick="deleteImage(this);" style="position:absolute;top:0;left:15px;" class="btn btn-danger" type="button"><span class="fa fa-remove"></span></button>
									<a href="<?=base_url('storage/images/'.$claim['response_photo'])?>" id="thumb-image" class="thumb-image"><img src="<?=base_url('storage/images/'.$claim['response_photo'])?>" alt="" title="" class="img-thumbnail" /></a><input type="hidden" name="invoice_image" value="<?=$claim['response_photo']?>" id="invoice_image" />
								</div>
							</div>
						<?php } ?>
						</div>
					</div>
				</div>
				<input type="hidden" name="id" value="<?=$claim['id']?>">
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> <?=lang('button_cancel')?></button>
			<button type="button" class="btn btn-primary" id="submit"><i class="fa fa-check"></i> <?=lang('button_save')?></button>
		</div>
	</div>
</div>