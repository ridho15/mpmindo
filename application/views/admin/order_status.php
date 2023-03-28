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
				<a onclick="getForm(null, '/order_status/create');" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?=lang('button_insert')?></a>
				<a onclick="delRecord();" class="btn btn-default"><i class="fa fa-check-square-o"></i> <?=lang('button_remove')?></a>
			</div>
			<div class="table-responsive">
				<?=form_open(admin_url('order_status/delete'), 'id="bulk-action"')?>
				<table class="table table-hover" id="datatable" width="100%">
					<thead>
						<tr>
							<th style="width: 20px"><input type="checkbox" id="select-all"></th>
							<th><?=lang('label_name')?></th>
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
				"url": "<?=admin_url('order_status')?>",
				"type": "POST",
			},
			"columns": [
				{"orderable": false, "searchable" : false, "data": "order_status_id"},
				{"data": "name"},
				{"orderable": false, "searchable" : false, "data": "order_status_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html('<input type="checkbox" class="checkbox" name="order_status_id[]" value="'+data.order_status_id+'"/>');
				
				html  = '<div class="btn-group btn-group-xs">';
				html += '<a onclick="getForm('+data.order_status_id+', \'/order_status/edit\');" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> <?=lang('button_edit')?></a>';
				html += '</div>';
				
				$('td', row).eq(2).addClass('text-right').html(html);
			},
			"order": [[0, 'asc']]
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
					url : '<?=admin_url('order_status/delete')?>',
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
	
	function getForm(order_status_id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'order_status_id='+order_status_id,
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