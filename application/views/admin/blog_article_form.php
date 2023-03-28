<div class="page-title">
	<div>
		<h1><?=$heading_title?></h1>
	</div>
	<div class="btn-group">
					<a onclick="window.location = '<?=admin_url('blog_article')?>';" class="btn btn-default"><i class="fa fa-reply"></i> Back</a>
					<button onclick="$('#form').submit();" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
				</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<?php if ($errors) {?>
				<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?=$errors?></div>
				<?php }?>
				<?=form_open($action, 'id="form" class="form-horizontal"')?>
				<input type="hidden" name="blog_article_id" value="<?=$blog_article_id?>">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-1" data-toggle="tab" id="default">General</a></li>
					<li><a href="#tab-2" data-toggle="tab">Data</a></li>
					<li><a href="#tab-3" data-toggle="tab">Configuration</a></li>
				</ul>
				<div class="tab-content" style="padding-top:20px;">
					<div class="tab-pane active" id="tab-1">
						<div class="form-group">
							<label class="col-sm-2 control-label">Title</label>
							<div class="col-sm-10">
								<input type="text" name="title" value="<?=$title?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Intro</label>
							<div class="col-sm-10">
								<textarea name="description" rows="8" class="form-control"><?=$description?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Content</label>
							<div class="col-sm-10">
								<textarea name="content" id="content" class="form-control summernote"><?=$content?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">SEO Title</label>
							<div class="col-sm-10">
								<input type="text" name="seo_title" value="<?=$seo_title?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Meta Description</label>
							<div class="col-sm-10">
								<textarea name="meta_description" rows="8" class="form-control"><?=$meta_description?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Meta Keywords</label>
							<div class="col-sm-10">
								<input type="text" name="meta_keyword" value="<?=$meta_keyword?>" class="form-control" placeholder="Separate keywords with comma(,)">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Gambar</label>
							<div class="col-sm-10">
								<div class="thumbnail">
									<a href="#" class="img-thumbnail" id="thumb" data-toggle="image"><img src="<?=$thumb?>" data-placeholder="<?=$thumb?>"/></a>
									<input type="hidden" id="image" name="image" value="<?=$image?>">
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-2">
						<div class="form-group">
							<label class="col-sm-2 control-label">Kategori</label>
							<div class="col-sm-10">
								<input type="text" name="category" value="" class="form-control" autocomplete="off">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label"></label>
							<div class="col-sm-10">
								<div class="controls" style="border: 1px solid #ddd; height: 200px; background: #fff; overflow-y: scroll;">
									<table class="table table-striped" width="100%">
										<tbody id="article-category">
											<?php foreach ($categories as $category) {?>
											<tr id="article-category<?=$category['blog_category_id']?>">
												<td><?=$category['category']?><input type="hidden" name="category[]" value="<?=$category['blog_category_id']?>"></td>
												<td class="text-right"><a class="btn btn-danger btn-xs btn-flat" onclick="$('#article-category<?=$category['blog_category_id']?>').remove();"><i class="fa fa-remove"></i></a></td> 
											</tr>
											<?php }?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Urutan</label>
							<div class="col-sm-3">
								<input type="text" name="sort_order" value="<?=$sort_order?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-3">
						<div class="form-group">
							<label class="col-sm-2 control-label">Published</label>
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
						<div class="form-group">
							<label class="col-sm-2 control-label">Komentar</label>
							<div class="toggle lg col-sm-4">
								<label style="margin-top:5px;">
								<?php if ($allow_comment) {?>
								<input type="checkbox" name="allow_comment" value="1" checked="checked">
								<?php } else {?>
								<input type="checkbox" name="allow_comment" value="1">
								<?php }?>
								<span class="button-indecator"></span>
								</label>
							</div>
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="<?=base_url('themes/admin/assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.min.js')?>"></script>
<script type="text/javascript">
	$('input[name=\'category\']').typeahead({
		source: function(query, process) {
			$.ajax({
				url: "<?=admin_url('blog_category/auto_complete')?>",
				data: 'name=' + query,
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
			$('#article-category' + map[item].blog_category_id).remove();
			$('#article-category').append('<tr id="article-category' + map[item].blog_category_id + '"><td>' + item + '<input type="hidden" name="category[]" value="' + map[item].blog_category_id + '" /></td><td class="text-right"><a class="btn btn-danger btn-xs btn-flat" onclick="$(\'#article-category' + map[item].blog_category_id + '\').remove();"><i class="fa fa-remove "></i></a></td></tr>');
		},
		minLength: 1
	});
</script>