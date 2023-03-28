<div class="page-title">
	<div>
		<h1><?=lang('heading_title')?></h1>
		<p><?=lang('heading_subtitle')?></p>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="form-action">
					<a onclick="getForm(null, '/list_distributor/create');" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?=lang('button_insert')?></a>
					<a onclick="delRecord();" class="btn btn-default"><i class="fa fa-check-square-o"></i> <?=lang('button_remove')?></a>
				</div>
				<div id="message"></div>
				<div class="table-responsive">
					<?=form_open(admin_url('list_distributor/delete'), 'id="bulk-action"')?>
						<table class="table table-hover" width="100%" id="datatable">
							<thead>
								<tr>
									<th style="width: 20px"><input type="checkbox" id="select-all"></th>
									<th><?=lang('entry_company_name')?></th>
									<th><?=lang('entry_shop_name')?></th>
									<th><?=lang('entry_address')?></th>
									<th><?=lang('entry_number_phone')?></th>
									<th class="text-right"></th>
								</tr>
							</thead>
						</table>
					</form>
				</div>
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
				"url": "<?=admin_url('list_distributor')?>",
				"type": "POST"
			},
			"columns": [
				{"orderable": false, "searchable" : false, "data": "list_distributor_id"},
				{"data": "company_name"},
				{"data": "shop_name"},
				{"data": "address"},
				{"data": "number_phone"},
				{"orderable": false, "searchable" : false, "data": "list_distributor_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html('<input type="checkbox" class="checkbox" name="list_distributor_id[]" value="'+data.list_distributor_id+'"/>');
				
				html  = '<div class="btn-group btn-group-xs">';
				html += '<a onclick="getForm('+data.list_distributor_id+', \'/list_distributor/edit\');" class="btn btn-default btn-xs"><i class="fa fa-cog"></i> <?=lang('button_edit')?></a>';
				html += '</div>';
				
				$('td', row).eq(5).addClass('text-right').html(html);
				
			},
			"order": [[1, 'asc']]
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
					url : '<?=admin_url('list_distributor/delete')?>',
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
	
	function getForm(list_distributor_id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'list_distributor_id='+list_distributor_id,
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