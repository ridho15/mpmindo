<h4>Metode Pembayaran</h4><span class="text-muted"><?php echo $text_payment_method; ?></span><hr>
<?php if ($error_warning) { ?>
<div class="warning alert alert-warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
	<?=form_open($action, 'class="form-horizontal" id="form-pm"')?>
		<?php foreach ($payment_methods as $payment_method) { ?>
		<div class="radio">
			<label>
				<?php if ($payment_method['code'] == $code || !$code) { ?>
				<?php $code = $payment_method['code']; ?>
				<input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" checked="checked" />
				<?php } else { ?>
				<input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" />
				<?php } ?>
				<?php echo $payment_method['title']; ?>
			</label>
		</div>
		<?php } ?>
	<div class="checkbox">
		<label>
			<input type="checkbox" name="agree" value="1" id="" /> Dengan ini saya menyetujui syarat dan ketentuan yang berlaku
		</label>
	</div>
	</form>
	<hr>
	<div>
		<button class="btn btn-primary pull-left" onclick="setSteps(2);"><i class="fa fa-chevron-left"></i> Kembali</button>
		<button class="btn btn-primary pull-right" id="button-pm">Lanjut <i class="fa fa-chevron-right"></i></button>
	</div>
<?php } else { ?>
<hr>
<div>
	<button class="btn btn-primary pull-left" onclick="setSteps(2);"><i class="fa fa-chevron-left"></i> Kembali</button>
</div>
<?php } ?>