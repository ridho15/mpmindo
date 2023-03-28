<div class="page-title">
	<div>
		<h1><?=$template['title']?></h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="form-action">
				<button onclick="window.location = '<?=admin_url('banner')?>';" class="btn btn-default"><i class="fa fa-reply"></i> <?=lang('button_back')?></button>
				<button id="submit" class="btn btn-primary"><i class="fa fa-check"></i> <?=lang('button_save')?></button>
			</div>
			<div class="card-body">
				<?=form_open($action, 'role="form" id="form" class="form-horizontal"')?>
				<input type="hidden" name="banner_id" value="<?=$banner_id?>">
				<div class="form-group">
					<label class="col-sm-2 control-label">Nama Banner</label>
					<div class="col-sm-6">
						<input type="text" name="name" value="<?=$name?>" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Deskripsi</label>
					<div class="col-sm-10">
						<input type="text" name="description" value="<?=$description?>" class="form-control">
					</div>
				</div>
				<div class="table-responsive">
					<table class="table" width="100%">
						<thead>
							<tr>
								<th>Foto</th>
								<th>Caption</th>
								<th>Link</th>
								<th style="width:10%;">Urutan</th>
								<th style="width:10%;"></th>
							</tr>
						</thead>
						<tbody>
							<?php $row = 0;?>
							<?php foreach ($images as $image) {?>
							<tr id="row-<?=$row?>">
								<td><a href="#" class="img-thumbnail" id="thumb<?=$row?>" data-toggle="image"><img src="<?=$image['thumb']?>" placeholder="<?=$no_image?>"/><input type="hidden" id="image<?=$row?>" name="banner_image[<?=$row?>][image]" value="<?=$image['image']?>"></a></td>
								<td><input type="text" name="banner_image[<?=$row?>][title]" value="<?=$image['title']?>" class="form-control" placeholder="Title"><br><input type="text" name="banner_image[<?=$row?>][subtitle]" value="<?=$image['subtitle']?>" class="form-control" placeholder="Sub title"></td>
								<td><input type="text" name="banner_image[<?=$row?>][link]" value="<?=$image['link']?>" class="form-control" placeholder="URL link"><br><input type="text" name="banner_image[<?=$row?>][link_title]" value="<?=$image['link_title']?>" class="form-control" placeholder="Button title"></td>
								<td><input type="text" name="banner_image[<?=$row?>][sort_order]" value="<?=$image['sort_order']?>" class="form-control" placeholder="0"><br><div style="margin-top:9px" class="checkbox"><label><input type="checkbox" name="banner_image[<?=$row?>][active]" value="1" <?=$image['active'] ? 'checked="checked"' : ''?>> Active</label></div></td>
								<td class="text-right"><a class="btn btn-danger" onclick="$('#row-<?=$row?>').remove(); return false;"><i class="fa fa-remove"></i></a></td>
							</tr>
							<?php $row++;?>
							<?php }?>
							<tr>
								<td colspan="4"></td>
								<td class="text-right"><a class="btn btn-info " id="insert-image"><i class="fa fa-plus"></i></a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
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
					window.location = "<?=admin_url('banner')?>";
				}
			}
		});
	});
	
	var row = "<?=$row?>";
	
	$('#insert-image').bind('click', function() {
		html  = '<tr id="row-'+row+'">';
		html += '<td><a href="#" class="img-thumbnail" id="thumb'+row+'" data-toggle="image"><img src="<?=$no_image?>" placeholder=""/><input type="hidden" id="image'+row+'" name="banner_image['+row+'][image]"></a></td>';
		html += '<td><input type="text" name="banner_image['+row+'][title]" class="form-control" placeholder="Title"><br><input type="text" name="banner_image['+row+'][subtitle]" class="form-control" placeholder="Sub title"></td>';
		html += '<td><input type="text" name="banner_image['+row+'][link]" class="form-control" placeholder="URL link"><br><input type="text" name="banner_image['+row+'][link_title]" class="form-control" placeholder="Link title"></td>';
		html += '<td style="width:10%;"><input type="text" name="banner_image['+row+'][sort_order]" value="0" class="form-control" placeholder="0"><br><div style="margin-top:9px" class="checkbox"><label><input type="checkbox" name="banner_image['+row+'][active]" value="1"> Active</label></div></td>';
		html += '<td style="width:10%;" class="text-right"><a class="btn btn-danger" onclick="$(\'#row-'+row+'\').remove(); return false;"><i class="fa fa-remove"></i></a></td>';
		html += '</tr>';
		
		$(this).closest('tr').before(html);
		row++;
	});
</script>