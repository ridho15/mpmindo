<?php if ($reviews) {?>
<?php foreach ($reviews as $review) {?>
<div class="media">
	<a class="pull-left"><img class="media-object" src="<?=$review['image']?>"></a>
	<div class="media-body">
		<div class="row">
			<div class="col-md-6 col-xs-12">
				<div class="media-heading">
					<strong><?=$review['author']?></strong><br><small class="text-muted"><?=$review['date_added']?></small>
				</div>
				<?=$review['text']?>
			</div>
			<div class="col-md-6 col-xs-12">
				<dl class="dl-horizontal">
					<dt>Kualitas</dt>
					<dd>
						<?php for ($i=1;$i<=5;$i++) {?>
						<?php if ($i<=$review['quality']) {?>
						<i class="fa fa-star" style="color:gold;"></i>
						<?php } else {?>
						<i class="fa fa-star" style="color:gray;"></i>
						<?php }?>
						<?php }?>
					</dd>
					<dt>Akurasi</dt>
					<dd>
						<?php for ($i=1;$i<=5;$i++) {?>
						<?php if ($i<=$review['accuracy']) {?>
						<i class="fa fa-star" style="color:gold;"></i>
						<?php } else {?>
						<i class="fa fa-star" style="color:gray;"></i>
						<?php }?>
						<?php }?>
					</dd>
				</dl>
			</div>
		</div>
	</div>
</div>
<hr>
<?php }?>
<?php } else {?>
<p>Tidak ada review untuk produk ini</p>
<?php }?>
<?php if ($next) {?>
<a href="<?=$next?>" class="label label-default">Load more</a>
<?php }?>