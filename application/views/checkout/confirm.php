<?php if (!isset($redirect)) { ?>
<div class="checkout-product">
	<p>Mohon periksa kembali belanjaan Anda sebelum melakukan proses pembayaran</p><br>
	<div class="row">
		<div class="col-xs-12 col-md-6">
			<div class="alert" style="background-color:#f5f5f5;">
				<b>Nama Pemesan:</b><br><?=$order['user_name']?><br>
				<b>No. Telepon:</b><br><?=$order['user_telephone']?><br>
				<b>Email:</b><br><?=$order['user_email']?>
			</div>
		</div>
		<div class="col-xs-12 col-md-6">
			<div class="alert" style="background-color:#f5f5f5;">
				<b>Alamat Pengiriman:</b><br><?=$order['user_address']?>, <?=$order['user_subdistrict']?>, <?=$order['user_city']?> - <?=$order['user_province']?> <?=$order['user_postcode']?><br>
				<b>Catatan:</b><br><?php if($order['comment']) echo $order['comment']; else echo "<br>";?>
			</div>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr style="background-color:#f5f5f5;">
					<th><b>Produk</b></th>
					<th><b>SKU</b></th>
					<th class="text-right"><b>Qty</b></th>
					<th class="text-right"><b>Harga</b></th>
					<th class="text-right"><b>Harga Total</b></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($order_products as $product) { ?>
				<tr>
					<td>
						<?=$product['name']?>
						<?php if ($product['tax_value']) {?>
						<br><small>Termasuk PPN <?=$product['tax_value']?>% (<?=$product['tax_type']?>)</small>
						<?php }?>
					</td>
					<td><?=$product['sku']?></td>
					<td class="text-right"><?=$product['quantity']?></td>
					<td class="text-right"><?=format_money($product['price'], 0)?></td>
					<td class="text-right"><?=format_money($product['total'], 0)?></td>
				</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<?php foreach ($order_totals as $total) { ?>
				<tr>
					<td colspan="4" class="text-right"><b><?=$total['title']?></b></td>
					<td class="text-right"><b><?=format_money($total['value'], 0)?></b></td>
				</tr>
				<?php } ?>
			</tfoot>
		</table>
	</div>
	<div class="row">
		<div class="col-6">
			<div class="alert" style="background-color:#f5f5f5;">
			<b>Metode Pembayaran:</b> <?=$order['payment_method']?><br>
				<img src="<?=base_url('assets/images/payments/'.$order['payment_code'].'.png')?>" class="img-responsive" style="max-width:100px; max-height:100px;" />
			</div>
		</div>
		<div class="col-6">
			<div class="alert" style="background-color:#f5f5f5;">
			<b>Metode Pengiriman:</b> <?=$order['shipping_method']?><br><br>
			<?php if(strpos($order['shipping_code'], 'jne') > 0) { ?>
				<img src="<?=base_url('assets/images/shippings/jne.png')?>" class="img-responsive" style="max-width:100px; max-height:100px;" />
			<?php } else if(strpos($order['shipping_code'], 'tiki') > 0) { ?>
				<img src="<?=base_url('assets/images/shippings/tiki.png')?>" class="img-responsive" style="max-width:100px; max-height:100px;" />
			<?php } else if(strpos($order['shipping_code'], 'sicepat') > 0) { ?>
				<img src="<?=base_url('assets/images/shippings/sicepat.png')?>" class="img-responsive" style="max-width:100px; max-height:100px;" />
			<?php } else if(strpos($order['shipping_code'], 'J&T') > 0) { ?>
				<img src="<?=base_url('assets/images/shippings/jt.png')?>" class="img-responsive" style="max-width:100px; max-height:100px;" />
			<?php } ?>
			<br><br>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="alert alert-warning" role="alert">
				<h4><b>Selesaikan Pembayaran Anda</b></h4>
				Mohon Segera Selesaikan Pembayaran Anda Sebelum Tanggal <b><?=date('d/m/Y H:i',strtotime($orderdata['date_added'])+86400);?> WIB</b> Dengan Mengklik Tombol Proses Pembayaran.
			</div>
		</div>
	</div>
</div>
<?=$payment?>
<?php } else { ?>
<script type="text/javascript">
window.location = '<?=$redirect?>';
</script> 
<?php } ?>