<div class="page-title">
	<div>
		<h1><?=$template['title']?></h1>
	</div>
	<div class="btn-group">
		<a onclick="window.location = '<?=admin_url('order')?>';" class="btn btn-default"><i class="fa fa-reply"></i> Batal</a>
		<a id="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</a>
	</div>
</div>
<div class="row">
	<div id="notification"></div>
	<div class="col-md-12">
	<div class="card">
	<div class="card-body">
		<?=form_open($action, 'role="form" id="form-order" class="form-horizontal"')?>
		<input type="hidden" name="order_id" value="<?=$order_id?>">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab-1" data-toggle="tab">Data Pelanggan</a></li>
			<li><a href="#tab-2" data-toggle="tab">Item</a></li>
			<li><a href="#tab-3" data-toggle="tab">Alamat Pengiriman</a></li>
			<li><a href="#tab-4" data-toggle="tab">Total</a></li>
		</ul>
		<div class="tab-content" style="padding-top:20px;">
			<div class="tab-pane active" id="tab-1">
				<div class="form-group">
					<label class="col-sm-3 control-label">Nama</label>
					<div class="col-sm-6">
						<div class="input-group">
							<?php if ($user_id) {?>
							<input type="text" name="user_name" value="<?=$user_name?>" class="form-control" readonly="readonly">
							<?php } else {?>
							<input type="text" name="user_name" value="" class="form-control">
							<?php }?>
						</div>
						<input type="hidden" name="user_id" value="<?=$user_id?>" class="form-control" autocomplete="off">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Email</label>
					<div class="col-sm-6">
						<input type="text" name="user_email" value="<?=$user_email?>" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">No. Telepon</label>
					<div class="col-sm-6">
						<input type="text" name="user_telephone" value="<?=$user_telephone?>" class="form-control">
					</div>
				</div>
			</div>
			<div class="tab-pane" id="tab-2">
				
			</div>
			<div class="tab-pane" id="tab-3">
				<div class="form-group">
					<label class="col-sm-3 control-label">Pilih Alamat</label>
					<div class="col-sm-9">
						<select name="user_address_id" class="form-control">
							<?php foreach ($addresses as $address) {?>
							<option value="<?=$address['address_id']?>"><?=$address['address']?></option>
							<?php }?>
						</select>
					</div>
				</div><hr>
				<div id="shipping-address">
				<div class="form-group">
					<label class="control-label col-sm-3">Alamat</label>
					<div class="col-sm-6">
						<input type="text" class="form-control" name="user_address" value="<?=$user_address?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Provinsi</label>
					<div class="col-sm-6">
						<select name="user_province_id" class="form-control">
							<?php foreach ($provinces as $province) {?>
							<?php if ($province['province_id'] == $user_province_id) {?>
							<option value="<?=$province['province_id']?>" selected="selected"><?=$province['name']?></option>
							<?php } else {?>
							<option value="<?=$province['province_id']?>"><?=$province['name']?></option>
							<?php }?>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Kabupaten/Kota</label>
					<div class="col-sm-6">
						<select name="user_city_id" class="form-control user_city"></select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Kecamatan</label>
					<div class="col-sm-6">
						<select name="user_subdistrict_id" class="form-control user_subdistrict"></select>
					</div>
				</div>
				</div>
			</div>
			<div class="tab-pane" id="tab-4">
				<div class="table-responsive">
					<table class="table table-bordered" id="product-table">
						<thead>
							<tr>
								<th>Produk</th>
								<th>Qty</th>
								<th class="text-right">@Harga</th>
								<th class="text-right">Subtotal</th>
								<th class="text-right"></th>
							</tr>
						</thead>
						<tbody>
							<?php $order_product_row = 0;?>
							<?php foreach ($products as $product) {?>
							<tr id="product-<?=$order_product_row?>">
								<input type="hidden" name="order_product[<?=$order_product_row?>][order_product_id]" value="<?=$product['order_product_id']?>">
								<input type="hidden" name="order_product[<?=$order_product_row?>][product_id]" value="<?=$product['product_id']?>">
								<td><input type="hidden" name="order_product[<?=$order_product_row?>][name]" value="<?=$product['name']?>"><?=$product['name']?></td>
								<td><input type="hidden" name="order_product[<?=$order_product_row?>][quantity]" value="<?=$product['quantity']?>"><?=$product['quantity']?></td>
								<td class="text-right"><input type="hidden" name="order_product[<?=$order_product_row?>][price]" value="<?=$product['price']?>"><?=$product['price']?></td>
								<td class="text-right"><input type="hidden" name="order_product[<?=$order_product_row?>][total]" value="<?=$product['total']?>"><?=$product['total']?></td>
								<td class="text-right"><a class="btn btn-xs btn-danger" onclick="$('#product-<?=$order_product_row?>').remove(); update();"><i class="fa fa-remove"></i></a></td>
							</tr>
							<?php $order_product_row++;?>
							<?php }?>
						</tbody>
						<tfoot>
							<?php $order_total_row = 0;?>
							<?php foreach ($totals as $total) {?>
							<tr>
								<input type="hidden" name="order_total[<?=$order_total_row?>][order_total_id]" value="<?=$total['order_total_id']?>">
								<input type="hidden" name="order_total[<?=$order_total_row?>][code]" value="<?=$total['code']?>">
								<input type="hidden" name="order_total[<?=$order_total_row?>][title]" value="<?=$total['title']?>">
								<input type="hidden" name="order_total[<?=$order_total_row?>][text]" value="<?=$total['text']?>">
								<input type="hidden" name="order_total[<?=$order_total_row?>][value]" value="<?=$total['value']?>">
								<input type="hidden" name="order_total[<?=$order_total_row?>][sort_order]" value="<?=$total['sort_order']?>">
								<th class="text-right" colspan="3"><?=$total['title']?></th>
								<th class="text-right"><?=$total['text']?></th>
								<th></th>
							</tr>
							<?php $order_total_row++;?>
							<?php }?>
						</tfoot>
					</table>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Tambah Produk</label>
					<div class="col-sm-9">
						<input type="text" name="product" class="form-control" autocomplete="off"><input type="hidden" name="product_id" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Quantity</label>
					<div class="col-sm-2">
						<input type="text" class="form-control" name="quantity">
					</div>
					<div class="col-sm-3">
						<a class="btn btn-info" onclick="update(this);"><i class="fa fa-plus"></i> Tambah Produk</a>
					</div>
				</div><hr>
				<div class="form-group">
					<label class="col-sm-3 control-label">Metode Pembayaran</label>
					<div class="col-sm-6">
						<select name="payment" class="form-control">
							<option value="">-- Pilih --</option>
							<?php if ($payment_code) { ?>
							<option value="<?=$payment_code?>" selected="selected"><?=$payment_method?></option>
							<?php } ?>
						</select>
						<input type="hidden" name="payment_method" value="<?=$payment_method?>" />
						<input type="hidden" name="payment_code" value="<?=$payment_code?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Metode Pengiriman</label>
					<div class="col-sm-8" id="shipping-method">
						
					</div>
				</div>
				<hr>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status Order</label>
					<div class="col-sm-6">
						<select name="order_status_id" class="form-control">
							<option value=""> -- None --</option>
							<?php foreach ($order_statuses as $order_status) { ?>
							<?php if ($order_status['order_status_id'] == $order_status_id) { ?>
							<option value="<?=$order_status['order_status_id']?>" selected="selected"><?=$order_status['name']?></option>
							<?php } else { ?>
							<option value="<?=$order_status['order_status_id']?>"><?=$order_status['name']?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Keterangan</label>
					<div class="col-sm-9">
						<textarea class="form-control" name="comment"><?=$comment?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Notifikasi</label>
					<div class="col-sm-3">
						<div class="checkbox">
							<label><input type="checkbox" name="notify" value="1"> Notifikasi ke user</label>
						</div>
					</div>
				</div>
			</div>
		</div>
		</form>
	</div>
	</div>
	</div>
</div>
<div id="modal"></div>
<script src="<?=site_url('assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.js')?>"></script>
<script type="text/javascript">
	$('#submit').bind('click', function() {
		var $this = $(this);
		$.ajax({
			url: $('#form-order').attr('action'),
			data: $('#form-order').serialize(),
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				$this.attr('disabled', true);
				$this.append('<span class="wait"> <i class="fa fa-refresh fa-spin"></i></span>');
			},
			complete: function() {
				$this.attr('disabled', false);
				$('.wait').remove();
			},
			success: function(json) {
				$('.form-group').removeClass('has-error');
				$('table tr').removeClass('has-error');
				$('.text-danger').remove();
				$('.alert-danger').remove();
				
				if (json['error']) {
					$('#notification').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+json['error']+'</div>');
				} else if (json['errors']) {
					for (i in json['errors']) {
						$('input[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('select[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('input[name=\''+i+'\']').after('<span class="text-danger">' + json['errors'][i] + '</span>');
						$('select[name=\''+i+'\']').after('<span class="text-danger">' + json['errors'][i] + '</span>');	
					}
				} else if (json['success']){
					window.location = "<?=admin_url('order')?>";
				}
			}
		});
	});
	
	$('input[name=\'user_name\']').typeahead({
		source: function(query, process) {
			$.ajax({
				url: "<?=admin_url('users/autocomplete')?>",
				data: 'name=' + query,
				type: 'get',
				dataType: 'json',
				success: function(json) {
					users = [];
					map = {};
					$.each(json, function(i, user) {
						map[user.name] = user;
						users.push(user.name);
					});
					process(users);
				}
			});
			
			$('input[name=\'user_id\']').val('');
		},
		updater: function (item) {
			$('input[name=\'user_id\']').val(map[item].user_id);
			$('input[name=\'user_name\']').val(map[item].name);
			$('input[name=\'user_email\']').val(map[item].email);
			$('input[name=\'user_telephone\']').val(map[item].telephone);
			
			$.ajax({
				url : "<?=admin_url('users/get_addresses')?>",
				data: 'user_id='+map[item].user_id,
				dataType: 'json',
				beforeSend: function() {
					$('.card').append('<div class="overlay" style="z-index:999;"><div class="m-loader mr-20"><svg class="m-circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"/></svg></div><h3 class="l-text">Loading</h3></div>');
				},
				complete: function() {
					$('.overlay').remove();
				},
				success: function(json) {
					if (json.length > 0) {
						html = '';
						html = '<option value="0">Baru</option>';
						for (i in json) {
							html += '<option value="'+json[i]['address_id']+'">'+json[i]['address']+', '+json[i]['city']+' - '+json[i]['province']+'</option>'; 
						}
						
						$('select[name=\'user_address_id\']').html(html);
					}
				}
			});
			
			return map[item].name;
		},
		minLength: 1
	});
	
	var user_province_id = "<?=$user_province_id?>";
	var user_city_id = "<?=$user_city_id?>";
	var user_subdistrict_id = "<?=$user_subdistrict_id?>";
	
	$('select[name=\'user_address_id\']').bind('change', function() {
		$.ajax({
			url : "<?=admin_url('address/get_address')?>",
			data: 'address_id=' + this.value,
			dataType: 'json',
			success: function(json) {
				if (json != '') {
					$('input[name=\'user_address\']').val(json['address']);
					$('input[name=\'user_postcode\']').val(json['postcode']);
					$('select[name=\'user_province_id\']').val(json['province_id']);
					$('select[name=\'user_city_id\']').val(json['city_id']);
					$('select[name=\'user_subdistrict_id\']').val(json['subdistrict_id']);
					
					user_province_id = json['province_id'];
					user_city_id = json['city_id'];
					user_subdistrict_id = json['subdistrict_id'];
					
					$('select[name=\'user_province_id\']').trigger('change');
				}
			}
		});
	});
	
	$('select[name=\'user_province_id\']').on('change', function() {
		$.ajax({
			url: "<?=site_url('location/province')?>",
			data: 'province_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'user_province_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
			},
			complete: function() {
				$('.fa-spin').remove();
			},
			success: function(json) {
				html = '<option value="">-- Pilih --</option>';

				if (json['cities'] && json['cities'] != '') {
					for (i = 0; i < json['cities'].length; i++) {
						html += '<option value="' + json['cities'][i]['city_id'] + '"';

						if (json['cities'][i]['city_id'] == user_city_id) {
							html += ' selected="selected"';
						}

						html += '>' + json['cities'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0" selected="selected">-- None --</option>';
				}

				$('select[name=\'user_city_id\']').html(html);
				$('select[name=\'user_city_id\']').trigger('change');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('select[name=\'user_city_id\']').on('change', function() {
		$.ajax({
			url: "<?=site_url('location/city')?>",
			data: 'city_id=' + this.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'user_city_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
			},
			complete: function() {
				$('.fa-spin').remove();
			},
			success: function(json) {
				html = '<option value="">-- Pilih --</option>';

				if (json['subdistricts'] && json['subdistricts'] != '') {
					for (i = 0; i < json['subdistricts'].length; i++) {
						html += '<option value="' + json['subdistricts'][i]['subdistrict_id'] + '"';

						if (json['subdistricts'][i]['subdistrict_id'] == user_subdistrict_id) {
							html += ' selected="selected"';
						}

						html += '>' + json['subdistricts'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0" selected="selected">-- None --</option>';
				}

				$('select[name=\'user_subdistrict_id\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('select[name=\'user_province_id\']').trigger('change');
	
	function reset() {
		$('input[name=\'user_id\']').val(null);
		$('input[name=\'user_email\']').val('');
		$('input[name=\'user_telephone\']').val('');
		$('select[name=\'user_address_id\']').html('');
		$('input[name=\'user_name\']').val('');
		$('select[name=\'user_province_id\']').html('');
		$('select[name=\'user_city_id\']').html('');
		$('select[name=\'user_subdistrict_id\']').html('');
		$('input[name=\'user_address\']').val('');
		$('input[name=\'user_postcode\']').val('');
	}
	
	$('input[name=\'product\']').typeahead({
		source: function(query, process) {
			$.ajax({
				url: "<?=admin_url('catalog_product/auto_complete')?>",
				data: 'filter_name=' + query,
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
			$('input[name=\'product_id\']').val(map[item].product_id);
			$('input[name=\'product\']').val(map[item].name);
			
			return map[item].name;
		},
		minLength: 1
	});
	
	var order_product_row = <?=$order_product_row?>;
	var order_total_row = <?=$order_total_row?>;
	
	function update()
	{
		$.ajax({
			url: "<?=admin_url('order/manual')?>",
			data: $('#form-order').serialize(),
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				$('.card').append('<div class="overlay" style="z-index:999;"><div class="m-loader mr-20"><svg class="m-circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"/></svg></div><h3 class="l-text">Loading</h3></div>');
			},
			complete: function() {
				$('.overlay').remove();
			},
			success: function(json) {
				$('table tr').removeClass('has-error');
				$('.form-group').removeClass('has-error');
				$('.text-danger').remove();
				$('.alert').remove();
				
				$('input[name=\'product\']').val('');
				$('input[name=\'product_id\']').val('');
				$('input[name=\'quantity\']').val('');
				
				if (json['error']) {
					if (json['error']['shipping']) {
						for (i in json['error']['shipping']) {
							$('input[name=\'user_'+i+'\']').parent().parent().addClass('has-error');
							$('input[name=\'user_'+i+'\']').after('<span class="text-danger">' + json['error']['shipping'][i] + '</span>');
							$('select[name=\'user_'+i+'\']').after('<span class="text-danger">' + json['error']['shipping'][i] + '</span>');	
						}
					}
					
					if (json['error']['payment']) {
						for (i in json['error']['payment']) {
							$('input[name=\'payment_'+i+'\']').parent().parent().addClass('has-error');
							$('input[name=\'payment_'+i+'\']').after('<span class="text-danger">' + json['error']['payment'][i] + '</span>');
							$('select[name=\'payment_'+i+'\']').after('<span class="text-danger">' + json['error']['payment'][i] + '</span>');	
						}
					}
					
					for (i in json['error']) {
						$('input[name=\''+i+'\']').parent().parent().addClass('has-error');
						$('input[name=\''+i+'\']').after('<span class="text-danger">' + json['error'][i] + '</span>');	
					}
					
					if (json['error']['warning']) {
						$('#notification').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+json['error']['warning']+'</div>');
					}
				}
				
				if (json['success']) {
					$('#notification').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+json['success']+'</div>');
				}
				
				if (json['order_product']) {
					html = '';
					for (i in json['order_product']) {
						html += '<tr id="product-'+order_product_row+'">';
						html += '<input type="hidden" name="order_product['+order_product_row+'][order_product_id]" value="">';
						html += '<input type="hidden" name="order_product['+order_product_row+'][product_id]" value="'+json['order_product'][i]['product_id']+'">';
						html += '<td><input type="hidden" name="order_product['+order_product_row+'][name]" value="'+json['order_product'][i]['name']+'">'+json['order_product'][i]['name']+'</td>';
						html += '<td><input type="hidden" name="order_product['+order_product_row+'][quantity]" value="'+json['order_product'][i]['quantity']+'">'+json['order_product'][i]['quantity']+'</td>';
						html += '<td class="text-right"><input type="hidden" name="order_product['+order_product_row+'][price]" value="'+json['order_product'][i]['price']+'">'+json['order_product'][i]['price']+'</td>';
						html += '<td class="text-right"><input type="hidden" name="order_product['+order_product_row+'][total]" value="'+json['order_product'][i]['total']+'">'+json['order_product'][i]['total']+'</td>';
						html += '<td class="text-right"><a class="btn btn-xs btn-danger" onclick="$(\'#product-'+order_product_row+'\').remove(); update();"><i class="fa fa-remove"></i></a></td>';
						html += '</tr>';	
						order_product_row++;
					}
					
					$('#product-table tbody').html(html);
				}
				
				if (json['order_total']) {
					html = '';
					for (i in json['order_total']) {
						html += '<tr>';
						html += '<input type="hidden" name="order_total['+order_total_row+'][order_total_id]" value="">';
						html += '<input type="hidden" name="order_total['+order_total_row+'][code]" value="'+json['order_total'][i]['code']+'">';
						html += '<input type="hidden" name="order_total['+order_total_row+'][title]" value="'+json['order_total'][i]['title']+'">';
						html += '<input type="hidden" name="order_total['+order_total_row+'][text]" value="'+json['order_total'][i]['text']+'">';
						html += '<input type="hidden" name="order_total['+order_total_row+'][value]" value="'+json['order_total'][i]['value']+'">';
						html += '<input type="hidden" name="order_total['+order_total_row+'][sort_order]" value="'+json['order_total'][i]['sort_order']+'">';
						html += '<th class="text-right" colspan="3">'+json['order_total'][i]['title']+'</th>';
						html += '<th class="text-right">'+json['order_total'][i]['text']+'</th>';
						html += '<th></th>';
						html += '</tr>';
						order_total_row++;
					}
					
					$('#product-table tfoot').html(html);
				}
				
				if (json['shipping_stores']) {
					var shippingStore = json['shipping_stores'];
					html = '';
					for (i in shippingStore) {
						var store = shippingStore[i];
						html += '<div class="panel panel-default">';
						html += '<div class="panel-heading"><label>'+store['store']+'</label><span class="pull-right shipping-cost"><b></b></span></div>';
						html += '<ul class="list-group">';
						for (p in store['products']) {
							html += '<li class="list-group-item">'+store['products'][p]['name']+'</li>';
						}
						html += '</ul>';
						html += '<div class="panel-body">';
						if (store['shipping_methods']) {
						html += '		<div class="form-group">';
						html += '			<label class="control-label col-md-4">Pilih metode pengiriman:</label>';
						html += '			<div class="col-md-8">';
									for (sm in store['shipping_methods']) {
										var shippingMethod = store['shipping_methods'][sm];
									if (shippingMethod['error']) {
						html += '			<div class="alert alert-danger">'+shippingMethod['error']+'</div>';
									} else {
						html += '			<select name="shipping_method['+i+']" class="form-control shipping-method" onchange="update();">';
									for (q in shippingMethod['quote']) {
									if (shippingMethod['quote'][q]['code'] == store['shipping_code']) {
						html += '			<option value="'+shippingMethod['quote'][q]['code']+'" rel="'+shippingMethod['quote'][q]['text']+'" selected="selected">'+shippingMethod['quote'][q]['title']+' - '+shippingMethod['quote'][q]['text']+'</option>';
									} else {
						html += '			<option value="'+shippingMethod['quote'][q]['code']+'" rel="'+shippingMethod['quote'][q]['text']+'">'+shippingMethod['quote'][q]['title']+' - '+shippingMethod['quote'][q]['text']+'</option>';
									}
									}
						html += '			</select>';
						
										} 
									}
						html += '			</div>';
						html += '		</div>';
						html += '		<div class="form-group">';
						html += '			<label class="control-label col-md-4">Catatan:</label>';
						html += '			<div class="col-md-8">';
						html += '				<textarea class="form-control" name="comment['+i+']" placeholder="Contoh: warna, ukuran, dsb" rows="5"></textarea>';
						html += '			</div>';
						html += '		</div>';
						}
						html += '</div>';
						html += '</div>';
					}
					$('#shipping-method').html(html);
				}
				
				if (json['payment_method']) {
					html = '<option value="">-- Pilih --</option>';
					
					for (i in json['payment_method']) {
						if (json['payment_method'][i]['code'] == $('input[name=\'payment_code\']').attr('value')) {
							html += '<option value="' + json['payment_method'][i]['code'] + '" selected="selected">' + json['payment_method'][i]['title'] + '</option>';
						} else {
							html += '<option value="' + json['payment_method'][i]['code'] + '">' + json['payment_method'][i]['title'] + '</option>';
						}		
					}
			
					$('select[name=\'payment\']').html(html);
					
					if ($('select[name=\'payment\'] option:selected').attr('value')) {
						$('input[name=\'payment_method\']').attr('value', $('select[name=\'payment\'] option:selected').text());
					} else {
						$('input[name=\'payment_method\']').attr('value', '');
					}
					
					$('input[name=\'payment_code\']').attr('value', $('select[name=\'payment\'] option:selected').attr('value'));
				}
			}
		});
	}
	
	$('select[name=\'shipping\']').bind('change', function() {
		if (this.value) {
			$('input[name=\'shipping_method\']').attr('value', $('select[name=\'shipping\'] option:selected').text());
		} else {
			$('input[name=\'shipping_method\']').attr('value', '');
		}
		$('input[name=\'shipping_code\']').attr('value', this.value);
		update(this);
	});
	
	$('select[name=\'payment\']').bind('change', function() {
		if (this.value) {
			$('input[name=\'payment_method\']').attr('value', $('select[name=\'payment\'] option:selected').text());
		} else {
			$('input[name=\'payment_method\']').attr('value', '');
		}
		$('input[name=\'payment_code\']').attr('value', this.value);
		update(this);
	});
</script>