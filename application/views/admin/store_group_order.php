<div class="page-title">
	<div>
		<h1>Order Membership</h1>
		<p>Order baru/upgrade membership toko</p>
	</div>
	<div class="btn-group">
		<a onclick="getForm(null, '/store_group_order/create');" class="btn btn-success"><i class="fa fa-plus-circle"></i> Tambah</a>
		<a onclick="refreshTable();" class="btn btn-default"><i class="fa fa-refresh"></i> Refresh</a>
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
							<th>#</th>
							<th>Tanggal</th>
							<th>Toko</th>
							<th>Membership</th>
							<th class="text-right">Harga</th>
							<th>Status</th>
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
				"url": "<?=admin_url('store_group_order')?>",
				"type": "POST",
			},
			"columns": [
				{"data": "store_group_order_id"},
				{"data": "date_added"},
				{"data": "store_name"},
				{"data": "store_group"},
				{"data": "amount"},
				{"data": "active"},
				{"orderable": false, "searchable" : false, "data": "store_group_order_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				$('td', row).eq(4).addClass('text-right');
				
				html  = '<div class="btn-group btn-group-xs">';
				
				if (data.active === '1') {
					$('td', row).eq(3).html(data.store_group+' <span class="badge">'+data.date_start+' s/d '+data.date_end+'</span>');
					$('td', row).eq(5).html('<span class="label label-success">ACTIVE</span>');
					html += '<a onclick="suspend('+data.store_group_order_id+', 0);" class="btn btn-danger btn-xs"><i class="fa fa-ban"></i> Suspend</a>';
				} else {
					$('td', row).eq(5).html('<span class="label label-default">INACTIVE</span>');
					html += '<a onclick="activate('+data.store_group_order_id+');" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Aktivasi</a>';
				}
				
				html += '<a onclick="delRecord('+data.store_group_order_id+');" class="btn btn-warning btn-xs"><i class="fa fa-minus-circle"></i> Hapus</a> ';
				html += '</div>';
				
				$('td', row).eq(6).addClass('text-right').html(html);
			},
			"order": [[6, 'desc']]
		});
	});
	
	function delRecord(store_group_order_id) {
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
					url : '<?=admin_url('store_group_order/delete')?>',
					type : 'post',
					data: 'store_group_order_id='+store_group_order_id,
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
	
	function suspend(store_group_order_id) {
		swal({
			title: "Apakah anda yakin?",
			text: "Tindakan suspend akan mengeset membership toko ini ke default namun tidak merubah masa berlaku keanggotaan!",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "Ya",
			cancelButtonText: "Tidak",
			closeOnConfirm: false,
			closeOnCancel: true
		}, function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					url : '<?=admin_url('store_group_order/suspend')?>',
					type : 'post',
					data: 'store_group_order_id='+store_group_order_id,
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
	
	function activate(store_group_order_id) {
		$.ajax({
			url : "<?=admin_url('store_group_order/activate')?>",
			data: 'store_group_order_id='+store_group_order_id,
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
				} else if (json['content']) {
					$('#modal').html('<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">'+json['content']+'</div>');
					$('#form-modal').modal('show');
					$('#form-modal').on('hidden.bs.modal', function (e) {
						refreshTable();
					});	
				}
			}
		});
	}
</script>