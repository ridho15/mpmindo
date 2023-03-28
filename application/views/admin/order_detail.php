<div class="page-title">
	<div>
		<h1><?=$template['title']?></h1>
	</div>
</div>
<section class="content">
	<div class="card">
		<div class="card-body">
			<?php if($order_status_id_current == $order_status_id_default) {?>
			<div class="alert alert-warning" role="alert">
				<h4><b>Pembayaran Belum Ada Dari Pembeli</b></h4>
				Jika Sampai Tanggal <b><?=$expiry_date;?> WIB</b> Tidak Ada Perubahan Status, Maka Pesanan Akan Otomatis Dibatalkan.
			</div>
			<?php }?>
			<div class="form-action">
				<a href="javascript:window.history.back();" class="btn btn-default"><i class="fa fa-reply"></i> Kembali</a>
				<a href="<?=admin_url('order/eprint/invoice/'.$order_id)?>" class="btn btn-default" target="_blank"><i class="fa fa-print"></i> Cetak</a>
			</div>
			
			<div class="row">
				<div class="col-sm-6">
					<dl class="dl-horizontal">
						<dt>No. Invoice :</dt>
						<dd><?=$invoice_no?></dd>
						<dt>Tanggal :</dt>
						<dd><?=$date_added?> WIB</dd>
						<dt>Status :</dt>
						<dd id="order-status"><?=$status?></dd>
						<dt>Nama :</dt>
						<dd><?=$user_name?></dd>
						<dt>Email :</dt>
						<dd><?=$user_email?></dd>
						<dt>No. Telepon :</dt>
						<dd><?=$user_telephone?></dd>
						<dt>Total :</dt>
						<dd><?=$total?></dd>
					</dl>
				</div>
				<div class="col-sm-6">
					<dl class="dl-horizontal">
						<dt>Metode Pembayaran :</dt>
						<dd><?=$payment_method?></dd>
						<dt>Metode Pengiriman :</dt>
						<dd><?=$shipping_method?></dd>
						<dt>Alamat Pengiriman :</dt>
						<dd><?=$shipping_address?></dd>
						<dt>Catatan :</dt>
						<dd><?php if($comment) echo $comment; else echo "<br>";?></dd>
						<?php if ($tax_id) {?>
						<dt>NPWP :</dt>
						<dd><?=$tax_id?></dd>
						<dt>Nama WP :</dt>
						<dd><?=$tax_name?></dd>
						<dt>Alamat WP :</dt>
						<dd><?=$tax_address?></dd>
						<?php }?>
					</dl>
					<?php if($order_status_id != $order_complete_id) {?>
					<?php if ($waybill) {?>
					<div class="alert alert-info">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">No. Resi</span>
							<input type="text" class="form-control" value="<?=$waybill?>" disabled="disabled">
							<span class="input-group-btn">
								<button class="btn btn-primary" type="button" id="button-tracking" data-order-id="<?=$order_id?>"><span class="fa fa-search"></span> Lacak</button>
							</span>
						</div>
					</div>
					<?php }?>
					<?php }?>
				</div>
			</div>
			<legend><h4>Item Produk</h4></legend>
			<div class="table-responsive">
				<table class="table table-bordered" width="100%">
					<thead>
						<tr>
							<th>Produk</th>
							<th class="text-right">Qty</th>
							<th class="text-right">Harga</th>
							<th class="text-right">Total</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($products as $product) {?>
						<tr>
							<td><?=$product['name']?><br><small>Kode Produk: <?=$product['sku']?></small></td>
							<td class="text-right"><?=$product['quantity']?></td>
							<td class="text-right"><?=$product['price']?></td>
							<td class="text-right"><?=$product['total']?></td>
						</tr>
						<?php }?>
					</tbody>
					<tfoot>
						<?php foreach ($totals as $total) {?>
						<tr>
							<th colspan="3" class="text-right"><?=$total['title']?></th>
							<th class="text-right"><?=$total['text']?></th>
						</tr>
						<?php }?>
					</tfoot>
				</table>
			</div>
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab-1" data-toggle="tab" id="default">HISTORI PESANAN</a></li>
				<li><a href="#tab-2" data-toggle="tab">KONFIRMASI PEMBAYARAN</a></li>
				<li><a href="#tab-3" data-toggle="tab">DAFTAR PRODUK UNTUK PESANAN</a></li>
			</ul>
			<div class="tab-content" style="padding-top:20px;">
				<div class="tab-pane active" id="tab-1">
					<div class="table-responsive">
						<table class="table" id="datatable" width="100%">
							<thead>
								<tr>
									<th style="min-width:15%;">Tanggal</th>
									<th>Status</th>
									<th>Diperbarui</th>
									<th width="30%">Catatan</th>
									<th>No. Resi</th>
									<th>Notified</th>
								</tr>
							</thead>
						</table>
					</div>
					<div id="notification">
					<?php if($this->session->flashdata('success')) {?>
						<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> <?php echo $this->session->flashdata('success');?></div>
					<?php }?>
					</div>
					<?php if($order_status_id != $order_complete_id) {?>
					<legend><h4>Update Status</h4></legend>
					<?=form_open($action, 'class="form-horizontal" id="form-status" style="padding:20px;background-color:#f5f5f5;border-radius:5px"')?>
						<input type="hidden" name="order_id" value="<?=$order_id?>">
						<div class="form-group">
							<label class="control-label col-sm-3">Status Pesanan</label>
							<div class="col-sm-6">
								<select name="order_status_id" class="form-control">
								<?php foreach ($order_statuses as $order_status) {?>
								<?php if ($order_status['order_status_id'] >= $order_status_id) {?>
								<option value="<?=$order_status['order_status_id']?>"><?=$order_status['name']?></option>
								<?php }?>
								<?php }?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Catatan</label>
							<div class="col-sm-9">
								<textarea name="comment" class="form-control" rows="8"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">No. Resi Pengiriman</label>
							<div class="col-sm-9">
								<input type="text" name="waybill" class="form-control" value="">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3">Notifikasi</label>
							<div class="col-sm-9">
								<div class="checkbox"><label><input type="checkbox" name="notify" value="1" checked="checked"> Notifikasi ke customer</label></div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3"></label>
							<div class="col-sm-9">
							<a class="btn btn-primary" id="button-status">Update Status</a>
							</div>
						</div>
					</form>
					<?php } ?>
				</div>
				<div class="tab-pane" id="tab-2">
					<div class="form-action">
						<button onclick="delRecord();" class="btn btn-default"><i class="fa fa-trash"></i> <?=lang('button_delete')?></button>
					</div>
					<?=form_open(admin_url('payment_confirmations'), 'id="bulk-action" target="_blank"')?>
					<div class="table-responsive">
					<table class="table table-hover" id="datatable2" width="100%">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all"></th>
								<th>Tanggal</th>
								<th>No. Invoice</th>
								<th>Tanggal Transfer</th>
								<th class="text-right">Nilai</th>
								<th class="text-right"></th>
							</tr>
						</thead>
					</table>
					</div>
					</form>
				</div>
				<div class="tab-pane" id="tab-3">
					<div id="notification2"></div>
					<?=form_open($product_action, 'class="form-horizontal" id="form-product"')?>
					<div class="table-responsive">
						<table class="table" id="datatable3" width="100%">
							<thead>
								<tr>
									<th>Nama Produk</th>
									<th width="30%">Kode Serial Produk</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($order_product_details as $detail) {?>
								<tr>
									<td><?=$detail['name'];?></td>
									<td><input type="text" name="product_code[]" class="form-control" maxlength="50" value="<?php if($detail['product_code'] == '' || $detail['product_code'] == 'W') echo 'W'; else echo $detail['product_code'];?>" <?php if($order_deliver_id <= $order_status_id) echo 'readonly';?>><input type="hidden" name="order_product_detail_id[]" value="<?=$detail['id'];?>"></td>
								</tr>
							<?php } ?>		
							</tbody>
						</table>
					</div>
					<?php if($order_deliver_id > $order_status_id) {?>
					<div align="right">
					<a class="btn btn-primary" id="button-product">Update Produk</a>
					</div>
					<?php }?>
					<input type="hidden" name="order_id" value="<?=$order_id?>">
					</form>
				</div>
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
				"url": "<?=admin_url('order/history')?>",
				"type": "POST",
				"data": function (d) {
					d.order_id = "<?=$order_id?>";
				}
			},
			"columns": [
				{"orderable": false, "searchable": false, "data": "date_added"},
				{"orderable": false, "searchable": false, "data": "status"},
				{"orderable": false, "searchable": false, "data": "updater"},
				{"orderable": false, "searchable": false, "data": "comment"},
				{"orderable": false, "searchable": false, "data": "waybill"},
				{"orderable": false, "searchable": false, "data": "notify"},
			],
			"createdRow": function (row, data, index) {
				if (data.notify == '1') {
					$('td', row).eq(5).html('<i class="fa fa-check" style="color:green;"></i>');
				} else {
					$('td', row).eq(5).html('');
				}
			},
			"sDom":'<"table-responsive" t>p',
			"order": [[0, 'desc']],
		});
		
		var table2 = $('#datatable2').DataTable({
	    	"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('payment_confirmations')?>",
				"type": "POST",
				"data": function(d) {
					d.order_id = <?=$order_id?>
				}
			},
			"columns": [
				{"orderable": false, "data": "payment_confirmation_id"},
				{"data": "date_added"},
				{"data": "invoice_no"},
				{"data": "date_transfered"},
				{"data": "amount"},
				{"orderable": false, "data": "payment_confirmation_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html('<input type="checkbox" class="checkbox" name="payment_confirmation_id[]" value="'+data.payment_confirmation_id+'"/>');
				$('td', row).eq(4).addClass('text-right');
				
				if (data.attachment == false) {
					html = '';
				} else {
					html = '<a href="'+data.attachment+'" target="_blank" title="View attachment"><i class="fa fa-paperclip"></i></a>&nbsp;&nbsp;&nbsp;';
				}
				
				html += '<a style="cursor:pointer;" onclick="viewPaymentConfirmation('+data.payment_confirmation_id+');" title="View Details"><i class="fa fa-eye"></i></a>';
				
				$('td', row).eq(5).addClass('text-right').html(html);
			},
			"order": [[5, 'desc']],
		});
		
		// var table3 = $('#datatable3').DataTable({
	    // 	"processing": true,
		// 	"serverSide": true,
		// 	"ajax": {
		// 		"url": "<?=admin_url('order/article')?>",
		// 		"type": "POST",
		// 		"data": function (d) {
		// 			d.order_id = "<?=$order_id?>";
		// 		}
		// 	},
		// 	"columns": [
		// 		{"orderable": false, "searchable": false, "data": "product_code"},
		// 		{"orderable": false, "searchable": false, "data": "name"},
		// 	],
		// 	"createdRow": function (row, data, index) {
		// 		$('td', row).eq(0).html('<input type="text" class="form-control" name="product_code[]" value="" maxlength="50"/>');
		// 	},
		// 	"sDom":'<"table-responsive" t>p',
		// });

		$('#select-all').click(function() {
			var checkBoxes = $('.checkbox');
			checkBoxes.prop('checked', !checkBoxes.prop('checked'));
		});
	});
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
	
	$('#button-status').on('click', function () {
		var btn = $(this);
		$.ajax({
			url: $('#form-status').attr('action'),
			data: $('#form-status').serialize(),
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				btn.button('loading');
			},
			complete: function() {
				btn.button('reset');
			},
			success: function(json) {
				$('html, body').animate({ scrollTop: 0 }, 'slow');
				if (json['success']) {
					// if(json['newstatus'] == json['completestatus']) location.reload();
					// $('#notification').html('<div class="alert alert-success">'+json['success']+'</div>');
					// $('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());
					// refreshTable();
					location.reload();
				}
				if (json['error']) {
					$('#notification').html('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+json['error']+'</div>');
				
				}
			}
		});
	});
	
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
	
	function delRecord() {
		swal({
			title: "<?=lang('text_confirm')?>",
			text: "<?=lang('text_delete_confirm')?>",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: '<?=lang('text_yes')?>',
			cancelButtonText: '<?=lang('text_no')?>',
			closeOnConfirm: false,
			closeOnCancel: true,
			showLoaderOnConfirm: true
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
	
	$('#button-tracking').on('click', function () {
		var btn = $(this);
		
		$.ajax({
			url: "<?=site_url('tracking')?>",
			data: 'order_id=' + btn.data('order-id'),
			dataType: 'json',
			beforeSend: function() {
				btn.button('loading');
			},
			complete: function() {
				btn.button('reset');
			},
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
	});

	$('#button-product').on('click', function () {
		var btn = $(this);
		$.ajax({
			url: $('#form-product').attr('action'),
			data: $('#form-product').serialize(),
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				btn.button('loading');
			},
			complete: function() {
				btn.button('reset');
			},
			success: function(json) {
				$('html, body').animate({ scrollTop: 0 }, 'slow');
				if (json['success']) {
					$('#notification2').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+json['success']+'</div>');
				}
				else if(json['warning']) {
					$('#notification2').html('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+json['warning']+'</div>');
				}
			}
		});
	});
</script>