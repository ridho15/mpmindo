<?php if ($comments) {?>
<?php foreach ($comments as $key=>$comment) {?>
<div class="media">
	<a class="pull-left"><img class="media-object" src="<?=$comment['image']?>"></a>
	<div class="media-body" id="accordion<?=$key?>">
		<div class="media-heading">
			<strong><?=$comment['author']?></strong> <?php if($comment['is_seller']){echo '<span class="label label-success">Penjual</span>';}else{echo '<span class="label label-warning">Pembeli</span>';}?><br><small class="text-muted"><?=$comment['date_added']?></small>
		</div>
		<?=$comment['text']?>
		<br/>
		<i><small><a class="text-muted" onclick="commentForm('<?=$comment['comment_id']?>');" style="cursor:pointer;">Balas</a></small></i>
		<?php if ($comment['child']) {?>
		- <i><small><a data-toggle="collapse" data-parent="#accordion<?=$key?>" href="#collapse_<?=$comment['comment_id']?>">Lihat <?=count($comment['child'])?> komentar lainnya</a></small></i>
		<br/>
		<div id="collapse_<?=$comment['comment_id']?>" class="collapse">
		<?php foreach ($comment['child'] as $child) {?>
		<hr>
		<div class="media">
			<a class="pull-left"><img class="media-object" src="<?=$child['image']?>"></a>
			<div class="media-body">
				<div class="media-heading">
					<strong><?=$child['author']?></strong> <?php if($child['is_seller']){echo '<span class="label label-success">Penjual</span>';}else{echo '<span class="label label-warning">Pembeli</span>';}?><br><small class="text-muted"><?=$child['date_added']?></small>
				</div>
				<?=$child['text']?><br/>
				<i><small><a class="text-muted" onclick="commentForm('<?=$comment['comment_id']?>');" style="cursor:pointer;">Balas</a></small></i>
			</div>
		</div>
		<?php }?>
		</div>
		<?php }?>
	</div>
</div>
<hr>
<?php }?>
<?php } else {?>
<p>Tidak ada diskusi untuk produk ini</p>
<?php }?>
<?php if ($next) {?>
<a href="<?=$next?>" class="next"><i class="fa fa-plus"></i> Tampilkan lebih banyak</a>
<?php }?>