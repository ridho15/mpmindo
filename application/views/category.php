<div class="ps-breadcrumb">
	<div class="ps-container">
		<ul class="breadcrumb">
			<?php foreach ($template['breadcrumbs'] as $key => $breadcrumb) {?>
			<?php if (count($breadcrumb) == $key+1) {?>
			<li><?=$breadcrumb['text']?></li>
			<?php } else {?>
			<li><a href="<?=$breadcrumb['href']?>"><?=$breadcrumb['text']?></a></li>
			<?php }?>
			<?php }?>
		</ul>
	</div>
</div>
<div class="ps-page--shop" id="shop-sidebar">
	<div class="ps-container">
		<div class="ps-layout--shop">
			<div class="ps-layout__left">
				<aside class="widget widget_shop">
					<h4 class="widget-title">Kategori</h4>
					<ul class="ps-list--categories">
						<?php foreach ($_categories as $category) {?>
						<?php if (count($category['children']) > 0) {?>
						<li class="current-menu-item menu-item-has-children"><a href="<?=$category['href']?>"><?=$category['name']?></a><span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
							<ul class="sub-menu">
								<?php foreach ($category['children'] as $children) {?>
								<?php if (count($children['children']) > 0) {?>
								<li class="current-menu-item menu-item-has-children"><a href="<?=$children['href']?>"><?=$children['name']?></a><span class="sub-toggle"><i class="fa fa-angle-down"></i></span>
									<ul class="sub-menu">
										<?php foreach ($children['children'] as $subchildren) {?>
										<li class="current-menu-item"><a href="<?=$subchildren['href']?>"><?=$subchildren['name']?></a></li>
										<?php }?>
									</ul>
								</li>
								<?php } else {?>
								<li class="current-menu-item"><a href="<?=$children['href']?>"><?=$children['name']?></a></li>
								<?php }?>
								<?php }?>
							</ul>
						</li>
						<?php } else {?>
						<li class="current-menu-item"><a href="<?=$category['href']?>"><?=$category['name']?></a>
						<?php }?>
						<?php }?>
					</ul>
				</aside>
			</div>
			<div class="ps-layout__right">
				<div class="ps-page__header" style="margin:0;">
					<?php if($mobile) { ?>
						<div align="center" width="100%">
						<?php foreach ($_categories as $category) {?>
							<?php if (count($category['children']) > 0) {?>
								<?php $countCategoryLoop = count($category['children']); $counterLoop = 1;?>
								<?php foreach ($category['children'] as $children) {?>
								<?php if($counterLoop < $countCategoryLoop) $divide = '|'; else $divide = '';?>
									<a href="<?=$children['href']?>"><small <?php if($name == $children['name']) echo 'style="font-weight:bold;"';?>><?=$children['name']?></small></a>&nbsp;&nbsp;<?=$divide?>&nbsp;
								<?php $counterLoop++;} ?>
							<?php } ?>
						<?php } ?>
						</div><br>
					<?php } ?>
					<h1><?=$name?></h1>
					<p><?=$description?></p>
				</div>
				<div class="ps-shopping ps-tab-root">
					<!--<div class="alert alert-danger" role="alert"><b>INFO: Untuk sementara kami tidak melayani pembelian online karena libur lebaran 2022. Kami akan melayani Anda kembali tanggal 9 Mei 2022. Terima kasih atas perhatiannya.</b></div><br>-->
					<div class="ps-shopping__header">
						<p><strong> <?=$total?></strong> Produk ditemukan</p>
						<div class="ps-shopping__actions">
							<select class="form-control" style="background-color:#fff" data-placeholder="Urutk Berdasarkan" onchange="window.location = $(this).find('option:selected').val();">
								<?php foreach ($sorts as $sort_data) {?>
								<?php if ($sort_data['value'] == $sort.'-'.$order) {?>
								<option value="<?=$sort_data['href']?>" selected="selected"><?=$sort_data['text']?></option>
								<?php } else {?>
								<option value="<?=$sort_data['href']?>"><?=$sort_data['text']?></option>
								<?php }?>
								<?php }?>
							</select>
						</div>
					</div>
					<div id="notification"></div>
					<div class="ps-tabs">
						<div>
							<div class="ps-shopping-product">
								<div class="row">
									<?php foreach ($products as $product) {?>
									<div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
										<div class="ps-product">
											<div class="ps-product__thumbnail"><a href="<?=$product['href']?>"><img src="<?=$product['thumb']?>" alt=""></a>
												<?php if ($product['discount_percent']) {?>
												<div class="ps-product__badge">Disc. <?=$product['discount_percent']?></div>
												<?php }?>
												<ul class="ps-product__actions" style="width:100%;padding-left:8px">
												<?php if($product['quantity'] > 0) {?>
													<li><a data-toggle="tooltip" data-placement="top" title="Beli Item Ini" onClick="cart.add(<?=$product['product_id']?>);"><i class="icon-bag2"></i></a></li>
												<?php } ?>
													<li><a data-toggle="tooltip" data-placement="top" title="Quick View" onClick="quickView(<?=$product['product_id']?>);"><i class="icon-eye"></i></a></li>
													<li><a href="<?=$product['href']?>" data-toggle="tooltip" data-placement="top" title="Lihat Detail"><i class="icon-link"></i></a></li>
													<li><a href="<?=site_url('compare_product')."/".$product['href_title']?>" data-toggle="tooltip" data-placement="top" title="Bandingkan Produk"><i class="icon-desktop"></i></a></li>
												</ul>
											</div>
											<div class="ps-product__container">
												<div class="ps-product__content"><a class="ps-product__title"  title="<?=$product['name']?>"  href="<?=$product['href']?>"><?=$product['name']?></a>
													<p class="ps-product__price sale">
														<?php if ($product['discount']) {?>
														<small><del style="margin:0"><?=$product['price']?></del></small><br><?=$product['discount']?>
														<?php } else {?>
														<?=$product['price']?>
														<?php }?>
													</p>
													<p><span <?php if($product['quantity_sale'] == 0) echo 'style="color:white;"'?>>Terjual <?=$product['quantity_sale'];?></span> <?php if($product['count_comment'] > 0) {?>• <span class="fa fa-star" style="color:orange;"></span> <?=number_format($product['rating_value']/$product['count_comment'],1)?><?php } ?></p>
													<br><br>
												</div>
												<div class="ps-product__content hover"><a class="ps-product__title"  title="<?=$product['name']?>"  href="<?=$product['href']?>"><?=$product['name']?></a>
													<p class="ps-product__price sale">
														<?php if ($product['discount']) {?>
														<small><del style="margin:0"><?=$product['price']?></del></small><br><?=$product['discount']?>
														<?php } else {?>
														<?=$product['price']?>
														<?php }?>
													</p>
													<p><span <?php if($product['quantity_sale'] == 0) echo 'style="color:white;"'?>>Terjual <?=$product['quantity_sale'];?></span> <?php if($product['count_comment'] > 0) {?>• <span class="fa fa-star" style="color:orange;"></span> <?=number_format($product['rating_value']/$product['count_comment'],1)?><?php } ?></p>
													<br><br>
												</div>
											</div>
										</div>
									</div>
									<?php }?>
								</div>
							</div>
							<div class="ps-pagination">
								<?=$pagination?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>