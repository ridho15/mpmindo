<?=form_open(user_url('login'), 'id="form-login-nav" class="login-form"')?>
<h4>Masuk ke akun kamu</h4>
<div class="form-group">
	<input type="email" name="email" class="form-control" placeholder="Email">
</div>
<div class="form-group">
	<input type="password" name="password" class="form-control" placeholder="Password">
</div>
<div class="row">
					<div class="col-md-6">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="remember" value="1"> <?=lang('entry_remember')?>
							</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="checkbox">
							<label><a href=""><?=$text_forgotten?></a></label>
						</div>
					</div>
				</div>
<button class="btn btn-primary btn-block" id="button-login">Login</button>
<div class="hr-sect">atau</div>
<a class="btn btn-facebook btn-block" href="<?=user_url('facebook_login')?>"><i class="fa fa-facebook fa-lg"></i> Login dengan Facebook</a>
<a class="btn btn-google btn-block" href="<?=user_url('google_login')?>"><i class="fa fa-google fa-lg"></i> Login dengan Google</a>
</form>
<script src="<?=base_url('assets/js/plugins/bootstrap-notify.min.js')?>"></script>
<script type="text/javascript">
	$(document).delegate('#form-login-nav #button-login', 'click', function(e) {
		e.preventDefault();
		var btn = $(this);
		$.ajax({
			url: btn.closest('form').attr('action'),
			data: btn.closest('form').serialize(),
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				btn.button('loading');
			},
			complete: function() {
				btn.button('reset');
			},
			success: function(json) {
				if (json['warning']) {
					$.notify({
						title: "Warning : ",
						message: json['warning'],
						icon: 'fa fa-ban' 
					},{
						type: "danger"
					});
				} else {
					$.notify({
						title: "Success : ",
						message: json['success'],
						icon: 'fa fa-ban' 
					},{
						type: "success"
					});
					
					window.location = json['redirect'];
				}
			}
		});
	});
</script>