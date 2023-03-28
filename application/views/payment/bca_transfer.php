<?=form_open(site_url('payment_bca_transfer/confirm'), 'id="bank-transfer-form"')?>
<p><?=$instruction?></p>
<input type="hidden" name="confirm" value="1"> 
<?=form_close()?>
<hr>
<div>
<button class="btn btn-primary pull-left" onclick="setSteps(3);"><i class="fa fa-chevron-left"></i> Kembali</button>
<button class="btn btn-danger pull-right" id="button-confirm">Proses Pembayaran <i class="fa fa-chevron-right"></i></button>
</div>
<script type="text/javascript">
$('#button-confirm').bind('click', function() {
	$.ajax({ 
		url: $('#bank-transfer-form').attr('action'),
		type: 'POST',
		data: $('#bank-transfer-form').serialize(),
		dataType: 'json',
		beforeSend: function() {
			$('.btn').button('loading');
		},
		complete: function() {
			$('.btn').button('reset');
		},	
		success: function(json) {
			if (json['success']) {
				location = '<?=$continue?>';
			} else {
				alert('Error');
			}
		}
	});
});
</script>