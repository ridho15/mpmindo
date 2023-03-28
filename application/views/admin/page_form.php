<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<?=form_open($action, 'id="form" class="form-horizontal" role="form"')?>
				<input type="hidden" name="page_id" value="<?=$page_id?>">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-1" data-toggle="tab" id="default"><?=lang('tab_general')?></a></li>
					<li><a href="#tab-2" data-toggle="tab"><?=lang('tab_seo')?></a></li>
					<li><a href="#tab-3" data-toggle="tab"><?=lang('tab_option')?></a></li>
				</ul>
				<div class="tab-content" style="padding-top:20px;">
					<div class="tab-pane active" id="tab-1">
						<div class="form-group">
							<label class="col-sm-3 control-label"><?=lang('entry_title')?></label>
							<div class="col-sm-9">
								<input type="text" name="title" value="<?=$title?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?=lang('entry_content')?></label>
							<div class="col-sm-9">
								<textarea name="content" id="content" class="form-control summernote"><?=$content?></textarea>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-2">
						<div class="form-group">
							<label class="col-sm-3 control-label"><?=lang('entry_meta_description')?></label>
							<div class="col-sm-9">
								<textarea name="meta_description" class="form-control"><?=$meta_description?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?=lang('entry_meta_keyword')?></label>
							<div class="col-sm-9">
								<input type="text" name="meta_keyword" value="<?=$meta_keyword?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-3">
						<div class="form-group">
							<label class="col-sm-3 control-label"><?=lang('entry_sort_order')?></label>
							<div class="col-sm-3">
								<input type="text" name="sort_order" value="<?=$sort_order?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"><?=lang('entry_active')?></label>
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
			<button type="button" class="btn btn-primary" id="submit"><i class="fa fa-check"></i> <?=lang('button_save')?></button>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('.summernote').summernote({
			height: 250,
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'clear']],
				['fontname', ['fontname']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link']]
			]
		});	
	});
	
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
						$('textarea[name=\''+i+'\']').parent().addClass('has-error');
						$('textarea[name=\''+i+'\']').after('<span class="text-danger">' + json['error'][i] + '</span>');	
					}
				} else if (json['success']){
					$('#form-modal').modal('hide');
					$.notify({
						title: '<strong>Success</strong><br>',
						message: json['success'],
						icon: 'fa fa-check' 
					},{
						type: "success"
					});
				}
			}
		});
	});
</script>