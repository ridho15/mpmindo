<section class="shop checkout section">
	<div class="container">
		<div class="row"> 
			<div class="col-lg-6 offset-lg-3 col-12">		
				<div class="checkout-form">
					<h2><?=lang('heading_title')?></h2>
					<p><?=lang('text_change_instruction')?></p>
					<?=form_open($action, 'id="form-reset" class="form"')?>
					<div class="row">
						<div class="col-12">
						<div class="form-group">
							<input type="password" name="password" placeholder="<?=lang('entry_new_password')?>">
						</div>
						</div>
						
						<div class="col-12">
						<div class="form-group">
							<input type="password" name="confirm" placeholder="<?=lang('entry_confirm')?>">
						</div>
						</div>
						
						<div class="col-12">
						<div class="form-group">
							<button class="btn primary" id="button-reset"><?=lang('button_reset')?></button>
						</div>
						</div>	
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
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
				} else {
					window.location = json['redirect'];
				}
			}
		});
	});
</script>