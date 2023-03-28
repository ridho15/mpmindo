<div class="row">
	<div class="">
		<div class="card">
			<div class="card-body">
				<div class="card-title-w-btn">
					<h3 class="title">Daftar Produk</h3>
					<div class="btn-group"><a href="<?=user_url('product/create')?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Tambah Produk</a></div>
				</div>
				<div id="message"></div>
				<table class="table table-bordered table-hover" width="100%" id="datatable">
					<thead>
						<tr>
							<th>Foto</th>
							<th>Nama Produk</th>
							<th>Showcase</th>
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
				"url": "<?=user_url('product')?>",
				"type": "POST",
			},
			"columns": [
				{"data": "image"},
				{"data": "name"},
				{"data": "showcase"},
				{"orderable": false, "searchable" : false, "data": "product_id"},
			],
			"sDom":'<"table-responsive" t>p',
			"createdRow": function (row, data, index) {
				html  = '<div class="btn-group btn-group-xs">';
				html += '<a href="<?=user_url('product/edit/\'+data.product_id+\'')?>" class="btn btn-info btn-xs">Edit</a>';
				html += '<a onclick="delRecord('+data.product_id+');" class="btn btn-warning btn-xs">Delete</a>';
				html += '</div>';
				$('td', row).eq(3).addClass('text-right').html(html);
			},
			"order": [[3, 'asc']]
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