<?=form_open($action, 'id="bank-transfer-form"')?>
<p><?=$instruction?></p>
<br>
<ul>
<?php foreach ($bank_accounts as $bank_account) {?>
	<li><?=$bank_account['title']?><br><small class="text-muted"><?=$bank_account['name']?> / <?=$bank_account['account_name']?> / <?=$bank_account['account_number']?></small></li>
<?php }?>
</ul>
<hr>
<?php if ($payment_confirmation) {?>
<p>Jika sudah melakukan pembayaran, mohon konfirmasikan pembayaran anda di halaman <b><a style="color:green" href="<?=site_url('payment_confirmation?order_id='.$order_id)?>" target="_blank">Konfirmasi Pembayaran</a></b>.</p>
<?php }?>
<input type="hidden" name="confirm" value="1"> 
<?=form_close()?>
<hr>
<div>
	<button class="ps-btn pull-left" onclick="setSteps(3);"><i class="fa fa-chevron-left"></i> <?=lang('button_back')?></button>
	<?php if ($payment_confirmation) {?>
	<button class="ps-btn pull-right" id="button-confirm">Konfirmasi Pembayaran <i class="fa fa-chevron-right"></i></button>
	<?php } else {?>
	<button class="ps-btn pull-right" id="button-confirm"><?=lang('button_process')?> <i class="fa fa-chevron-right"></i></button>
	<?php }?>
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
				window.location = '<?=$continue?>';
			} else {
				alert("<?=lang('error_process')?>");
			}
		}
	});
});
</script>