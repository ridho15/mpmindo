<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="payout-modal-label">Tambah Dana</h4>
		</div>
		<?=form_open($action, 'id="form-topup"')?>
		<div class="modal-body">
			<div id="notification"></div>
			<label class="control-label">Pilih Rekening</label>
			<div class="form-group">
				<?php foreach ($payment_methods as $payment_method) { ?>
				<div class="radio">
					<label>
						<input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" /> <?php echo $payment_method['title']; ?>
					</label>
				</div>
				<?php } ?>
			</div>
			<label class="control-label">Nominal</label>
			<div class="form-group">
				<input type="input" class="form-control" value="10000" id="amount" placeholder="Ketik nominal tanpa titik tanpa koma">
				<input type="hidden" name="amount" value="">
			</div>
			<div class="alert alert-info amount text-center"><h3></h3>Untuk memudahkan proses verifikasi, mohon mentransfer dengan nominal hingga 3 digit terakhir</div>
		</div>
		</form>
		<div class="modal-footer">
			<a class="btn btn-warning" id="button-verify">Konfirmasi</a>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#button-verify').on('click', function() {
		var btn = $(this);
		$.ajax({
			url: $('#form-topup').attr('action'),
			data: $('#form-topup').serialize(),
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				btn.button('loading');
			},
			complete: function() {
				btn.button('reset');
			},
			success: function(json) {
				$('.alert-warning, .error').remove();
				if (json['error']) {
					$('#notification').html('<div class="alert alert-warning">'+json['error']+'</div>');
				} else {
					window.location = "<?=user_url('credit')?>";
				}
			}
		});
	});
	
	$('#amount').on('keyup', function() {
		var unique = '<?=$unique?>';
		var value = $(this).val();
		if ($.isNumeric(value)) {
			var amount = parseFloat($(this).val()) + parseFloat(unique);
		} else {
			var amount = 0;
		}
		
		$('input[name=\'amount\']').val(amount);
		$('.amount').find('h3').html(amount.format(0, 3, '.', ','));
	});
	
	Number.prototype.format = function(n, x, s, c) {
		var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')', num = this.toFixed(Math.max(0, ~~n));
		return 'Rp '+(c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
	
	$('#amount').trigger('keyup');
</script>