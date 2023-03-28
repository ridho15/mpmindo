<table class="table table-bordered" id="product-table">
	<thead>
		<tr>
			<th>Produk</th>
			<th>Qty</th>
			<th class="text-right">@Harga</th>
			<th class="text-right">Subtotal</th>
			<th class="text-right"></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($products as $product) {?>
		<tr>
			<td><?=$product['name']?></td>
			<td><?=$product['quantity']?></td>
			<td class="text-right"><?=$product['price']?></td>
			<td class="text-right"><?=$product['total']?></td>
			<td class="text-right"><a class="btn btn-xs btn-danger" onclick="cart.remove(<?=$product['key']?>);"><i class="fa fa-remove"></i></a></td>
		</tr>
		<?php }?>
	</tbody>
</table>