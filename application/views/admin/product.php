<div class="page-title">
	<div>
		<h1><?=lang('heading_title')?></h1>
		<p><?=lang('heading_subtitle')?></p>
	</div>
</div>
<section class="content">
	<div class="card">
		<div class="card-body">
			<div class="tab-content">
				<div class="tab-pane active" id="tabs-1">
					<div class="form-action">
						<div class="row">
							<div class="col-md-8">
								<a onclick="getForm(null, '/product/create');" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?=lang('button_insert')?></a>
								<a onclick="delRecord();" class="btn btn-default"><i class="fa fa-check-square-o"></i> <?=lang('button_remove')?></a>
							</div>
							<div class="col-md-4">
								<select name="category_id" class="form-control" style="width:100%;">
									<option value="">-- Semua Kategori --</option>
									<?php foreach ($categories as $category) {?>
									<option value="<?=$category['category_id']?>"><?=$category['name']?></option>
									<?php }?>
								</select>
							</div>
						</div>
					</div>
					<div class="table-responsive">
						<?=form_open('', 'id="bulk-action"')?>
						<table class="table table-hover" id="datatable" width="100%">
							<thead>
								<tr>
									<th style="width: 20px"><input type="checkbox" id="select-all"></th>
									<th><?=lang('label_name')?></th>
									<!-- <th>SKU</th> -->
									<th><?=lang('label_price')?></th>
									<th><?=lang('label_active')?></th>
									<th>Total Stok</th>
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
</section>
<script type="text/javascript">
	$(document).ready(function() {
		$('select[name=\'category_id\']').on('change', function() {
			refreshTable('#datatable');
		});
		
		$('select[name=\'inventory_category_id\'], select[name=\'location_id\']').on('change', function() {
			refreshTable('#datatable2');
		});
		
		$('#tabs').find('a').on('shown.bs.tab', function (e) {
			num = parseInt(e.currentTarget.hash.replace('#tabs-', ''));
			elem = '#datatable'+(num > 1 ? num : '');
			refreshTable(elem);
		});
		
		var table = $('#datatable').DataTable({
			"sPaginationType": "full_numbers",
			"bStateSave": true,
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('product')?>",
				"type": "POST",
				"data": function(d) {
					d.category_id = $('select[name=\'category_id\'] option:selected').val();
				}
			},
			"columns": [
				{"orderable": false, "searchable" : false, "data": "product_id"},
				{"data": "name"},
				// {"data": "sku"},
				{"data": "price"},
				{"data": "active"},
				{"orderable": false, "searchable" : false, "data": "stock"},
				{"orderable": false, "searchable" : false, "data": "product_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html('<input type="checkbox" class="checkbox" name="product_id[]" value="'+data.product_id+'"/>');
				if (data.active == '0' & data.stock == '0')
					$('td', row).eq(3).html('<a onclick="activeError()"><span class="label label-default"><?=lang('text_inactive')?></span></a>');
				else if (data.active == '1') {
					$('td', row).eq(3).html('<a onclick="toggleActive('+data.product_id+',0)"><span class="label label-success"><?=lang('text_active')?></span></a>');
				} else {
					$('td', row).eq(3).html('<a onclick="toggleActive('+data.product_id+',1)"><span class="label label-default"><?=lang('text_inactive')?></span></a>');
				}
				if(data.stock == '0' || data.stock == '') {
					$('td', row).eq(4).html('0');
				}
				
				html  = '<div class="btn-group-xs">';
				html += '<a onclick="getHistory('+data.product_id+');" class="btn btn-default btn-xs"><i class="fa fa-exchange"></i> History Stok</a> <a onclick="getForm('+data.product_id+', \'/product/edit\');" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> <?=lang('button_edit')?></a>';
				html += '</div>';
				
				$('td', row).eq(5).html(html).addClass('text-right');
			},
			"order": [[0, 'asc']]
		});
		
		$('#select-all').click(function() {
			var checkBoxes = $('.checkbox');
			checkBoxes.prop('checked', !checkBoxes.prop('checked'));
		});
		
		var table2 = $('#datatable2').DataTable({
			
			"sPaginationType": "full_numbers",
        	"bStateSave": true,
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('product/iproducts')?>",
				"type": "POST",
				"data": function(d) {
					d.product_category_id = $('select[name=\'inventory_category_id\'] option:selected').val();
					d.location = $('select[name=\'location_id\'] option:selected').data('location');
					d.location_id = $('select[name=\'location_id\'] option:selected').val();
				}
			},
			"columns": [
				{"orderable": false, "searchable" : false, "data": "id"},
				{"data": "product_name"},
				{"data": "product_code"},
				{"data": "limit_price"},
				{"data": "stock"},
				{"orderable": false, "searchable" : false, "data": "id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html('<input type="checkbox" class="checkbox" name="id[]" value="'+data.id+';'+data.stock+'"/>');
				$('td', row).eq(3).html(data.limit_price+' - '+data.sale_price);
				if (data.product_id == null) {
					$('td', row).eq(5).html('');
				} else {
					$('td', row).eq(5).html('<small><span class="fa fa-check" style="color:green;"></span> E-Commerce Ready</small>');
				}
			},
			"order": [[0, 'asc']]
		});
		
		$('#select-all2').click(function() {
			var checkBoxes = $('.checkbox');
			checkBoxes.prop('checked', !checkBoxes.prop('checked'));
		});
	});
	
	function toggleActive(id,status) {
		swal({
			title: "<?=lang('text_confirm')?>",
			text: "Anda yakin ingin mengubah status produk dari "+ (status==1? "Non":"") +" Aktif, menjadi "+ (status==0? "Non":"")+" Aktif ?",
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
					url : '<?=admin_url('product/toggleActive')?>',
					type : 'post',data: {'product_id':id,'status':status},
					dataType: 'json',
					success: function(json) {
						if (json['success']) {
							swal("<?=lang('text_success')?>", json['success'], 'success');
						} else if (json['error']) {
							swal("<?=lang('text_error')?>", json['error'], 'error');
						} else if (json['redirect']) {
							window.location = json['redirect'];
						}
						refreshTable('#datatable');
						refreshTable('#datatable2');
					}
				});
			}
		});
	}
	
	function activeError() {
		swal("Bad Request!", "Can't activate when there is no stock left!", "error");
	}
	
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
					url : '<?=admin_url('product/delete')?>',
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
						
						refreshTable('#datatable');
						refreshTable('#datatable2');
					}
				});
			}
		});
	}
	
	function refreshTable(elem) {
		$(elem).DataTable().ajax.reload(null,false);
	}
	
	function getForm(product_id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'product_id='+product_id,
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
					if(product_id==null) $("[name='active']").click();
					$('#form-modal').on('hidden.bs.modal', function (e) {
						$('#form-modal').remove();
						refreshTable('#datatable');
					});	
				}
			}
		});
	}
	
	function getHistory(product_id) {
		$.ajax({
			url : "<?=admin_url('inventory/history')?>",
			data: 'product_id='+product_id,
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
	
	function synch() {
		swal({
			title: "<?=lang('text_confirm')?>",
			text: "Tambahkan ke produk E-Commerce. Note: Jika data sudah ada sebelumnya, maka data produk seperti nama, SKU, harga, dan pajak akan diatur ulang menyesuaikan data terbaru dari sistem inventory.",
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
					url : "<?=admin_url('product/synch')?>",
					type : 'post',
					data: $('#bulk-action2').serialize(),
					dataType: 'json',
					success: function(json) {
						if (json['success']) {
							swal("<?=lang('text_success')?>", json['success'], 'success');
						} else if (json['error']) {
							swal("<?=lang('text_error')?>", json['error'], 'error');
						} else if (json['redirect']) {
							window.location = json['redirect'];
						}
						
						refreshTable('#datatable');
						refreshTable('#datatable2');
					}
				});
			}
		});
	}
</script>