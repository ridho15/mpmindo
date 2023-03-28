<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?=$title?></title>
	</head>
	<body style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; font-size: 14px; color: #000000;">
		<table style="border-collapse: collapse; width: 100%; border-bottom: 1px solid #DDDDDD; margin-bottom: 20px;">
			<tbody>
				<tr>
					<td style="font-size: 14px;	text-align: center; padding: 7px;">
						<!-- <img src="<?=$_logo?>"> -->
						<img src="<?=base_url('assets/images/wahl.png')?>" width="10%"/>
					</td>
				</tr>
			</tbody>
		</table>
		<p style="margin-top: 0px; margin-bottom: 20px;">Hi <strong><?=$name?></strong>,</p>
		<p style="margin-top: 0px; margin-bottom: 20px;">Someone has requested to reset your password. If so, please follow by clicking the Reset button. But if not, cancel this request by clicking the Cancel button</p>
		<p style="margin-top: 0px; margin-bottom: 20px; text-align:center;">
			<a href="<?=$continue?>" target="_blank" style="display:inline-block;padding:6px 12px;margin-bottom:0;font-size:14px;font-weight:400;line-height:1.428571429;text-align:center;white-space:nowrap;vertical-align:middle;cursor:pointer;border:1px solid transparent;border-radius:4px;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;-o-user-select:none;user-select:none;color:#fff;background-color:#5cb85c;border-color:#4cae4c;text-decoration:none;">Reset</a>&nbsp;
			<a href="<?=$cancel?>" target="_blank" style="display:inline-block;padding:6px 12px;margin-bottom:0;font-size:14px;font-weight:400;line-height:1.428571429;text-align:center;white-space:nowrap;vertical-align:middle;cursor:pointer;border:1px solid transparent;border-radius:4px;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;-o-user-select:none;user-select:none;color:#fff;background-color:#f0ad4e;border-color:#eea236;text-decoration:none;">Cancel</a>
		</p>
		<p style="margin-top: 0px; margin-bottom: 20px;"><em>Thank You</em></p>
		<table width="100%">
			<tr>
				<td style="border-top:1px solid #ddd; font-size:12px; color:#666;" align="center">
					<p>Copyright &copy;<?=date('Y', time())?> <a href="<?=site_url()?>" style="text-decoration:none; color:#666;"><?=$this->config->item('company')?>. All Rights Reserved.</p>
				</td>
			</tr>
		</table>
	</body>
</html>