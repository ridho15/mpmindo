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
						<div id="notification">
						<?php if($this->session->flashdata('success')) {?>
							<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> <?php echo $this->session->flashdata('success');?></div>
						<?php }?>
						</div>
						<button class="ps-btn mt-3 mb-3"><a href="<?=user_url('warranty/add')?>"><i class="fa fa-plus"></i> Ajukan Klaim</a></button>
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover" width="100%" id="datatable">
								<thead>
									<tr>
										<th>Tanggal Klaim</th>
										<th>Kode Serial Produk</th>
										<th>Nama Produk</th>
										<th>Alasan Klaim</th>
										<th>Gudang / Distributor Tujuan</th>
										<th>Status</th>
										<th>Informasi</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
								<?php foreach ($warranties as $warranty) {?>
									<tr>
										<td><?=date('d-m-Y', strtotime($warranty['claim_date']));?></td>
										<td><?=$warranty['product_code']?></td>
										<td><?=$warranty['product_name']?></td>
										<td><?=$warranty['reason']?></td>
										<td><?=$warranty['warehouse']?></td>
										<td><?=$warranty['status']?></td>
										<td>
										<?php 
											if($warranty['status'] == 'Klaim Baru' || $warranty['status'] == 'Barang Sudah Diterima Dari Customer Dan Sedang Diperiksa' || $warranty['status'] == 'Customer Telah Melakukan Pembayaran Dan Menunggu Konfirmasi') echo '<i class="fa fa-info"></i> Menunggu Konfirmasi';
											else if($warranty['status'] == 'Barang Belum Diterima Dari Customer') echo '<i class="fa fa-info"></i> Silakan Kirim Barang Anda ke Gudang / Distributor Tujuan Untuk Diproses Garansi (alamat bisa dilihat disamping)';
											else if($warranty['status'] == 'Proses Selesai Dan Barang Dikembalikan Ke Customer' || $warranty['status'] == 'Klaim Tidak Disetujui Dan Barang Dikembalikan Ke Customer') echo '<i class="fa fa-info"></i> Barang Anda Akan Dikirimkan Kembali, Silakan Menunggu Barang Anda Sampai';
											else if($warranty['status'] == 'Klaim Ditolak') echo '<i class="fa fa-info"></i> Silakan Lihat Detail Untuk Alasan Klaim Ditolak';
											else if($warranty['status'] == 'Klaim Dikenakan Biaya Dan Menunggu Pembayaran Dari Customer') echo '<i class="fa fa-info"></i> Klaim ini Dikenakan Biaya Untuk Diproses. Silakan Lihat Detail Untuk Alasan Klaim Dikenakan Biaya dan Nominal Biaya yang Diperlukan. Jika Anda Setuju, Silakan Melakukan Pembayaran (tujuan transfer bisa dilihat disamping), atau Anda Bisa Tidak Melanjutkan Klaim dan Barang Akan Dikembalikan.';
										?>
										</td>
										<td>
											<?php if($warranty['status'] == 'Barang Belum Diterima Dari Customer') {?>
											<a onclick="getAddress('<?=$warranty['warehouse_id']?>', '/warranty/address');" style="text-decoration: underline;cursor:pointer;"><span class="fa fa-book"></span> Lihat Alamat</a><br>
											<?php } ?>
											<?php if($warranty['status'] == 'Klaim Dikenakan Biaya Dan Menunggu Pembayaran Dari Customer') {?>
											<a onclick="getTransferBank('<?=$warranty['claim_id']?>', '/warranty/transfer_bank');" style="text-decoration: underline;cursor:pointer;"><span class="fa fa-bank"></span> Lihat Alamat Transfer Bank</a><br>
											<a href="<?=user_url('warranty/transfer_done?claim_id='.$warranty['claim_id'])?>" style="text-decoration: underline;cursor:pointer;"><span class="fa fa-check"></span> Klaim Sudah Dibayar</a><br>
											<a onclick="stopClaim('<?=$warranty['claim_id']?>', '/warranty/stop_claim');" style="text-decoration: underline;cursor:pointer;"><span class="fa fa-times"></span> Tidak Melanjutkan Klaim</a><br>
											<?php } ?>
											<?php if($warranty['status'] == 'Proses Selesai Dan Barang Dikembalikan Ke Customer' || $warranty['status'] == 'Klaim Tidak Disetujui Dan Barang Dikembalikan Ke Customer' || $warranty['status'] == 'Barang Sudah Diterima Oleh Customer' || $warranty['status'] == 'Klaim Tidak Dilanjutkan Customer Dan Barang Dikembalikan Ke Customer') {?>
											<a onclick="finishClaim('<?=$warranty['claim_id']?>');" style="text-decoration: underline;cursor:pointer;"><span class="fa fa-check"></span> Klaim Selesai</a><br>
											<?php } ?>
											<a href="<?=user_url('warranty/detail?claim_id='.$warranty['claim_id'])?>" style="text-decoration: underline;cursor:pointer;"><span class="fa fa-eye"></span> Lihat Detail</a>
										</td>
									</tr>
									<?php }?>
								</tbody>
							</table>
						</div>
						<br><br><br><br><br><br><br><br><br><br><br>
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
<div id="modal"></div>
<script src="<?=base_url('assets/js/plugins/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/js/plugins/dataTables.bootstrap.min.js')?>"></script>
<script type="text/javascript">
var table = $('#datatable').DataTable({
    "ordering": false
});
$( table.table().container() ).removeClass( 'form-inline' );
function getAddress(warehouse_id) {
	$.ajax({
		url : "<?=user_url('warranty/warehouse')?>",
		data: 'warehouse_id='+warehouse_id,
		dataType: 'json',
		success: function(json) {
			if (json['error']) {
				$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
			} else if (json['content']) {
				$('#modal').html('<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">'+json['content']+'</div>');
				$('#form-modal').modal('show');
				$('#form-modal').on('hidden.bs.modal', function (e) {
					$('#form-modal').remove();
				});	
			}
		}
	});
}
function finishClaim(claim_id) {
	if(window.confirm('Apa anda yakin ingin menyelesaikan klaim?')) {
		$.ajax({
			url : "<?=user_url('warranty/finish_claim')?>",
			data: 'claim_id='+claim_id,
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
				} else if (json['success']) {
					window.location = "<?=user_url('warranty')?>";
				}
			}
		});
	}
}
function getTransferBank(claim_id) {
	$.ajax({
		url : "<?=user_url('warranty/transfer_bank')?>",
		data: 'claim_id='+claim_id,
		dataType: 'json',
		success: function(json) {
			if (json['error']) {
				$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
			} else if (json['content']) {
				$('#modal').html('<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">'+json['content']+'</div>');
				$('#form-modal').modal('show');
				$('#form-modal').on('hidden.bs.modal', function (e) {
					$('#form-modal').remove();
				});	
			}
		}
	});
}
function stopClaim(claim_id) {
	if(window.confirm('Apa anda yakin tidak ingin melanjutkan klaim?')) {
		$.ajax({
			url : "<?=user_url('warranty/stop_claim')?>",
			data: 'claim_id='+claim_id,
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
				} else if (json['success']) {
					window.location = "<?=user_url('warranty')?>";
				}
			}
		});
	}
}
</script>