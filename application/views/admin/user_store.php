<div class="page-title">
	<div>
		<h1><?=$template['title']?></h1>
		<p>Kelola toko seller</p>
	</div>
	<div class="btn-group">
		<a onclick="window.location = '<?=admin_url('user_store/create')?>'" class="btn btn-success"><i class="fa fa-plus-circle"></i> Daftar Baru</a>
		<a onclick="refreshTable();" class="btn btn-default"><i class="fa fa-refresh"></i> Refresh</a>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div id="message"></div>
				<table class="table table-hover table-bordered" width="100%" id="datatable">
					<thead>
						<tr>
							<th>Toko</th>
							<th>Member Toko</th>
							<th>Owner</th>
							<th>No. Telepon</th>
							<th>Tanggal Buka</th>
							<th>Status</th>
							<th class="text-right">Saldo</th>
							<th class="text-right"></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="modal"></div>
<script type="text/javascript">
	$(document).ready(function() {
	    var table = $('#datatable').DataTable({
	    	"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('user_store')?>",
				"type": "POST",
			},
			"columns": [
				{"data": "name"},
				{"data": "store_group"},
				{"data": "user_name"},
				{"data": "telephone"},
				{"data": "date_added"},
				{"data": "active"},
				{"data": "balance"},
				{"orderable": false, "searchable" : false, "data": "store_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html('<a href="<?=site_url('store/\'+data.domain+\'')?>" target="_blank">'+data.name+'</a>');
				html  = '<div class="btn-group btn-group-xs">';
				html += '<a href="<?=admin_url('user_store/edit/\'+data.store_id+\'')?>" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> Edit</a>';
				html += '<a onclick="delRecord('+data.store_id+');" class="btn btn-warning btn-xs"><i class="fa fa-minus-circle"></i> Hapus</a> ';
				html += '</div>';
				$('td', row).eq(7).addClass('text-right').html(html);
				$('td', row).eq(6).addClass('text-right');
				
				if (data.active === '1') {
					$('td', row).eq(5).html('<span class="label label-success">Active</span>');
				} else {
					$('td', row).eq(5).html('<span class="label label-default">Inactive</span>');
				}
			},
			"order": [[0, 'asc']]
		});
	});
	
	function delRecord(store_id) {
		swal({
			title: "Apakah anda yakin?",
			text: "Data yang sudah dihapus tidak dapat dikembalikan!",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "Ya",
			cancelButtonText: "Tidak",
			closeOnConfirm: false,
			closeOnCancel: true
		}, function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					url : '<?=admin_url('user_store/delete')?>',
					type : 'post',
					data: 'store_id='+store_id,
					dataType: 'json',
					success: function(json) {
						if (json['success']) {
							swal("Terhapus!", json['success'], "success");
						} else if (json['error']) {
							swal("Error!", json['error'], "error");
						} else if (json['redirect']) {
							window.location = json['redirect'];
						}
						refreshTable();
					}
				});
			}
		});
	}
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
</script>