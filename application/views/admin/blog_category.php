<div class="page-title">
	<div>
		<h1>Kategori Artikel</h1>
		<p>Kelola kategori artikel</p>
	</div>
	<div class="btn-group">
		<a href="<?=admin_url('blog_category/create')?>" class="btn btn-success"><i class="fa fa-plus"></i> Tambah</a>
		<a onclick="refreshTable();" class="btn btn-info"><i class="fa fa-refresh"></i> Refresh</a>
	</div>
</div>
<div class="row">
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div id="message"></div>
			<table class="table table-hover table-bordered" id="datatable" width="100%">
				<thead>
					<tr>
						<th>Name</th>
						<th>Active</th>
						<th>Sort Order</th>
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
				"url": "<?=admin_url('blog_category')?>",
				"type": "POST",
			},
			"columns": [
				{"data": "name"},
				{"data": "active"},
				{"data": "sort_order"},
				{"orderable": false, "searchable" : false, "data": "blog_category_id"},
			],
			"createdRow": function (row, data, index) {
				if (data.active === '1') {
					$('td', row).eq(1).html('<span class="label label-success">Yes</span>');
				} else {
					$('td', row).eq(1).html('<span class="label label-default">No</span>');
				}
				html  = '<div class="btn-group btn-group-xs">';
				html += '<a href="<?=admin_url('blog_category/edit')?>/'+data.blog_category_id+'" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> Edit</a>';
				html += '<a onclick="delRecord('+data.blog_category_id+');" class="btn btn-warning btn-xs"><i class="fa fa-minus-circle"></i> Hapus</a> ';
				html += '</div>';
				$('td', row).eq(3).addClass('text-right').html(html);
			},
			"order": [0, 'asc'],
		});
	});
	
	function delRecord(blog_category_id) {
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
					url : '<?=admin_url('blog_category/delete')?>',
					type : 'post',
					data: 'blog_category_id='+blog_category_id,
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