<?php foreach($selectArticles as $selectArticle) {?>
<div class="form-group">
	<label class="control-label col-sm-3"><?php echo $selectArticle['product_name'].' ('.$selectArticle['qty'].')';?></label>
	<div class="col-sm-9">
		<select multiple class="form-control" name="selectProduct<?php echo $selectArticle['product_id'];?>[]" id="selectProduct<?php echo $selectArticle['product_id'];?>[]" size="7">
			<?php foreach($selectArticle['articles'] as $article) { ?>
				<option value="<?php echo $article['id'];?>"><?php echo $article['product_code'];?></option>
			<?php } ?>
		</select>
	</div>
</div>
<?php } ?>
<?php if(count($selectArticles) == 0) {?>
<div class="form-group">
	<div class="col-sm-12">
		<b>Tidak Perlu Pilih Stok</b>
	</div>
</div>
<?php } ?>