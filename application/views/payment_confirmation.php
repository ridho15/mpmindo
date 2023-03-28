<link href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">

<div class="ps-page--simple">
	<div class="ps-breadcrumb">
		<div class="ps-container">
			<ul class="breadcrumb">
				<li><a href="<?=site_url()?>">Home</a></li>
				<li>Konfirmasi Pembayaran</li>
			</ul>
		</div>
	</div>
	<div class="ps-section--page" style="padding: 30px 0;">		
		<div class="ps-container">
			<div class="ps-section__content">
				<div class="row">
					<div class="col-12">
						<div class="blog-single-main">
							<div class="row">
								<div class="col-12">
									<div class="blog-detail">
										<h2 class="blog-title">Konfirmasi Pembayaran</h2>
										<div class="content">
											<p>Sudah melakukan pembayaran? silakan lakukan konfirmasi di sini agar kami dapat segera memverifikasi pembayaran Anda</p>
											<?=form_open($action, 'id="contact-form" class="form"')?>
											<input type="hidden" name="order_id" value="<?=$order_id?>">
											<div class="form-group row">
												<label class="control-label col-sm-3">No. Invoice</label>
												<div class="col-sm-4">
													<input type="text" name="invoice_no" class="form-control" value="<?=$invoice_no?>">
												</div>
											</div>
											<div class="form-group row">
												<label class="control-label col-sm-3">Bank Tujuan</label>
												<div class="col-sm-4">
													<select name="to_bank" class="form-control">
													<?php foreach ($bank_accounts as $bank_account) {?>
													<option value="<?=$bank_account['title']?>"><?=$bank_account['title']?></option>
													<?php }?>
													</select>
												</div>
											</div>
											<div class="form-group row">
												<label class="control-label col-sm-3">Jumlah Transfer</label>
												<div class="col-sm-4">
													<input type="text" name="amount" value="<?=$amount?>" class="form-control" id="amount">
												</div>
											</div>
											<div class="form-group row">
												<label class="control-label col-sm-3">Tgl. Transfer</label>
												<div class="col-sm-4">
													<input type="text" name="date_transfered" value="<?=date('d-m-Y', time())?>" class="form-control" id="datepicker" data-date-format="dd-mm-yyyy" readonly="readonly">
												</div>
											</div>
											<div class="form-group row">
												<label class="control-label col-sm-3">Upload Bukti</label>
												<div class="col-sm-6">
													<div class="input-group mb-3">
														<input type="text" id="input-receipt" name="receipt_image" value="" class="form-control" readonly="readonly">
														<div class="input-group-append">
															<button type="button" class="btn" id="receipt"><span class="fa fa-upload"></span> UPLOAD</button>
														</div>
													</div>
													<?php if($mobile) echo '<i>*) Jika mengupload foto dengan kamera, gunakan posisi landscape pada kamera</i>';?>
												</div>
											</div>
											<div class="form-group row">
												<label class="control-label col-sm-3">Bank Pengirim</label>
												<div class="col-sm-9">
													<input type="text" name="from_bank" value="" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label class="control-label col-sm-3">Pemilik Rekening</label>
												<div class="col-sm-9">
													<input type="text" name="from_name" value="<?=$from_name?>" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label class="control-label col-sm-3">No Rekening Pengirim</label>
												<div class="col-sm-9">
													<input type="text" name="from_number" value="" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label class="control-label col-sm-3">No. Telepon</label>
												<div class="col-sm-9">
													<input type="text" name="telephone" value="<?=$telephone?>" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label class="control-label col-sm-3">Email</label>
												<div class="col-sm-9">
													<input type="text" name="email" value="<?=$email?>" class="form-control">
												</div>
											</div>
											<div class="form-group row">
												<label class="control-label col-sm-3">Catatan</label>
												<div class="col-sm-9">
													<textarea name="note" id="message" style="height:auto;" class="form-control" rows="5"></textarea>
												</div>
											</div>
										</form>
										<button id="submit" class="btn primary pull-right"><span class="fa fa-paper-plane"></span> Submit</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="<?=base_url('assets/js/ajaxupload.js')?>" type="text/javascript"></script>
<script src="<?=base_url('assets/js/plugins/bootstrap-datepicker.min.js')?>"></script>
<script type="text/javascript">
	$('#datepicker').datepicker({language:'id', autoclose:true});
	$('#submit').bind('click', function() {
		var btn = $(this);
		var btnTxt = btn.html();
		$.ajax({
			url: $('#contact-form').attr('action'),
			data: $('#contact-form').serialize(),
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				btn.attr('disabled', true);
				btn.html('<span class="spinner-border spinner-border-sm"></span> Loading..');
			},
			complete: function() {
				btn.attr('disabled', false);
				btn.html(btnTxt);
			},
			success: function(json) {
				$('input').removeClass('is-invalid');
				$('.invalid-feedback').remove();
				if (json['redirect']) {
					window.location = json['redirect']; 
				} else if (json['error']) {
					for (i in json['error']) {
						$('input[name=\''+i+'\']').addClass('is-invalid');
						$('input[name=\''+i+'\']').after('<div class="invalid-feedback">' + json['error'][i] + '</div>');	
					}
				}
			}
		});
	});
	
	new AjaxUpload('#receipt', {
		action: "<?=site_url('payment_confirmation/upload');?>",
		name: 'userfile',
		autoSubmit: false,
		responseType: 'json',
		onChange: function(file, extension) {
			this.submit();
		},
		onSubmit: function(file, extension) {
			$('#receipt').append('<span class="wait"><i class="fa fa-refresh"></i></span>');
		},
		onComplete: function(file, json) {
			if (json.success) { 
				$('.wait').remove();
				$('#input-receipt').val(json.file);
			}
			if (json.error) {
				alert(json.error);
			}
			
			$('.loading').remove();	
		}
	});
</script>