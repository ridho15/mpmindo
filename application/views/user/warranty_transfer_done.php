<div class="ps-breadcrumb">
	<div class="ps-container">
		<ul class="breadcrumb">
			<?php foreach ($template['breadcrumbs'] as $key => $breadcrumb) {?>
			<?php if (count($breadcrumb) == $key+1) {?>
			<li><?=$breadcrumb['text']?></li>
			<?php } else {?>
			<li><a href="<?=$breadcrumb['href']?>"><?=$breadcrumb['text']?></a></li>
			<?php }?>
			<?php }?>
		</ul>
	</div>
</div>
<div class="ps-page--product">
	<div class="ps-container">
		<div class="ps-page__container">
			<div class="ps-page__left">
				<div class="row">
					<div class="col-md-12">		
						<div id="message"></div>
						<div id="notification"></div>
						<?=form_open($action, 'id="form" class="form"')?>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Kode Serial Produk:</label>
										<input type="text" name="product_code" value="<?=$claim['product_code'];?>" class="form-control" readonly/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Nama Produk:</label>
										<input type="text" name="product_name" value="<?=$claim['product_name'];?>" class="form-control" readonly/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Upload Bukti Transfer Pembayaran Klaim:</label>
										<label><a id="upload-images" class="btn btn-default"><i class="fa fa-upload"></i> Upload</a></label>
										<div id="image-add"></div>
									</div>
								</div>
							</div>
							<input type="hidden" name="id" value="<?=$claim['id']?>">
						</form><br><br><br><br><br><br><br><br><br>
						<hr>
						<div class="mb-5">
							<button onclick="window.location='<?=user_url('warranty')?>';" class="ps-btn ps-btn--outline mt-3 mb-3"><i class="fa fa-reply"></i> Kembali</button>
							<button id="submit" class="ps-btn"><i class="fa fa-check"></i> Submit</button>
						</div>
					</div>
				</div>
			</div>
			<div class="ps-page__right">
				<aside class="widget widget_features">
					<ul class="list-unstyled">
						<li class="mb-3"><a href="<?=user_url()?>"><i class="icon-network"></i> Dashboard</a></li>
						<li class="mb-3"><a href="<?=user_url('profile/edit')?>"><i class="icon-user"></i> Edit Profil</a></li>
						<li class="mb-3"><a href="<?=user_url('purchase')?>"><i class="icon-bag"></i> Pesanan Saya</a></li>
						<li class="mb-3"><a href="<?=user_url('reg_sale_offline')?>"><i class="fa fa-edit"></i> Registrasi Produk</a></li>
						<li class="mb-3"><a href="<?=user_url('warranty')?>"><i class="fa fa-medkit"></i> Klaim Garansi</a></li>
						<li><a href="<?=user_url('logout')?>"><i class="icon-user"></i> Logout</a></li>
					</ul>
				</aside>
			</div>
		</div>
	</div>
</div>
<div id="modal"></div>
<script src="<?=base_url('assets/js/plugins/bootstrap-datepicker.min.js')?>"></script>
<script src="<?=base_url('assets/js/plugins/bootstrap-datepicker.id.js')?>"></script>
<script type="text/javascript">
	$('#submit').bind('click', function() {
		var btn = $(this);
		$.ajax({
			url: $('#form').attr('action'),
			data: $('#form').serialize(),
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				btn.html('<i class="fa fa-refresh"></i> Sedang Diproses...');
				btn.attr("disabled", true);
				//btn.button('loading');
			},
			complete: function() {
				btn.html('<i class="fa fa-check"></i> Submit');
				btn.removeAttr("disabled");
				//btn.button('reset');
			},
			success: function(json) {
				$('.form-group').removeClass('has-error');
				$('.text-danger').remove();
				$('.alert-danger').remove();
				if (json['error']) {
					$('#notification').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+json['error']+'</div>');
				}

				if (json['errors']) {
					for (i in json['errors']) {
						$('input[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('select[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('input[name=\''+i+'\']').after('<span class="text-danger">' + json['errors'][i] + '</span>');
						$('select[name=\''+i+'\']').after('<span class="text-danger">' + json['errors'][i] + '</span>');	
						if(i =='invoice_image') {
							$('#notification').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bukti Transfer Perlu Diupload</div>');
						}
						else if(i =='claim_description') {
							$('#notification').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Deskripsi Kerusakan Perlu Diisi</div>');
						}
					}
				} else if (json['success']) {
					window.location = "<?=user_url('warranty')?>";
				}
			}
		});
	});

	var image_row = 0;

	function addImage(image, thumb) {
		html  = '<div class="col-xs-6 col-md-3">';
		html +=	'	<div class="thumbnail">';
		html += '		<button onclick="deleteImage(this);" style="position:block;top:0;left:15px;" class="btn btn-danger" type="button"><span class="fa fa-remove"></span></button>';
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
					url: "<?=user_url('reg_sale_offline/upload')?>",
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