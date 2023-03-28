<style>
	#scrollable {
		background-color: #fff;
		height: 300px;
		width: 100%;
		overflow-y: auto;
		border: 1px solid #ddd;
		margin-top: 15px;
	}
	#scrollable table tr:first-child td {
		border-top: none;
	}
</style>
<h4>Metode Pembayaran</h4><span class="text-muted"><?php echo $text_payment_method; ?></span>
<?php if ($error_warning) { ?>
<div class="warning alert alert-warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
<?=form_open($action, 'class="form-horizontal" id="form-pm"')?>
	<div id="scrollable">
		<table class="table table-hover">
		<?php foreach ($payment_methods as $payment_method) { ?>
		<tr>
			<td style="width:5%;vertical-align:middle;">
				<?php if ($payment_method['code'] == $code || !$code) { ?>
				<?php $code = $payment_method['code']; ?>
				<input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" checked="checked" />
				<?php } else { ?>
				<input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" />
				<?php } ?>
			</td>
			<td><img src="<?=base_url('assets/images/payments/'.$payment_method['code'].'.png')?>" class="img-responsive" style="max-width:50px; max-height:50px;" /></td>
			<td>
				<?php echo $payment_method['title']; ?>
				<?php if (isset($payment_method['description'])) {?>
				<br><small class="text-muted"><?php echo $payment_method['description']; ?></small>
				<?php }?>

				<?php $code = explode(".", $payment_method['code']);
				if (isset($payment_method['instalment'])) {
					foreach(($payment_method['instalment']) as $data){
						if($data['interval'] == "DAY") $interval = "Hari";
						elseif($data['interval'] == "WEEK") $interval = "Minggu";
						elseif($data['interval'] == "MONTH") $interval = "Bulan";
						else $interval = "";?>
					<br><small class="text-muted"><?= $data['total_recurrence'] ?> <?= $interval ?> x Rp <?= number_format($data['installment_amount']) ?>  Bunga <?= $data['interest_rate']?> % </small>
				<?php }}?>
			</td>
		</tr>
		<?php } ?>
		</table>
	</div>
	<table class="table table-hover mt-3">
		<tr style="background-color:#f5f5f5">
			<td style="width:5%;vertical-align:middle;">
				<input type="checkbox" name="agree" value="1" id="" />
			</td>
			<td colspan="2">Dengan ini saya menyetujui syarat dan ketentuan yang berlaku</td>
		</tr>
	</table>
</form>
<hr>
<div>
	<button class="ps-btn pull-left" onclick="setSteps(2);"><i class="fa fa-chevron-left"></i> Kembali</button>
	<button class="ps-btn pull-right" id="button-pm">Lanjut <i class="fa fa-chevron-right"></i></button>
</div>
<?php } else { ?>
<hr>
<div>
	<button class="ps-btn pull-left" onclick="setSteps(2);"><i class="fa fa-chevron-left"></i> Kembali</button>
</div>
<?php } ?>