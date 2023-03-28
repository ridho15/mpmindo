<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">		
<title><?=$this->config->item('site_name')?></title>
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
	<p style="margin-top: 0px; margin-bottom: 20px;">Dear User,</p>
	<p style="margin-top: 0px; margin-bottom: 20px;">Ada klaim garansi baru di <?=$this->config->item('site_name')?>. Berikut ini adalah rincian klaim:</p>
	<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
		<thead>
			<tr>
				<td style="font-size: 14px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="2">Detail Klaim</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="font-size: 14px;	border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
					<b>Tanggal Klaim :</b> <?=$claim_date?><br />
					<b>Kode Serial Produk:</b> <?=$product_code?><br />
					<b>Nama Produk:</b> <?=$product_name?><br />
					<b>Alasan Klaim:</b> <?=$reason?><br />
					<b>Pendaftar:</b> <?=$claim_by?><br />
					<b>Email Pendaftar:</b> <?=$user_email?><br />
					<?php if($user_phone) {?> 
					<b>Telepon Pendaftar:</b> <?=$user_phone?><br />
					<?php } ?>
				</td>
			</tr>
		</tbody>
	</table>
	<p style="margin-top: 0px; margin-bottom: 20px;">Silakan untuk dapat memproses klaim ini.</p>
	<p style="margin-top: 0px; margin-bottom: 20px;">Terima kasih</p>
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