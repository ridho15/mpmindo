<h4>Metode Pengiriman</h4><span class="text-muted"><?php echo $text_shipping_method; ?></span><hr>
<?=form_open($action, 'id="form-sm" class="form-horizontal"')?>
<?php foreach ($shipping_stores as $store_id => $store) {?>
		<div class="panel panel-default">
			<div class="panel-heading"><label><?=$store['store']?></label><span class="pull-right shipping-cost"><b></b></span></div>
			<ul class="list-group">
			<?php foreach ($store['products'] as $product) {?>
			<li class="list-group-item">
				<div class="row">
					<div class="col-md-2">
						<img src="<?=$product['thumb']?>">
					</div>
					<div class="col-md-8">
						<a href="<?=$product['href']?>"><?=$product['name']?></a>
					</div>
				</div>
			</li>
			<?php }?>
			</ul>
			<div class="panel-body">
				<?php if ($store['shipping_methods']) { ?>
					<div class="form-group">
						<label class="control-label col-md-4">Pilih metode pengiriman:</label>
						<div class="col-md-8">
						<?php foreach ($store['shipping_methods'] as $shipping_method) { ?>
						<?php if ( ! $shipping_method['error']) { ?>
						<select name="shipping_method[<?=$store_id?>]" class="form-control shipping-method">
						<?php foreach ($shipping_method['quote'] as $quote) { ?>
						<?php if ($quote['code'] == $store['shipping_code']) {?>
						<option value="<?=$quote['code']?>" rel="<?=$quote['text']?>" selected="selected"><?=$quote['title']?> - <?=$quote['text']?></option>
						<?php } else {?>
						<option value="<?=$quote['code']?>" rel="<?=$quote['text']?>"><?=$quote['title']?> - <?=$quote['text']?></option>
						<?php }?>
						<?php } ?>
						</select>
						<?php } else { ?>
						<div class="alert alert-danger"><?=$shipping_method['error']?></div>
						<?php } ?>
						<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Catatan:</label>
						<div class="col-md-8">
							<textarea class="form-control" name="comment[<?=$store_id?>]" placeholder="Contoh: warna, ukuran, dsb" rows="5"><?php if (isset($comments[$store_id])) { echo $comments[$store_id];} ?></textarea>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php }?>
</form>
<hr>
<div>
	<button class="btn btn-primary pull-left" onclick="setSteps(1);"><i class="fa fa-chevron-left"></i> Kembali</button>
	<button class="btn btn-primary pull-right" id="button-sm">Lanjut <i class="fa fa-chevron-right"></i></button>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('.store-check').each(function(i) {
			$(this).on('change', function() {
				if ($(this).prop('checked') === false) {
					$('.store-checks').prop('checked', false);
					$(this).closest('.panel').find('.button-checkout').fadeIn('slow');
				} else {
					$(this).closest('.panel').find('.button-checkout').fadeOut('slow');
				}
			});
		});
		
		$('.shipping-method').on('change', function() {
			$(this).closest('.panel').find('.shipping-cost b').html($(this).find('option:selected').attr('rel'));
			var btn = $('#button-sm');
			$.ajax({
				url: "<?=site_url('checkout/shipping_method')?>",
				type: 'post',
				data: $('#form-sm').serialize(),
				dataType: 'json',
				beforeSend: function() {
					btn.button('loading');
				},
				complete: function() {
					btn.button('reset');
				},			
				success: function(json) {
					$('.warning, .error').remove();
					
					if (json['redirect']) {
						location = json['redirect'];
					} else if (json['error']) {
						if (json['error']['warning']) {
							$('#shipping-method .checkout-content').prepend('<div class="warning alert alert-warning" style="display:none; margin:15px;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
							$('.warning').fadeIn('slow');
						}			
					} else {
						$('html, body').animate({ scrollTop: 0 }, 'slow');
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
				}
			});
		});
		
		$('.shipping-method').trigger('change');
	});
</script>