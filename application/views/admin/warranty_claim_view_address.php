<?php $inventory_row = 0; ?>
<script src="<?=base_url('assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.min.js')?>"></script>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Lihat Alamat Pendaftar</h4>
		</div>
		<div class="modal-body">
			<div id="notification"></div>
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Alamat</th>
							<th>Kecamatan</th>
							<th>Kota / Kabupaten</th>
							<th>Provinsi</th>
							<th>Kode Pos</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($addresses as $address) { ?>
						<tr>
							<td><?=$address['address']?></td>
							<td><?=$address['subdistrict']?></td>
							<td><?=$address['city']?></td>
							<td><?=$address['province']?></td>
							<td><?=$address['postcode']?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> <?=lang('button_cancel')?></button>
		</div>
	</div>
</div>