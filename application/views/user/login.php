<div class="ps-breadcrumb">
	<div class="ps-container">
		<ul class="breadcrumb">
			<?php foreach ($template['breadcrumbs'] as $key => $breadcrumb) {?>
			<?php if (count($breadcrumb) == $key) {?>
			<li><?=$breadcrumb['text']?></li>
			<?php } else {?>
			<li><a href="<?=$breadcrumb['href']?>"><?=$breadcrumb['text']?></a></li>
			<?php }?>
			<?php }?>
		</ul>
	</div>
</div>
<div class="ps-page--my-account">
	<div class="ps-my-account-2">
		<div class="ps-container">
			<div class="ps-form--account ps-tab-root">
				<div class="ps-form__content">
					<?=form_open(site_url('user/login'), 'id="form-login"')?>
						<h3><?=lang('text_login')?></h3>
						<p><?=$text_register?></p>
						<div id="notification"></div>
						<div class="form-group">
							<input class="form-control" name="email" type="text" placeholder="Email Anda">
						</div>
						<div class="form-group form-forgot">
							<input class="form-control" name="password" type="password" placeholder="Password Anda"><a href="<?=user_url('reset')?>"><?=$text_forgotten?></a>
						</div>
						<div class="form-group">
							<div class="ps-checkbox">
								<input class="form-control" type="checkbox" id="remember-me" name="remember">
								<label for="remember-me">Ingat saya</label>
							</div>
						</div>
						<div class="form-group submit">
							<button id="button-login" class="ps-btn ps-btn--fullwidth" type="submit"><i class="fa fa-sign-in"></i> Login</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('#button-login').on('click', function() {
		var btn = $(this);
		$.ajax({
			url: $('#form-login').attr('action'),
			data: $('#form-login').serialize(),
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				btn.html('<i class="fa fa-refresh"></i> Sedang Diproses...');
				btn.attr("disabled", true);
				//btn.button('loading');
			},
			complete: function() {
				btn.html('<i class="fa fa-sign-in"></i> Login');
				btn.removeAttr("disabled");
				//btn.button('reset');
			},
			success: function(json) {
				if (json['warning']) {
					$('#notification').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+json['warning']+'</div>');
				} else {
					window.location = json['redirect'];
				}
			}
		});
	});
</script>