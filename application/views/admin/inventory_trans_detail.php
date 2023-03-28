<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$template['title']?></h4>
		</div>
		<div class="modal-body">
			<div id="notification"></div>
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th>Tanggal</th>
						<td><?=format_date($date_added)?></td>
					</tr>
					<tr>
						<th>Asal</th>
						<td><?=$warehouse_from_name ? $warehouse_from_name : lang('text_inbound')?></td>
					</tr>
					<tr>
						<th>Tujuan</th>
						<td><?=$warehouse_to_name ? $warehouse_to_name : lang('text_outbound')?></td>
					</tr>
					<tr>
						<th>Keterangan</th>
						<td><?=$description?></td>
					</tr>
				</tbody>
			</table>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Item</th>
						<th>Qty</th>
						<th>Warehouse</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($inventories as $inventory) { ?>
					<tr>
						<?php //if ($inventory['quantity'] > 0) {?>
						<td><?=$inventory['product']?></td>
						<td><?=$inventory['quantity']?></td>
						<td><?=$inventory['warehouse_name']?></td>
						<?php //} ?>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>