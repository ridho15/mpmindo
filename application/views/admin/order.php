<div class="page-title">
	<div>
		<h1>Pesanan</h1>
		<p>Kelola daftar pesanan</p>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<!-- <div class="form-action">
					<a onclick="delRecord();" class="btn btn-default"><i class="fa fa-check-square-o"></i> <?=lang('button_remove')?></a>
				</div> -->
				<div id="message"></div>
				<?php if ($success) {?>
				<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?=$success?></div>
				<?php }?>
				<?php if ($error) {?>
				<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?=$error?></div>
				<?php }?>
				<?=form_open(admin_url('order/delete'), 'id="bulk-action"')?>
				<table class="table table-hover" id="datatable" width="100%">
					<thead>
						<tr>
							<!-- <th style="width: 20px"><input type="checkbox" id="select-all"></th> -->
							<th>No. Invoice</th>
							<th>Tanggal</th>
							<th>Pelanggan</th>
							<th class="text-right">Total (Rp)</th>
							<th>Status</th>
							<th class="text-right" style="min-width:20%;"></th>
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
				"url": "<?=admin_url('order')?>",
				"type": "POST",
			},
			"columns": [
				//{"orderable": false, "searchable" : false, "data": "order_id"},
				{"data": "invoice_no"},
				{"data": "date_added"},
				{"data": "user_name"},
				{"data": "total"},
				{"data": "status"},
				{"orderable": false, "data": "order_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				//$('td', row).eq(0).html('<input type="checkbox" class="checkbox" name="order_id[]" value="'+data.order_id+'"/>');
				$('td', row).eq(0).html('<a href="<?=admin_url('order/detail/\'+data.order_id+\'')?>" data-toggle="tooltip" title="Lihat detil">'+data.invoice_no+'</a>');
				$('td', row).eq(3).addClass('text-right');
				html  = '<div class="btn-group btn-group-xs">';
				
				if (data.order_status_id === '10') {
					html += '<a class="btn btn-default" onClick="setStatus('+data.order_id+', 12);"><i class="fa fa-check"></i> Proses</a>';
					html += '<a class="btn btn-default" onClick="setStatus('+data.order_id+', 11);"><i class="fa fa-minus-circle"></i> Tolak</a>';
				}
				
				html += '<a href="<?=admin_url('order/detail/\'+data.order_id+\'')?>" class="btn btn-default"><i class="fa fa-eye"></i> Detil</a>';
				html += '</div>';
				
				$('td', row).eq(5).addClass('text-right').html(html);
			},
			"order": [[5, 'desc']],
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
			closeOnCancel: true,
			showLoaderOnConfirm: true
		}, function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					url : '<?=admin_url('order/delete')?>',
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
	
	function setStatus(order_id, order_status_id) {
		if (order_status_id === 12) {
			var _text = 'Pesanan Diproses';
		} else if (order_status_id === 11) {
			var _text = 'Pesanan Ditolak';
		} else {
			alert('Status pesanan tidak valid!');
			return;
		}
		
		swal({
			title: "<?=lang('text_confirm')?>",
			text: _text,
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "<?=lang('text_yes')?>",
			cancelButtonText: "<?=lang('text_no')?>",
			closeOnConfirm: false,
			closeOnCancel: true,
			showLoaderOnConfirm: true
		}, function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					url : '<?=admin_url('order/add_history')?>',
					type : 'post',
					data: 'order_id='+order_id+'&order_status_id='+order_status_id+'&comment='+_text+'&notify=1',
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