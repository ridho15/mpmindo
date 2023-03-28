<div class="ps-panel__header">
	<h3>Keranjang Belanja</h3>
</div>
<div class="navigation__content">
	<div class="ps-cart--mobile">
		<?php if ($products) {?>
		<div class="ps-cart__content">
			<?php foreach ($products as $key => $product) {?>
			<div class="ps-product--cart-mobile">
				<div class="ps-product__thumbnail">
					<a href="<?=$product['href']?>"><img src="<?=$product['thumb']?>" alt=""></a>
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
			<div class="ps-cart__footer mt-3">
				<?php foreach ($totals as $total) {?>
				<h3><?=$total['title']?>:<strong><?=$total['text']?></strong></h3>
				<?php }?>
				<figure><a class="ps-btn" href="<?=site_url('cart')?>">Lihat Keranjang</a><a class="ps-btn" href="<?=site_url('checkout')?>">Checkout</a></figure>
			</div>
		</div>
		<?php } else {?>
		<div class="ps-cart__content">
			<h4>Keranjang kosong!</h4>
			<p>Belum ada item apapun di keranjang belanja anda!</p>
		</div>
		<?php }?>
	</div>
</div>