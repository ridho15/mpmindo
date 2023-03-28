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
						<?=form_open($action, 'id="form" class="form"')?>
							<ul class="nav nav-tabs">
								<li class="nav-item"><a class="nav-link active" href="#tab-1" data-toggle="tab" id="default">Informasi</a></li>
								<li class="nav-item"><a class="nav-link" href="#tab-2" data-toggle="tab">History</a></li>
							</ul>
							<div class="tab-content" style="padding-top:20px;">
								<div class="tab-pane active" id="tab-1">
									<div class="row">
										<div class="col-md-6 col-12">
											<div class="form-group">
												<label>Tanggal Klaim:</label>
												<input type="text" name="productcode" value="<?=date('d-m-Y',strtotime($claim['claim_date']))?>" class="form-control" maxLength="100" readonly/>
											</div>
											<div class="form-group">
												<label>Nama Produk:</label>
												<input type="text" name="productcode" value="<?=$claim['product_name']?>" class="form-control" maxLength="100" readonly/>
											</div>
										</div>
										<div class="col-md-6 col-12">
											<div class="form-group">
												<label>Kode Serial Produk:</label>
												<input type="text" name="productcode" value="<?=$claim['product_code']?>" class="form-control" maxLength="100" readonly/>
											</div>
											<div class="form-group">
												<label>Alasan Klaim:</label>
												<input type="text" name="reason" value="<?=$claim['reason']?>" class="form-control" maxLength="100" readonly/>
											</div>
										</div>
										<div class="col-md-6 col-12">
											<div class="form-group">
												<label>Deskripsi Kerusakan:</label>
												<textarea name="claim_description" class="form-control" readonly><?=$claim['claim_description']?></textarea>
											</div>
											<div class="form-group">
												<label>Status:</label>
												<input type="text" name="reason" value="<?=$claim['status']?>" class="form-control" maxLength="100" readonly/>
											</div>
										</div>
										<div class="col-md-6 col-12">
											<div class="form-group">
												<label>Gudang / Distributor:</label>
												<input type="text" name="reason" value="<?=$warehouse?>" class="form-control" maxLength="100" readonly/>
											</div>
											<?php if($claim['evidence_photo']) { ?>
											<div class="form-group">
												<label>Foto Pendukung Klaim:</label>
												<div class="thumbnail">
												<a href="<?=base_url('storage/images/'.$claim['evidence_photo'])?>">
													<img src="<?=base_url('storage/images/'.$claim['evidence_photo'])?>" alt="Photo" style="width:10%">
												</a>
												</div>
											</div>
											<?php } ?>
										</div>
										<div class="col-md-6 col-12">
										<?php if($claim['response_note']) { ?>
											<div class="form-group">
												<label>Respon Klaim:</label>
												<textarea name="claim_description" class="form-control" readonly><?=$claim['response_note']?></textarea>
											</div>
											<?php } ?>
											<?php if($claim['update_date']) { ?>
											<div class="form-group">
												<label>Terakhir Update Tanggal:</label>
												<input type="text" name="reason" value="<?=date('d-m-Y H:i:s',strtotime($claim['update_date']))?>" class="form-control" maxLength="100" readonly/>
											</div>
											<?php } ?>
										</div>
										<div class="col-md-6 col-12">
											<?php if($claim['response_photo']) { ?>
											<div class="form-group">
												<label>Foto Pendukung Respon:</label>
												<div class="thumbnail">
												<a href="<?=base_url('storage/images/'.$claim['response_photo'])?>">
													<img src="<?=base_url('storage/images/'.$claim['response_photo'])?>" alt="Photo" style="width:10%">
												</a>
												</div>
											</div>
											<?php } ?>
											<?php if($claim['update_by']) { ?>
											<div class="form-group">
												<label>Terakhir Update Oleh:</label>
												<input type="text" name="reason" value="<?=$claim['update_by']?>" class="form-control" maxLength="100" readonly/>
											</div>
											<?php } ?>
										</div>
										<?php if($claim['transfer_photo']) { ?>
										<div class="col-md-6 col-12">
											<div class="form-group">
												<label>Bukti Transfer Pembayaran Klaim:</label>
												<div class="thumbnail">
												<a href="<?=base_url('storage/images/'.$claim['transfer_photo'])?>">
													<img src="<?=base_url('storage/images/'.$claim['transfer_photo'])?>" alt="Photo" style="width:10%">
												</a>
												</div>
											</div>
										</div>
										<?php } ?>
									</div>
								</div>
								<div class="tab-pane" id="tab-2">
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>Tanggal</th>
											<th>Status</th>
											<th>Diproses Oleh</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach($histories as $history) { ?>
										<tr>
											<td><?=date('d-m-Y H:i:s',strtotime($history['process_date']))?></td>
											<td><?=$history['status']?></td>
											<td><?=$history['process_by']?></td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
								</div>
							</div>
						</form>
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
</script>