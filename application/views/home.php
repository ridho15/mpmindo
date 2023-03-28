<div id="homepage-1">
	<?php if ($banners) {?>
	<div class="ps-home-banner ps-home-banner--1">
		<div class="ps-container">
			<div class="ps-carousel--nav-inside owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="5000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="1000" data-owl-mousedrag="on">
				<?php foreach ($banners as $banner) {?>
				<div class="ps-banner"><a href="<?=$banner['link']?>"><img src ="<?=$banner['image']?>" alt="<?=$banner['title']?>"></a></div>
				<?php }?>
			</div>
		</div>
	</div>
	<?php }?>
	<div class="ps-site-features">
		<div class="ps-container">
			<div class="ps-block--site-features">
				<?php foreach ($footer_cards as $footer_card) {?>
				<div class="ps-block__item">
					<div class="ps-block__left"><i class="<?=$footer_card['icon']?>"></i></div>
					<div class="ps-block__right">
						<h4><?=$footer_card['heading']?></h4>
						<p><?=$footer_card['caption']?></p>
					</div>
				</div>
				<?php }?>
			</div>
		</div>
	</div>
	<?php if ($specials) {?>
	<div class="ps-deal-of-day">
		<div class="ps-container">
			<div class="ps-section__header">
				<h3>Deals of the day</h3>
			</div>
			<div class="ps-section__content">
				<div class="ps-carousel--nav owl-slider" data-owl-auto="false" data-owl-loop="false" data-owl-speed="10000" data-owl-gap="30" data-owl-nav="true" data-owl-dots="true" data-owl-item="7" data-owl-item-xs="2" data-owl-item-sm="3" data-owl-item-md="4" data-owl-item-lg="5" data-owl-item-xl="6" data-owl-duration="1000" data-owl-mousedrag="on">
					<?php foreach ($specials as $special) { ?>
					<div class="ps-product">
						<div class="ps-product__thumbnail"><a href="<?=$special['href']?>"><img src="<?=$special['thumb']?>" alt="<?=$special['name']?>"></a>
							<?php if ($special['discount_percent']) {?>
							<div class="ps-product__badge">-<?=$special['discount_percent']?></div>
							<?php }?>
							<ul class="ps-product__actions" style="width:100%;padding:10px;">
								<li><a data-toggle="tooltip" data-placement="top" title="Beli Item Ini" onClick="cart.add(<?=$special['product_id']?>);"><i class="icon-bag2"></i></a></li>
								<li><a data-toggle="tooltip" data-placement="top" title="Quick View" onClick="quickView(<?=$special['product_id']?>);"><i class="icon-eye"></i></a></li>
								<li><a href="<?=$special['href']?>" data-toggle="tooltip" data-placement="top" title="Lihat Detail"><i class="icon-link"></i></a></li>
							</ul>
						</div>
						<div class="ps-product__container">
							<div class="ps-product__content">
								<a class="ps-product__title"  title="<?=$special['name']?>"  href="<?=$special['href']?>"><?=$special['name']?></a>
								<p class="ps-product__price sale">
									<?php if ($special['discount']) {?>
									<small><del style="margin:0"><?=$special['price']?></del></small><br><?=$special['discount']?>
									<?php } else {?>
									<?=$special['price']?>
									<?php }?>
								</p>
								<p>&nbsp;</p>
							</div>
							<div class="ps-product__content hover"><a class="ps-product__title"  title="<?=$special['name']?>"  href="<?=$special['href']?>"><?=$special['name']?></a>
								<p class="ps-product__price sale">
									<?php if ($special['discount']) {?>
									<small><del style="margin:0"><?=$special['price']?></del></small><br><?=$special['discount']?>
									<?php } else {?>
									<?=$special['price']?>
									<?php }?>
								</p>
							</div>
						</div>
					</div>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
	<?php }?>
	
	<?php if ($banner_ads) {?>
	<div class="ps-home-ads">
		<div class="ps-container">
			<div class="row">
				<?php foreach ($banner_ads as $banner) {?>
				<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 ">
					<a class="ps-collection" href="<?=$banner['link']?>"><img src="<?=$banner['image']?>" alt="<?=$banner['title']?>"></a>
				</div>
				<?php }?>
			</div>
		</div>
	</div>
	<?php }?>
	
	<?php foreach ($carousels as $carousel) { ?>
	<div class="ps-product-list ps-clothings mt-5">
		<div class="ps-container">
			<div class="ps-section__header">
				<h3><?=$carousel['name']?></h3>
				<ul class="ps-section__links">
					<li><a href="<?=$carousel['href']?>">Tampilkan Semua</a></li>
				</ul>
			</div>
			<div class="ps-section__content">
				<div class="ps-carousel--nav owl-slider" data-owl-auto="false" data-owl-loop="false" data-owl-speed="10000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="7" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3" data-owl-item-lg="4" data-owl-item-xl="6" data-owl-duration="1000" data-owl-mousedrag="on">
					<?php foreach ($carousel['products'] as $carousel_product) { ?>
					<div class="ps-product">
						<div class="ps-product__thumbnail"><a href="<?=$carousel_product['href']?>"><img src="<?=$carousel_product['thumb']?>" alt="<?=$carousel_product['name']?>"></a>
							<?php if ($carousel_product['discount_percent']) {?>
							<div class="ps-product__badge">-<?=$carousel_product['discount_percent']?></div>
							<?php }?>
							<ul class="ps-product__actions" style="width:100%;padding:10px;">
								<li><a data-toggle="tooltip" data-placement="top" title="Beli Item Ini" onClick="cart.add(<?=$carousel_product['product_id']?>);"><i class="icon-bag2"></i></a></li>
								<li><a data-toggle="tooltip" data-placement="top" title="Quick View" onClick="quickView(<?=$carousel_product['product_id']?>);"><i class="icon-eye"></i></a></li>
								<li><a href="<?=$carousel_product['href']?>" data-toggle="tooltip" data-placement="top" title="Lihat Detail"><i class="icon-link"></i></a></li>
							</ul>
						</div>
						<div class="ps-product__container">
							<div class="ps-product__content">
								<a class="ps-product__title"  title="<?=$carousel_product['name']?>"  href="<?=$carousel_product['href']?>"><?=$carousel_product['name']?></a>
								<p class="ps-product__price sale">
									<?php if ($carousel_product['discount']) {?>
									<small><del style="margin:0"><?=$carousel_product['price']?></del></small><br><?=$carousel_product['discount']?>
									<?php } else {?>
									<?=$carousel_product['price']?>
									<?php }?>
								</p>
								<p>&nbsp;</p>
							</div>
							<div class="ps-product__content hover"><a class="ps-product__title"  title="<?=$carousel_product['name']?>" href="<?=$carousel_product['href']?>"><?=$carousel_product['name']?></a>
								<p class="ps-product__price sale">
									<?php if ($carousel_product['discount']) {?>
									<small><del style="margin:0"><?=$carousel_product['price']?></del></small><br><?=$carousel_product['discount']?>
									<?php } else {?>
									<?=$carousel_product['price']?>
									<?php }?>
								</p>
							</div>
						</div>
					</div>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
	<?php }?>
</div>