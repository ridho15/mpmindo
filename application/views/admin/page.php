<div class="page-title">
	<div>
		<h1><?=lang('heading_title')?></h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="form-action">
					<a onclick="getForm(null, 'pages/create');" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?=lang('button_insert')?></a>
					<a onclick="delRecord();" class="btn btn-default"><i class="fa fa-check-square-o"></i> <?=lang('button_remove')?></a>
				</div>
				<div id="message"></div>
				<?=form_open(admin_url('pages/delete'), 'id="bulk-action"')?>
				<table class="table table-hover" width="100%" id="datatable">
					<thead>
						<tr>
							<th style="width: 20px"><input type="checkbox" id="select-all"></th>
							<th><?=lang('entry_title')?></th>
							<th><?=lang('entry_sort_order')?></th>
							<th><?=lang('entry_active')?></th>
							<th class="text-right"></th>
						</tr>
					</thead>
				</table>
				</form>
			</div>
		</div>
	</div>
</div>
<div id="modal"></div>
<script src="<?=base_url('assets/js/sweetalert.min.js')?>" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
	    var table = $('#datatable').DataTable({
	    	"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('pages')?>",
				"type": "POST",
				"data": function(d) {
					d.csrf_token = '<?=$this->security->get_csrf_hash()?>'
				}
			},
			"columns": [
				{"orderable": false, "searchable" : false, "data": "page_id"},
				{"data": "title"},
				{"data": "sort_order"},
				{"data": "active"},
				{"orderable": false, "searchable" : false, "data": "page_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html('<input type="checkbox" class="checkbox" name="page_id[]" value="'+data.page_id+'"/>');
				
				if (data.active == '1') {
					$('td', row).eq(3).html('<span class="label label-success">ACTIVE</span>');
				} else {
					$('td', row).eq(3).html('<span class="label label-default">INACTIVE</span>');
				}
				
				html  = '<div class="btn-group-xs">';
				html += '<a onclick="getForm('+data.page_id+', \'pages/edit\');" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> <?=lang('button_edit')?></a> ';
				html += '</div>';
				
				$('td', row).eq(4).addClass('text-right').html(html);
			},
			"order": [[2, 'asc']]
		});
		
		$('#select-all').click(function() {
			var checkBoxes = $('.checkbox');
			checkBoxes.prop('checked', !checkBoxes.prop('checked'));
		});
	});
	
	function delRecord() {
		swal({
			title: "<?=lang('text_confirm')?>",
			text: "<?=lang('text_delete_confirm')?>",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "<?=lang('text_yes')?>",
			cancelButtonText: "<?=lang('text_no')?>",
			closeOnConfirm: false,
			closeOnCancel: true
		}, function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					url : '<?=admin_url('pages/delete')?>',
					type : 'post',
					data: $('#bulk-action').serialize(),
					dataType: 'json',
					success: function(json) {
						if (json['success']) {
							swal("<?=lang('text_success')?>", json['success'], 'success');
						} else if (json['error']) {
							swal("<?=lang('text_error')?>", json['error'], 'error');
						} else if (json['redirect']) {
							window.page = json['redirect'];
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
	
	function getForm(page_id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'page_id='+page_id,
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