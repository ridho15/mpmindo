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
						<h3>Pilih Produk Yang Ingin Diajukan Klaim Garansi</h3><br>
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover" width="100%" id="datatable">
								<thead>
									<tr>
										<th width="40%">Kode Serial Produk</th>
										<th width="40%">Nama Produk</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
								<?php foreach ($products as $product) {?>
									<tr>
										<td><?=$product['product_code'];?></td>
										<td><?=$product['product_name'];?></td>
										<td>
											<a href="<?=user_url('warranty/add_detail?id='.$product['id'])?>" style="text-decoration: underline;cursor:pointer;"><span class="fa fa-medkit"></span> Klaim Garansi</a>
										</td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
						<br><br><br><br><br><br><br><br><br><br><br>
						<hr>
						<div class="mb-5">
							<button onclick="window.location='<?=user_url('warranty')?>';" class="ps-btn"><i class="fa fa-reply"></i> Kembali</button>
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
<div id="modal"></div>
<script src="<?=base_url('assets/js/plugins/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/js/plugins/dataTables.bootstrap.min.js')?>"></script>
<script type="text/javascript">
var table = $('#datatable').DataTable({
    "ordering": false
});
$( table.table().container() ).removeClass( 'form-inline' );
var table2 = $('#datatable2').DataTable({
    "ordering": false
});
$( table2.table().container() ).removeClass( 'form-inline' );
</script>