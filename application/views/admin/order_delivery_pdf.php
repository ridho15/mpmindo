<html>
<head>
<style>
	html {
		margin:150px 50px 50px;
	}
	body {
		font-family: 'Arial';
		font-size: 12px;
	}
	.header {
		top: -100px;
	}
	.footer {
		bottom: 0px;
		font-size: 10px;
		color: #333;
		font-style: italic;
	}
	.header,
	.footer {
		width: 100%;
		text-align: left;
		position: fixed;
	}
	.inv-num {
		font-size: 24px;
		font-weight: bold;
	}
	.left {
		text-align: left;
	}
	.right {
		text-align: right;
	}
	table.item {
		border-collapse: collapse;
		border-spacing: 0;
		margin: 20px 0;
	}
	table.item > thead > tr > th {
		background: #eee;
	}
	table.item tr td, table.item tr th {
		padding: 5px;
	}
	table.item tr td, table.item tr th {
		border: 1px solid #ddd;
	}
	.status {
		font-weight: bold;
		font-size: 18px;
		padding: 10px;
	}
	.status-paid {
		color: #0fc305;
		border: 1px solid #0fc305;
	}
	.status-unpaid {
		color: #df5700;
		border: 1px solid #df5700;
	}
	.spellout {
		padding: 10px;
		background-color: #efefef;
		border: 1px solid #ddd;
	}
	.instruction {
		margin: 20px 0;
	}
</style>
</head>
<body>
<div class="header">
	<table width="100%">
		<tr>
			<td class="left"><span class="inv-num">SURAT JALAN</span>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td>Order Code: </td>
						<td> : #<?=$code?></td>
					</tr>
					<tr>
						<td>Tanggal </td>
						<td> : <?=$date_added?></td>
					</tr>
				</table>
			</td>
			<td class="right"><img src="<?=$logo?>"/></td>
		</tr>
		<tr>
			<td class="left" style="vertical-align: top;">
				<strong>Kepada :</strong><br><?=$name?><br><?=$shipping_address?> 
				<?=$shipping_regency?> - <?=$shipping_province?><br>
				<?=$shipping_country?><br>
				Telp. <?=$telephone?><br><br>
				<strong>Jasa Pengiriman :</strong><br><?=$shipping_method?>
				<?php if ($tracking_no) {?>
					<br><?=$tracking_no?>
				<?php }?>
			</td>
			<td class="right" style="vertical-align: top;">
				<strong>Pengirim :</strong><br>
				<?=$this->config->item('company')?><br>
				<?=$this->config->item('address')?>
				<?php if ($regency) {?>
					<br><?=$regency?>
				<?php }?>
				<?php if ($province && $country) {?>
					<br><?=$province?> - <?=$country?>
				<?php }?><br>
				<?=$this->config->item('telephone')?>
			</td>
		</tr>
	</table>
	<table width="100%" class="item">
			<thead>
				<tr>
					<th class="left" style="background:#eee;">No.</th>
					<th class="left">Nama Barang</th>
					<th class="left">Quantity</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($products as $key => $product) {?>
				<tr>
					<td><?=$key?></td>
					<td><?=$product['name']?></td>
					<td><?=$product['quantity']?> <?=$product['unit']?></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		<div class="instruction">
			<p><strong>Perhatian :</strong></p>
			<ol>
				<li>Barang yang dikirim telah diperiksa oleh bagian logistik kami sesuai order Anda</li>
				<li>Kami tidak menjamin barang kiriman sampai tujuan tepat waktu, bergantung pada ekspedisi.</li>
			</ol>
		</div>
	</div>
</div>
<div class="footer">
	<span>Dicetak tanggal <?=$print_date?> WIB</span>
</div>
</body>
</html>