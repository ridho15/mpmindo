<div class="page-title">
	<div>
		<h1>Pesanan Seller</h1>
		<p>Kelola pesanan seller</p>
	</div>
	<div class="btn-group">
		<a onclick="refreshTable();" class="btn btn-info"><i class="fa fa-refresh"></i> Refresh</a>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div id="message"></div>
				<table class="table table-bordered table-hover" width="100%" id="datatable">
					<thead>
						<tr>
							<th>ID</th>
							<th>Tanggal</th>
							<th>Seller</th>
							<th>Pembeli</th>
							<th class="text-right">Total</th>
							<th>Status</th>
							<th class="text-right"></th>
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
				"url": "<?=admin_url('order_shipping')?>",
				"type": "POST",
			},
			"columns": [
				{"data": "order_id"},
				{"data": "date_added"},
				{"data": "store"},
				{"data": "user_name"},
				{"data": "total"},
				{"data": "status"},
				{"orderable": false, "searchable" : false, "data": "order_id"},
			],
			"sDom":'<"table-responsive" t>p',
			"createdRow": function (row, data, index) {
				html  = '<div class="btn-group btn-group-xs">';
				html += '<a href="<?=admin_url('order_shipping/detail/\'+data.store_order_id+\'')?>" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Detil</a><a onclick="delRecord('+data.store_order_id+');" class="btn btn-warning btn-xs"><i class="fa fa-minus-circle"></i> Hapus</a>';
				html += '</div>';
				$('td', row).eq(2).html('<a href="<?=site_url('store/\'+data.store_domain+\'')?>" target="_blank">'+data.store+'</a>');
				$('td', row).eq(4).addClass('text-right');
				$('td', row).eq(6).addClass('text-right').html(html);
			},
			"order": [[0, 'desc']]
		});
	});
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
	
	function delRecord(store_order_id) {
		if (store_order_id) {
			data = 'store_order_id='+store_order_id;
		} else {
			if ($('.check:checked').length === 0) {
				alert('Tidak ada order yang dipilih!');
				return false;
			}
			data = $('#form-checks').serialize();
		}
		
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
					url : '<?=admin_url('order_shipping/delete')?>',
					type : 'post',
					data: data,
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
</script>