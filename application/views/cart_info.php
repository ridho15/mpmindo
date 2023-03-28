<a class="header__extra" href="javascript:void(0);"><i class="icon-bag2"></i><span><i><?=count($products)?></i></span></a>
<div class="ps-cart__content">
	<?php if ($products) {?>
	<div class="ps-cart__items">
		<?php foreach ($products as $key => $product) {?>
		<div class="ps-product--cart-mobile">
			<div class="ps-product__thumbnail">
				<a href="<?=$product['href']?>"><img src ="<?=$product['thumb']?>" alt=""></a>
			</div>
			<div class="ps-product__content">
				<a class="ps-product__remove" onclick="cart.remove('<?=$product['key']?>')"><i class="icon-cross"></i></a>
				<a href="<?=$product['href']?>"><?=$product['name']?></a>
				<p><small><?=$product['quantity']?> x <?=$product['price']?></small>
				<?php if ($product['tax_value'] && $product['tax_type'] == 'Inklusif') {?>
				<br><small>Termasuk PPN <?=$product['tax_value']?>%</small>
				<?php }?>
				</p>
			</div>
		</div>
		<?php }?>
	</div>
	<div class="ps-cart__footer">
		<table class="table">
		<?php foreach ($totals as $total) {?>
			<tr>
				<th><?=$total['title']?></th>
				<th class="text-right"><?=$total['text']?></th>
			</tr>
		<?php }?>
		</table>
		<figure>
			<a class="ps-btn" href="<?=site_url('cart')?>">Lihat Keranjang</a>
			<a class="ps-btn" href="<?=site_url('checkout')?>">Checkout</a>
		</figure>
	</div>
	<?php } else {?>
	<div class="ps-cart__items">
		<h4>Keranjang kosong!</h4>
		<p>Belum ada item apapun di keranjang belanja anda!</p>
	</div>
	<div class="ps-cart__footer" style="padding:0;"></div>
	<?php }?>
</div>