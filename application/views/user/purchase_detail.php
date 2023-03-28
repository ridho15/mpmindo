<style>
@media screen and (max-width:767px){
  .nav-tabs > li {width:100%;}
}
</style>
<div class="ps-breadcrumb">
	<div class="ps-container">
		<ul class="breadcrumb">
			<?php foreach ($template['breadcrumbs'] as $key => $breadcrumb) {?>
			<?php if (count($breadcrumb) == $key) {?>
			<li><?=$breadcrumb['text']?></li>
			<?php } else {?>
			<li><a href="<?=$breadcrumb['href']?>"><?=$breadcrumb['text']?></a></li>
			<?php }?>
			<?php }?>
		</ul>
	</div>
</div>
<div class="ps-page--product pb-5">
	<div class="ps-container">
		<div class="ps-page__container">
			<div class="ps-page__left">
				<ul class="nav nav-tabs">
					<li class="nav-item"><a class="nav-link active" href="#tab-1" data-toggle="tab" id="default">Informasi Pesanan</a></li>
					<?php if($order_status_id == $order_complete_id) { ?>
						<li class="nav-item"><a class="nav-link" href="#tab-3" data-toggle="tab">Ulasan</a></li>
						<li class="nav-item"><a class="nav-link" href="#tab-2" data-toggle="tab">Klaim Garansi</a></li>
					<?php } ?>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab-1">
						<legend class="mt-5"><h4>Informasi Pesanan</h4></legend>
						<div class="row">
							<div class="col-md-6 col-12">
								<ul class="list-group">
									<li class="list-group-item"><b>ID Pesanan</b><br>#<?=$order_id?></li>
									<li class="list-group-item"><b>No. Invoice</b><br><?=$invoice_no?></li>
									<li class="list-group-item"><b>Tanggal</b><br><?=$date_added?> WIB</li>
									<li class="list-group-item"><b>Status</b><br><?=$status?></li>
									<li class="list-group-item"><b>Nama</b><br><?=$user_name?></li>
									<li class="list-group-item"><b>Email</b><br><?=$user_email?></li>
									<li class="list-group-item"><b>No. Telepon</b><br><?=$user_telephone?></li>
								</ul>
							</div>
							<div class="col-md-6 col-12">
								<ul class="list-group">		
									<?php if ($tax_id) {?>
									<li class="list-group-item"><b>NPWP</b><br><?=$tax_id?></li>
									<?php }?>
									<li class="list-group-item"><b>Metode Pembayaran</b><br><?=$payment_method?></li>
									<li class="list-group-item"><b>Metode Pengiriman</b><br><?=$shipping_method?></li>
									<li class="list-group-item"><b>Alamat Pengiriman</b><br><?=$shipping_address?></li>
									<li class="list-group-item"><b>Catatan</b><br><?php if($comment) echo $comment; else echo "<br>";?></li>
									<?php if ($waybill) {?>
									<li class="list-group-item"><b>No. Resi</b><br><a style="cursor:pointer;border-bottom:1px dashed #ccc;" onclick="waybill(<?=$order_id?>);"><?=$waybill?></a></li>
									<?php }?>
									<li class="list-group-item">
										<div class="dropdown show">
											<!-- <a class="ps-btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Cetak Invoice</a>
											<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
												<a class="dropdown-item" href="<?=user_url('purchase/eprint?order_id='.$order_id.'&type=invoice_total')?>" target="_blank">Cetak Invoice (Total)</a>
												<?php if ($tax_id) {?>
												<a class="dropdown-item" href="<?=user_url('purchase/eprint?order_id='.$order_id.'&type=invoice_tax')?>" target="_blank">Cetak Invoice (PPN)</a>
												<a class="dropdown-item" href="<?=user_url('purchase/eprint?order_id='.$order_id.'&type=invoice_no_tax')?>" target="_blank">Cetak Invoice (Non PPN)</a>
												<?php }?>
											</div> -->
											<a class="ps-btn" href="<?=user_url('purchase/eprint?order_id='.$order_id.'&type=invoice_total')?>" target="_blank">Cetak Invoice</a>
										</div>
									</li>
								</ul>
								<?php if($order_status_id == $flag_order_status_id) {?>
										<?=form_open($snap_action, 'id="payment-form"')?>
										<input type="hidden" name="result_type" id="result-type" value="">
										<input type="hidden" name="result_data" id="result-data" value="">
										<?=form_close()?><br>
										<div class="alert alert-warning" role="alert">
											<h4><b>Menunggu Pembayaran</b></h4>
											Mohon Segera Selesaikan Pembayaran Anda Sebelum Tanggal <b><?=$expiry_date?> WIB</b>.
										</div>
										<div>
											<button class="ps-btn pull-right" id="button-confirm">Proses Pembayaran <i class="fa fa-chevron-right"></i></button>
										</div>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<legend class="mt-5"><h4>Item Produk</h4></legend>
								<div class="table-responsive">
									<table class="table table-bordered" width="100%">
										<thead>
											<tr style="background-color:#efefef">
												<th><b>Item Pesanan</b></th>
												<th class="text-right"><b>Qty</b></th>
												<th class="text-right"><b>Harga</b></th>
												<th class="text-right"><b>Total</b></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($products as $product) {?>
											<tr>
												<td><?=$product['name']?></td>
												<td class="text-right"><?=$product['quantity']?></td>
												<td class="text-right"><?=$product['price']?></td>
												<td class="text-right"><?=$product['total']?></td>
											</tr>
											<?php }?>
										</tbody>
										<tfoot>
											<?php foreach ($totals as $total) {?>
											<tr>
												<th colspan="3" class="text-right"><b><?=$total['title']?></b></th>
												<th class="text-right"><?=$total['text']?></th>
											</tr>
											<?php }?>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">		
								<legend class="mt-5"><h4>Riwayat Pesanan</h4></legend>
								<table class="table" id="datatable" width="100%">
									<?php foreach ($histories as $history) {?>
									<tr>
										<td><b><?=format_date($history['date_added'], true)?></b><br><?=$history['status']?> - <?=$history['comment']?></td>
									</tr>
									<?php }?>
								</table>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-2">
						<legend class="mt-5"><h4>Daftar Produk Yang Diterima</h4></legend>
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover" width="100%" id="datatable2">
								<thead>
									<tr>
										<th>Kode Serial Produk</th>
										<th>Nama Produk</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($articles as $article) {?>
									<tr>
										<td><?=$article['product_code']?></td>
										<td><?=$article['name']?></td>
										<td>
											<a href="<?=user_url('purchase/claim_warranty?order_p_id='.$article['id'])?>" style="text-decoration: underline;cursor:pointer;"><span class="fa fa-medkit"></span> Klaim Garansi</a>
										</td>
									</tr>
									<?php }?>
								</tbody>
							</table>
						</div>
						<br><br><br><br><br><br><br><br><br><br><br>
					</div>
					<div class="tab-pane" id="tab-3">
						<legend class="mt-5"><h4>Item Produk</h4></legend>
						<div class="table-responsive">
							<table class="table table-bordered" width="100%">
								<thead>
									<tr style="background-color:#efefef">
										<th>Nama Produk</th>
										<th>Rating</th>
										<th>Ulasan</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($ratings as $rating) {?>
									<tr>
										<td><?=$rating['name']?></td>
										<td>
										<span class="fa fa-star" <?php if($rating['value'] >= 1) echo "style='color:orange;'";?>></span>
										<span class="fa fa-star" <?php if($rating['value'] >= 2) echo "style='color:orange;'";?>></span>
										<span class="fa fa-star" <?php if($rating['value'] >= 3) echo "style='color:orange;'";?>></span>
										<span class="fa fa-star" <?php if($rating['value'] >= 4) echo "style='color:orange;'";?>></span>
										<span class="fa fa-star" <?php if($rating['value'] >= 5) echo "style='color:orange;'";?>></span>
										</td>
										<td><?=$rating['notes']?></td>
										<td>
										<?php if($rating['rating_date'] == '') {?>
											<a href="<?=user_url('purchase/rating?order_p_id='.$rating['id'])?>" style="text-decoration: underline;cursor:pointer;"><span class="fa fa-comment-o"></span> Beri Ulasan</a>
										<?php } ?>
										</td>
									</tr>
									<?php }?>
								</tbody>
							</table>
						</div>
						<br><br><br><br><br><br><br><br><br><br><br>
					</div>
				</div>
				<hr>
				<a href="javascript:window.history.back();" class="ps-btn ps-btn--outline"><i class="fa fa-reply"></i> Kembali</a><br><br>
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
<script src="<?=base_url('assets/js/plugins/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/js/plugins/dataTables.bootstrap.min.js')?>"></script>
<script type="text/javascript">
var table = $('#datatable2').DataTable({"order": [[0, 'asc']]});
$( table.table().container() ).removeClass( 'form-inline' );
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
<?php if($order_status_id == $flag_order_status_id) {?>
<script type="text/javascript" src="<?=$snap_js?>" data-client-key="<?=$snap_client_key?>"></script>
<?php if($payment_code == "midtrans"){ ?>
<script type="text/javascript">
$('#button-confirm').click(function (event) {
	event.preventDefault();
	
	$(this).attr('disabled', 'disabled');
	
	$.ajax({
		url: "<?=site_url('payment/token')?>",
		cache: false,
		success: function(data) {
			console.log('token = '+data);
			var resultType = document.getElementById('result-type');
			var resultData = document.getElementById('result-data');
			
			function changeResult(type, data) {
				$('#result-type').val(type);
				$('#result-data').val(JSON.stringify(data));
			}
			
			snap.pay(data, {
				onSuccess: function(result) {
					changeResult('success', result);
					console.log(result.status_message);
					console.log(result);
					$('#payment-form').submit();
				},
				onPending: function(result) {
					changeResult('pending', result);
					console.log(result.status_message);
					$('#payment-form').submit();
				},
				onError: function(result) {
					changeResult('error', result);
					console.log(result.status_message);
					$('#payment-form').submit();
				}
			});
		}
	});
});
</script>
<?php }elseif($payment_code == "xendit"){ ?>
<script type="text/javascript">
$('#button-confirm').click(function (event) {
	event.preventDefault();	
	var btn = $(this);
	var btnTxt = btn.html();
	$.ajax({
		url: "<?=site_url('payment/xendit')?>",
		cache: false,
		beforeSend: function() {
			btn.attr('disabled', true);
			btn.html('<span class="spinner-border spinner-border-sm"></span> Loading...');
		},
		complete: function() {
			btn.attr('disabled', false);
			btn.html(btnTxt);
		},
		success: function(data) {
			window.location.href = data;
		}
	});
});
</script>
<?php } ?>
<?php } ?>