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
					<a onclick="getForm(null, '/admin_group/create');" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?=lang('button_insert')?></a>
					<a onclick="delRecord();" class="btn btn-default"><i class="fa fa-check-square-o"></i> <?=lang('button_remove')?></a>
				</div>
				<div id="message"></div>
				<?=form_open(admin_url('admin_group/delete'), 'id="bulk-action"')?>
				<table class="table table-hover" width="100%" id="datatable">
					<thead>
						<tr>
							<th style="width: 20px"><input type="checkbox" id="select-all"></th>
							<th><?=lang('entry_name')?></th>
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
				"url": "<?=admin_url('admin_group')?>",
				"type": "POST",
				"data": function(d) {
					d.csrf_token = '<?=$this->security->get_csrf_hash()?>'
				}
			},
			"columns": [
				{"orderable": false, "searchable" : false, "data": "admin_group_id"},
				{"data": "name"},
				{"orderable": false, "searchable" : false, "data": "admin_group_id"},
			],
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html('<input type="checkbox" class="checkbox" name="admin_group_id[]" value="'+data.admin_group_id+'"/>');
				
				html  = '<div class="btn-group btn-group-xs">';
				html += '<a onclick="getForm('+data.admin_group_id+', \'/admin_group/edit\');" class="btn btn-default btn-xs"><i class="fa fa-cog"></i> Edit</a>';
				html += '</div>';
				
				$('td', row).eq(2).addClass('text-right').html(html);
			},
			"order": [[0, 'asc']],
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
					url : '<?=admin_url('admin_group/delete')?>',
					type : 'post',
					data: $('#bulk-action').serialize(),
					dataType: 'json',
					success: function(json) {
						if (json['success']) {
							swal('Success', json['success'], 'success');
						} else if (json['error']) {
							swal('Warning', json['error'], 'error');
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
	
	function getForm(admin_group_id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'admin_group_id='+admin_group_id,
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