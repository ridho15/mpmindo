<div class="page-title">
	<div>
		<h1><?=$template['title']?></h1>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="form-action">
					<a onclick="refreshTable();" class="btn btn-default"><i class="fa fa-refresh"></i> <?=lang('button_refresh')?></a>
					<button onclick="delRecord();" class="btn btn-default"><i class="fa fa-trash"></i> <?=lang('button_delete')?></button>
				</div>
				<div id="message"></div>
				<?php if ($success) {?>
				<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?=$success?></div>
				<?php }?>
				<?php if ($error) {?>
				<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?=$error?></div>
				<?php }?>
				<?=form_open(admin_url('payment_confirmations'), 'id="bulk-action" target="_blank"')?>
				<table class="table table-hover" id="datatable" width="100%">
					<thead>
						<tr>
							<th><input type="checkbox" id="select-all"></th>
							<th>Tanggal</th>
							<th>No. Pesanan</th>
							<th>No. Invoice</th>
							<th>Tanggal Transfer</th>
							<th class="text-right">Nilai</th>
							<th class="text-right"></th>
						</tr>
					</thead>
				</table>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="<?=base_url('assets/js/sweetalert.min.js')?>" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
	    var table = $('#datatable').DataTable({
	    	"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('payment_confirmations')?>",
				"type": "POST",
			},
			"columns": [
				{"orderable": false, "data": "payment_confirmation_id"},
				{"data": "date_added"},
				{"data": "order_id"},
				{"data": "invoice_no"},
				{"data": "date_transfered"},
				{"data": "amount"},
				{"orderable": false, "data": "payment_confirmation_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html('<input type="checkbox" class="checkbox" name="payment_confirmation_id[]" value="'+data.payment_confirmation_id+'"/>');
				$('td', row).eq(2).html('#'+data.order_id);
				$('td', row).eq(5).addClass('text-right');
				
				if (data.attachment == false) {
					html = '';
				} else {
					html = '<a href="'+data.attachment+'" target="_blank" title="View attachment"><i class="fa fa-paperclip"></i></a>&nbsp;&nbsp;&nbsp;';
				}
				
				html += '<a style="cursor:pointer;" onclick="viewPaymentConfirmation('+data.payment_confirmation_id+');" title="View Details"><i class="fa fa-eye"></i></a>';
				
				$('td', row).eq(6).addClass('text-right').html(html);
			},
			"order": [[6, 'desc']],
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
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<?=lang('text_yes')?>',
			cancelButtonText: '<?=lang('text_no')?>',
			closeOnConfirm: false,
			closeOnCancel: true
		}, function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					url : '<?=admin_url('payment_confirmations/delete')?>',
					type : 'post',
					data: $('#bulk-action').serialize(),
					dataType: 'json',
					success: function(json) {
						if (json['success']) {
							swal('<?=lang('text_success')?>', json['success'], 'success');
							refreshTable();
						} else if (json['error']) {
							swal('<?=lang('text_error')?>', json['error'], 'error');
						} else if (json['redirect']) {
							window.location = json['redirect'];
						}
					}
				});
			}
		});
	}
	
	function actionSuccess(json) {
		if (json['success']) {
			$('#message').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> '+json['success']+'</div>');
		} else if (json['error']) {
			$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
		} else if (json['redirect']) {
			window.location = json['redirect'];
		}	
		refreshTable();
	}
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
	
	function viewPaymentConfirmation(payment_confirmation_id) {
		$.ajax({
			url : "<?=site_url('payment_confirmation/detail')?>",
			data: 'payment_confirmation_id='+payment_confirmation_id,
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
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