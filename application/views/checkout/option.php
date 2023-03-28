<div class="ps-page--simple">
	<div class="ps-breadcrumb">
		<div class="container">
			<ul class="breadcrumb">
				<li><a href="<?=site_url()?>">Home</a></li>
				<li>Checkout</li>
			</ul>
		</div>
	</div>
	<div class="ps-section--shopping ps-shopping-cart" style="padding-top:20px;">
		<div class="container">
			<div class="ps-section__content">
				<div class="row">
					<div class="col-lg-12 col-12">
						<div class="form-main">
							<div class="row">
								<div class="col-lg-5 col-sm-12">
									<div class="title">
										<h4>Pelanggan Baru</h4>
										<p>Dengan membuat akun, belanja Anda akan lebih mudah. Anda dapat melihat status pesanan, melihat riwayat pesanan, melacak pesanan, dan mengajukan klaim garansi secara online.</p>
										<br>
										<?=form_open(site_url('checkout/option'))?>
										<button type="button" onclick="window.location = '<?=user_url('register?redirect=checkout')?>';" class="ps-btn ps-btn--fullwidth mb-2">Mendaftar Akun</button>
										<button type="submit" name="type" value="guest" class="ps-btn ps-btn--outline ps-btn--fullwidth">Belanja Sebagai Tamu</button>
										</form>
									</div>
								</div>
								<div class="col-lg-2 col-sm-12">
								</div>
								<div class="col-lg-5 col-sm-12">
									<div class="title">
										<h4>Pelanggan Terdaftar</h4>
										<p>Silakan masuk untuk langsung memproses pesanan</p>
									</div>
									<?=form_open(user_url('login'), 'id="form-login" class="form"')?>
									<div class="row">
										<div class="col-12">
											<div class="form-group">
												<input type="text" name="email" placeholder="Email Anda" class="form-control" required="required" autofocus>
											</div>
										</div>
										<div class="col-12">
											<div class="form-group">
												<input type="password" name="password" placeholder="Password Anda" class="form-control" required="required">
											</div>
										</div>
										<div class="col-12">
											<div class="form-group login-btn">
												<button id="button-login" class="ps-btn" type="submit">Login</button>
												<button type="button" onclick="window.location = '<?=user_url('reset')?>';" class="ps-btn ps-btn--outline"><?=$text_forgotten?></button>
											</div>
											<input type="hidden" name="remember" value="1">
										</div>
									</div>
									</form>
								</div>
							</div><br><br><br><br><br><br>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).delegate('#button-login', 'click', function(e) {
		e.preventDefault();
		var btn = $(this);
		var btnTxt = btn.html();
		$.ajax({
			url: $('#form-login').attr('action'),
			data: $('#form-login').serialize(),
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				btn.attr('disabled', true);
				btn.html('<span class="spinner-border spinner-border-sm"></span> Loading...');
			},
			complete: function() {
				btn.attr('disabled', false);
				btn.html(btnTxt);
			},
			success: function(json) {
				if (json['warning']) {
					alert(json['warning']);
				} else {
					window.location = json['redirect'];
				}
			}
		});
	});
</script>