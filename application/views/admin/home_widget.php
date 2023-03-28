<div class="page-title">
	<div>
		<h1>Home Widget</h1>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="form-action">
					<button id="submit" class="btn btn-success"><i class="fa fa-lg fa-check"></i> Save</button>
				</div>
				<div id="message"></div>
				<?=form_open($action, 'id="form" class="form-horizontal"')?>
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-1" data-toggle="tab" id="default">Slideshow</a></li>
					<li><a href="#tab-2" data-toggle="tab">Footer Card</a></li>
					<li><a href="#tab-3" data-toggle="tab">Carousel Kategori</a></li>
				</ul>
				<div class="tab-content" style="padding-top:20px;">
					<div class="tab-pane active" id="tab-1">
						<div class="form-group">
							<label class="control-label col-sm-3">Banner<br><span class="label-help">Pilih banner yang dijadikan slideshow</span></label>
							<div class="col-sm-9">
								<select name="slideshow_banner_id" class="form-control">
									<?php foreach ($banners as $banner) {?>
									<?php if ($banner['banner_id'] == $slideshow_banner_id) { ?>
									<option value="<?=$banner['banner_id']?>" selected="selected"><?=$banner['name']?></option>
									<?php } else {?>
									<option value="<?=$banner['banner_id']?>"><?=$banner['name']?></option>
									<?php }?>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Banner For Mobile<br><span class="label-help">Pilih banner yang dijadikan slideshow di browser mobile</span></label>
							<div class="col-sm-9">
								<select name="slideshow_mobile_banner_id" class="form-control">
									<?php foreach ($banners as $banner) {?>
									<?php if ($banner['banner_id'] == $slideshow_mobile_banner_id) { ?>
									<option value="<?=$banner['banner_id']?>" selected="selected"><?=$banner['name']?></option>
									<?php } else {?>
									<option value="<?=$banner['banner_id']?>"><?=$banner['name']?></option>
									<?php }?>
									<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Banner For Home Ads</label>
							<div class="col-sm-9">
								<select name="ads_banner_id" class="form-control">
									<?php foreach ($banners as $banner) {?>
									<?php if ($banner['banner_id'] == $ads_banner_id) { ?>
									<option value="<?=$banner['banner_id']?>" selected="selected"><?=$banner['name']?></option>
									<?php } else {?>
									<option value="<?=$banner['banner_id']?>"><?=$banner['name']?></option>
									<?php }?>
									<?php }?>
								</select>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-2">
						<table class="table">
							<thead>
								<tr>
									<th>Heading</th>
									<th>Caption</th>
									<th>Icon Class</th>
									<th>Sort Order</th>
									<th class="text-right" style="width:1%;"></th>
								</tr>
							</thead>
							<tbody>
								<?php $footer_card_row = 0; ?>
								<?php foreach ($footer_cards as $footer_card) { ?>
								<tr id="footer-card-row<?=$footer_card_row?>">
									<td><input type="text" name="footer_cards[<?=$footer_card_row?>][heading]" value="<?=$footer_card['heading']?>" class="form-control"></td>
									<td><input type="text" name="footer_cards[<?=$footer_card_row?>][caption]" value="<?=$footer_card['caption']?>" class="form-control"></td>
									<td><input type="text" name="footer_cards[<?=$footer_card_row?>][icon]" value="<?=$footer_card['icon']?>" class="form-control"></td>
									<td><input type="text" name="footer_cards[<?=$footer_card_row?>][sort_order]" value="<?=$footer_card['sort_order']?>" class="form-control"></td>
									<td class="text-right"><a onclick="$('#footer-card-row<?=$footer_card_row?>').remove();" class="btn btn-danger"><i class="fa fa-remove"></i></a></td>
								</tr>
								<?php $footer_card_row++; ?>
								<?php } ?>
								<tr id="footer-card-add">
									<td colspan="4"></td>
									<td class="text-right"><a onclick="addFooterCard();" class="btn btn-primary"><i class="fa fa-plus"></i></a></td>
								</tr>
							</tbody>
						</table>
						<div class="alert alert-info">
							Untuk referensi penamaan icon class, bisa menggunakan salah satu dari penyedia web icon berikut :
							<ul>
								<li><a href="https://themify.me/themify-icons" target="_blank">Themify Icon</a></li>
								<li><a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank">Font Awesome</a></li>
							</ul>
						</div>
					</div>
					<div class="tab-pane" id="tab-3">
						<div class="form-group">
							<label class="control-label col-sm-3">Tambah Carousel</label>
							<div class="col-sm-9">
								<input type="text" name="category" value="" class="form-control" autocomplete="off" placeholder="Ketikkan nama kategori...">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3"></label>
							<div class="col-sm-9" id="category-carousel">
								<?php foreach ($categories as $category) {?>
								<div class="alert alert-info" id="category-carousel<?=$category['category_id']?>">
								<button type="button" class="close" onclick="$('#category-carousel<?=$category['category_id']?>').remove();">&times;</button><strong><?=$category['name']?></strong><input type="hidden" name="carousel_categories[]" value="<?=$category['category_id']?>">
								</div>
								<?php }?>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
		</div>
	</div>
</div>
<script src="<?=base_url('assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.js')?>"></script>
<script type="text/javascript">
	$('#submit').bind('click', function() {
		$this = $(this);
		$.ajax({
			url: $('#form').attr('action'),
			data: $('#form').serialize(),
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				$this.button('loading');
			},
			complete: function() {
				$this.button('reset');
			},
			success: function(json) {
				$('.error').remove();
				$('.form-group').removeClass('has-error');
				if (json['success']) {
					$.notify({
						title: '<strong><?=lang('text_success')?></strong><br>',
						message: json['success'],
						icon: 'fa fa-check' 
					},{
						type: "success"
					});
				} else if (json['error']) {
					$.notify({
						title: '<strong><?=lang('text_error')?></strong><br>',
						message: json['error'],
						icon: 'fa fa-ban' 
					},{
						type: "error"
					});
				} else if (json['errors']) {
					for (i in json['errors']) {
						$('input[name=\''+i+'\']').after('<span class="help-block error">'+json['errors'][i]+'</span>');
						$('input[name=\''+i+'\']').parent().addClass('has-error');
					}
				}
			}
		});
	});
	
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
			$('#category-carousel'+map[item].category_id).remove();
			html  = '<div class="alert alert-info" id="category-carousel'+map[item].category_id+'">';
			html += '<button type="button" class="close" onclick="$(\'#category-carousel'+map[item].category_id+'\').remove();">&times;</button><strong>'+item + '</strong><input type="hidden" name="carousel_categories[]" value="'+map[item].category_id+'">';
			html += '</div>';
			
			$('#category-carousel').append(html);
		},
		minLength: 1
	});
	
	var footer_card_row = "<?=$footer_card_row?>";
	
	function addFooterCard() {
		html  = '<tr id="footer-card-row'+footer_card_row+'">';
		html += '<td><input type="text" name="footer_cards['+footer_card_row+'][heading]" value="" class="form-control"></td>';
		html += '<td><input type="text" name="footer_cards['+footer_card_row+'][caption]" value="" class="form-control"></td>';
		html += '<td><input type="text" name="footer_cards['+footer_card_row+'][icon]" value="ti-reload" class="form-control"></td>';
		html += '<td><input type="text" name="footer_cards['+footer_card_row+'][sort_order]" value="1" class="form-control"></td>';
		html += '<td class="text-right"><a onclick="$(\'#footer-card-row'+footer_card_row+'\').remove();" class="btn btn-danger"><i class="fa fa-remove"></i></a></td>';
		html += '</tr>';
		
		$('#footer-card-add').before(html);
		
		footer_card_row++;
	}
</script>