<section class="blog-single section">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-12">
				<div class="blog-single-main">
					<div class="row">
						<div class="col-12">
							<div class="blog-detail">
								<h2 class="blog-title">Lacak Status Pengiriman</h2>
								<div class="content">
									<?php if ($tracking) {?>
									<table class="table table-bordered" width="100%">
									<?php if (isset($tracking['summary'])) {?>
									<tr>
										<th colspan="3" style="background-color:#f5f5f5">SUMMARY</th>
									</tr>
									<tr>
										<td><small class="text-muted">Kurir</small><br><?=$tracking['summary']['courier_name']?></td>
										<td><small class="text-muted">No. Resi</small><br><?=$tracking['summary']['waybill_number']?></td>
										<td><small class="text-muted">Status</small><br><?=$tracking['summary']['status']?></td>
									</tr>
									<?php }?>
									<?php if (isset($tracking['manifest'])) {?>
									<tr>
										<th colspan="3" style="background-color:#f5f5f5">MANIFEST</th>
									</tr>
									<?php foreach ($tracking['manifest'] as $manifest) {?>
									<tr>
										<td><?=date('d/m/Y H:i', strtotime($manifest['manifest_date'].' '.$manifest['manifest_time']))?></td>
										<td><?=$manifest['city_name']?></td>
										<td><?=$manifest['manifest_description']?></td>
									</tr>
									<?php }?>
									<?php }?>
									</table>
									<?php } else {?>
									<div class="alert alert-warning">Nomor resi tidak ditemukan!</div>
									<?php }?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>