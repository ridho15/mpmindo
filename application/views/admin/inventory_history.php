<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$product?></h4>
			<?=$sku?>
		</div>
		<div class="modal-body">
			<div class="form-action" id="inventory-history">
				<div class="row">
					<div class="col-md-6">
						<select name="warehouse_id" class="form-control" style="width:100%;">
							<?php foreach ($warehouses as $warehouse) {?>
							<option value="<?=$warehouse['warehouse_id']?>"><?=$warehouse['code']?> (<?=$warehouse['name']?>)</option>
							<?php }?>
						</select>
					</div>
				</div>
			</div>
			<table class="table table-hover" id="datatables" width="100%">
				<thead>
					<tr>
						<th>Tanggal</th>
						<th>Deskripsi</th>
						<th>Referensi</th>
						<th>Masuk</th>
						<th>Keluar</th>
						<th>Sisa</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('select[name=\'warehouse_id\']').on('change', function() {
			refreshTable();
		});
		
	    var table = $('#datatables').DataTable({
	    	"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('inventory/history')?>",
				"type": "POST",
				"data": function(d) {
					d.product_id = "<?=$product_id?>";
					d.warehouse_id = $('select[name=\'warehouse_id\'] option:selected').val();
				}
			},
			"columns": [
				{"data": "inventory_id"},
				{"data": "description"},
				{"data": "description"},
				{"data": "item_in"},
				{"data": "item_out"},
				{"data": "balance"},
			],
			"sDom":'<"table-responsive" t>p',
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html(data.date_added);
				
				if (data.invoice_no !== null) {
					$('td', row).eq(2).html('<a target="blank" href="<?=admin_url('order/detail')?>/'+data.order_id+'">#'+data.invoice_no+'</a>');
				} else {
					$('td', row).eq(2).html('#'+data.inventory_trans_id);
				}
			},
			"order": [[0, 'desc']],
			"initComplete": function( settings, json ) {
				setTimeout(function() {
					$('#datatables').DataTable().page('last').draw('page');
				}, 100);
			}
		});
	});
	
	function refreshTable() {
		$('#datatables').DataTable().ajax.reload();
	}
</script>