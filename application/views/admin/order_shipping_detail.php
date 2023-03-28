<div class="page-title">
	<div>
		<h1><?=$template['title']?></h1>
	</div>
	<div class="btn-group">
		<a href="javascript:window.history.back();" class="btn btn-info"><i class="fa fa-reply"></i> Kembali</a>
		<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
		<i class="fa fa-print"></i> Cetak <span class="caret"></span>
		</button>
		<ul class="dropdown-menu" role="menu">
			<li><a href="<?=admin_url('order/eprint/invoice/'.$order_id)?>" target="_blank">Faktur</a></li>
			<li><a href="<?=admin_url('order/eprint/delivery/'.$order_id)?>" target="_blank">Surat Jalan</a></li>
		</ul>
	</div>
</div>
<section class="content">
	<div class="card">
		<div class="card-body">
			<div id="notification"></div>
			<div class="row">
				<div class="col-sm-6">
					<dl class="dl-horizontal">
						<dt>Dropship Order ID :</dt>
						<dd>#<?=$store_order_id?></dd>
						<dt>Order ID :</dt>
						<dd><a href="<?=admin_url('order/detail/'.$order_id)?>">#<?=$order_id?></a></dd>
						<dt>Tanggal :</dt>
						<dd><?=$date_added?></dd>
						<dt>Seller :</dt>
						<dd><?=$store?></dd>
						<dt>Status :</dt>
						<dd id="order-status"><?=$status?></dd>
						<dt>Total :</dt>
						<dd><?=$total?></dd>
					</dl>
				</div>
				<div class="col-sm-6">
					<dl>
						<dt>Alamat Pengiriman :</dt>
						<dd><?=$shipping_address?></dd>
						<dt>Metode Pengiriman :</dt>
						<dd><?=$shipping_method?></dd>
					</dl>
				</div>
			</div>
			<legend><h4>Item Barang</h4></legend>
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
							<td><?=$product['name']?></td>
							<td class="text-right"><?=$product['quantity']?></td>
							<td class="text-right"><?=$product['price']?></td>
							<td class="text-right"><?=$product['total']?></td>
						</tr>
						<?php }?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="3" class="text-right">Subtotal</th>
							<th class="text-right"><?=$subtotal?></th>
						</tr>
						<tr>
							<th colspan="3" class="text-right">Biaya Kirim</th>
							<th class="text-right"><?=$shipping_cost?></th>
						</tr>
						<?php if ($admin_fee) {?>
						<tr>
							<th colspan="3" class="text-right">Admin Fee</th>
							<th class="text-right"><?=$admin_fee?></th>
						</tr>
						<?php }?>
						<tr>
							<th colspan="3" class="text-right">Total</th>
							<th class="text-right"><?=$total?></th>
						</tr>
					</tfoot>
				</table>
			</div>
			<legend><h4>Riawayat Pesanan</h4></legend>
			<table class="table table-bordered" id="datatable" width="100%">
				<thead>
					<tr>
						<th style="min-width:15%;">Tanggal</th>
						<th>Status</th>
						<th>Dibuat Oleh</th>
						<th>Catatan</th>
						<th>Notified</th>
					</tr>
				</thead>
			</table>
			<?php if ($update) {?>
			<legend><h4>Update Status</h4></legend>
			<?=form_open($action, 'class="form-horizontal" id="form-status"')?>
				<input type="hidden" name="order_id" value="<?=$order_id?>">
				<input type="hidden" name="store_order_id" value="<?=$store_order_id?>">
				<div class="form-group">
					<label class="control-label col-sm-3">Status Pesanan</label>
					<div class="col-sm-6">
						<select name="order_status_id" class="form-control">
						<?php foreach ($order_statuses as $order_status) {?>
						<option value="<?=$order_status['order_status_id']?>"><?=$order_status['name']?></option>
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
					<label class="control-label col-sm-3">Notifikasi</label>
					<div class="col-sm-9">
						<div class="checkbox"><label><input type="checkbox" name="notify" value="1"> Notifikasi ke pembeli dan penjual</label></div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"></label>
					<div class="col-sm-9">
					<a class="btn btn-primary" id="button-status">Update Status</a>
					</div>
				</div>
			</form>
			<?php }?>
		</div>
	</div>
</section>

<script type="text/javascript">
	$(document).ready(function() {
	    var table = $('#datatable').DataTable({
	    	"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('order_shipping/history')?>",
				"type": "POST",
				"data": function (d) {
					d.order_id = "<?=$order_id?>";
					d.store_order_id = "<?=$store_order_id?>";
				}
			},
			"columns": [
				{"orderable": false, "searchable": false, "data": "date_added"},
				{"orderable": false, "searchable": false, "data": "status"},
				{"orderable": false, "searchable": false, "data": "updater"},
				{"orderable": false, "searchable": false, "data": "comment"},
				{"orderable": false, "searchable": false, "data": "notify"},
			],
			"createdRow": function (row, data, index) {
				if (data.notify == '1') {
					$('td', row).eq(4).html('<i class="fa fa-check" style="color:green;"></i>');
				} else {
					$('td', row).eq(4).html('');
				}
			},
			"sDom":'<"table-responsive" t>p',
			"order": [[0, 'desc']],
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
					$('#notification').html('<div class="alert alert-success">'+json['success']+'</div>');
					$('#order-status').html($('select[name=\'order_status_id\'] option:selected').text());
					refreshTable();
				}
			}
		});
	});
</script>