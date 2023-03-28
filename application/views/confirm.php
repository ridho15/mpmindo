<?php if (!isset($redirect)) { ?>
<div class="checkout-product">
	<div class="table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<td>Produk</td>
					<td>SKU</td>
					<td class="text-right">Qty</td>
					<td class="text-right">@Harga</td>
					<td class="text-right">Harga Total</td>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($products as $product) { ?>
				<tr>
					<td><a href="<?=$product['href']?>"><?=$product['name']?></a></td>
					<td><?=$product['sku']?></td>
					<td class="text-right"><?=$product['quantity']?></td>
					<td class="text-right"><?=$product['price']?></td>
					<td class="text-right"><?=$product['total']?></td>
				</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<?php foreach ($totals as $total) { ?>
				<tr>
					<td colspan="4" class="text-right"><b><?=$total['title']?>:</b></td>
					<td class="text-right"><?=$total['text']?></td>
				</tr>
				<?php } ?>
			</tfoot>
		</table>
	</div>
</div>
<div class="payment"><?=$payment?></div>
<?php } else { ?>
<script type="text/javascript">
location = '<?=$redirect?>';
</script> 
<?php } ?>