<div class="ps-product">
		<div class="ps-product__thumbnail"><a href="<?=$product['href']?>"><img src="<?=$product['thumb']?>" alt=""></a>
			<?php if ($product['discount_percent']) {?>
			<div class="ps-product__badge">Disc. <?=$product['discount_percent']?></div>
			<?php }?>
			<ul class="ps-product__actions" style="width:100%;padding-left:8px">
			<?php if($product['quantity'] > 0) {?>
				<li><a data-toggle="tooltip" data-placement="top" title="Beli Item Ini" onClick="cart.add(<?=$product['product_id']?>);"><i class="icon-bag2"></i></a></li>
			<?php }?>
				<li><a data-toggle="tooltip" data-placement="top" title="Quick View" onClick="quickView(<?=$product['product_id']?>);"><i class="icon-eye"></i></a></li>
				<li><a href="<?=$product['href']?>" data-toggle="tooltip" data-placement="top" title="Lihat Detail"><i class="icon-link"></i></a></li>
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
				<p><b>Deskripsi:</b></p>
				<p><?=$product['description'];?></p>
				<p><b>Spesifikasi:</b></p>
				<p><?=$product['specification'];?></p>
				<p><b>Apa Yang Saya Dapatkan:</b></p>
				<p><?=$product['whatisinthebox'];?></p>
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
				<p><b>Deskripsi:</b></p>
				<p><?=$product['description'];?></p>
				<p><b>Spesifikasi:</b></p>
				<p><?=$product['specification'];?></p>
				<p><b>Apa Yang Saya Dapatkan:</b></p>
				<p><?=$product['whatisinthebox'];?></p>
				<br><br>
			</div>
		</div>
	</div>
</div>