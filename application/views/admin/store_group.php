<div class="page-title">
	<div>
		<h1>Membership Toko</h1>
		<p>Kelola jenis keanggotaan toko</p>
	</div>
	<div class="btn-group">
		<a onclick="getForm(null, '/store_group/create');" class="btn btn-success"><i class="fa fa-plus-circle"></i> Tambah</a>
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
							<th>Jenis Membership</th>
							<th class="text-right">Admin Fee (%)</th>
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
				"url": "<?=admin_url('store_group')?>",
				"type": "POST",
			},
			"columns": [
				{"data": "name"},
				{"data": "admin_fee"},
				{"orderable": false, "searchable" : false, "data": "store_group_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				html  = '<div class="btn-group btn-group-xs">';
				html += '<a onclick="getForm('+data.store_group_id+', \'/store_group/edit\');" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> Edit</a>';
				html += '<a onclick="delRecord('+data.store_group_id+');" class="btn btn-warning btn-xs"><i class="fa fa-minus-circle"></i> Hapus</a> ';
				html += '</div>';
				$('td', row).eq(2).addClass('text-right').html(html);
				$('td', row).eq(1).addClass('text-right');
			},
			"order": [[2, 'asc']]
		});
	});
	
	function delRecord(store_group_id) {
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
					url : '<?=admin_url('store_group/delete')?>',
					type : 'post',
					data: 'store_group_id='+store_group_id,
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
	
	function getForm(store_group_id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'store_group_id='+store_group_id,
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