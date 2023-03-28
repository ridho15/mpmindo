<div class="page-title">
	<div>
		<h1><?=$heading_title?></h1>
	</div>
	<div class="btn-group">
		<a onclick="window.location = '<?=admin_url('blog_category')?>';" class="btn btn-default"><i class="fa fa-reply"></i> Back</a>
		<button onclick="$('#form').submit();" class="btn btn-primary"><i class="fa fa-check"></i> Save</button>
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
				<input type="hidden" name="blog_category_id" value="<?=$blog_category_id?>">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-1" data-toggle="tab" id="default">General</a></li>
					<li><a href="#tab-2" data-toggle="tab">Konfigurasi</a></li>
				</ul>
				<div class="tab-content" style="padding-top:20px;">
					<div class="tab-pane active" id="tab-1">
						<div class="form-group">
							<label class="col-sm-2 control-label">Nama</label>
							<div class="col-sm-10">
								<input type="text" name="name" value="<?=$name?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Deskripsi</label>
							<div class="col-sm-10">
								<textarea name="description" id="description" class="form-control summernote"><?=$description?></textarea>
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
							<label class="col-sm-2 control-label">Meta Keyword</label>
							<div class="col-sm-10">
								<input type="text" name="meta_keyword" value="<?=$meta_keyword?>" class="form-control" placeholder="Separate keywords with comma(,)">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Image</label>
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
							<label class="col-sm-2 control-label">Sort Order</label>
							<div class="col-sm-3">
								<input type="text" name="sort_order" value="<?=$sort_order?>" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">Status</label>
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
		</div>
	</div>
</div>