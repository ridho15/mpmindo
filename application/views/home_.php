<?php if ($banners) {?>
<div id="slideshow" class="carousel slide" data-ride="carousel" data-interval="2000">
	<ol class="carousel-indicators">
		<?php foreach ($banners as $key => $banner) {?>
		<?php if ($key == 0) {?>
		<li data-target="#slideshow" data-slide-to="<?=$key?>" class="active"></li>
		<?php } else {?>
		<li data-target="#slideshow" data-slide-to="<?=$key?>"></li>
		<?php }?>
		<?php }?>
	</ol>
	<div class="carousel-inner">
		<?php foreach ($banners as $key => $banner) {?>
		<?php if ($key == 0) {?>
		<div class="carousel-item active">
		<?php } else {?>
		<div class="carousel-item">
		<?php }?>
			<img class="d-block w-100" src="<?=$banner['image']?>">
			<div class="carousel-caption" style="text-align:left;">
				<div class="hero-text">
					<?php if ($banner['title']) {?>
					<h1 style="color: #84C225;"><?=$banner['title']?></h1>
					<?php }?>
					<?php if ($banner['subtitle']) {?>
					<p><?=$banner['subtitle']?></p>
					<?php }?>
					<?php if ($banner['link'] && $banner['link_title']) {?>
					<div class="button">
						<a href="<?=$banner['link']?>" class="btn"><?=$banner['link_title']?></a>
					</div>
					<?php }?>
				</div>
			</div>
		</div>
		<?php }?>
	</div>
	<a class="carousel-control-prev" href="#slideshow" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="carousel-control-next" href="#slideshow" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div>
<?php }?>
<?php if (count($specials) > 1) {?>
<div class="product-area most-popular section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="section-title">
					<h2>Hot Item</h2>
				</div>
			</div>
		</div>
		<div class="row no-gutters">
			<div class="col-12">
				<div class="owl-carousel popular-slider no-gutters">
					<?php foreach ($specials as $product) {?>
					<div class="single-product" style="border: 1px solid #ddd; margin:5px; padding:15px;border-radius: 5px">
						<div class="product-img">
							<a href="<?=$product['href']?>">
								<img class="default-img" src="<?=$product['thumb']?>" alt="#">
								<img class="hover-img" src="<?=$product['thumb']?>" alt="#">
								<span class="out-of-stock">-<?=$product['discount_percent']?></span>
							</a>
							<div class="button-head">
								<div class="product-button">
									<?php if ((int)$product['quantity'] > 0) {?>
									<a title="Beli" onclick="cart.add('<?=$product['product_id']?>', '1');"><span class="btn">Beli</span></a>
									<?php } else { ?>
									<a title="Stok Kosong"><span class="btn bg-warning">KOSONG</span></a>
									<?php }?>
								</div>
							</div>
						</div>
						<div class="product-content">
							<h3><a href="<?=site_url('product/details')?>"><?=$product['name']?></a></h3>
							<div class="product-price">
								<span class="old"><?=$product['price']?></span>
								<span><?=$product['discount']?></span>
							</div>
						</div>
					</div>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php }?>
<?php if ($carousels) {?>
<section class="shop-home-list section">
	<div class="container">
		<div class="row">
			<?php foreach ($carousels as $key => $category) {?>
			<?php if ($category['products']) {?>
			<div class="col-lg-4 col-md-6 col-12" style="margin-bottom: 30px;margin-top: 30px">
				<div class="row">
					<div class="col-12">
						<div class="shop-section-title">
							<h1><?=$category['name']?></h1>
						</div>
					</div>
				</div>
				<div class="row no-gutters">
				<?php foreach ($category['products'] as $product) {?>
				<div class="col-lg-12 col-md-12 col-6 single-list">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-12">
							<div class="list-image overlay">
								<img src="<?=$product['thumb']?>">
								<a href="<?=$product['href']?>" class="buy"><i class="fa fa-shopping-bag"></i></a>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-12 no-padding">
							<div class="content" style="padding-top:0">
								<h4 class="title"><a href="<?=$product['href']?>"><?=$product['name']?></a></h4>
								<p class="description d-none d-lg-block d-xl-block"><?=$product['description']?></p>
								<p class="price with-discount"><?=$product['price']?></p>
							</div>
						</div>
					</div>
				</div>
				<?php }?>
				</div>
			</div>
			<?php }?>
			<?php }?>
		</div>
	</div>
</section>
<?php }?>
<?php if ($footer_cards) {?>
<section class="shop-services section home" style="padding-bottom:0">
	<div class="container">
		<div class="row">
			<?php foreach ($footer_cards as $footer_card) {?>
			<div class="col-lg-3 col-md-6 col-12">
				<div class="single-service" style="margin-bottom:48px">
					<i class="<?=$footer_card['icon']?>"></i>
					<h4><?=$footer_card['heading']?></h4>
					<p><?=$footer_card['caption']?></p>
				</div>
			</div>
			<?php }?>
		</div>
	</div>
</section>
<?php }?>