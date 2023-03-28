<?=form_open($action, 'id="payment-form"')?>
<input type="hidden" name="result_type" id="result-type" value=""></div>
<input type="hidden" name="result_data" id="result-data" value=""></div>
<?=form_close()?>
<hr>
<div>
	<button class="ps-btn pull-left" onclick="setSteps(3);"><i class="fa fa-chevron-left"></i> Kembali</button>
	<button class="ps-btn pull-right" id="button-confirm">Proses Pembayaran <i class="fa fa-chevron-right"></i></button>
</div>
<script type="text/javascript" src="<?=$snap_js?>" data-client-key="<?=$client_key?>"></script>
<script type="text/javascript">
$('#button-confirm').click(function (event) {
	event.preventDefault();
	
	$(this).attr('disabled', 'disabled');
	
	$.ajax({
		url: "<?=site_url('payment/token')?>",
		cache: false,
		success: function(data) {
			console.log('token = '+data);
			var resultType = document.getElementById('result-type');
			var resultData = document.getElementById('result-data');
			
			function changeResult(type, data) {
				$('#result-type').val(type);
				$('#result-data').val(JSON.stringify(data));
			}
			
			snap.pay(data, {
				onSuccess: function(result) {
					changeResult('success', result);
					console.log(result.status_message);
					console.log(result);
					$('#payment-form').submit();
				},
				onPending: function(result) {
					changeResult('pending', result);
					console.log(result.status_message);
					$('#payment-form').submit();
				},
				onError: function(result) {
					changeResult('error', result);
					console.log(result.status_message);
					$('#payment-form').submit();
				}
			});
		}
	});
});
</script>