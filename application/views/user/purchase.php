<style>
@media screen and (max-width:767px){
  .nav-tabs > li {width:100%;}
}
</style>
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
						<div id="message"></div>
						<ul class="nav nav-tabs">
							<li class="nav-item"><a class="nav-link active" href="#tab-1" data-toggle="tab" id="default">Semua</a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-2" data-toggle="tab">Menunggu Pembayaran</a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-3" data-toggle="tab">Pembayaran Lunas, Menunggu Konfirmasi</a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-4" data-toggle="tab">Pesanan Diproses</a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-5" data-toggle="tab">Pesanan Dikirim</a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-6" data-toggle="tab">Pesanan Selesai</a></li>
							<li class="nav-item"><a class="nav-link" href="#tab-7" data-toggle="tab">Pesanan Dibatalkan</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane mt-5 active" id="tab-1">
								<?php foreach ($orders as $order) {?>
								<div class="card mb-3">
									<div class="card-body">
										<div class="row">
											<div class="col-xs-12 col-md-6">
												<a href="<?=user_url('purchase/detail/'.$order['order_id'])?>"><h5 class="card-title"><?=$order['invoice_no']?></h5></a>
												<p class="card-text">
													Tanggal: <?=format_date($order['date_added'], true)?><br>
													Total Belanja: Rp <?=format_money($order['total'], 0)?><br>
												</p>
											</div>
											<div class="col-xs-12 col-md-6">
												<p class="card-text">Status: <?=$order['status']?></p>
												<?php if ($order['order_status_id'] == $this->config->item('order_delivered_status_id')) {?>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="waybill(<?=$order['order_id']?>, this);"><span class="fa fa-truck"></span> Lacak Pengiriman</button>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="confirmAccept(<?=$order['order_id']?>);"><span class="fa fa-check"></span> Terima</button>
												<?php }?>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="window.location = '<?=user_url('purchase/detail/'.$order['order_id'])?>';"><span class="fa fa-eye"></span> Detail</button>
											</div>
										</div>
									</div>
								</div>
								<?php }?>
								<?php if(count($orders) <= 0) { ?>
									<div class="card mb-3">
										<div class="card-body">
											<div class="row">
												<div class="col-xs-12 col-md-6">
													<p class="card-text">
														Belum ada riwayat transaksi
													</p>
												</div>
											</div>
										</div>
									</div>
								<?php }?>
								<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
							</div>
							<div class="tab-pane mt-5" id="tab-2">
								<?php 
									$order_waits = [];
									foreach ($orders as $order) { 
										if($order['order_status_id'] == $this->config->item('order_status_id')) $order_waits[] = $order;
									} 
								?>
								<?php foreach ($order_waits as $order) { ?>
								<div class="card mb-3">
									<div class="card-body">
										<div class="row">
											<div class="col-xs-12 col-md-6">
												<a href="<?=user_url('purchase/detail/'.$order['order_id'])?>"><h5 class="card-title"><?=$order['invoice_no']?></h5></a>
												<p class="card-text">
													Tanggal: <?=format_date($order['date_added'], true)?><br>
													Total Belanja: Rp <?=format_money($order['total'], 0)?><br>
												</p>
											</div>
											<div class="col-xs-12 col-md-6">
												<p class="card-text">Status: <?=$order['status']?></p>
												<?php if ($order['order_status_id'] == $this->config->item('order_delivered_status_id')) {?>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="waybill(<?=$order['order_id']?>, this);"><span class="fa fa-truck"></span> Lacak Pengiriman</button>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="confirmAccept(<?=$order['order_id']?>);"><span class="fa fa-check"></span> Terima</button>
												<?php }?>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="window.location = '<?=user_url('purchase/detail/'.$order['order_id'])?>';"><span class="fa fa-eye"></span> Detail</button>
											</div>
										</div>
									</div>
								</div>
								<?php }?>
								<?php if(count($order_waits) <= 0) { ?>
									<div class="card mb-3">
										<div class="card-body">
											<div class="row">
												<div class="col-xs-12 col-md-6">
													<p class="card-text">
														Tidak ada transaksi
													</p>
												</div>
											</div>
										</div>
									</div>
								<?php }?>
								<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
							</div>
							<div class="tab-pane mt-5" id="tab-3">
								<?php 
									$order_paids = [];
									foreach ($orders as $order) { 
										if($order['order_status_id'] == $this->config->item('order_paid_status_id')) $order_paids[] = $order;
									} 
								?>
								<?php foreach ($order_paids as $order) { ?>
								<div class="card mb-3">
									<div class="card-body">
										<div class="row">
											<div class="col-xs-12 col-md-6">
												<a href="<?=user_url('purchase/detail/'.$order['order_id'])?>"><h5 class="card-title"><?=$order['invoice_no']?></h5></a>
												<p class="card-text">
													Tanggal: <?=format_date($order['date_added'], true)?><br>
													Total Belanja: Rp <?=format_money($order['total'], 0)?><br>
												</p>
											</div>
											<div class="col-xs-12 col-md-6">
												<p class="card-text">Status: <?=$order['status']?></p>
												<?php if ($order['order_status_id'] == $this->config->item('order_delivered_status_id')) {?>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="waybill(<?=$order['order_id']?>, this);"><span class="fa fa-truck"></span> Lacak Pengiriman</button>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="confirmAccept(<?=$order['order_id']?>);"><span class="fa fa-check"></span> Terima</button>
												<?php }?>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="window.location = '<?=user_url('purchase/detail/'.$order['order_id'])?>';"><span class="fa fa-eye"></span> Detail</button>
											</div>
										</div>
									</div>
								</div>
								<?php }?>
								<?php if(count($order_paids) <= 0) { ?>
									<div class="card mb-3">
										<div class="card-body">
											<div class="row">
												<div class="col-xs-12 col-md-6">
													<p class="card-text">
														Tidak ada transaksi
													</p>
												</div>
											</div>
										</div>
									</div>
								<?php }?>
								<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
							</div>
							<div class="tab-pane mt-5" id="tab-4">
								<?php 
									$order_processes = [];
									foreach ($orders as $order) { 
										if($order['order_status_id'] == $this->config->item('order_process_status_id')) $order_processes[] = $order;
									} 
								?>
								<?php foreach ($order_processes as $order) { ?>
								<div class="card mb-3">
									<div class="card-body">
										<div class="row">
											<div class="col-xs-12 col-md-6">
												<a href="<?=user_url('purchase/detail/'.$order['order_id'])?>"><h5 class="card-title"><?=$order['invoice_no']?></h5></a>
												<p class="card-text">
													Tanggal: <?=format_date($order['date_added'], true)?><br>
													Total Belanja: Rp <?=format_money($order['total'], 0)?><br>
												</p>
											</div>
											<div class="col-xs-12 col-md-6">
												<p class="card-text">Status: <?=$order['status']?></p>
												<?php if ($order['order_status_id'] == $this->config->item('order_delivered_status_id')) {?>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="waybill(<?=$order['order_id']?>, this);"><span class="fa fa-truck"></span> Lacak Pengiriman</button>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="confirmAccept(<?=$order['order_id']?>);"><span class="fa fa-check"></span> Terima</button>
												<?php }?>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="window.location = '<?=user_url('purchase/detail/'.$order['order_id'])?>';"><span class="fa fa-eye"></span> Detail</button>
											</div>
										</div>
									</div>
								</div>
								<?php }?>
								<?php if(count($order_processes) <= 0) { ?>
									<div class="card mb-3">
										<div class="card-body">
											<div class="row">
												<div class="col-xs-12 col-md-6">
													<p class="card-text">
														Tidak ada transaksi
													</p>
												</div>
											</div>
										</div>
									</div>
								<?php }?>
								<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
							</div>
							<div class="tab-pane mt-5" id="tab-5">
								<?php 
									$order_delivers = [];
									foreach ($orders as $order) { 
										if($order['order_status_id'] == $this->config->item('order_delivered_status_id')) $order_delivers[] = $order;
									} 
								?>
								<?php foreach ($order_delivers as $order) { ?>
								<div class="card mb-3">
									<div class="card-body">
										<div class="row">
											<div class="col-xs-12 col-md-6">
												<a href="<?=user_url('purchase/detail/'.$order['order_id'])?>"><h5 class="card-title"><?=$order['invoice_no']?></h5></a>
												<p class="card-text">
													Tanggal: <?=format_date($order['date_added'], true)?><br>
													Total Belanja: Rp <?=format_money($order['total'], 0)?><br>
												</p>
											</div>
											<div class="col-xs-12 col-md-6">
												<p class="card-text">Status: <?=$order['status']?></p>
												<?php if ($order['order_status_id'] == $this->config->item('order_delivered_status_id')) {?>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="waybill(<?=$order['order_id']?>, this);"><span class="fa fa-truck"></span> Lacak Pengiriman</button>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="confirmAccept(<?=$order['order_id']?>);"><span class="fa fa-check"></span> Terima</button>
												<?php }?>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="window.location = '<?=user_url('purchase/detail/'.$order['order_id'])?>';"><span class="fa fa-eye"></span> Detail</button>
											</div>
										</div>
									</div>
								</div>
								<?php }?>
								<?php if(count($order_delivers) <= 0) { ?>
									<div class="card mb-3">
										<div class="card-body">
											<div class="row">
												<div class="col-xs-12 col-md-6">
													<p class="card-text">
														Tidak ada transaksi
													</p>
												</div>
											</div>
										</div>
									</div>
								<?php }?>
								<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
							</div>
							<div class="tab-pane mt-5" id="tab-6">
								<?php 
									$order_finishes = [];
									foreach ($orders as $order) { 
										if($order['order_status_id'] == $this->config->item('order_complete_status_id')) $order_finishes[] = $order;
									} 
								?>
								<?php foreach ($order_finishes as $order) { ?>
								<div class="card mb-3">
									<div class="card-body">
										<div class="row">
											<div class="col-xs-12 col-md-6">
												<a href="<?=user_url('purchase/detail/'.$order['order_id'])?>"><h5 class="card-title"><?=$order['invoice_no']?></h5></a>
												<p class="card-text">
													Tanggal: <?=format_date($order['date_added'], true)?><br>
													Total Belanja: Rp <?=format_money($order['total'], 0)?><br>
												</p>
											</div>
											<div class="col-xs-12 col-md-6">
												<p class="card-text">Status: <?=$order['status']?></p>
												<?php if ($order['order_status_id'] == $this->config->item('order_delivered_status_id')) {?>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="waybill(<?=$order['order_id']?>, this);"><span class="fa fa-truck"></span> Lacak Pengiriman</button>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="confirmAccept(<?=$order['order_id']?>);"><span class="fa fa-check"></span> Terima</button>
												<?php }?>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="window.location = '<?=user_url('purchase/detail/'.$order['order_id'])?>';"><span class="fa fa-eye"></span> Detail</button>
											</div>
										</div>
									</div>
								</div>
								<?php }?>
								<?php if(count($order_finishes) <= 0) { ?>
									<div class="card mb-3">
										<div class="card-body">
											<div class="row">
												<div class="col-xs-12 col-md-6">
													<p class="card-text">
														Tidak ada transaksi
													</p>
												</div>
											</div>
										</div>
									</div>
								<?php }?>
								<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
							</div>
							<div class="tab-pane mt-5" id="tab-7">
								<?php 
									$order_cancels = [];
									foreach ($orders as $order) { 
										if($order['order_status_id'] == $this->config->item('order_cancel_status_id')) $order_cancels[] = $order;
									} 
								?>
								<?php foreach ($order_cancels as $order) { ?>
								<div class="card mb-3">
									<div class="card-body">
										<div class="row">
											<div class="col-xs-12 col-md-6">
												<a href="<?=user_url('purchase/detail/'.$order['order_id'])?>"><h5 class="card-title"><?=$order['invoice_no']?></h5></a>
												<p class="card-text">
													Tanggal: <?=format_date($order['date_added'], true)?><br>
													Total Belanja: Rp <?=format_money($order['total'], 0)?><br>
												</p>
											</div>
											<div class="col-xs-12 col-md-6">
												<p class="card-text">Status: <?=$order['status']?></p>
												<?php if ($order['order_status_id'] == $this->config->item('order_delivered_status_id')) {?>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="waybill(<?=$order['order_id']?>, this);"><span class="fa fa-truck"></span> Lacak Pengiriman</button>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="confirmAccept(<?=$order['order_id']?>);"><span class="fa fa-check"></span> Terima</button>
												<?php }?>
												<button class="ps-btn ps-btn--outline ps-btn--sm" onclick="window.location = '<?=user_url('purchase/detail/'.$order['order_id'])?>';"><span class="fa fa-eye"></span> Detail</button>
											</div>
										</div>
									</div>
								</div>
								<?php }?>
								<?php if(count($order_cancels) <= 0) { ?>
									<div class="card mb-3">
										<div class="card-body">
											<div class="row">
												<div class="col-xs-12 col-md-6">
													<p class="card-text">
														Tidak ada transaksi
													</p>
												</div>
											</div>
										</div>
									</div>
								<?php }?>
								<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
							</div>
						</div>
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
<script type="text/javascript">
	function confirmAccept(order_id) {
		if (confirm('Dengan ini anda menyatakan bahwa barang sudah diterima. Apakah anda yakin?')) {
			$.ajax({
				url : "<?=user_url('purchase/accept')?>",
				data: 'order_id='+order_id,
				type: 'post',
				dataType: 'json',
				success: function(json) {
					if (json['error']) {
						$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
					} else if (json['success']) {
						$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> '+json['success']+'</div>');
						location.reload();
					}
				}
			});	
		}
	}
	
	function waybill(order_id, elem) {
		var btn = $(elem);
		var html = btn.html();
		$.ajax({
			url: "<?=site_url('tracking')?>",
			data: 'order_id='+order_id,
			type: 'get',
			dataType: 'json',
			beforeSend: function() {
				btn.html('Mohon tunggu...');
				btn.attr('disabled', true);
			},
			complete: function() {
				btn.html(html);
				btn.attr('disabled', false);
			},
			success: function(json) {
				$('body').append('<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">'+json['content']+'</div>');
				$('#modal').modal('show');
				$('#modal').on('hidden.bs.modal', function (e) {
					$('#modal').remove();
				});	
			}
		});
	}
</script>