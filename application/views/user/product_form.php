<div class="row">
	<div class="col-md-12">
	<div class="card">
	<?=form_open($action, 'role="form" id="form" class="form-horizontal"')?>
	<input type="hidden" name="product_id" value="<?=$product_id?>">
	<div class="card-body">
		<div class="card-title-w-btn">
			<h3 class="title"><?=$heading_title?></h3>
			<div class="btn-group"><a onclick="window.location = '<?=user_url('product')?>';" class="btn btn-default"><i class="fa fa-reply"></i> Batal</a>
			<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button></div>
		</div>
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab-1" data-toggle="tab">Umum</a></li>
			<li><a href="#tab-2" data-toggle="tab">Detil</a></li>
			<li><a href="#tab-3" data-toggle="tab">Diskon</a></li>
			<li><a href="#tab-5" data-toggle="tab">Gambar</a></li>
		</ul>
		<div class="tab-content" style="padding-top:20px;">
			<?php if ($errors) {?>
			<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?=$errors?></div>
			<?php }?>
			<div class="tab-pane active" id="tab-1">
				<div class="form-group">
					<label class="col-sm-3 control-label">Nama Produk<br><span class="help">Produk apa yang ingin Anda jual?</span></label>
					<div class="col-sm-9">
						<input type="text" name="name" value="<?=$name?>" class="form-control">
					</div>
				</div><hr>
				<div class="form-group">
					<label class="col-sm-3 control-label">Deskripsi<br><span class="help">Deskripsi produk maksimum 2000 karakter</span></label>
					<div class="col-sm-9">
						<textarea id="description" name="description" class="summernote form-control" rows="5"><?=$description?></textarea>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="tab-2">
				<div class="form-group">
					<label class="col-sm-3 control-label">Kategori<br><span class="help">Pilih kategori yang sesuai</span></label>
					<div class="col-sm-3 cat-level-1">
						<select name="category_id" class="form-control" onchange="getSubcategories(2, this);">
						<?php foreach ($categories as $category) {?>
						<?php if (isset($path_ids[0]) && $category['category_id'] == $path_ids[0]) {?>
						<option value="<?=$category['category_id']?>" selected="selected"><?=$category['name']?></option>
						<?php } else {?>
						<option value="<?=$category['category_id']?>"><?=$category['name']?></option>
						<?php }?>
						<?php }?>
						</select>
					</div>
					<div class="col-sm-3 cat-level-2"></div>
					<div class="col-sm-3 cat-level-3"></div>
				</div><hr>
				<div class="form-group">
					<label class="col-sm-3 control-label">Harga</label>
					<div class="col-sm-3">
						<input type="text" name="price" value="<?=$price?>" class="form-control">
					</div>
				</div><hr>
				<div class="form-group">
					<label class="col-sm-3 control-label">Berat<br><span class="help">Masukkan berat termasuk packing. Gunakan tanda titik(.) untuk mengganti koma. Contoh : 180.50</span></label>
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
				</div><hr>
				<div class="form-group">
					<label class="col-sm-3 control-label">Jumlah Stok</label>
					<div class="col-sm-3">
						<input type="text" name="quantity" value="<?=$quantity?>" class="form-control">
					</div>
				</div><hr>
				<div class="form-group">
					<label class="col-sm-3 control-label">Status Stok</label>
					<div class="col-sm-3">
						<select name="stock_status_id" class="form-control">
							<?php foreach ($stock_statuses as $stock_status) {?>
							<?php if ($stock_status['stock_status_id'] == $stock_status_id) {?>
							<option value="<?=$stock_status['stock_status_id']?>" selected="selected"><?=$stock_status['name']?></option>
							<?php } else {?>
							<option value="<?=$stock_status['stock_status_id']?>"><?=$stock_status['name']?></option>
							<?php }?>
							<?php }?>
						</select>
					</div>
				</div><hr>
				<div class="form-group">
					<label class="col-sm-3 control-label">Etalase</label>
					<div class="col-sm-3">
						<select name="showcase_id" class="form-control">
						<?php foreach ($showcases as $showcase) {?>
						<?php if ($showcase['showcase_id'] == $showcase_id) {?>
						<option value="<?=$showcase['showcase_id']?>" selected="selected"><?=$showcase['name']?></option>
						<?php } else {?>
						<option value="<?=$showcase['showcase_id']?>"><?=$showcase['name']?></option>
						<?php }?>
						<?php }?>
						</select>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="tab-3">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th class="text-right">Qty</th>
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
					<label class="col-sm-3 control-label"><a id="upload-images" class="btn btn-success btn-small"><i class="fa fa-upload"></i> Upload Gambar</a></label>
					<div class="col-sm-9" id="image-add">
						<?php $image_row = 0; ?>
						<?php foreach ($images as $image) { ?>
							<div class="col-xs-6 col-md-3">
								<div class="thumbnail">
									<a href="#" id="thumb-image<?=$image_row?>" class="thumb-image"><img src="<?=$image['thumb']?>" alt="" title="" data-placeholder="<?=$no_image?>" class="img-thumbnail" /></a><input type="hidden" name="product_image[<?=$image_row?>][image]" value="<?=$image['image']?>" id="input-image<?=$image_row?>" />
								</div>
							</div>
						<?php $image_row++; ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	</form>
	</div>
</div>
</div>
<script type="text/javascript">
	$('#submit').bind('click', function() {
		$.ajax({
			url: $('#form').attr('action'),
			data: $('#form').serialize(),
			type: 'POST',
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
					window.location = "<?=user_url('product')?>";
				}
			}
		});
	});
	
	var path_ids = <?=json_encode($path_ids)?>;
	
	function getSubcategories(level, selectElem) {
		var element = $('.cat-level-'+level);
		var select = $(element).find('select');
		
		if (select.length > 0) {
			select.remove();
		}
		
		if (level == 2) {
			$('.cat-level-2').find('select').remove();
			$('.cat-level-3').find('select').remove();
		}
		
		if (level == 2) {
			var path_id = path_ids[1];
		} else if (level == 3)  {
			var path_id = path_ids[2];
		}
		
		var nextLevel = level+1;
		
		$.ajax({
			url: "<?=user_url('product/subcategories')?>",
			type: 'get',
			dataType: 'json',
			data: 'parent_id='+$(selectElem).val(),
			beforeSend: function() {
				element.append('<i class="fa fa-refresh fa-2x fa-spin"></i>');
			},
			complete: function() {
				$('.fa-refresh').remove();
			},
			success: function(json) {
				if (json.length > 0) {
					html =	'<select name="category_id" class="form-control" onchange="getSubcategories('+nextLevel+', this);">';
					html +=	'<option value="0"> -- Pilih --</option>';
					for (i in json) {
						if (json[i]['category_id'] == path_id) {
							html +=	'<option value="'+json[i]['category_id']+'" selected="selected">'+json[i]['name']+'</option>';
						} else {
							html +=	'<option value="'+json[i]['category_id']+'">'+json[i]['name']+'</option>';
						}
					}
					html +=	'</select>';
						
					element.html(html);
					
					$('.cat-level-'+level+' select').trigger('change');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
	
	$('.cat-level-1 select').trigger('change');

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
					url: "<?=user_url('product/upload')?>",
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
	
	$(document).delegate('a[class="thumb-image"]', 'click', function(e) {
		e.preventDefault();

		$('.popover').popover('hide', function() {
			$('.popover').remove();
		});

		var element = this;

		$(element).popover({
			html: true,
			placement: 'right',
			trigger: 'manual',
			content: function() {
				return '<button type="button" id="delete-image" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
			}
		});

		$(element).popover('show');
		
		$('#delete-image').on('click', function() {
			parentElement = $(element).parent().parent();
			parentElement.remove();
		});
	});
</script>