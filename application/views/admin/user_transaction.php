<div class="page-title">
	<div>
		<h1><?=$template['title']?></h1>
		<p>Daftar transaksi <?=$name?></p>
	</div>
	<div class="btn-group">
		<a href="javascript:window.history.back();" class="btn btn-info"><i class="fa fa-reply"></i> Kembali</a>
		<a onclick="refreshTable();" class="btn btn-default"><i class="fa fa-refresh"></i> Refresh</a>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div id="message"></div>
				<table class="table table-hover table-bordered" width="100%" id="datatable">
					<thead>
						<tr>
							<th>#</th>
							<th>Tanggal</th>
							<th>Deskripsi</th>
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
<div id="modal"></div>
<script type="text/javascript">
	$(document).ready(function() {
	    var table = $('#datatable').DataTable({
	    	"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('users/transaction')?>",
				"type": "POST",
				"data": function(d) {
					d.user_id = "<?=$user_id?>"
				}
			},
			"columns": [
				{"orderable": false, "searchable" : false, "data": "user_transaction_id"},
				{"orderable": false, "searchable" : false, "data": "date_added"},
				{"orderable": false, "searchable" : false, "data": "description"},
				{"orderable": false, "searchable" : false, "data": "debit"},
				{"orderable": false, "searchable" : false, "data": "credit"},
				{"orderable": false, "searchable" : false, "data": "balance"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				$('td', row).eq(3).addClass('text-right');
				$('td', row).eq(4).addClass('text-right');
				$('td', row).eq(5).addClass('text-right');
			},
			"order": [[0, 'desc']]
		});
	});
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
</script>