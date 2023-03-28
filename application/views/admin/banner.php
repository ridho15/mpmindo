<div class="page-title">
	<div>
		<h1>Banner</h1>
		<p>Kelola Banner</p>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="form-action">
				<a onclick="window.location = '<?=admin_url('banner/create')?>';" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?=lang('button_insert')?></a>
				<a onclick="delRecord();" class="btn btn-default"><i class="fa fa-check-square-o"></i> <?=lang('button_remove')?></a>
			</div>
			<div id="message"></div>
			<?php if ($success) {?>
			<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> <?=$success?></div>
			<?php }?>
			<?php if ($error) {?>
			<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> <?=$error?></div>
			<?php }?>
			<div class="card-body">
				<?=form_open(admin_url('banner/delete'), 'id="bulk-action"')?>
				<table class="table" id="datatable" width="100%">
					<thead>
						<tr>
							<th style="width: 20px"><input type="checkbox" id="select-all"></th>
							<th>Nama</th>
							<th>Deskripsi</th>
							<th>Total Foto</th>
							<th class="text-right"></th>
						</tr>
					</thead>
				</table>
				</form>
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
				"url": "<?=admin_url('banner')?>",
				"type": "POST",
			},
			"columns": [
				{"orderable": false, "searchable" : false, "data": "banner_id"},
				{"data": "name"},
				{"data": "description"},
				{"data": "images"},
				{"orderable": false, "searchable" : false, "data": "banner_id"},
			],
			"sDom":'<"table-responsive" t>p',
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html('<input type="checkbox" class="checkbox" name="banner_id[]" value="'+data.banner_id+'"/>');
				$('td', row).eq(4).html('<div class="btn-group btn-group-xs"><a href="<?=admin_url('banner/edit/\'+data.banner_id+\'')?>" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> <?=lang('button_edit')?></a></div>').addClass('text-right');
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
					url : '<?=admin_url('banner/delete')?>',
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
</script>