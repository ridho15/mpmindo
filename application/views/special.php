<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) {?>
			<li><a href="<?=$breadcrumb['href']?>"><?=$breadcrumb['text']?></a></li>
			<?php }?>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-md-3">
		<div class="form-actions">
			<div class="form-group">
				<label>Sortir</label>
				<select class="form-control sorting" onchange="window.location = $(this).find('option:selected').val();">
					<?php foreach ($sorts as $sort_data) {?>
					<?php if ($sort_data['value'] == $sort.'-'.$order) {?>
					<option value="<?=$sort_data['href']?>" selected="selected"><?=$sort_data['text']?></option>
					<?php } else {?>
					<option value="<?=$sort_data['href']?>"><?=$sort_data['text']?></option>
					<?php }?>
					<?php }?>
				</select>
			</div>
			<div class="form-group">
				<label>Pilih Provinsi</label>
				<select name="province_id" class="form-control province">
					<?php foreach ($provinces as $province) {?>
					<?php if ($province['province_id'] == $province_id) {?>
					<option value="<?=$province['province_id']?>" selected="selected"><?=$province['name']?></option>
					<?php } else {?>
					<option value="<?=$province['province_id']?>"><?=$province['name']?></option>
					<?php }?>
					<?php }?>
				</select>
			</div>
			<div class="form-group">
				<label>Pilih Kota</label>
				<select name="city_id" class="form-control" onchange="window.location = $('.sorting option:selected').val()+'&province_id='+$('.province option:selected').val()+'&city_id='+ $(this).find('option:selected').val();">
				</select>
			</div>
		</div>
	</div>
	<div class="col-md-9">
		<div class="row">
			<?php foreach ($products as $product) {?>
			<div class="product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="product-thumb">
					<?php if ($product['wholesaler']) {?>
					<span class="grosir">Grosir</span>
					<?php } elseif ($product['discount_percent']) {?>
					<span class="sale"><?=$product['discount_percent']?></span>
					<?php }?>
					<div class="image"><a href="<?=$product['href']?>"><img src="<?=$product['thumb']?>" alt="<?=$product['name']?>" title="<?=$product['name']?>" class="img-responsive" /></a></div>
					<div>
						<div class="caption">
						<div class="title"><a href="<?=$product['href']?>"><?=$product['name']?></a></div>
							<?php if ($product['discount']) {?>
							<p class="price"><span class="price-old"><small><?=$product['price']?></small></span> <span class="price-new"><?=$product['discount']?></span></p>
							<?php } else {?>
							<p class="price"><span class="price-new"><?=$product['price']?></span></p>
							<?php }?>
						</div>
						<div class="seller-info">
							<p><strong><?=$product['store']?></strong><br>
							<?=$product['location']?></p>
							<button type="button" onclick="cart.add('<?=$product['product_id']?>', '1');" class="btn btn-primary btn-block"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md">Beli</span></button>
						</div>
					</div>
				</div>
			</div>
			<?php }?>
		</div>
		<?=$pagination?>
	</div>
</div>
<script type="text/javascript">
	$('select[name=\'province_id\']').on('change', function() {
		$.ajax({
			url: "<?=site_url('location/province')?>",
			data: 'province_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'province_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
			},
			complete: function() {
				$('.fa-spin').remove();
			},
			success: function(json) {
				html = '<option value="">-- Pilih --</option>';

				if (json['cities'] && json['cities'] != '') {
					for (i = 0; i < json['cities'].length; i++) {
						html += '<option value="' + json['cities'][i]['city_id'] + '"';

						if (json['cities'][i]['city_id'] == '<?=$city_id?>') {
							html += ' selected="selected"';
						}

						html += '>' + json['cities'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0" selected="selected">Semua Kota</option>';
				}

				$('select[name=\'city_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
	
	$('select[name=\'province_id\']').trigger('change');
</script>