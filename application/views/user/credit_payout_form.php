<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="payout-modal-label">Tarik Dana</h4>
		</div>
		<?=form_open('', 'id="form-request"')?>
		<div class="modal-body">
			<?php if ($error) {?>
			<div class="alert alert-danger"><b>Kesalahan:</b><br><?=$error?></div>
			<?php } else {?>
			<?php if ($bank_name && $bank_account_number && $bank_account_name) {?>
			<div class="alert alert-warning">Dana akan ditransfer ke nomor rekening berikut :<br><b><?=$bank_name?></b> No. rekening <b><?=$bank_account_number?></b> atas nama <b><?=$bank_account_name?></b><?php if ($bank_charge) { echo '<br><i><small>Note: Transfer akan dikenakan biaya '.$bank_charge.'</small></i>';}?> </div>
			<?php }?>
			<div id="notification"></div>
			<label class="control-label">Nominal</label>
			<div class="form-group">
				<input name="amount" type="input" class="form-control" value="" placeholder="Ketik nominal tanpa titik tanpa koma">
				<input name="bank_code" type="hidden" value="<?=$bank_code?>">
			</div>
			<label class="control-label">Kode Keamanan</label>
			<div class="form-group">
				<div class="row">
					<div class="col-sm-6">
						<input name="code" type="input" class="form-control" value="" placeholder="6 digit kode keamanan">
					</div>
					<div class="col-sm-6">
						<p><a class="btn btn-default" id="button-request-code"><i class="fa fa-refresh"></i> Kirim kode via SMS</a></p>
					</div>
				</div>
			</div>
			<br>
			<?php }?>
		</div>
		</form>
		<div class="modal-footer">
			<a class="btn btn-warning" id="button-verify">Konfirmasi</a>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#button-request-code').on('click', function() {
		var btn = $(this);
		$.ajax({
			url: "<?=user_url('credit/request_code')?>",
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				btn.button('loading');
			},
			complete: function() {
				btn.button('reset');
			},
			success: function(json) {
				$('.alert, .error').remove();
				$('.form-group').removeClass('has-error');
				if (json['success']) {
					$('#notification').html('<div class="alert alert-success">'+json['success']+'</div>');
				}
			}
		});
	});
	
	$('#button-verify').on('click', function() {
		var btn = $(this);
		$.ajax({
			url: "<?=user_url('credit/payout_validate')?>",
			data: $('#form-request').serialize(),
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				btn.button('loading');
			},
			complete: function() {
				btn.button('reset');
			},
			success: function(json) {
				$('.alert, .error').remove();
				if (json['error']) {
					$('#notification').html('<div class="alert alert-warning">'+json['error']+'</div>');
				} else {
					window.location = "<?=user_url('credit')?>";
				}
			}
		});
	});
</script>