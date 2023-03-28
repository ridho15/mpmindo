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
					<?=form_open(site_url('user/reset'), 'id="form-reset" class="form"')?>
					<h3><?=lang('heading_title')?></h3>
					<p><?=lang('text_instruction')?></p>
					<div class="row">
						<div class="col-12">
							<div class="form-group">
								<input class="form-control" type="text" name="email" placeholder="<?=lang('entry_email')?>">
							</div>
						</div>
						<div class="col-12">
							<div class="form-group" style="background-color:#efefef; padding: 10px;">
								<div class="row">
									<div class="col-9" id="captcha"></div>
									<div class="col-2 text-right"><a class="btn secondary" id="reset-captcha"><i class="fa fa-refresh fa-3x" title="Reload"></i></a></div>
								</div>
								<div class="row">
									<div class="col-12">
										<input class="form-control" style="background-color:#fff" type="text" name="captcha" placeholder="<?=lang('text_captcha')?>">
									</div>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<div class="form-group login-btn">
									<button class="ps-btn" id="button-reset"><?=lang('button_reset')?></button>
								</div>
								<a href="<?=site_url('user/login')?>"><i class="fa fa-angle-left fa-fw"></i> <?=$text_login?></a>
							</div>
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).delegate('#button-reset', 'click', function(e) {
		e.preventDefault();
		var btn = $(this);
		$.ajax({
			url: $('#form-reset').attr('action'),
			data: $('#form-reset').serialize(),
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
					alert(json['warning']);
				} else if (json['redirect']) {
					window.location = json['redirect'];
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