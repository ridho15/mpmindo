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
						<td>No. Pesanan </td>
						<td> : <?=$invoice_no?></td>
					</tr>
					<tr>
						<td>Tanggal </td>
						<td> : <?=$date_added?></td>
					</tr>
					<tr>
						<td>Metode Pembayaran </td>
						<td> : <?=$payment_method?></td>
					</tr>
				</table>
			</td>
			<td>
				<?php if ($order_status_id == $this->config->item('order_paid_status_id') || $order_status_id == $this->config->item('order_process_status_id') || $order_status_id == $this->config->item('order_delivered_status_id') || $order_status_id == $this->config->item('order_complete_status_id')) {?>
				<span class="status status-paid">PAID</span>
				<?php } else {?>
				<span class="status status-unpaid">UNPAID</span>
				<?php }?>
			</td>
			<!--<td class="right"><img src="<?=$logo?>"/></td>-->
			<td class="right"><img src="<?=base_url('assets/murdock/images/demo/logo-wahl612.jpg')?>"/></td>
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
					<th class="left">No.</th>
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
				<?php foreach ($totals as $total) {?>
				<tr>
					<th colspan="4" class="right"><?=$total['title']?></th>
					<th class="right"><?=$total['text']?></th>
				</tr>
				<?php }?>
			</tfoot>
		</table>
		<div class="spellout"><?=ucwords(to_word($total['value']))?> Rupiah</div>
	</div>
</div>
<div class="footer">
	<span>Dicetak tanggal <?=date('d/m/Y H:i', time())?> WIB</span>
</div>
</body>
</html>