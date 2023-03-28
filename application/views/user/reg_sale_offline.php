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
						<button class="ps-btn mt-3 mb-3"><a href="<?=user_url('reg_sale_offline/add')?>"><i class="fa fa-plus"></i> Registrasi</a></button>
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover" width="100%" id="datatable">
								<thead>
									<tr>
										<th>Tanggal Register</th>
										<th>Kode Serial Produk</th>
										<th>Nama Produk</th>
										<th>Tipe Registrasi</th>
										<th>Nama Toko</th>
										<th>Mulai Garansi</th>
										<th>Akhir Garansi</th>
										<th>Keterangan</th>
										<th width="25%"></th>
									</tr>
								</thead>
								<tbody>
								<?php foreach ($registrations as $registration) {?>
									<tr>
										<td><?=date('d-m-Y', strtotime($registration['register_date']));?></td>
										<td><?=$registration['product_code']?></td>
										<td><?=$registration['product_name']?></td>
										<td><?php if($registration['register_type'] == "reg_by_staff") echo 'Registrasi oleh staff';
										else if($registration['register_type'] == "reg_by_online") echo 'Registrasi dari belanja online';
										else if($registration['register_type'] == "reg_by_customer") echo 'Registrasi oleh customer';?></td>
										<td><?=$registration['shop_name']?></td>
										<td><?=date('d-m-Y', strtotime($registration['start_warranty']));?></td>
										<td>
										<?php 
											$period = '+ '.$this->config->item('warranty_period').' month';
											if(strtotime(date('d-m-Y')) > strtotime($registration['start_warranty'].$period)) $style = "style='color: red; font-weight: bold;'"; else $style = "";
											echo "<span ".$style.">".date('d-m-Y', strtotime($registration['start_warranty'].$period))."</span>";
										?>
										</td>
										<td><?=$registration['notes']?></td>
										<td>
										<?php if($registration['register_type'] != 'reg_by_online') {?>
											<a onclick="getPhoto('<?=$registration['id']?>', '/reg_sale_offline/photo');" style="text-decoration: underline;cursor:pointer;"><span class="fa fa-photo"></span> Foto Struk Pembelian</a>
										<?php } ?>
											<a href="<?=user_url('reg_sale_offline/claim_warranty?detail_id='.$registration['id'])?>" style="text-decoration: underline;cursor:pointer;"><span class="fa fa-medkit"></span> Klaim Garansi</a>
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
function getPhoto(reg_id) {
	$.ajax({
		url : "<?=user_url('reg_sale_offline/photo')?>",
		data: 'reg_id='+reg_id,
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
</script>