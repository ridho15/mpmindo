<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div id="notification"></div>
				<div class="card-title-w-btn">
					<h3 class="title"><?=$template['title']?></h3>
					<div class="btn-group">
						<a href="javascript:window.history.back();" class="btn btn-info"><i class="fa fa-reply"></i> Kembali</a>
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-print"></i> Cetak <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?=user_url('order/eprint/delivery/'.$order_id)?>" target="_blank">Surat Jalan</a></li>
						</ul>
					</div>
				</div>
				<div class="row">
				<div class="col-sm-6">
					<dl class="dl-horizontal">
						<dt>No. Pesanan :</dt>
						<dd><?=$store_order_id?></dd>
						<dt>Tanggal :</dt>
						<dd><?=$date_added?></dd>
						<dt>Status :</dt>
						<dd id="order-status"><?=$status?></dd>
						<dt>Nama Pemesan :</dt>
						<dd><?=$user_name?></dd>
						<dt>Email :</dt>
						<dd><?=$user_email?></dd>
						<dt>No. Telepon :</dt>
						<dd><?=$user_telephone?></dd>
					</dl>
				</div>
				<div class="col-sm-6">
					<dl>
						<dt>Alamat Pengiriman :</dt>
						<dd><?=$shipping_address?></dd>
						<dt>Metode Pengiriman :</dt>
						<dd><?=$shipping_method?></dd>
						<?php if ($waybill) {?>
						<dt>No. Resi :</dt>
						<dd><a style="cursor:pointer;border-bottom:1px dashed #ccc;" onclick="waybill('<?=$waybill?>', '<?=$shipping_code?>');"><?=$waybill?></a></dd>
						<?php }?>
					</dl>
				</div>
			</div>
			<legend><h4>Item Produk</h4></legend>
			<div class="table-responsive">
				<table class="table table-bordered" width="100%">
					<thead>
						<tr>
							<th>Item Pesanan</th>
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
						<tr>
							<th colspan="3" class="text-right">Total</th>
							<th class="text-right"><?=$total?></th>
						</tr>
					</tfoot>
				</table>
			</div>
			<legend><h4>Riwayat Pesanan</h4></legend>
			<table class="table table-bordered" id="datatable" width="100%">
				<thead>
					<tr>
						<th>Waktu Update</th>
						<th>Status</th>
						<th>Catatan</th>
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
				<div class="form-group" id="input-waybill">
					<label class="control-label col-sm-3">No. Resi</label>
					<div class="col-sm-9">
						<input type="text" name="waybill" value="" class="form-control">
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
						<div class="checkbox"><label><input type="checkbox" name="notify" value="1"> Notifikasi ke pembeli</label></div>
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
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
	    var table = $('#datatable').DataTable({
	    	"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=user_url('order/history')?>",
				"type": "POST",
				"data": function (d) {
					d.order_id = "<?=$order_id?>";
					d.store_order_id = "<?=$store_order_id?>";
				}
			},
			"columns": [
				{"orderable": false, "searchable": false, "data": "date_added"},
				{"orderable": false, "searchable": false, "data": "status"},
				{"orderable": false, "searchable": false, "data": "comment"},
			],
			"sDom":'<"table-responsive" t>p',
			"order": [[0, 'desc']],
		});
	});
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
	
	function waybill (waybill, shipping_code) {
		//var btn = $(this);
		$.ajax({
			url: "<?=user_url('order/waybill')?>",
			data: 'waybill='+waybill+'&shipping_code='+shipping_code,
			type: 'get',
			dataType: 'html',
			beforeSend: function() {
				//btn.button('loading');
			},
			complete: function() {
				//btn.button('reset');
			},
			success: function(html) {
				$('body').append('<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">'+html+'</div>');
				$('#modal').modal('show');
				$('#modal').on('hidden.bs.modal', function (e) {
					$('#modal').remove();
				});	
			}
		});
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
	
	var odsid = "<?=$this->config->item('order_delivered_status_id')?>";
	
	$('select[name=\'order_status_id\']').on('change', function() {
		if ($(this).val() === odsid) {
			$('#input-waybill').show();
		} else {
			$('#input-waybill').hide();
		}
	});
	
	$('select[name=\'order_status_id\']').trigger('change');
</script>