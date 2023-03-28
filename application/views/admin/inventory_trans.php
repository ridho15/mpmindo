<div class="page-title">
	<div>
		<h1><?=lang('heading_title')?></h1>
		<p><?=lang('heading_subtitle')?></p>
	</div>
</div>
<section class="content">
	<div class="card">
		<div class="card-body">
			<div class="form-action">
				<a onclick="getForm(null, '/inventory_trans/create');" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?=lang('button_insert')?></a>
				<!-- <a onclick="delRecord();" class="btn btn-default"><i class="fa fa-check-square-o"></i> <?=lang('button_remove')?></a> -->
			</div>
			<div class="table-responsive">
				<?=form_open(admin_url('inventory_trans/delete'), 'id="bulk-action"')?>
				<table class="table table-hover" id="datatable" width="100%">
					<thead>
						<tr>
							<!-- <th style="width: 20px"><input type="checkbox" id="select-all"></th> -->
							<th><?=lang('column_id')?></th>
							<th><?=lang('column_date')?></th>
							<th><?=lang('column_from')?></th>
							<th><?=lang('column_to')?></th>
							<th><?=lang('column_note')?></th>
							<th class="text-right"></th>
						</tr>
					</thead>
				</table>
				</form>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#datatable').DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('inventory_trans')?>",
				"type": "POST",
			},
			"columns": [
				// {"orderable": false, "searchable" : false, "data": "inventory_trans_id"},
				{"data": "inventory_trans_id"},
				{"data": "date_added"},
				{"data": "warehouse_from"},
				{"data": "warehouse_to"},
				{"data": "description"},
				{"orderable": false, "searchable" : false, "data": "inventory_trans_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				// $('td', row).eq(0).html('<input type="checkbox" class="checkbox" name="inventory_trans_id[]" value="'+data.inventory_trans_id+'"/>');
				
				html  = '<div class="btn-group-xs">';
				html += '<a onclick="getForm('+data.inventory_trans_id+', \'/inventory_trans/detail\');" class="btn btn-default btn-xs"><span class="fa fa-eye"></span> <?=lang('button_view')?></a> ';
				// html += '<a onclick="getForm('+data.inventory_trans_id+', \'/inventory_trans/edit\');" class="btn btn-default btn-xs"><span class="fa fa-pencil"></span> <?=lang('button_edit')?></a>';
				html += '</div>';
				
				$('td', row).eq(5).html(html).addClass('text-right');
			},
			"order": [[0, 'desc']]
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
					url : '<?=admin_url('inventory_trans/delete')?>',
					type : 'post',
					data: $('#bulk-action').serialize(),
					dataType: 'json',
					success: function(json) {
						if (json['success']) {
							swal("<?=lang('text_success')?>", json['success'], 'success');
						} else if (json['error']) {
							swal("<?=lang('text_error')?>", json['error'], 'error');
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
	
	function getForm(inventory_trans_id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'inventory_trans_id='+inventory_trans_id,
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					$.notify({
						title: '<strong><?=lang('text_error')?></strong><br>',
						message: json['error'],
						icon: 'fa fa-info' 
					},{
						type: "danger"
					});
				} else if (json['content']) {
					$('body').append('<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">'+json['content']+'</div>');
					$('#form-modal').modal('show');
					$('#form-modal').on('hidden.bs.modal', function (e) {
						$('#form-modal').remove();
						refreshTable();
					});	
				}
			}
		});
	}
</script>