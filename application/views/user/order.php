<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="card-title-w-btn">
					<h3 class="title">Pesanan</h3>
					<div class="btn-group"><a onclick="refreshTable();" class="btn btn-default pull-right"><i class="fa fa-refresh"></i> Refresh</a></div>
				</div>
				<div id="message"></div>
				<table class="table table-bordered table-hover" width="100%" id="datatable">
					<thead>
						<tr>
							<th>No. Pesanan</th>
							<th>Tanggal</th>
							<th>Pembeli</th>
							<th>Asal</th>
							<th>Total</th>
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
				"url": "<?=user_url('order')?>",
				"type": "POST",
			},
			"columns": [
				{"data": "store_order_id"},
				{"data": "date_added"},
				{"data": "user_name"},
				{"data": "location"},
				{"data": "total"},
				{"data": "status"},
				{"orderable": false, "searchable" : false, "data": "order_id"},
			],
			"sDom":'<"table-responsive" t>p',
			"createdRow": function (row, data, index) {
				html  = '<div class="btn-group btn-group-xs">';
				html += '<a href="<?=user_url('order/detail/\'+data.order_id+\'')?>" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Lihat</a>';
				html += '</div>';
				$('td', row).eq(6).addClass('text-right').html(html);
			},
			"order": [[0, 'desc']]
		});
	});
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
	
	function delRecord(showcase_id) {
		if (confirm('Data yang sudah dihapus tidak dapat dikembalikan. Kamu yakin?')) {
			$.ajax({
				url : '<?=user_url('showcase/delete')?>',
				type : 'post',
				data: 'showcase_id='+showcase_id,
				dataType: 'json',
				success: function(json) {
					if (json['success']) {
						$('#message').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> '+json['success']+'</div>');
					} else if (json['error']) {
						$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
					} else if (json['redirect']) {
						window.location = json['redirect'];
					}
					
					$('html, body').animate({ scrollTop: 0 }, 'slow');
					
					refreshTable();
				}
			});
		}
	}
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
	
	function getForm(user_product_id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'user_product_id='+user_product_id,
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