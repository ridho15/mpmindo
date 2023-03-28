<div class="modal fade" id="product-quickview" tabindex="-1" role="dialog" aria-labelledby="product-quickview" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content pb-5"><span class="modal-close" data-dismiss="modal"><i class="icon-cross2"></i></span>
			<article class="ps-product--detail ps-product--fullwidth ps-product--quickview">
				<div class="ps-product__header">
					<div class="ps-product__thumbnail" data-vertical="false">
						<div class="ps-product__images" data-arrow="true">
							<div class="item"><img src="<?=$thumb?>" alt=""></div>
						</div>
					</div>
					<div class="ps-product__info">
						<h1><?=$name?></h1>
						<div class="ps-product__meta pb-2">
							<a href="<?=$category_link?>"><?=$category?></a>
						</div>
						<h4 class="ps-product__price">
							<?php if ($discount) {?>
							<del><?=$price?></del><br><?=$discount?>
							<?php } else {?>
							<?=$price?>
							<?php }?>
						</h4>
						<?php if ($tax_value && $tax_type == 'Inklusif') {?>
						<p>Harga sudah termasuk PPN <?=format_money($tax_value, 0)?>%</p>
						<?php }?>
						<div class="ps-product__desc pr-10">
							<p><span <?php if($quantity_sale == 0) echo 'style="color:white;"'?>>Terjual <?=$quantity_sale;?></span> <?php if($count_comment > 0) {?>• <span class="fa fa-star" style="color:orange;"></span> <?=number_format($rating_value/$count_comment,1)?><?php } ?></p>
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
						<?php if($quantitystok > 0) {?>
							<a class="ps-btn ps-btn--black" onclick="cart.add(<?=$product_id?>);">Tambah Ke Keranjang</a>
						<?php } ?>
							<a class="ps-btn" href="<?=$href?>">Lihat Detil</a>
						</div>
					</div>
				</div>
			</article>
		</div>
	</div>
</div>