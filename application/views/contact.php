<div class="ps-page--single" id="contact-us">
	<div class="ps-breadcrumb">
		<div class="ps-container">
			<ul class="breadcrumb">
				<li><a href="<?=site_url()?>">Home</a></li>		
				<li>Kontak</li>
			</ul>
		</div>
	</div>
	<div class="ps-contact-info">
            <div class="container">
                <div class="ps-section__content">
                    <div class="row">
                        <div class="col-12">
                            <div class="ps-block--contact-info">
                                <h4>Hubungi Kami</h4>
                                <p><b><?=$this->config->item('company')?></b></p>
                                <p><span class="fa fa-map-marker"></span> <span><?=$this->config->item('address')?></span></p>
                                <p>Call Center: <span><a href="tel:<?=$this->config->item('email')?>"><?=$this->config->item('telephone')?></a></span></p>
                                <p<span class="fa fa-envelope"></span> <span><a href="mailto:<?=$this->config->item('email')?>"><?=$this->config->item('email')?></a></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
	<div class="ps-contact-form">
		<div class="container">
			<?=form_open($action, 'id="contact-form" class="ps-form--contact-us"')?>
				<div class="row">
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
							<div class="form-group">
								<input class="form-control" type="text" name="name" placeholder="Nama Lengkap">
							</div>
						</div>
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
							<div class="form-group">
								<input class="form-control" type="text" name="telephone" placeholder="No. Telepon">
							</div>
						</div>
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
							<div class="form-group">
								<input class="form-control" type="text" name="email" placeholder="Email">
							</div>
						</div>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
							<div class="form-group">
								<input class="form-control" type="text" name="subject" placeholder="Subjek">
							</div>
						</div>
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
							<div class="form-group">
								<div class="row">
									<div class="col-9" id="captcha"></div>
										<div class="col-2 text-right"><button type="button" class="btn btn-lg" id="reset-captcha"><i class="fa fa-refresh fa-lg" title="Reload image"></i></button></div>
									</div>
									<div class="row">
										<div class="col-12">
											<input type="text" class="form-control" name="captcha" placeholder="Tuliskan image di atas"	>
										</div>
									</div>
								</div>
						</div>
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
							<div class="form-group">
								<textarea class="form-control" rows="5" name="message" placeholder="Message"></textarea>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
						<div class="form-group submit">
							<button class="ps-btn" id="submit" type="button">Kirim</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('#submit').bind('click', function() {
		var btn = $(this);
		$.ajax({
			url: $('#contact-form').attr('action'),
			data: $('#contact-form').serialize(),
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				btn.button('loading');
			},
			complete: function() {
				btn.button('reset');
			},
			success: function(json) {
				$('.text-danger').remove();
				if (json['error']) {
					for (i in json['error']) {
						$('input[name=\''+i+'\']').after('<span class="text-danger">' + json['error'][i] + '</span>');
						$('textarea[name=\''+i+'\']').after('<span class="text-danger">' + json['error'][i] + '</span>');	
					}
				} else if (json['success']){
					window.location = "<?=site_url()?>";
				}
			}
		});
	});
	
	$(document).delegate('#reset-captcha', 'click', function(e) {
		e.preventDefault();
		var btn = $(this);
		$.ajax({
			url: "<?=site_url('captcha')?>",
			dataType: 'html',
			beforeSend: function() {
				btn.button('loading');
			},
			complete: function() {
				btn.button('reset');
			},
			success: function(html) {
				$('#captcha').html(html);
			}
		});
	});
		
	$('#reset-captcha').trigger('click');
</script>