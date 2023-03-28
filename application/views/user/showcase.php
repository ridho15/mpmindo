<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-title-w-btn">
				<h3 class="title">Etalase</h3>
				<div class="btn-group"><a onclick="getForm('create');" class="btn btn-primary pull-right"><i class="fa fa-plus-circle"></i> Tambah Baru</a></div>
			</div>
			<div class="card-body">		
<div id="message"></div>
<?=form_open(null, 'id="form-checks"')?>
<table class="table table-hover" id="datatable" width="100%">
	<thead>
		<tr>
			<th>Nama Showcase</th>
			<th class="text-right"></th>
		</tr>
	</thead>
</table>
<?=form_close()?>
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
				"url": "<?=user_url('showcase')?>",
				"type": "POST",
			},
			"columns": [
				{"data": "name"},
				{"orderable": false, "searchable" : false, "data": "showcase_id"},
			],
			"sDom":'<"table-responsive" t>p',
			"createdRow": function (row, data, index) {
				$('td', row).eq(1).html('<div class="btn-group btn-group-xs"><a onclick="getForm(\'edit/'+data.showcase_id+'\');" class="btn btn-default btn-xs">Edit</a><a onclick="delRecord('+data.showcase_id+');" class="btn btn-warning btn-xs">Hapus</a></div>').addClass('text-right');
			},
			"order": [[0, 'asc']],
		});
	});
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
	
	function getForm(path) {
		$.ajax({
			url : "<?=user_url('showcase')?>/"+path,
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
</script>