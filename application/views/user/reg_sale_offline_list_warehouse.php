<style>
.table-wrapper2 {
  max-height: 300px;
  overflow: auto;
}
</style>
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header" style="padding:20px 20px 10px">
			<h4>Daftar Lokasi Kirim Untuk Klaim Garansi</h4>
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		</div>
		<div class="modal-body" style="padding:20px">
			<div id="notification"></div>
			<div class="form-group">
			<div class="table-responsive">
			<div class="table-wrapper2">
				<table class="table table-bordered table-striped table-hover" width="100%" id="datatable">
					<thead>
						<tr>
							<th>Kode Gudang/Distributor</th>
							<th>Nama Gudang/Distributor</th>
							<th>Wilayah</th>
							<th>Alamat</th>
							<th>Telepon</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($warehouses as $warehouse) {?>
					<?php if($warehouse['warehouse_id'] != '2') { ?>
						<tr>
							<td><?=$warehouse['code']?></td>
							<td><?=$warehouse['name']?></td>
							<td><?=$warehouse['region']?></td>
							<td><?=$warehouse['address']?></td>
							<td><?=$warehouse['telephone']?></td>
						</tr>
						<?php }?>
						<?php }?>
					</tbody>
				</table>
			</div>
			</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="ps-btn ps-btn--outline ps-btn--sm" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
		</div>
	</div>
</div>
<!-- <script src="<?=base_url('assets/js/plugins/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/js/plugins/dataTables.bootstrap.min.js')?>"></script>
<script type="text/javascript">
var table = $('#datatable').DataTable();
$( table.table().container() ).removeClass( 'form-inline' );
</script> -->