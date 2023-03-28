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
	.center {
		text-align: center;
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
		font-size: 32px;
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
			<td class="left"><span class="inv-num">INVOICE</span>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td>Nomor Invoice </td>
						<td> : <?=$invoice_prefix.$invoice_no?></td>
					</tr>
					<tr>
						<td>No. Pesanan </td>
						<td> : <?=$store_order_id?></td>
					</tr>
					<tr>
						<td>Tanggal </td>
						<td> : <?=$date_added?></td>
					</tr>
				</table>
			</td>
			<td></td>
			<td class="right"><img src="<?=$_logo?>"/></td>
		</tr>
		<tr>
			<td class="left" style="vertical-align: top;">
				<strong>Kepada,</strong><br><?=$user_name?><br><?=$user_address?>, <?=$user_subdistrict?><br>
				<?=$user_city?> - <?=$user_province?><br>
				Telp. <?=$user_telephone?><br>
			</td>
			<td class="center">
				
			</td>
			<td class="right" style="vertical-align: top;">
				<strong><?=$this->config->item('company')?></strong><br>
				<?=$this->config->item('address')?><br>
				<?=$this->config->item('telephone')?>
			</td>
		</tr>
	</table>
	<table width="100%" class="item">
			<thead>
				<tr>
					<th class="left" style="background:#eee;">No.</th>
					<th class="left">Nama Barang</th>
					<th class="left">Qty</th>
					<th class="right">Harga@</th>
					<th class="right">Subtotal</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($products as $key => $product) {?>
				<tr>
					<td><?=$key?></td>
					<td><?=$product['name']?></td>
					<td><?=$product['quantity']?></td>
					<td class="right"><?=$product['price']?></td>
					<td class="right"><?=$product['total']?></td>
				</tr>
				<?php }?>
			</tbody>
			<tfoot>
				<?php foreach ($products as $key => $product) {?>
				<tr>
					<th colspan="4" class="right">Subtotal</th>
					<th class="right"><?=$subtotal?></th>
				</tr>
				<tr>
					<th colspan="4" class="right">Biaya Kirim</th>
					<th class="right"><?=$shipping_cost?></th>
				</tr>
				<tr>
					<th colspan="4" class="right">Grand Total</th>
					<th class="right"><?=format_money($total)?></th>
				</tr>
				<?php }?>
			</tfoot>
		</table>
		<div class="spellout"><?=ucwords(to_word($total))?> Rupiah</div>
	</div>
</div>
<div class="footer">
	<span>Dicetak tanggal <?=date('d/m/Y H:i', time())?> WIB</span>
</div>
</body>
</html>