<?php if ($products) {?>
<div class="row no-gutters">
	<?php foreach ($products as $product) {?>
	<div class="col-lg-3 col-md-6 col-6">
		<div class="single-product" style="margin:5px;border-radius:5px;background-color:#fff;padding:5px;">
			<div class="product-img">
				<a href="<?=$product['href']?>">
					<img class="default-img" src="<?=$product['thumb']?>" alt="#">
					<img class="hover-img" src="<?=$product['thumb']?>" alt="#">
					<?php if ($product['discount_percent']) {?>
					<span class="new">Diskon <?=$product['discount_percent']?></span>
					<?php }?>
				</a>
				<div class="button-head">
					<div class="product-button">
						<?php if ((int)$product['quantity'] > 0) {?>
						<a title="Beli" onclick="cart.add('<?=$product['product_id']?>', '1');"><span class="btn">Beli Item Ini</span></a>
						<?php } else { ?>
						<a title="Stok Kosong"><span class="btn bg-warning">KOSONG</span></a>
						<?php }?>
					</div>
				</div>
			</div>
			<div class="product-content" style="padding-bottom: 15px">
				<h3><a href="<?=$product['href']?>"><?=$product['name']?></a></h3>
				<div class="product-price">
					<?php if ($product['discount']) {?>
					<span class="old" style="font-size:12px;"><?=$product['price']?></span>
					<span><?=$product['discount']?></span>
					<?php } else {?>
					<span><?=$product['price']?></span>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
	<?php }?>
</div>
<?php } elseif ($page == 1) {?>
<div class="alert alert-warning">Ups! belum ada produk dalam kategori ini</div>
<?php }?>