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
				<div class="row">
					<div class="col-md-8">
					</div>
					<div class="col-md-4">
						<select name="statusform" class="form-control" style="width:100%;">
							<option value="">-- Semua Status --</option>
								<option value="Klaim Baru">Klaim Baru</option>
								<option value="Barang Belum Diterima Dari Customer">Barang Belum Diterima Dari Customer</option>
								<option value="Barang Sudah Diterima Dari Customer Dan Sedang Diperiksa">Barang Sudah Diterima Dari Customer Dan Sedang Diperiksa</option>
								<option value="Klaim Disetujui Dan Sedang Diproses">Klaim Disetujui Dan Sedang Diproses</option>
								<option value="Klaim Tidak Disetujui Dan Barang Dikembalikan Ke Customer">Klaim Tidak Disetujui Dan Barang Dikembalikan Ke Customer</option>
								<option value="Klaim Dikenakan Biaya Dan Menunggu Pembayaran Dari Customer">Klaim Dikenakan Biaya Dan Menunggu Pembayaran Dari Customer</option>
								<option value="Klaim Tidak Dilanjutkan Customer Dan Barang Dikembalikan Ke Customer">Klaim Tidak Dilanjutkan Customer Dan Barang Dikembalikan Ke Customer</option>
								<option value="Customer Telah Melakukan Pembayaran Dan Menunggu Konfirmasi">Customer Telah Melakukan Pembayaran Dan Menunggu Konfirmasi</option>
								<option value="Proses Selesai Dan Barang Dikembalikan Ke Customer">Proses Selesai Dan Barang Dikembalikan Ke Customer</option>
								<option value="Barang Sudah Diterima Oleh Customer">Barang Sudah Diterima Oleh Customer</option>
								<option value="Selesai">Selesai</option>
								<option value="Klaim Ditolak">Klaim Ditolak</option>
						</select>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-hover" id="datatable" width="100%">
					<thead>
						<tr>
							<th><?=lang('column_claim_date')?></th>
							<th><?=lang('column_product_code')?></th>
							<th><?=lang('column_product_name')?></th>
							<th><?=lang('column_reason')?></th>
							<th><?=lang('column_destination')?></th>
							<th><?=lang('column_claim_by')?></th>
							<th><?=lang('column_status')?></th>
							<th class="text-right"></th>
						</tr>
					</thead>
				</table>
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
				"url": "<?=admin_url('warranty_claim')?>",
				"type": "POST",
				"data": function(d) {
					d.status = $('select[name=\'statusform\'] option:selected').val();
				}
			},
			"columns": [
				{"data": "claim_date"},
				{"data": "product_code"},
				{"data": "product_name"},
				{"data": "reason"},
				{"data": "warehouse"},
				{"data": "claim_by"},
				{"data": "status"},
				{"orderable": false, "searchable" : false, "data": "claim_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				html  = '<div class="btn-group-xs">';
				html += '<a onclick="getInfo('+data.claim_id+', \'/warranty_claim/information\');" class="btn btn-default btn-xs"><span class="fa fa-info"></span> <?=lang('button_info')?></a> ';
				if(data.user_id != null && (data.status == 'Klaim Tidak Disetujui Dan Barang Dikembalikan Ke Customer' || data.status == 'Proses Selesai Dan Barang Dikembalikan Ke Customer')) {
					html += '<a onclick="viewAddress('+data.claim_id+', \'warranty_claim/view_address\');" class="btn btn-default btn-xs"><span class="fa fa-home"></span> <?=lang('button_view_address')?></a> ';
				}
				if(data.status != 'Selesai' && data.status != 'Klaim Ditolak') {
					html += '<a onclick="changeStatus('+data.claim_id+', \'warranty_claim/change_status\');" class="btn btn-default btn-xs"><span class="fa fa-pencil"></span> <?=lang('button_change_status')?></a> ';
				}
				html += '</div>';
				
				$('td', row).eq(7).html(html).addClass('text-right');
			},
			"order": [[0, 'desc']]
		});
	});
	
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
	
	$('select[name=\'statusform\']').on('change', function() {
			refreshTable('#datatable');
	});

	function getInfo(claim_id) {
		$.ajax({
			url : "<?=admin_url('warranty_claim/information')?>",
			data: 'claim_id='+claim_id,
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
						//refreshTable('#datatable');
					});	
				}
			}
		});
	}

	function changeStatus(claim_id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'claim_id='+claim_id,
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
						refreshTable('#datatable');
					});	
				}
			}
		});
	}

	function viewAddress(claim_id) {
		$.ajax({
			url : "<?=admin_url('warranty_claim/view_address')?>",
			data: 'claim_id='+claim_id,
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
						//refreshTable('#datatable');
					});	
				}
			}
		});
	}
</script>