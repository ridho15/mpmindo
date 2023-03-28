<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">		
<title><?=$this->config->item('site_name')?></title>
<style>
	@media  only screen and (max-width: 500px) {
		.button {
			width: 100% !important;
		}
	}
</style>
</head>
<body style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; color: #000000;">
	<table width="100%" style="margin-bottom:20px;">
	 <tbody>
		<tr>
		 <td style="border-bottom: 1px solid #ddd; text-align:center; padding:5px;">
		 <!-- <a><img src="<?=$logo?>" alt="<?=$this->config->item('site_name')?>" style="border: none;" /></a> -->
		<a><img src="<?=base_url('assets/images/wahl.png')?>" alt="<?=$this->config->item('site_name')?>" style="border: none;" width="10%"/></a>
		 </td>
		</tr>
	 </tbody>
	</table>
	<p style="margin-top: 0px; margin-bottom: 20px;">Hai <strong><?=$user_name?></strong>,</p>
	<p style="margin-top: 0px; margin-bottom: 20px;">Status pesanan anda dengan nomor invoice <b><?=$invoice_no?></b> telah diperbarui menjadi :</p>
	<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
		<thead>
			<tr>
				<td style="font-size: 14px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="2">Status Pesanan</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="font-size: 14px;	border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
					<b>Status:</b> <?=strtoupper($status)?><br />
					<?php if ($waybill) {?>
					<b>No. Resi:</b> <?=$waybill?><br />
					<?php }?>
					<b>Catatan:</b> <?=$comment?>
				</td>
				<?php if ($waybill) {?>
				<td style="font-size: 14px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
					<a href="<?=$tracking?>" class="button button-blue" target="_blank" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); color: #FFF; display: inline-block; text-decoration: none; -webkit-text-size-adjust: none; background-color: #2196F3; border-top: 10px solid #2196F3; border-right: 18px solid #2196F3; border-bottom: 10px solid #2196F3; border-left: 18px solid #2196F3;">Lacak Status Pengiriman</a>
				</td>
				<?php }?>
			</tr>
		</tbody>
	</table>
	<p style="margin-top: 0px; margin-bottom: 20px;">Berikut ini adalah rincian pesanan Anda:</p>
	<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
		<thead>
			<tr>
				<td style="font-size: 14px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="2">Detail Pesanan</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="font-size: 14px;	border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
					<b>Invoice:</b> <?=$invoice_no?><br />
					<b>Tanggal:</b> <?=$date_added?><br />
					<b>Metode Pembayaran:</b> <?=$payment_method?>
				</td>
				<td style="font-size: 14px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
					<b>Email:</b> <?=$user_email?><br />
					<b>Telepon:</b> <?=$user_telephone?>
				</td>
			</tr>
		</tbody>
	</table>
	<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
		<thead>
			<tr>
				<td style="font-size: 14px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Alamat</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="font-size: 14px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?=$address?></td>
			</tr>
		</tbody>
	</table>
	<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
		<thead>
			<tr>
				<td style="font-size: 14px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Produk</td>
				<td style="font-size: 14px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;">Qty</td>
				<td style="font-size: 14px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;">Harga</td>
				<td style="font-size: 14px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;">Total</td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($products as $product) {?>
			<tr>
				<td style="font-size: 14px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><?=$product['name']?></td>
				<td style="font-size: 14px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?=$product['quantity']?></td>
				<td style="font-size: 14px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?=$product['price']?></td>
				<td style="font-size: 14px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?=$product['total']?></td>
			</tr>
			<?php }?>
		</tbody>
		<tfoot>
			<?php foreach ($totals as $total) {?>
			<tr>
				<td style="font-size: 14px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="3"><b><?=$total['title']?></b></td>
				<td style="font-size: 14px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?=$total['text']?></td>
			</tr>
			<?php }?>
		</tfoot>
	</table>
	<p style="margin-top: 0px; margin-bottom: 20px;">Apabila ada pertanyaan, silakan menghubungi kami melalui email <?=$this->config->item('email')?>, atau melalui telepon di nomor <?=$this->config->item('telephone')?></p>
	<p style="margin-top: 0px; margin-bottom: 20px;"><em>– <?=$this->config->item('site_name')?></em</p>
	<table width="100%">
		 <tr>
			<td style="border-top:1px solid #ddd; font-size:12px; color:#666;" align="center">
				 <p>Copyright &copy;<?=date('Y')?> <a href="<?=site_url()?>" style="text-decoration:none; color:#666;"><?=$this->config->item('site_name')?></a>. All Rights Reserved.</p>
			</td>
		 </tr>
	</table>
</body>
</html>