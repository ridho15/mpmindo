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
			<?=form_open(admin_url('reg_sale_offline/excel'), 'id="excel-action"')?>
				<div class="row">
					<div class="col-md-8">
						<a onclick="exportExcel();" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export Excel</a>
						<a onclick="getForm(null, '/reg_sale_offline/create');" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?=lang('button_insert')?></a>
					</div>
					<div class="col-md-2">
						<select name="reg_type" class="form-control">
							<option value="">-- Semua Tipe Registrasi --</option>
							<option value="reg_by_online">Registrasi dari belanja online</option>
							<option value="reg_by_customer">Registrasi oleh customer</option>
							<option value="reg_by_staff">Registrasi oleh staff</option>
						</select>
					</div>
					<div class="col-md-2">
						<select name="warranty_period" class="form-control">
							<option value="">-- Semua Masa Garansi --</option>
							<option value="1">Belum lewat masa garansi</option>
							<option value="2">Sudah lewat masa garansi</option>
						</select>
					</div>
				</div>
			</form>	
			</div>
			<div class="table-responsive">
				<table class="table table-hover" id="datatable" width="100%">
					<thead>
						<tr>
							<th><?=lang('column_register_date')?></th>
							<th><?=lang('column_product_code')?></th>
							<th><?=lang('column_product_name')?></th>
							<th><?=lang('column_register_type')?></th>
							<th><?=lang('column_shop_name')?></th>
							<th><?=lang('column_registrant')?></th>
							<th><?=lang('column_start_warranty')?></th>
							<th><?=lang('column_end_warranty')?></th>
							<th><?=lang('column_notes')?></th>
							<th class="text-right"></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	var warranty_period = '<?=$warranty_period;?>';
	var is_distributor = '<?=$this->admin->is_distributor();?>';
	$(document).ready(function() {
		$('select[name=\'reg_type\']').on('change', function() {
			refreshTable('#datatable');
		});

		$('select[name=\'warranty_period\']').on('change', function() {
			refreshTable('#datatable');
		});

		var table = $('#datatable').DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('reg_sale_offline')?>",
				"type": "POST",
				"data": function(d) {
					d.reg_type = $('select[name=\'reg_type\'] option:selected').val();
					d.warranty_period = $('select[name=\'warranty_period\'] option:selected').val();
				}
			},
			"columns": [
				{"data": "register_date"},
				{"data": "product_code"},
				{"data": "product_name"},
				{"data": "register_type"},
				{"data": "shop_name"},
				{"data": "user_name"},
				{"data": "start_warranty"},
				{"data": "start_warranty"},
				{"data": "notes"},
				{"orderable": false, "searchable" : false, "data": "reg_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				var today = new Date();
				var warranty = new Date(data.datewarranty);
				warranty.setMonth( warranty.getMonth() + parseInt(warranty_period));
				var datestring = ("0" + warranty.getDate()).slice(-2) + "/" + ("0"+(warranty.getMonth()+1)).slice(-2) + "/" + warranty.getFullYear();

				if(today > warranty) {
					$('td', row).eq(7).html('<span style="color:red;"><b>'+datestring+'</b></span>');
				}
				else {
					$('td', row).eq(7).html(datestring);
				}

				if(data.register_type == 'reg_by_staff') {
					$('td', row).eq(3).html('Registrasi oleh staff');
				} else if(data.register_type == 'reg_by_customer') {
					$('td', row).eq(3).html('Registrasi oleh customer');
				}
				else if(data.register_type == 'reg_by_online') {
					$('td', row).eq(3).html('Registrasi dari belanja online');
				}
				html  = '<div class="btn-group-xs">';
				if(data.register_type != 'reg_by_online') {
					html += '<a onclick="getPhoto('+data.reg_id+', \'/reg_sale_offline/detail_foto\');" class="btn btn-default btn-xs"><span class="fa fa-photo"></span> <?=lang('button_show')?></a> ';
				}
				if(is_distributor == '0') {
					html += '<a onclick="editWarranty('+data.reg_id+', \'/reg_sale_offline/edit_warrranty\');" class="btn btn-default btn-xs"><span class="fa fa-pencil"></span> <?=lang('button_edit_warranty')?></a> ';
				}
				html += '<a onclick="claimWarranty('+data.reg_id+', \'reg_sale_offline/start_warranty\');" class="btn btn-default btn-xs"><span class="fa fa-medkit"></span> <?=lang('button_start_warranty')?></a> ';
				html += '</div>';
				
				$('td', row).eq(9).html(html).addClass('text-right');
			}
		});
	});
	
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
	
	function getForm(reg_id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'reg_id='+reg_id,
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

	function getPhoto(reg_id) {
		$.ajax({
			url : "<?=admin_url('reg_sale_offline/photo')?>",
			data: 'reg_id='+reg_id,
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

	function editWarranty(reg_id) {
		$.ajax({
			url : "<?=admin_url('reg_sale_offline/edit_warrranty')?>",
			data: 'reg_id='+reg_id,
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

	function claimWarranty(reg_id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'reg_id='+reg_id,
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
					});	
				}
			}
		});
	}

	function exportExcel() {
		$('#excel-action').submit();
	}
</script>