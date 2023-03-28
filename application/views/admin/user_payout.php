<div class="page-title">
	<div>
		<h1><?=$template['title']?></h1>
		<p>Permintaan penarikan dana</p>
	</div>
	<div class="btn-group">
		<a onclick="" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Tambah</a>
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
							<th>ID Trans</th>
							<th>Tanggal</th>
							<th>Nama</th>
							<th class="text-right">Jumlah Penarikan</th>
							<th class="text-right">Saldo</th>
							<th>Tanggal Cair</th>
							<th></th>
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
				"url": "<?=admin_url('user_payout')?>",
				"type": "POST",
			},
			"columns": [
				{"data": "user_transaction_id"},
				{"data": "date_added"},
				{"data": "name"},
				{"data": "amount"},
				{"data": "balance"},
				{"data": "date_paid"},
				{"orderable": false, "searchable" : false, "data": "user_transaction_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				$('td', row).eq(3).addClass('text-right');
				$('td', row).eq(4).addClass('text-right');
				html  = '<div class="btn-group btn-group-xs">';
				
				if (data.date_paid == '') {
					html += '<a onclick="approve('+data.user_transaction_id+');" class="btn btn-primary btn-xs"><i class="fa fa-check"></i> Cairkan</a>';
				}
				
				html += '<a onclick="delRecord('+data.user_transaction_id+');" class="btn btn-warning btn-xs"><i class="fa fa-minus-circle"></i> Hapus</a> ';
				html += '</div>';
				
				if (data.date_paid == '') {
					$('td', row).eq(5).html('<span class="label label-warning">Belum Dicairkan</span>');
				}
				
				$('td', row).eq(6).addClass('text-right').html(html);
			},
			"order": [[0, 'desc']]
		});
	});
	
	function delRecord(user_transaction_id) {
		swal({
			title: "Hapus data?",
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
					url : '<?=admin_url('user_payout/delete')?>',
					type : 'post',
					data: 'user_transaction_id='+user_transaction_id,
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
	
	function approve(user_transaction_id) {
		swal({
			title: "Cairkan permintaan ini?",
			text: "Proses approval pencairan tidak dapat dianulir!",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "Ya",
			cancelButtonText: "Tidak",
			closeOnConfirm: false,
			closeOnCancel: true
		}, function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					url : '<?=admin_url('user_payout/approve')?>',
					type : 'post',
					data: 'user_transaction_id='+user_transaction_id,
					dataType: 'json',
					success: function(json) {
						if (json['success']) {
							swal("Berhasil!", json['success'], "success");
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