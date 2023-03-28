<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<?=form_open($action, 'id="form" role="form" class="form-horizontal"')?>
				<input type="hidden" name="product_id" value="<?=$product_id?>">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-1" data-toggle="tab">Umum</a></li>
					<li><a href="#tab-2" data-toggle="tab">Data</a></li>
					<li><a href="#tab-3" data-toggle="tab">Diskon</a></li>
					<li><a href="#tab-5" data-toggle="tab">Gambar</a></li>
					<li><a href="#tab-6" data-toggle="tab">Produk Terkait</a></li>
				</ul>
				<div class="tab-content" style="padding-top:20px;">
					<div class="tab-pane active" id="tab-1">
						<div class="form-group">
							<label class="col-sm-3 control-label">Nama Produk</label>
							<div class="col-sm-9">
								<input type="text" name="name" value="<?=$name?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Deskripsi</label>
							<div class="col-sm-9">
								<textarea name="description" class="summernote form-control" rows="3"><?=$description?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Spesifikasi</label>
							<div class="col-sm-9">
								<textarea name="specification" class="summernote form-control" rows="3"><?=$specification?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Apa Yang Saya Dapatkan</label>
							<div class="col-sm-9">
								<textarea name="whatisinthebox" class="summernote form-control" rows="3"><?=$whatisinthebox?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Meta Description</label>
							<div class="col-sm-9">
								<textarea name="meta_description" class="form-control" rows="3"><?=$meta_description?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Meta Keywords</label>
							<div class="col-sm-9">
								<input type="text" name="meta_keyword" value="<?=$meta_keyword?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-2">
						<div class="form-group">
							<label class="col-sm-3 control-label">Kategori</label>
							<div class="col-sm-8">
								<input type="text" name="category" value="<?=$category?>" class="form-control" autocomplete="off" placeholder="">
								<input type="hidden" name="category_id" value="<?=$category_id?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Harga</label>
							<div class="col-sm-3">
								<input type="text" name="base_price" value="<?=$price?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Kode Produk</label>
							<div class="col-sm-3">
								<input type="text" name="sku" value="<?=$sku?>" class="form-control">
							</div>
						</div>
						
						<!-- <div class="form-group">
							<label class="col-sm-3 control-label">Pajak</label>
							<div class="col-sm-3">
								<div class="input-group">
									<input type="text" name="tax_value" value="<?=$tax_value?>" class="form-control" placeholder="Pajak">
									<span class="input-group-addon">%</span>
								</div>
							</div>
							<div class="col-sm-3">
								<select name="tax_type" class="form-control">
								<?php foreach (array('Non Pajak', 'Inklusif', 'Eksklusif') as $value) {?>
								<?php if ($value == $tax_type) {?>
								<option value="<?=$value?>" selected="selected"><?=$value?></option>
								<?php } else {?>
								<option value="<?=$value?>"><?=$value?></option>
								<?php }?>
								<?php }?>
								</select>
							</div>
						</div> -->
						<div class="form-group">
							<label class="col-sm-3 control-label">Berat</label>
							<div class="col-sm-3">
								<input type="text" name="weight" value="<?=$weight?>" class="form-control">
							</div>
							<div class="col-sm-3">
								<select name="weight_class_id" class="form-control">
								<?php foreach ($weight_classes as $weight_class) {?>
								<?php if ($weight_class['weight_class_id'] == $weight_class_id) {?>
								<option value="<?=$weight_class['weight_class_id']?>" selected="selected"><?=$weight_class['title']?></option>
								<?php } else {?>
								<option value="<?=$weight_class['weight_class_id']?>"><?=$weight_class['title']?></option>
								<?php }?>
								<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Satuan</label>
							<div class="col-sm-3">
								<select name="unit_class_id" class="form-control">
								<?php foreach ($unit_classes as $unit_class) {?>
								<?php if ($unit_class['unit_class_id'] == $unit_class_id) {?>
								<option value="<?=$unit_class['unit_class_id']?>" selected="selected"><?=$unit_class['title']?></option>
								<?php } else {?>
								<option value="<?=$unit_class['unit_class_id']?>"><?=$unit_class['title']?></option>
								<?php }?>
								<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Pembelian Minimum</label>
							<div class="col-sm-3">
								<input type="text" name="minimum" value="<?=$minimum?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?=lang('label_active')?></label>
							<div class="toggle lg col-sm-4">
								<label style="margin-top:5px;">
								<?php if ($active) {?>
								<input type="checkbox" name="active" value="1" checked="checked">
								<?php } else {?>
								<input type="checkbox" name="active" value="1">
								<?php }?>
								<span class="button-indecator"></span>
								</label>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-3">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th class="text-right">Qty Pembelian</th>
									<th class="text-right">Prioritas</th>
									<th class="text-right">Harga</th>
									<th>Tanggal Mulai</th>
									<th>Tanggal Berakhir</th>
									<th class="text-right" style="width:1%;"></th>
								</tr>
							</thead>
							<tbody>
								<?php $discount_row = 0; ?>
								<?php foreach ($discounts as $discount) { ?>
								<tr id="discount-row<?=$discount_row?>">
									<td><input type="text" name="product_discount[<?=$discount_row?>][quantity]" value="<?=$discount['quantity']?>" class="form-control text-right"></td>
									<td><input type="text" name="product_discount[<?=$discount_row?>][priority]" value="<?=$discount['priority']?>" class="form-control text-right"></td>
									<td><input type="text" name="product_discount[<?=$discount_row?>][price]" value="<?=$discount['price']?>" class="form-control text-right"></td>
									<td><input type="text" name="product_discount[<?=$discount_row?>][date_start]" value="<?=$discount['date_start']?>" class="form-control" id="dps<?=$discount_row?>" data-date-format="dd-mm-yyyy"></td>
									<td><input type="text" name="product_discount[<?=$discount_row?>][date_end]" value="<?=$discount['date_end']?>" class="form-control" id="dpe<?=$discount_row?>" data-date-format="dd-mm-yyyy"></td>
									<td class="text-right"><a onclick="$('#discount-row<?=$discount_row?>').remove();" class="btn btn-danger"><i class="fa fa-remove"></i></a></td>
								</tr>
								<script type="text/javascript">
									$('#dps<?=$discount_row?>').datepicker({language:'id', autoclose:true});
									$('#dpe<?=$discount_row?>').datepicker({language:'id', autoclose:true});
								</script>
								<?php $discount_row++; ?>
								<?php } ?>
								<tr id="discount-add">
									<td colspan="5"></td>
									<td class="text-right"><a onclick="addDiscount();" class="btn btn-success"><i class="fa fa-plus"></i></a></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="tab-pane" id="tab-5">
						<div class="form-group">
							<label class="col-sm-3 control-label">
							<a id="upload-images" class="btn btn-default btn-small"><i class="fa fa-upload"></i> Upload Gambar</a>
							<?php if($mobile) echo '<br><i>*) Jika mengupload gambar dengan kamera, gunakan posisi landscape pada kamera</i>';?>
							</label>
							<div class="col-sm-9" id="image-add">
								<?php $image_row = 0; ?>
								<?php foreach ($images as $image) { ?>
									<div class="col-xs-6 col-md-3">
										<div class="thumbnail">
											<button onclick="deleteImage(this);" style="position:absolute;top:0;left:15px;" class="btn btn-danger" type="button"><span class="fa fa-remove"></span></button>
											<a href="#" id="thumb-image<?=$image_row?>" class="thumb-image"><img src="<?=$image['thumb']?>" alt="" title="" data-placeholder="<?=$no_image?>" class="img-thumbnail" /></a><input type="hidden" name="product_image[<?=$image_row?>][image]" value="<?=$image['image']?>" id="input-image<?=$image_row?>" />
										</div>
									</div>
								<?php $image_row++; ?>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-6">
						<div class="form-group">
							<label class="col-sm-2 control-label">Produk</label>
							<div class="col-sm-10">
								<input type="text" name="product" value="" class="form-control" autocomplete="off" placeholder="Ketikkan nama atau ID produk">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-10">
								<div class="controls" style="border: 1px solid #ddd; height: 200px; background: #fff; overflow-y: scroll;">
									<table class="table table-striped" width="100%">
										<tbody id="product-related">
											<?php foreach ($related as $product) {?>
											<tr id="product-related<?=$product['product_id']?>">
												<td><?=$product['name']?><input type="hidden" name="product_related[]" value="<?=$product['product_id']?>"></td>
												<td class="text-right"><a class="btn btn-danger" onclick="$('#product-related<?=$product['product_id']?>').remove();"><i class="fa fa-remove"></i></a></td> 
											</tr>
											<?php }?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> <?=lang('button_cancel')?></button>
			<button type="button" class="btn btn-primary" id="submit"><i class="fa fa-check"></i> <?=lang('button_save')?></button>
		</div>
	</div>
</div>
<script src="<?=base_url('assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.min.js')?>"></script>
<script type="text/javascript">
	$('.summernote').summernote({
		height: 100,
		toolbar: [
			['style', ['style']],
			['font', ['bold', 'clear']],
			['fontname', ['fontname']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['table', ['table']],
			['view', ['fullscreen', 'codeview']]
		],
	});
	$('#form select').select2();
	$('#submit').bind('click', function() {
		$.ajax({
			url: $('#form').attr('action'),
			data: $('#form').serialize(),
			type: 'post',
			dataType: 'json',
			success: function(json) {
				$('.form-group').removeClass('has-error');
				$('.text-danger').remove();
				if (json['error']) {
					for (i in json['error']) {
						$('input[name=\''+i+'\']').parent().addClass('has-error');
						$('input[name=\''+i+'\']').after('<span class="text-danger">' + json['error'][i] + '</span>');	
					}
				} else if (json['success']){
					$('#form-modal').modal('hide');
					$.notify({
						title: '<strong><?=lang('text_success')?></strong><br>',
						message: json['success'],
						icon: 'fa fa-check' 
					},{
						type: "success"
					});
				}
			}
		});
	});

	var discount_row = "<?=$discount_row?>";
	
	function addDiscount() {
		html  = '<tr id="discount-row'+discount_row+'">';
		html += '<td><input type="text" name="product_discount['+discount_row+'][quantity]" value=""  class="form-control text-right"></td>';
		html += '<td><input type="text" name="product_discount['+discount_row+'][priority]" value=""  class="form-control text-right"></td>';
		html += '<td><input type="text" name="product_discount['+discount_row+'][price]" value=""  class="form-control text-right"></td>';
		html += '<td><input type="text" name="product_discount['+discount_row+'][date_start]" value=""  class="form-control" id="dps'+discount_row+'" data-date-format="dd-mm-yyyy"></td>';
		html += '<td><input type="text" name="product_discount['+discount_row+'][date_end]" value=""  class="form-control" id="dpe'+discount_row+'" data-date-format="dd-mm-yyyy"></td>';
		html += '<td class="text-right"><a onclick="$(\'#discount-row'+discount_row+'\').remove();" class="btn btn-danger"><i class="fa fa-remove"></i></a></td>';
		html += '</tr>';
		
		$('#discount-add').before(html);
		
		$('#dps'+discount_row).datepicker({language:'id', autoclose:true});
		$('#dpe'+discount_row).datepicker({language:'id', autoclose:true});
		
		discount_row++;
	}
	
	var image_row = "<?=$image_row?>";
	
	function addImage(image, thumb) {
		html  = '<div class="col-xs-6 col-md-3">';
		html +=	'	<div class="thumbnail">';
		html += '		<button onclick="deleteImage(this);" style="position:absolute;top:0;left:15px;" class="btn btn-danger" type="button"><span class="fa fa-remove"></span></button>';
		html +=	'		<a href="#" id="thumb-image' + image_row + '" class="thumb-image"><img src="'+thumb+'" class="img-thumbnail"/></a><input type="hidden" name="product_image[' + image_row + '][image]" value="'+image+'" id="input-image' + image_row + '" />';
		html +=	'	</div>';
		html +=	'</div>';
		
		$('#image-add').append(html);
		
		image_row++;
	}
	
	$('#upload-images').on('click', function() {
		$('#form-upload').remove();
		$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="userfile" value="" multiple="multiple" /></form>');
		$('#form-upload input[name=\'userfile\']').trigger('click');

		if (typeof timer != 'undefined') {
			clearInterval(timer);
		}

		timer = setInterval(function() {
			if ($('#form-upload input[name=\'userfile\']').val() != '') {
				clearInterval(timer);

				$.ajax({
					url: "<?=admin_url('product/upload')?>",
					type: 'post',
					dataType: 'json',
					data: new FormData($('#form-upload')[0]),
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function() {
						$('#upload-images i').replaceWith('<i class="fa fa-refresh fa-spin"></i>');
						$('#upload-images').prop('disabled', true);
					},
					complete: function() {
						$('#upload-images i').replaceWith('<i class="fa fa-upload"></i>');
						$('#upload-images').prop('disabled', false);
					},
					success: function(json) {
						if (json['error']) {
							alert(json['error']);
						}

						if (json['success']) {
							addImage(json['image'], json['thumb']);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		}, 500);
	});
	
	function deleteImage(element) {
		if (confirm('Apakah anda yakin?')) {
			element = $(element).parent().parent();
			element.remove();
		}
	}
	
	$('input[name=\'category\']').typeahead({
		source: function(query, process) {
			$.ajax({
				url: "<?=admin_url('category/auto_complete')?>",
				data: 'filter_name=' + query,
				type: 'get',
				dataType: 'json',
				success: function(json) {
					categories = [];
					map = {};
					$.each(json, function(i, category) {
						map[category.name] = category;
						categories.push(category.name);
					});
					process(categories);
				}
			});
		},
		updater: function (item) {
			$('input[name=\'category\']').val(map[item].name);
			$('input[name=\'category_id\']').val(map[item].category_id);
			
			return map[item].name;
		},
		minLength: 1
	});
	
	$('input[name=\'product\']').typeahead({
		source: function(query, process) {
			$.ajax({
				url: "<?=admin_url('product/auto_complete')?>",
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
			$('#product-related' + map[item].product_id).remove();
			$('#product-related').append('<tr id="product-related' + map[item].product_id + '"><td>' + item + '<input type="hidden" name="product_related[]" value="' + map[item].product_id + '" /></td><td class="text-right"><a class="btn btn-danger" onclick="$(\'#product-related' + map[item].product_id + '\').remove();"><i class="fa fa-remove "></i></a></td></tr>');
		},
		minLength: 1
	});
</script>