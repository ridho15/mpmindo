<?php if ($products) {?>
<div class="row">
	<?php foreach ($products as $product) {?>
	<div class="col-md-3">
		<div class="thumbnail">
			<a style="cursor:pointer;" onclick="productInfo(<?=$product['user_product_id']?>);" class="thumbnail"><img src="<?=$product['image']?>" class="img-thumbnail"></a>
			<div class="caption text-center">
				<p><strong><?=$product['name']?></strong></p>
			</div>
		</div>
	</div>
	<?php }?>
</div>
<div class="pagination"><?=$pagination?></div>
<?php } else {?>
<div class="alert alert-warning">No products found!</div>
<?php }?>
<div id="modal"></div>
<script type="text/javascript">
	function productInfo(user_product_id) {
		$.ajax({
			url : "<?=user_url('product/product_info')?>",
			data: 'user_product_id='+user_product_id,
			dataType: 'json',
			success: function(json) {
				if (json['content']) {
					$('#modal').html('<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">'+json['content']+'</div>');
					$('#form-modal').modal('show');
				}
			}
		});
	}
</script>