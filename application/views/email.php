<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?=$subject?></title>
</head>
<body style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 12px; color: #000000;">
	<table style="border-collapse: collapse; width: 100%; border-bottom: 1px solid #DDDDDD; margin-bottom: 20px;">
		<tbody>
			<tr>
				<td style="font-size: 12px;	text-align: center; padding: 7px;">
					<!--<img src="<?=$_logo?>">-->
					<img src="<?=base_url('assets/images/wahl.png')?>" alt="<?=$this->config->item('site_name')?>" style="border: none;" width="10%"/>
				</td>
			</tr>
		</tbody>
	</table>
	<?=$content?>
	<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; margin-top: 20px;">
		<tbody>
			<tr>
				<td style="font-size: 12px;	text-align: center; padding: 7px; color:#66666;">
					<?=$this->config->item('company')?><br><?=$this->config->item('address')?><br>Phone: <?=$this->config->item('telephone')?>
				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>