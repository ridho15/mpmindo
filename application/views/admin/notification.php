<div class="page-title">
	<div>
		<h1>Notifikasi</h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<table class="table table-hover" id="datatable" width="100%">
					<thead>
						<tr>
							<th>Tanggal</th>
							<th>Judul</th>
							<th>Deskripsi</th>
							<th>Status</th>
							<th class="text-right" style="min-width:15%;"></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
	    var table = $('#datatable').DataTable({
	    	"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('notifications')?>",
				"type": "POST",
			},
			"columns": [
				{"orderable": false, "searchable" : false, "data": "date_added"},
				{"data": "title"},
				{"data": "message"},
				{"data": "read"},
				{"orderable": false, "data": "table_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				if (data.read === '1') {
					$('td', row).eq(3).html('Sudah Dibaca');
				} else {
					$('td', row).eq(3).html('Belum Dibaca');
				}
				if(data.table == 'order') {
					$('td', row).eq(4).html('<a href="<?=admin_url('order/detail/\'+data.table_id+\'')?>" data-toggle="tooltip" title="Lihat detil">Lihat Detil</a>');
				}
				else if(data.table == 'reg_sale_offline') {
					$('td', row).eq(4).html('<a href="<?=admin_url('reg_sale_offline?reg_id=\'+data.table_id+\'')?>" data-toggle="tooltip" title="Lihat detil">Lihat Detil</a>');
				}
				else if(data.table == 'warranty_claim') {
					$('td', row).eq(4).html('<a href="<?=admin_url('warranty_claim?claim_id=\'+data.table_id+\'')?>" data-toggle="tooltip" title="Lihat detil">Lihat Detil</a>');
				}
			},
			"order": [[0, 'desc']],
		});
		
		$('#select-all').click(function() {
			var checkBoxes = $('.checkbox');
			checkBoxes.prop('checked', !checkBoxes.prop('checked'));
		});
	});
</script>