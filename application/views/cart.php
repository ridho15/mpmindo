<div class="ps-page--simple">
	<div class="ps-breadcrumb">
		<div class="ps-container">
			<ul class="breadcrumb">
				<li><a href="<?=site_url()?>">Home</a></li>
				<li>Keranjang Belanja</li>
			</ul>
		</div>
	</div>
	<div class="ps-section--shopping ps-shopping-cart" style="padding-top:20px;">
		<div class="ps-container">
			<?php if ($products) {?>
			<div class="ps-section__content">
				<div class="row">
					<div class="col-lg-9">
						<?php if ($success) {?>
						<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?=$success?></div>
						<?php }?>
						<?php if ($error) {?>
						<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?=$error?></div>
						<?php }?>
						<?=form_open($action, 'id="form-cart"')?>
						<div class="table-responsive">
							<table class="table ps-table--shopping-cart">
								<thead>
									<tr>
										<th>Product name</th>
										<th>PRICE</th>
										<th>QUANTITY</th>
										<th>TOTAL</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($products as $product) {?>
									<tr>
										<td>
											<div class="ps-product--cart">
												<div class="ps-product__thumbnail"><a href="<?=$product['href']?>"><img src="<?=$product['thumb']?>" alt=""></a></div>
												<div class="ps-product__content">
													<a href="<?=$product['href']?>"><?=$product['name']?></a>
													<?php if (!$product['stock']) {?>
													<span class="text-danger">***</span>
													<?php }?>
													<?php if ($product['tax_value'] && $product['tax_type'] == 'Inklusif') {?>
													<br><small>Termasuk PPN <?=$product['tax_value']?>%</small>
													<?php }?>
												</div>
											</div>
										</td>
										<td class="price"><?=$product['price']?></td>
										<td>
											<div class="form-group--number">
												<button type="button" class="up btn-number" data-type="plus" data-field="quantity[<?=$product['key']?>]">+</button>
												<button type="button" class="down btn-number" data-type="minus" data-field="quantity[<?=$product['key']?>]">-</button>
												<input type="text" name="quantity[<?=$product['key']?>]" data-key="<?=$product['key']?>" class="form-control input-number" data-min="1" data-max="100" value="<?=$product['quantity']?>">
											</div>
										</td>
										<td><?=$product['total']?></td>
										<td><a onclick="cart.remove(<?=$product['product_id']?>);"><i class="icon-cross"></i></a></td>
									</tr>
									<?php }?>
								</tbody>
							</table>
						</div>
						<?=form_close()?>
					</div>
					<div class="col-lg-3">
						<div class="ps-block--shopping-total">
							<?php foreach ($totals as $key => $total) { ?>
							<div class="ps-block__header">
							<?php if ($key+1 == count($totals)) {?>
							<p><b><?=$total['title']?></b><span><b><?=$total['text']?></b></span></p>
							<?php } else {?>
							<p><?=$total['title']?><span><?=$total['text']?></span></p>
							<?php }?>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="ps-section__cart-actions"><a class="ps-btn ps-btn--outline" href="<?=site_url('category/'.$first_category['slug'])?>"><i class="icon-arrow-left"></i> Belanja Lagi</a><a class="ps-btn" href="<?=site_url('checkout')?>">Checkout <i class="icon-arrow-right"></i></a></div>
					</div>
				</div>
			</div>
			<?php } else {?>
			<div class="ps-section__header pt-5">
                <h1>Keranjang Kosong</h1>
                <p>Keranjang belanja kamu belum terisi produk sama sekali</p>
                <a class="ps-btn mt-10" href="<?=site_url('category/'.$first_category['slug'])?>"><i class="icon-arrow-left"></i> Belanja Lagi</a>
            </div>
			<br><br><br><br><br><br><br><br><br>
			<?php } ?>
		</div>
	</div>
</div>

<?php if ($products) {?>
<script type="text/javascript">
	$('.btn-number').click(function(e){
		e.preventDefault();
		fieldName = $(this).attr('data-field');
		type = $(this).attr('data-type');
		var input = $("input[name='"+fieldName+"']");
		var currentVal = parseInt(input.val());
		if (!isNaN(currentVal)) {
			if(type == 'minus') {	
				if(currentVal > input.attr('data-min')) {
					input.val(currentVal - 1).change();
				} 
				
				if(parseInt(input.val()) == input.attr('data-min')) {
					$(this).attr('disabled', true);
				}
			} else if(type == 'plus') {
				if(currentVal < input.attr('data-max')) {
					input.val(currentVal + 1).change();
				}
				
				if(parseInt(input.val()) == input.attr('data-max')) {
					$(this).attr('disabled', true);
				}
			}
		} else {
			input.val(0);
		}
	});
	
	$('.input-number').focusin(function(){
	   $(this).data('oldValue', $(this).val());
	});
	
	$('.input-number').change(function() {
		minValue = parseInt($(this).attr('data-min'));
		maxValue = parseInt($(this).attr('data-max'));
		productId = parseInt($(this).attr('data-key'));
		valueCurrent = parseInt($(this).val());
		
		name = $(this).attr('name');
		
		if(valueCurrent >= minValue) {
			$(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled');
			cart.update(productId, valueCurrent);
		} else {
			alert('Sorry, the minimum value was reached');
			$(this).val($(this).data('oldValue'));
		}
		
		if(valueCurrent <= maxValue) {
			$(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled');
			cart.update(productId, valueCurrent);
		} else {
			alert('Sorry, the maximum value was reached');
			$(this).val($(this).data('oldValue'));
		}
	});
	
	$(".input-number").keydown(function (e) {
		// Allow: backspace, delete, tab, escape, enter and .
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
			 // Allow: Ctrl+A
			(e.keyCode == 65 && e.ctrlKey === true) || 
			 // Allow: home, end, left, right
			(e.keyCode >= 35 && e.keyCode <= 39)) {
				 // let it happen, don't do anything
				 return;
		}
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});
	
	$('#button-checkout').on('click', function() {
		$(this).button('loading');
		window.location = "<?=site_url('checkout')?>";
	});
</script>
<?php } ?>