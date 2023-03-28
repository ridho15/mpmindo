<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="<?= base_url('assets/css/main.css') ?>" rel="stylesheet">
	<link href="<?= base_url('assets/js/plugins/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css">
	<link href="<?= $_favicon ?>" rel="shortcut icon">
	<title><?= $this->config->item('site_name') ?></title>
	<!--if lt IE 9
script(src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')
script(src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js')
-->
</head>

<body>
	<section class="login-content">
		<div class="login-box">
			<?= form_open(admin_url('login'), 'id="form-login" class="login-form"') ?>
			<div class="logo text-center" style="margin:0 0 25px">
				<!-- <img src="<?= $_logo ?>"> -->
				<img src="<?= base_url('assets/murdock/images/demo/logo-wahl2.png') ?>" alt="logo" width="60%">
			</div>
			<p>Admin Panel</p>
			<div class="form-group">
				<input type="text" name="email" placeholder="Email atau username" autofocus class="form-control">
			</div>
			<div class="form-group">
				<input type="password" name="password" placeholder="Password" class="form-control">
			</div>
			<div class="form-group">
				<div class="utility">
					<div class="animated-checkbox">
						<label class="semibold-text">
							<input type="checkbox" name="remember" value="1"><span class="label-text">Ingatkan Saya</span>
						</label>
					</div>
				</div>
			</div>
			<div class="form-group btn-container">
				<button class="btn btn-primary btn-block" id="button-login">Masuk <i class="fa fa-sign-in fa-lg"></i></button>
			</div>
			</form>
		</div>
	</section>
	<script src="<?= base_url('assets/js/jquery-2.1.4.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/essential-plugins.js') ?>"></script>
	<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/plugins/pace.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/main.js') ?>"></script>
	<script src="<?= base_url('assets/js/plugins/bootstrap-notify.min.js') ?>"></script>
	<script type="text/javascript">
		$(document).delegate('#button-login', 'click', function(e) {
			e.preventDefault();
			var btn = $(this);
			$.ajax({
				url: $('#form-login').attr('action'),
				data: $('#form-login').serialize(),
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
							title: "Kesalahan : ",
							message: json['warning'],
							icon: 'fa fa-ban'
						}, {
							type: "danger"
						});
					} else {
						$.notify({
							title: "Berhasil : ",
							message: json['success'],
							icon: 'fa fa-ban'
						}, {
							type: "success"
						});

						window.location = json['redirect'];
					}
				}
			});
		});
	</script>
</body>

</html>