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
					<h1>Bandingkan Produk</h1>
				</div>
				<!--<div class="alert alert-danger" role="alert"><b>INFO: Untuk sementara kami tidak melayani pembelian online karena libur lebaran 2022. Kami akan melayani Anda kembali tanggal 9 Mei 2022. Terima kasih atas perhatiannya.</b></div><br>-->
				<div class="ps-shopping ps-tab-root">
					<div id="notification"></div>
					<div class="ps-tabs">
						<div>
							<div class="ps-shopping-product">
								<div class="row">
									<div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
										<!-- <input type="text" class="form-control" value="Produk" readonly/><br> -->
										<input type="text" class="form-control" value="<?=$product['name']?>" name="compare1" autocomplete="off" readonly/><br>
										<div id="result_compare1">
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
									</div>
									<div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
										<!-- <select name="optioncompare2" class="form-control">
											<option value="1">Pilih Produk</option>
											<option value="2">Ketik Produk</option>
										</select><br> -->
										<div id="displaycomparepilih2">
											<select name="optionproduct2" class="form-control">
												<option value="">-- Pilih Produk --</option>
											<?php foreach($product_names as $product) {?>
												<option value="<?=$product['slug'];?>"><?=$product['name'];?></option>
											<?php } ?>
											</select><br>
										</div>
										<div id="displaycompareketik2">
											<input type="text" class="form-control" name="compare2" autocomplete="off" placeholder="Ketik Nama Produk Disini"/><br>
										</div>
										<div id="result_compare2">
										</div>
									</div>
									<div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
										<!-- <select name="optioncompare3" class="form-control">
											<option value="1">Pilih Produk</option>
											<option value="2">Ketik Produk</option>
										</select><br> -->
										<div id="displaycomparepilih3">
											<select name="optionproduct3" class="form-control">
												<option value="">-- Pilih Produk --</option>
											<?php foreach($product_names as $product) {?>
												<option value="<?=$product['slug'];?>"><?=$product['name'];?></option>
											<?php } ?>
											</select><br>
										</div>
										<div id="displaycompareketik3">
											<input type="text" class="form-control" name="compare3" autocomplete="off" placeholder="Ketik Nama Produk Disini"/><br>
										</div>
										<div id="result_compare3">
										</div>
									</div>
									<div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
										<!-- <select name="optioncompare4" class="form-control">
											<option value="1">Pilih Produk</option>
											<option value="2">Ketik Produk</option>
										</select><br> -->
										<div id="displaycomparepilih4">
											<select name="optionproduct4" class="form-control">
												<option value="">-- Pilih Produk --</option>
											<?php foreach($product_names as $product) {?>
												<option value="<?=$product['slug'];?>"><?=$product['name'];?></option>
											<?php } ?>
											</select><br>
										</div>
										<div id="displaycompareketik4">
											<input type="text" class="form-control" name="compare4" autocomplete="off" placeholder="Ketik Nama Produk Disini"/><br>
										</div>
										<div id="result_compare4">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?=base_url('assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.min.js')?>"></script>
<script type="text/javascript">
	$('#displaycompareketik2').hide();
	$('#displaycompareketik3').hide();
	$('#displaycompareketik4').hide();

	$('select[name=\'optioncompare2\']').on('change', function() {
		if($('select[name=\'optioncompare2\'] option:selected').val() == 1) {
			$('#displaycomparepilih2').show();
			$('#displaycompareketik2').hide();
		}
		else if($('select[name=\'optioncompare2\'] option:selected').val() == 2) {
			$('#displaycomparepilih2').hide();
			$('#displaycompareketik2').show();
		}
	});
	$('select[name=\'optioncompare3\']').on('change', function() {
		if($('select[name=\'optioncompare3\'] option:selected').val() == 1) {
			$('#displaycomparepilih3').show();
			$('#displaycompareketik3').hide();
		}
		else if($('select[name=\'optioncompare3\'] option:selected').val() == 2) {
			$('#displaycomparepilih3').hide();
			$('#displaycompareketik3').show();
		}
	});
	$('select[name=\'optioncompare4\']').on('change', function() {
		if($('select[name=\'optioncompare4\'] option:selected').val() == 1) {
			$('#displaycomparepilih4').show();
			$('#displaycompareketik4').hide();
		}
		else if($('select[name=\'optioncompare4\'] option:selected').val() == 2) {
			$('#displaycomparepilih4').hide();
			$('#displaycompareketik4').show();
		}
	});

	$('select[name=\'optionproduct2\']').on('change', function() {
		if($('select[name=\'optionproduct2\'] option:selected').val() == "") {
			$('#result_compare2').html("");
		}
		else {
			$.ajax({
				url : "<?=site_url('compare_product/find_product')?>",
				data: 'slug='+$('select[name=\'optionproduct2\'] option:selected').val(),
				dataType: 'json',
				success: function(json) {
					$('#result_compare2').html(json['content']);
				}
			});
		}
	});
	$('select[name=\'optionproduct3\']').on('change', function() {
		if($('select[name=\'optionproduct3\'] option:selected').val() == "") {
			$('#result_compare3').html("");
		}
		else {
			$.ajax({
				url : "<?=site_url('compare_product/find_product')?>",
				data: 'slug='+$('select[name=\'optionproduct3\'] option:selected').val(),
				dataType: 'json',
				success: function(json) {
					$('#result_compare3').html(json['content']);
				}
			});
		}
	});
	$('select[name=\'optionproduct4\']').on('change', function() {
		if($('select[name=\'optionproduct4\'] option:selected').val() == "") {
			$('#result_compare4').html("");
		}
		else {
			$.ajax({
				url : "<?=site_url('compare_product/find_product')?>",
				data: 'slug='+$('select[name=\'optionproduct4\'] option:selected').val(),
				dataType: 'json',
				success: function(json) {
					$('#result_compare4').html(json['content']);
				}
			});
		}
	});

	autoComplete(2); autoComplete(3); autoComplete(4);
	function autoComplete(counter) {
		$('input[name=\'compare'+counter+'\']').typeahead({
			source: function(query, process) {
				$.ajax({
					url: "<?=site_url('compare_product/auto_complete')?>",
					data: 'filter_name=' + query ,
					type: 'get',
					dataType: 'json',
					success: function(json) {
						products = [];
						map = {};
						$.each(json, function(i, product) {
							map[product.name] = product;
							products.push(product.name);
						});
						process(products);
					}
				});
			},
			updater: function (item) {
				$.ajax({
					url : "<?=site_url('compare_product/find_product')?>",
					data: 'slug='+map[item].slug,
					dataType: 'json',
					success: function(json) {
						$('#result_compare'+counter).html(json['content']);
					}
				});
			},
			minLength: 1
		});
	}
</script>