<?=form_open($action, 'id="payment-form"')?>
<input type="hidden" name="result_type" id="result-type" value=""></div>
<input type="hidden" name="result_data" id="result-data" value=""></div>
<?=form_close()?>
<hr>
<div>
	<button class="ps-btn pull-left" onclick="setSteps(3);"><i class="fa fa-chevron-left"></i> Kembali</button>
	<button class="ps-btn pull-right" id="button-confirm">Proses Pembayaran <i class="fa fa-chevron-right"></i></button>
</div>
<script type="text/javascript">
$('#button-confirm').click(function (event) {
	event.preventDefault();	
	var btn = $(this);
	var btnTxt = btn.html();
	$.ajax({
		url: "<?=site_url('payment/xendit')?>",
		cache: false,
		beforeSend: function() {
			btn.attr('disabled', true);
			btn.html('<span class="spinner-border spinner-border-sm"></span> Loading...');
		},
		complete: function() {
			btn.attr('disabled', false);
			btn.html(btnTxt);
		},
		success: function(data) {
			window.location.href = data;
		}
	});
});
</script>