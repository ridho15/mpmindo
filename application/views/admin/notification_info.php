<a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell fa-lg"></i>
	<?php if ($count_notifications) {?>
	<span class="counter counter-lg" id="notification-badge"><?=$count_notifications?></span>
	<?php }?>
</a>
<ul class="dropdown-menu">
	<li class="not-head"><small>
		<?php if ($count_notifications) {?>
		Ada <?=$count_notifications?> notifikasi belum dilihat
		<?php } else {?>
		Belum ada notifikasi baru
		<?php }?>
		</small>
	</li>
	<?php if ($count_order_notifications) {?>
	<li>
		<a class="media" href="<?=admin_url('notifications')?>"><span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-primary"></i><i class="fa fa-shopping-bag fa-stack-1x fa-inverse"></i></span></span>
			<div class="media-body">
				<span class="block">Pemesanan</span>
				<span class="text-muted block"><small>Ada <?=$count_order_notifications?> yang belum dilihat</small></span>
			</div>
		</a>
	</li>
	<?php }?>
	<?php if ($count_reg_sale_offline_notifications) {?>
	<li>
		<a class="media" href="<?=admin_url('notifications')?>"><span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-primary"></i><i class="fa fa-shopping-bag fa-stack-1x fa-inverse"></i></span></span>
			<div class="media-body">
				<span class="block">Registrasi Produk</span>
				<span class="text-muted block"><small>Ada <?=$count_reg_sale_offline_notifications?> yang belum dilihat</small></span>
			</div>
		</a>
	</li>
	<?php }?>
	<?php if ($warranty_notifications) {?>
	<li>
		<a class="media" href="<?=admin_url('notifications')?>"><span class="media-left media-icon"><span class="fa-stack fa-lg"><i class="fa fa-circle fa-stack-2x text-primary"></i><i class="fa fa-shopping-bag fa-stack-1x fa-inverse"></i></span></span>
			<div class="media-body">
				<span class="block">Klaim Garansi</span>
				<span class="text-muted block"><small>Ada <?=$warranty_notifications?> yang belum dilihat</small></span>
			</div>
		</a>
	</li>
	<?php }?>
	<li class="not-footer"><a href="<?=admin_url('notifications')?>">Lihat semua notifikasi</a></li>
</ul>