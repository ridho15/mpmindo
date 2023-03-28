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
					<div class="col-md-6">
						<a onclick="exportExcel();" class="btn btn-success"><i class="fa fa-check-square-o"></i> Export Excel</a>
					</div>
					<div class="col-md-3">
						<select name="product_id" class="form-control" style="width:100%;">
							<option value="">-- Semua Produk --</option>
							<?php foreach ($products as $product) {?>
							<option value="<?=$product['product_id']?>"><?=$product['name']?></option>
							<?php }?>
						</select>
					</div>
					<div class="col-md-3">
						<select name="warehouse_id" class="form-control" style="width:100%;">
							<option value="">-- Semua Gudang / Distributor --</option>
							<?php foreach ($warehouses as $warehouse) {?>
							<?php if(($warehouse_distributor != 0 && $warehouse_distributor == $warehouse['warehouse_id']) || $warehouse_distributor == 0) {?>
							<option value="<?=$warehouse['warehouse_id']?>"><?=$warehouse['name']?></option>
							<?php }?>
							<?php }?>
							<?php if($warehouse_distributor == 0) {?>
							<option value="xxx">Barang Terjual / Keluar</option>
							<?php }?>
						</select>
					</div>
				</div>
			</div>
			<div class="table-responsive">
				<?=form_open(admin_url('article/excel'), 'id="bulk-action"')?>
				<table class="table table-hover" id="datatable" width="100%">
					<thead>
						<tr>
							<th style="width: 20px"><input type="checkbox" id="select-all"></th>
							<th><?=lang('column_product_code')?></th>
							<th><?=lang('column_product_name')?></th>
							<th><?=lang('column_warehouse')?></th>
							<?php if($warehouse_distributor == 0) {?>
							<th><?=lang('column_invoice')?></th>
							<?php }?>
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
	var warehouse_distributor = "<?=$warehouse_distributor;?>";
	$(document).ready(function() {
		$('select[name=\'product_id\']').on('change', function() {
			refreshTable('#datatable');
		});
		$('select[name=\'warehouse_id\']').on('change', function() {
			refreshTable('#datatable');
		});

		if(warehouse_distributor != 0) {
			var table = $('#datatable').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url": "<?=admin_url('article')?>",
					"type": "POST",
					"data": function(d) {
						d.product_id = $('select[name=\'product_id\'] option:selected').val();
						d.warehouse_id = $('select[name=\'warehouse_id\'] option:selected').val();
					}
				},
				"columns": [
					{"orderable": false, "searchable" : false, "data": "article_id"},
					{"data": "product_code"},
					{"data": "product_name"},
					{"data": "warehouse_name"},
					{"orderable": false, "searchable" : false, "data": "article_id"},
				],
				"sDom":'lfr<"table-responsive" t>ip',
				"createdRow": function (row, data, index) {
					$('td', row).eq(0).html('<input type="checkbox" class="checkbox" name="article_id[]" value="'+data.article_id+'"/>');
					
					html  = '<div class="btn-group-xs">';
					html += '<a onclick="getForm('+data.article_id+', \'/article/detail\');" class="btn btn-default btn-xs"><span class="fa fa-exchange"></span> <?=lang('button_history')?></a> ';
					html += '</div>';
					
					$('td', row).eq(4).html(html).addClass('text-right');
				},
				"order": [[1, 'asc']]
			});
		}
		else {
			var table = $('#datatable').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url": "<?=admin_url('article')?>",
					"type": "POST",
					"data": function(d) {
						d.product_id = $('select[name=\'product_id\'] option:selected').val();
						d.warehouse_id = $('select[name=\'warehouse_id\'] option:selected').val();
					}
				},
				"columns": [
					{"orderable": false, "searchable" : false, "data": "article_id"},
					{"data": "product_code"},
					{"data": "product_name"},
					{"data": "warehouse_name"},
					{"data": "invoice_no"},
					{"orderable": false, "searchable" : false, "data": "article_id"},
				],
				"sDom":'lfr<"table-responsive" t>ip',
				"createdRow": function (row, data, index) {
					$('td', row).eq(0).html('<input type="checkbox" class="checkbox" name="article_id[]" value="'+data.article_id+'"/>');
					
					html  = '<div class="btn-group-xs">';
					html += '<a onclick="getForm('+data.article_id+', \'/article/detail\');" class="btn btn-default btn-xs"><span class="fa fa-exchange"></span> <?=lang('button_history')?></a> ';
					html += '</div>';
					
					$('td', row).eq(5).html(html).addClass('text-right');
				},
				"order": [[1, 'asc']]
			});
		}

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
					url : '<?=admin_url('inventory_trans/delete')?>',
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
	
	function getForm(article_id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'article_id='+article_id,
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
						//refreshTable();
					});	
				}
			}
		});
	}

	function exportExcel() {
		$('#bulk-action').submit();
	}
</script>