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
						<th>Kode Produk</th>
						<td><?=$product_code?></td>
					</tr>
					<tr>
						<th>Nama Produk</th>
						<td><?=$product_name?></td>
					</tr>
					<tr>
						<th>Gudang / Distributor</th>
						<td><?=$warehouse_name?></td>
					</tr>
				</tbody>
			</table>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Tanggal</th>
						<th>Referensi</th>
						<th>Keterangan</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($details as $detail) { ?>
					<tr>
						<td><?=format_date($detail['date_added'])?></td>
						<td>#<?=$detail['inventory_trans_id']?></td>
						<td><?=$detail['description']?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>