<div class="ps-breadcrumb">
	<div class="ps-container">
		<ul class="breadcrumb">
			<?php foreach ($template['breadcrumbs'] as $key => $breadcrumb) {?>
			<?php if (count($breadcrumb) == $key+1) {?>
			<li><?=$breadcrumb['text']?></li>
			<?php } else {?>
			<li><a href="<?=$breadcrumb['href']?>"><?=$breadcrumb['text']?></a></li>
			<?php }?>
			<?php }?>
		</ul>
	</div>
</div>
<div class="ps-page--product">
	<div class="ps-container">
		<div class="ps-page__container">
			<div class="ps-page__left">
				<div class="row">
					<div class="col-md-12">	
						<div id="notification">
						<?php if($this->session->flashdata('success')) {?>
							<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> <?php echo $this->session->flashdata('success');?></div>
						<?php }?>
						</div>	
						<ul class="list-group">
							<li class="list-group-item"><i class="fa fa-shopping-cart"></i> Pembelian<span class="pull-right"><?=$count_orders?></span></li>
							<li class="list-group-item"><i class="fa fa-user"></i> Nama<span class="pull-right"><?=$user['name']?></span></li>
							<li class="list-group-item"><i class="fa fa-envelope-o"></i> Email<span class="pull-right"><?=$user['email']?></span></li>
							<li class="list-group-item"><i class="fa fa-phone"></i> No. Telepon / Handphone<span class="pull-right"><?=$user['telephone']?></span></li>
							<li class="list-group-item"><i class="fa fa-calendar"></i> Member Sejak<span class="pull-right"><?=format_date($user['date_added'])?></span></li>
						</ul>
						<button class="ps-btn mt-3 mb-3" onclick="window.location = '<?=user_url('profile/edit')?>';"><i class="fa fa-edit"></i> Edit Profil</button>
					</div>
				</div>
			</div>
			<div class="ps-page__right">
				<aside class="widget widget_features">
					<ul class="list-unstyled">
						<li class="mb-3"><a href="<?=user_url()?>"><i class="icon-network"></i> Dashboard</a></li>
						<li class="mb-3"><a href="<?=user_url('profile/edit')?>"><i class="icon-user"></i> Edit Profil</a></li>
						<li class="mb-3"><a href="<?=user_url('purchase')?>"><i class="icon-bag"></i> Pesanan Saya</a></li>
						<li class="mb-3"><a href="<?=user_url('reg_sale_offline')?>"><i class="fa fa-edit"></i> Registrasi Produk</a></li>
						<li class="mb-3"><a href="<?=user_url('warranty')?>"><i class="fa fa-medkit"></i> Klaim Garansi</a></li>
						<li><a href="<?=user_url('logout')?>"><i class="icon-user"></i> Logout</a></li>
					</ul>
				</aside>
			</div>
		</div>
	</div>
</div>