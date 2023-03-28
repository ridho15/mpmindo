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
<div class="ps-page--product">
	<div class="ps-container">
		<div id="notification"></div>
		<div class="ps-page__container" style="display: inherit !important;">
			<div class="ps-product--detail ps-product--fullwidth">
				<!--<div class="alert alert-danger" role="alert"><b>INFO: Untuk sementara kami tidak melayani pembelian online karena libur lebaran 2022. Kami akan melayani Anda kembali tanggal 9 Mei 2022. Terima kasih atas perhatiannya.</b></div><br>-->
				<div class="ps-product__header">
					<div class="ps-product__thumbnail" data-vertical="true">
						<figure>
							<div class="ps-wrapper">
								<div class="ps-product__gallery" data-arrow="true">
									<?php foreach ($images as $image) { ?>
									<div class="item">
										<a href="<?=$image['popup']?>"><img src="<?=$image['thumb']?>" alt=""></a>
									</div>
									<?php } ?>
								</div>
							</div>
						</figure>
						<div class="ps-product__variants" data-item="4" data-md="4" data-sm="4" data-arrow="false">
							<?php foreach ($images as $image) { ?>
							<div class="item"><img src="<?=$image['thumb']?>" alt=""></div>
							<?php }?>
						</div>
					</div>
					<div class="ps-product__info">
						<h1><?=$name?></h1>
						<p>Dilihat : <?=$viewed?> kali</p>
						<?php if($quantity_sale > 0) {?>
						<p>Terjual <?=$quantity_sale;?> <?php if($count_comment > 0) {?>• <span class="fa fa-star" style="color:orange;"></span> <?=number_format($rating_value/$count_comment,1)?> (<?=$count_comment;?>  ulasan)<?php } ?></p>
						<?php } ?>
						<hr>
						<h4 class="ps-product__price">
							<?php if ($discount) {?>
							<del><?=$price?></del><br><?=$discount?>
							<?php } else {?>
							<?=$price?>
							<?php }?>
						</h4>
						<!-- <?php if($tax_value && $tax_type == 'Inklusif') {?>
						<p>Harga sudah termasuk PPN <?=format_money($tax_value, 0)?>%</p>
						<?php } elseif($tax_value && $tax_type == 'Eksklusif') {?>
						<p>Harga belum termasuk PPN <?=format_money($tax_value, 0)?>%</p>
						<?php }?> -->
						<div class="ps-product__desc">
							<ul class="nav nav-tabs">
								<li class="nav-item" style="list-style-type: none;"><a class="nav-link active" href="#tab-1" data-toggle="tab" id="default">Deskripsi</a></li>
								<li class="nav-item" style="list-style-type: none;"><a class="nav-link" href="#tab-2" data-toggle="tab">Spesifikasi</a></li>
								<li class="nav-item" style="list-style-type: none;"><a class="nav-link" href="#tab-3" data-toggle="tab">Apa Yang Saya Dapatkan</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane mt-5 active" id="tab-1">
									<?=$description?>
								</div>
								<div class="tab-pane mt-5" id="tab-2">
									<?=$specification?>
								</div>
								<div class="tab-pane mt-5" id="tab-3">
									<?=$whatisinthebox?>
								</div>
							</div>
						</div>
						<div class="ps-product__shopping">
							<?php if ($quantity > 0) {?>
							<figure>
								<div class="form-group--number">
									<button type="button" class="up btn-number" data-type="plus" data-field="quantity"><i class="fa fa-plus"></i></button>
									<button type="button" class="down btn-number" data-type="minus" data-field="quantity"><i class="fa fa-minus"></i></button>
									<input type="text" name="quantity" class="form-control input-number" data-min="<?=(int)$minimum?>" data-max="<?=(int)$quantity?>" value="<?=(int)$minimum?>" placeholder="<?=(int)$minimum?>">
								</div>
							</figure>
							<button type="button" onclick="cart.add('<?=$product_id?>', $('input[name=\'quantity\']').val());" class="ps-btn" style="padding:10px 20px">Tambah ke Keranjang</button>
							<?php } else {?>
							<div class="alert alert-warning">Maaf, saat ini stok sedang kosong!</div>
							<?php }?>
						</div>
						<div class="ps-product__specification">
							<p><strong>Kode Produk:</strong> <?=$sku?></p>
							<p class="categories mb-5"><strong> Kategori:</strong><a href="<?=$category_link?>"><?=$category_name?></a></p>
							<p>
								<button type="button" class="ps-btn ps-btn--outline ps-btn--sm" style="padding:10px 20px">
								<a href="<?=site_url('compare_product')."/".$href_title?>">Bandingkan Produk</a>
								</button>
							</p>
						</div>
						<div class="ps-product__sharing">
							<p>Bagikan ke:</p>
							<div class="sharethis-inline-share-buttons"></div>
						</div>
						<?php if($quantity_sale > 0) {?>
						<br><hr>
						<div class="ps-product__specification"><br>
							<p>ULASAN PRODUK:</p>
							<?php if(count($comments) > 0) {?>
							<?php foreach($comments as $comment) {?>
							<div class="card mb-3">
								<div class="card-body">
									<div class="row">
										<div class="col-xs-1 col-md-1">
										<?php if($comment['image']) {?>
											<div class="pull-left image"><img src="<?=base_url('storage/images/'.$comment['image'])?>" alt="User Photo" class="img-circle" <?php if($mobile) echo 'width="10%"';?>></div>
										<?php } else { ?>
											<div class="pull-left image"><img src="<?=base_url('storage/images/user.png')?>" alt="User Photo" class="img-circle" <?php if($mobile) echo 'width="10%"';?>></div>
										<?php } ?>
										</div>
										<div class="col-xs-2 col-md-2">
											<div class="pull-left info">
												<p><?php if($comment['user_name'] != '') echo $comment['user_name']; else echo $comment['dummy_name'];?></p>
												<p><small><?=date('d M Y H:i',strtotime($comment['rating_date']));?></small></p>
											</div>
										</div>
										<div class="col-xs-2 col-md-2">
											<span class="fa fa-star" <?php if($comment['value'] >= 1) echo "style='color:orange;'";?>></span>
											<span class="fa fa-star" <?php if($comment['value'] >= 2) echo "style='color:orange;'";?>></span>
											<span class="fa fa-star" <?php if($comment['value'] >= 3) echo "style='color:orange;'";?>></span>
											<span class="fa fa-star" <?php if($comment['value'] >= 4) echo "style='color:orange;'";?>></span>
											<span class="fa fa-star" <?php if($comment['value'] >= 5) echo "style='color:orange;'";?>></span>
										</div>
										<div class="col-xs-7 col-md-7">
											<p class="card-text">
												<?=$comment['notes'];?>
											</p><br>
											<?php if($comment['photo'] != '') {?>
											<p class="card-text">
												<div class="thumbnail">
													<a href="<?=base_url('storage/images/'.$comment['photo'])?>">
														<img src="<?=base_url('storage/images/'.$comment['photo'])?>" alt="Photo" style="width:15%">
													</a>
												</div>
											</p>
											<br>
											<?php } ?>
											<?php if($comment['response'] != '') {?>
											<p class="card-text" style="background-color: #efefef;">
												<b>PT Moda Pratama Mandiri</b><br>
												<small><?=date('d M Y H:i',strtotime($comment['response_date']));?></small><br>
												<?=$comment['response'];?>
											</p>
											<?php }?>
										</div>
									</div>
								</div>
							</div>
							<?php }?>
							<?php } else {?>
								<div class="card mb-3">
									<div class="card-body">
										<div class="row">
											<div class="col-xs-12 col-md-6">
												<p class="card-text">
													Belum ada ulasan
												</p>
											</div>
										</div>
									</div>
								</div>
							<?php }?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php if ($related_products && count($related_products) > 1) {?>
		<div class="ps-section--default">
			<div class="ps-section__header">
				<h3>Produk Terkait</h3>
				<p>Mungkin anda tertarik dengan produk berikut ini</p>
			</div>
			<div class="ps-section__content">
				<div class="ps-carousel--nav owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="10000" data-owl-gap="30" data-owl-nav="true" data-owl-dots="true" data-owl-item="6" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3" data-owl-item-lg="4" data-owl-item-xl="5" data-owl-duration="1000" data-owl-mousedrag="on">
					<?php foreach ($related_products as $product) {?>
					<div class="ps-product">
						<div class="ps-product__thumbnail">
							<a href="<?=$product['href']?>"><img src="<?=$product['thumb']?>" alt="<?=$product['name']?>"></a>
							<ul class="ps-product__actions">
								<li><a data-toggle="tooltip" data-placement="top" title="Beli Item Ini" onClick="cart.add(<?=$product['product_id']?>);"><i class="icon-bag2"></i></a></li>
								<li><a data-toggle="tooltip" data-placement="top" title="Quick View" onClick="quickView(<?=$product['product_id']?>);"><i class="icon-eye"></i></a></li>
								<li><a href="<?=$product['href']?>" data-toggle="tooltip" data-placement="top" title="Lihat Detail"><i class="icon-link"></i></a></li>
								<li><a href="<?=site_url('compare_product')."/".$product['href_title']?>" data-toggle="tooltip" data-placement="top" title="Bandingkan Produk"><i class="icon-desktop"></i></a></li>
							</ul>
						</div>
						<div class="ps-product__container">
							<div class="ps-product__content">
								<a class="ps-product__title" href="<?=$product['href']?>"><?=$product['name']?></a>
								<p class="ps-product__price">
									<?php if ($product['discount']) {?>
									<small><del class="text-muted"><?=$product['price']?></del></small><br>
									<?=$product['discount']?>
									<?php } else {?>
									<?=$product['price']?>
									<?php }?>
								</p>
								<p><span <?php if($product['quantity_sale'] == 0) echo 'style="color:white;"'?>>Terjual <?=$product['quantity_sale'];?></span> <?php if($product['count_comment'] > 0) {?>• <span class="fa fa-star" style="color:orange;"></span> <?=number_format($product['rating_value']/$product['count_comment'],1)?><?php } ?></p>
								<br><br>
							</div>
							<div class="ps-product__content hover">
								<a class="ps-product__title" href="<?=$product['href']?>"><?=$product['name']?></a>
								<p class="ps-product__price">
									<?php if ($product['discount']) {?>
									<small><del class="text-muted"><?=$product['price']?></del></small><br>
									<?=$product['discount']?>
									<?php } else {?>
									<?=$product['price']?>
									<?php }?>
								</p>
								<p><span <?php if($product['quantity_sale'] == 0) echo 'style="color:white;"'?>>Terjual <?=$product['quantity_sale'];?></span> <?php if($product['count_comment'] > 0) {?>• <span class="fa fa-star" style="color:orange;"></span> <?=number_format($product['rating_value']/$product['count_comment'],1)?><?php } ?></p>
							</div>
						</div>
					</div>
					<?php }?>
				</div>
			</div>
		</div>
		<?php }?>
		<?php if ($product_histories && count($product_histories) > 1) {?>
			<div class="ps-section--default">
				<div class="ps-section__header">
					<h3>Terakhir Dilihat</h3>
					<p>Mungkin anda tertarik untuk melihat kembali produk berikut ini</p>
				</div>
				<div class="ps-section__content">
					<div class="ps-carousel--nav owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="10000" data-owl-gap="30" data-owl-nav="true" data-owl-dots="true" data-owl-item="6" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3" data-owl-item-lg="4" data-owl-item-xl="5" data-owl-duration="1000" data-owl-mousedrag="on">
						<?php foreach ($product_histories as $product) {?>
						<div class="ps-product">
							<div class="ps-product__thumbnail">
								<a href="<?=$product['href']?>"><img src="<?=$product['thumb']?>" alt="<?=$product['name']?>"></a>
								<ul class="ps-product__actions">
									<li><a data-toggle="tooltip" data-placement="top" title="Beli Item Ini" onClick="cart.add(<?=$product['product_id']?>);"><i class="icon-bag2"></i></a></li>
									<li><a data-toggle="tooltip" data-placement="top" title="Quick View" onClick="quickView(<?=$product['product_id']?>);"><i class="icon-eye"></i></a></li>
									<li><a href="<?=$product['href']?>" data-toggle="tooltip" data-placement="top" title="Lihat Detail"><i class="icon-link"></i></a></li>
									<li><a href="<?=site_url('compare_product')."/".$product['href_title']?>" data-toggle="tooltip" data-placement="top" title="Bandingkan Produk"><i class="icon-desktop"></i></a></li>
								</ul>
							</div>
							<div class="ps-product__container">
								<div class="ps-product__content">
									<a class="ps-product__title" href="<?=$product['href']?>"><?=$product['name']?></a>
									<p class="ps-product__price">
										<?php if ($product['discount']) {?>
										<small><del class="text-muted"><?=$product['price']?></del></small><br>
										<?=$product['discount']?>
										<?php } else {?>
										<?=$product['price']?>
										<?php }?>
									</p>
									<p><span <?php if($product['quantity_sale'] == 0) echo 'style="color:white;"'?>>Terjual <?=$product['quantity_sale'];?></span> <?php if($product['count_comment'] > 0) {?>• <span class="fa fa-star" style="color:orange;"></span> <?=number_format($product['rating_value']/$product['count_comment'],1)?><?php } ?></p>
									<br><br>
								</div>
								<div class="ps-product__content hover">
									<a class="ps-product__title" href="<?=$product['href']?>"><?=$product['name']?></a>
									<p class="ps-product__price">
										<?php if ($product['discount']) {?>
										<small><del class="text-muted"><?=$product['price']?></del></small><br>
										<?=$product['discount']?>
										<?php } else {?>
										<?=$product['price']?>
										<?php }?>
									</p>
									<p><span <?php if($product['quantity_sale'] == 0) echo 'style="color:white;"'?>>Terjual <?=$product['quantity_sale'];?></span> <?php if($product['count_comment'] > 0) {?>• <span class="fa fa-star" style="color:orange;"></span> <?=number_format($product['rating_value']/$product['count_comment'],1)?><?php } ?></p>
								</div>
							</div>
						</div>
						<?php }?>
					</div>
				</div>
			</div>
			<?php }?>
	</div>
</div>
<script>
	$('.btn-number').click(function(e){
		e.preventDefault();
		fieldName = $(this).attr('data-field');
		type = $(this).attr('data-type');
		var input = $("input[name='"+fieldName+"']");
		var currentVal = parseInt(input.val());
		if (!isNaN(currentVal)) {
			if (type == 'minus') {	
				if(currentVal > input.attr('data-min')) {
					input.val(currentVal - 1).change();
				} 
				
				if(parseInt(input.val()) == input.attr('data-min')) {
					$(this).attr('disabled', true);
				}
				
				$('button[data-type=\'plus\']').attr('disabled', false);
			} else if (type == 'plus') {
				if(currentVal < input.attr('data-max')) {
					input.val(currentVal + 1).change();
				}
				
				if(parseInt(input.val()) == input.attr('data-max')) {
					$(this).attr('disabled', true);
				}
				
				$('button[data-type=\'minus\']').attr('disabled', false);
			}
		} else {
			input.val(0);
		}
	});
	
	$('.input-number').keydown(function (e) {
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 || (e.keyCode == 65 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39)) {
			return;
		}
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});
</script>