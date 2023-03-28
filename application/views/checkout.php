<style>
.f1 h3 { margin-top: 0; margin-bottom: 5px; text-transform: uppercase; }
.f1-steps { overflow: hidden; position: relative; margin-top: 20px; text-align: center;}
.f1-progress { position: absolute; top: 24px; left: 0; width: 100%; height: 5px; background: #ddd; }
.f1-progress-line { position: absolute; top: 0; left: 0; right:0;height: 5px; background: #fcb800;}
.f1-step { position: relative; float: left; width: 25%; padding: 0 5px; }
.f1-step-icon {
	display: inline-block; width: 40px; height: 40px; margin-top: 4px; background: #ddd;
	font-size: 16px; color: #fff; line-height: 40px;
	-moz-border-radius: 50%; -webkit-border-radius: 50%; border-radius: 50%;
}
.f1-step.activated .f1-step-icon {
	background: #fff; border: 1px solid #fcb800; color: #fcb800; line-height: 38px;
}
.f1-step.active .f1-step-icon {
	background: #fcb800;
}
.f1-step p { color: #ccc; }
.f1-step.activated p { color: #fcb800; }
.f1-step.active p { color: #fcb800; }
.f1 fieldset { display: none; text-align: left; }
.f1-buttons { text-align: right; }
.f1 .input-error { border-color: #fcb800; }

@media (max-width: 415px) {
	.f1 { padding-bottom: 20px; }
	.f1-buttons button { margin-bottom: 5px; }
}

.form-steps {
	margin-top: 30px;
}
</style>

<div class="ps-page--simple">
	<div class="ps-breadcrumb">
		<div class="ps-container">
			<ul class="breadcrumb">
				<li><a href="<?=site_url()?>">Home</a></li>
				<li>Checkout</li>
			</ul>
		</div>
	</div>
	<div class="ps-section--shopping ps-shopping-cart" style="padding-top:20px;">
		<div class="ps-container">
			<div class="ps-section__content">
				<div class="row">
					<div class="col-md-8">
						<div class="f1-steps">
							<div class="f1-progress">
								<div class="f1-progress-line" data-number-of-steps="4" style="width: 25%;"></div>
							</div>
							<div class="f1-step active">
								<div class="f1-step-icon"><i class="fa fa-check"></i></div>
								<p>Alamat</p>
							</div>
							<div class="f1-step">
								<div class="f1-step-icon"><i class="fa fa-check"></i></div>
								<p>Pengiriman</p>
							</div>
							<div class="f1-step">
								<div class="f1-step-icon"><i class="fa fa-check"></i></div>
								<p>Pembayaran</p>
							</div>
							<div class="f1-step">
								<div class="f1-step-icon"><i class="fa fa-check"></i></div>
								<p>Selesai</p>
							</div>
						</div>
						<div style="margin-bottom:20px;">
							<?php if ($success) {?>
							<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?=$success?></div>
							<?php }?>
							<?php if ($error) {?>
							<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?=$error?></div>
							<?php }?>
							<div class="status alert alert-success" style="display: none"></div>
							<div id="step-1" class="form-steps">
								<div id="shipping-address"></div>
							</div>
							<div id="step-2" class="form-steps" style="display:none;">
								<div id="shipping-method"></div>
							</div>
							<div id="step-3" class="form-steps" style="display:none;">
								<div id="payment-method"></div>
							</div>
							<div id="step-4" class="form-steps" style="display:none;">
								<div id="checkout-confirm"></div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div id="overview-content"></div>
					</div>
				</div><br><br><br><br>
			</div>
		</div>
	</div>
</div>
<script src="https://unpkg.com/imask"></script>
<script type="text/javascript">
	$(document).ready(function() {
		setSteps(1);
	});
	
	function setSteps(num) {
		$('html, body').animate({ scrollTop: 0 }, 'slow');
		if (num == 1) {
			$.ajax({
				url: "<?=site_url('checkout/shipping_address')?>",
				dataType: 'html',
				beforeSend: function() {
					$('#shipping-address').html('<div class="text-center loading-state"><h4 class="text-muted"><i class="fa fa-refresh fa-spin"></i> Loading...</h4></div>');
				},
				complete: function() {
					$('#shipping-address .loading-state').remove();
				},
				success: function (html) {
					$('#shipping-address').html(html);
				}
			});
		} else if (num == 2) {
			$.ajax({
				url: "<?=site_url('checkout/shipping_method')?>",
				dataType: 'html',
				beforeSend: function() {
					$('#shipping-method').html('<div class="text-center loading-state"><h4 class="text-muted"><i class="fa fa-refresh fa-spin"></i> Loading...</h4></div>');
				},
				complete: function() {
					$('#shipping-method .loading-state').remove();
				},
				success: function (html) {
					$('#shipping-method').html(html);
				}
			});
		} else if (num == 3) {
			$.ajax({
				url: "<?=site_url('checkout/payment_method')?>",
				dataType: 'html',
				beforeSend: function() {
					$('#payment-method').html('<div class="text-center loading-state"><h4 class="text-muted"><i class="fa fa-refresh fa-spin"></i> Loading...</h4></div>');
				},
				complete: function() {
					$('#payment-method .loading-state').remove();
				},
				success: function (html) {
					$('#payment-method').html(html);
				}
			});
		} else if (num == 4) {
			$.ajax({
				url: "<?=site_url('checkout/confirm')?>",
				dataType: 'html',
				beforeSend: function() {
					$('#checkout-confirm').html('<div class="text-center loading-state"><h4 class="text-muted"><i class="fa fa-refresh fa-spin"></i> Loading...</h4></div>');
				},
				complete: function() {
					$('#checkout-confirm .loading-state').remove();
				},
				success: function (html) {
					$('#checkout-confirm').html(html);
				}
			});
		}
		
		var steps = $('.f1-step');
		var formSteps = $('.form-steps');
		var steps_progress = $('.f1-progress-line');
		steps.removeClass('active');
		formSteps.hide();
		
		var stepElement = [];
		
		steps.each(function(index){
			i = index+1;
			stepElement[i] = $(this);
		});
		
		$('#step-' + num).show();
		
		for (i=1; i<=num; i++) {
			if (stepElement[i]) {
				stepElement[i].addClass('active');
			}
		}
		 
		steps_progress.css('width', num*25 + '%');
		
		$.ajax({
			url: "<?=site_url('checkout/overview')?>",
			dataType: 'html',
			beforeSend: function() {
				$('#overview-content').html('<div class="text-center loading-state"><h4 class="text-muted"><i class="fa fa-refresh fa-spin"></i> Loading...</h4></div>');
			},
			complete: function() {
				$('#overview-content .loading-state').remove();
			},
			success: function(html) {
				$('#overview-content').html(html);
			}
		}); 
	}
	
	$('#shipping-address').on('click', '#button-sa', function() {
		var btn = $(this);
		var btnTxt = btn.html();
		$.ajax({
			url: $('#form-sa').attr('action'),
			type: 'post',
			data: $('#form-sa').serialize(),
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
				$('.form-group').removeClass('has-error');
				$('.text-danger').remove();
				$('.alert-danger').remove();
				
				if (json['redirect']) {
					location = json['redirect'];
				} else if (json['error']) {
					if (json['error']['warning']) {
						$('#shipping-address').prepend('<div class="warning alert alert-warning" style="display:none; margin:15px;">' + json['error']['warning'] + '</div>');
						$('.warning').fadeIn('slow');
					}
					for (i in json['error']) {
						$('#shipping-address input[name='+i+']').closest('.form-group').addClass('has-error');
						$('#shipping-address input[name='+i+']').after('<small class="text-danger">'+json['error'][i]+'</small>');
						$('#shipping-address textarea[name='+i+']').closest('.form-group').addClass('has-error');
						$('#shipping-address textarea[name='+i+']').after('<small class="text-danger">'+json['error'][i]+'</small>');
					}
				} else {
					setSteps(2);			
				}
			}
		});	
	});
	
	$('#shipping-method').on('click', '#button-sm', function() {
		var btn = $(this);
		var btnTxt = btn.html();
		$.ajax({
			url: "<?=site_url('checkout/shipping_method')?>",
			type: 'post',
			data: $('#shipping-method #form-sm').serialize(),
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
				$('.warning, .error').remove();
				
				if (json['redirect']) {
					location = json['redirect'];
				} else if (json['error']) {
					if (json['error']['warning']) {
						$('#shipping-method .checkout-content').prepend('<div class="warning alert alert-warning" style="display:none; margin:15px;">' + json['error']['warning'] + '</div>');
						$('.warning').fadeIn('slow');
					}			
				} else {
					setSteps(3);				
				}
			}
		});	
	});
	
	$('#payment-method').on('click', '#button-pm', function() {
		var btn = $(this);
		var btnTxt = btn.html();
		$.ajax({
			url: "<?=site_url('checkout/payment_method')?>", 
			type: 'post',
			data: $('#payment-method input[type=\'radio\']:checked, #payment-method input[type=\'checkbox\']:checked, #payment-method input[type=\'hidden\'], #payment-method textarea'),
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
				$('.warning, .error').remove();
				
				if (json['redirect']) {
					location = json['redirect'];
				} else if (json['error']) {
					if (json['error']['warning']) {
						$('#payment-method').prepend('<div class="warning alert alert-danger" style="display:none;">' + json['error']['warning'] + '</div>');
						$('html, body').animate({ scrollTop: 0 }, 'slow');
						$('.warning').fadeIn('slow');
					}			
				} else {
					setSteps(4);
				}
			}
		});	
	});
</script> 