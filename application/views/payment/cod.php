<hr><a id="button-confirm" class="btn btn-primary btn-flat">Konfirmasi</a>
<script type="text/javascript">
$('#button-confirm').bind('click', function() {
	$.ajax({ 
		url: "<?=secure_url('payment/cod/confirm')?>",
		type: 'GET',
		beforeSend: function() {
			$('#button-confirm').attr('disabled', true);
			$('#button-confirm').append('<span class="wait"> <i class="fa fa-refresh fa-spin"></i></span>');
		},	
		complete: function() {
			$('#button-confirm').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			location = '<?php echo $continue; ?>';
		}
	});
});
</script> 
