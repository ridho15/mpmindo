<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="card-title-w-btn">
					<h3 class="title">DompetKu</h3>
					<div class="btn-group pull-right">
					<a class="btn btn-primary" id="button-topup"><i class="fa fa-plus-circle"></i> Tambah Saldo</a>
					<a class="btn btn-warning" id="button-payout"><i class="fa fa-download"></i> Tarik Dana</a>
					</div>
				</div>
				<div id="message"></div>
				<?php if ($success) {?>
				<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<?=$success?>
				</div>
				<?php }?>
				<div class="widget-small primary"><i class="icon fa fa-money fa-3x"></i>
				  <div class="info">
					<h4>Saldo DompetKu</h4>
					<p><b><?=$balance?></b></p>
				  </div>
				</div>
				<table class="table table-hover table-bordered" width="100%" id="datatable">
					<thead>
						<tr>
							<th>#</th>
							<th>Tanggal</th>
							<th>Deskripsi</th>
							<th>Status</th>
							<th class="text-right">Debit</th>
							<th class="text-right">Kredit</th>
							<th class="text-right">Saldo</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true"></div>
<script type="text/javascript">
	$(document).ready(function() {
	    var table = $('#datatable').DataTable({
	    	"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=user_url('credit')?>",
				"type": "POST",
				"data": function(d) {
					d.code = "<?=$code?>";
				}
			},
			"columns": [
				{"orderable": false, "searchable" : false, "data": "user_transaction_id"},
				{"orderable": false, "searchable" : false, "data": "date_added"},
				{"orderable": false, "searchable" : false, "data": "description"},
				{"orderable": false, "searchable" : false, "data": "approved"},
				{"orderable": false, "searchable" : false, "data": "debit"},
				{"orderable": false, "searchable" : false, "data": "credit"},
				{"orderable": false, "searchable" : false, "data": "balance"},
			],
			"sDom":'<"table-responsive" t>p',
			"createdRow": function (row, data, index) {
				$('td', row).eq(4).addClass('text-right');
				$('td', row).eq(5).addClass('text-right');
				$('td', row).eq(6).addClass('text-right');
				
				if (data.approved == '0') {
					$('td', row).eq(3).html('<i class="fa fa-clock-o"></i>');
				} else {
					$('td', row).eq(3).html('<i class="fa fa-check" style="color:green;"></i>');
				}
			},
			"order": [[0, 'desc']]
		});
	});
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
	
	$('#button-payout').on('click', function() {
		$.ajax({
			url : "<?=user_url('credit/payout_request')?>",
			dataType: 'html',
			success: function(html) {
				$('#form-modal').html(html);
				$('#form-modal').modal('show');
			}
		});
	});
	
	$('#button-topup').on('click', function() {
		$.ajax({
			url : "<?=user_url('credit/topup')?>",
			dataType: 'html',
			success: function(html) {
				$('#form-modal').html(html);
				$('#form-modal').modal('show');
			}
		});
	});
	
	$('#form-modal').on('hidden.bs.modal', function (e) {
		refreshTable();
	});	
</script>