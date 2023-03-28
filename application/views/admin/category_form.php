<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<?=form_open($action, 'id="form" role="form" class="form-horizontal"')?>
				<input type="hidden" name="category_id" value="<?=$category_id?>">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-1" data-toggle="tab"><?=lang('tab_general')?></a></li>
					<li><a href="#tab-2" data-toggle="tab"><?=lang('tab_data')?></a></li>
				</ul>
				<div class="tab-content" style="padding-top:20px;">
					<div id="message"></div>
					<div class="tab-pane active" id="tab-1">
						<div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_name')?></label>
							<div class="col-sm-8">
								<input type="text" name="name" value="<?=$name?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_description')?></label>
							<div class="col-sm-8">
								<textarea name="description" class="form-control" rows="5"><?=$description?></textarea>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-2">
						<div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_parent')?></label>
							<div class="col-sm-8">
								<input type="text" name="parent" value="<?=$path?>" class="form-control" autocomplete="off">
								<input type="hidden" name="parent_id" value="<?=$parent_id?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_image')?></label>
							<div class="col-sm-4">
								<img id="image-image" src="<?=$thumb_detail?>" class="img-thumbnail" data-placeholder="<?=$thumb_detail?>">
								<div class="caption" style="margin-top:10px;">
									<button type="button" class="btn btn-default btn-block" id="upload-image"><span class="fa fa-upload"></span> <?=lang('button_upload')?></button>
								</div>
								<input type="hidden" id="input-image" name="image" value="<?=$image?>">
								<?php if($mobile) echo '<i>*) Jika mengupload foto dengan kamera, gunakan posisi landscape pada kamera</i>';?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_sort_order')?></label>
							<div class="col-sm-4">
								<input type="text" name="sort_order" value="<?=$sort_order?>" class="form-control"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"><?=lang('label_active')?></label>
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
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> <?=lang('button_cancel')?></button>
			<button type="button" class="btn btn-primary" id="submit"><i class="fa fa-check"></i> <?=lang('button_save')?></button>
		</div>
	</div>
</div>
<script src="<?=base_url('assets/js/ajaxupload.js')?>" type="text/javascript"></script>
<script src="<?=base_url('assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.min.js')?>"></script>
<script type="text/javascript">
	$('input[name=\'parent\']').typeahead({
		source: function(query, process) {
			$.ajax({
				url: "<?=admin_url('category/auto_complete')?>",
				data: 'filter_name=' + query + '&category_id=' + '<?=$category_id?>',
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
			$('input[name=\'parent\']').val(map[item].name);
			$('input[name=\'parent_id\']').val(map[item].category_id);
			
			return map[item].name;
		},
		minLength: 1
	});
	
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
	
	new AjaxUpload('#upload-image', {
		action: "<?=admin_url('category/upload');?>",
		name: 'userfile',
		autoSubmit: false,
		responseType: 'json',
		onChange: function(file, extension) {
			this.submit();
		},
		onSubmit: function(file, extension) {
			$('#upload-image').button('loading');
		},
		onComplete: function(file, json) {
			$('#upload-image').button('reset');
			
			if (json.success) { 
				$('#image-image').attr('src', json.thumb);
				$('#input-image').val(json.image);
			}
			
			if (json.error) {
				$.notify({
					title: '<strong><?=lang('text_error')?></strong><br>',
					message: json.error,
					icon: 'fa fa-info' 
				},{
					type: "danger"
				});
			}
		}
	});
</script>