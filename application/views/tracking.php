<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-remove fa-lg"></span></button>
		</div>
		<div class="modal-body">
			<h4 class="modal-title">Lacak Status Pengiriman</h4>
			<div class="row">
				<div class="col-lg-12">
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