<div class="page-title">
	<div>
		<h1>Artikel</h1>
		<p>Kelola artikel</p>
	</div>
	<div class="btn-group"><a href="<?=admin_url('blog_article/create')?>" class="btn btn-success"><i class="fa fa-plus"></i> Tambah</a><a onclick="refreshTable();" class="btn btn-info"><i class="fa fa-refresh"></i> Refresh</a></div>
</div>
<div class="row">
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div id="message"></div>
			<table class="table table-hover table-bordered" id="datatable" width="100%">
				<thead>
					<tr>
						<th>Judul</th>
						<th>Urutan</th>
						<th>Published</th>
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
				"url": "<?=admin_url('blog_article')?>",
				"type": "POST",
			},
			"columns": [
				{"data": "title"},
				{"data": "sort_order"},
				{"data": "active"},
				{"orderable": false, "searchable" : false, "data": "blog_article_id"},
			],
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html('<a href="<?=site_url('blog/\'+data.slug+\'')?>" target="_blank">'+data.title+'</a>');
				html  = '<div class="btn-group btn-group-xs">';
				html += '<a href="<?=admin_url('blog_article/edit')?>/'+data.blog_article_id+'" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> Edit</a>';
				html += '<a onclick="delRecord('+data.blog_article_id+');" class="btn btn-warning btn-xs"><i class="fa fa-minus-circle"></i> Hapus</a> ';
				html += '</div>';
				$('td', row).eq(3).addClass('text-right').html(html);
				if (data.active === '1') {
					$('td', row).eq(2).html('<span class="label label-success">Yes</span>');
				} else {
					$('td', row).eq(2).html('<span class="label label-default">No</span>');
				}
			},
			"order": [[3, 'desc']],
		});
	});
	
	function delRecord(blog_article_id) {
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
					url : '<?=admin_url('blog_article/delete')?>',
					type : 'post',
					data: 'blog_article_id='+blog_article_id,
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