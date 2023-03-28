<?=form_open($action, 'id="ovo-form"')?>
<div class="row">
	<div class="col-md-6 col-6">
		<img src="<?=$qrcode?>" class="img-thumbnail img-responsive">
	</div>
	<div class="col-md-6 col-6">
		<p><?=$instruction?></p>
		<hr>
		<?php if ($payment_confirmation) {?>
		<p>Jika sudah melakukan pembayaran, mohon konfirmasikan pembayaran anda di halaman <b><a style="color:green" href="<?=site_url('payment_confirmation?order_id='.$order_id)?>" target="_blank">Konfirmasi Pembayaran</a></b>.</p>
		<?php }?>
	</div>
</div>
<input type="hidden" name="confirm" value="1"> 
<?=form_close()?>
<hr>
<div>
	<button class="btn primary pull-left" onclick="setSteps(3);"><i class="fa fa-chevron-left"></i> <?=lang('button_back')?></button>
	<?php if ($payment_confirmation) {?>
	<button class="btn primary pull-right" id="button-confirm">Konfirmasi Pembayaran <i class="fa fa-chevron-right"></i></button>
	<?php } else {?>
	<button class="btn primary pull-right" id="button-confirm"><?=lang('button_process')?> <i class="fa fa-chevron-right"></i></button>
	<?php }?>
</div>
<script type="text/javascript">
$('#button-confirm').bind('click', function() {
	$.ajax({ 
		url: $('#ovo-form').attr('action'),
		type: 'POST',
		data: $('#ovo-form').serialize(),
		dataType: 'json',
		beforeSend: function() {
			$('.btn').button('loading');
		},
		complete: function() {
			$('.btn').button('reset');
		},	
		success: function(json) {
			if (json['success']) {
				window.location = '<?=$continue?>';
			} else {
				alert("<?=lang('error_process')?>");
			}
		}
	});
});
</script>