<div class="row">
	<div class="">
		<div class="card">
			<div class="card-body">
				<div class="card-title-w-btn">
					<h3 class="title">Detil Pesanan</h3>
					<div class="btn-group">
						<a onclick="$(this).append(' <i class=\'fa fa-refresh fa-spin\'></i>'); window.location = '<?=user_url('order')?>';" class="btn btn-info"><i class="fa fa-reply"></i> Kembali</a>
						<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-print"></i> Cetak <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?=user_url('order/eprint/invoice/'.$order_id)?>" target="_blank">Faktur</a></li>
							<li><a href="<?=user_url('order/eprint/delivery/'.$order_id)?>" target="_blank">Surat Jalan</a></li>
						</ul>
					</div>
				</div>
				<div class="row">
				<div class="col-sm-6">
					<dl class="dl-horizontal">
						<dt>Order Code :</dt>
						<dd><?=$order_id?></dd>
						<dt>Tanggal :</dt>
						<dd><?=$date_added?></dd>
						<dt>Status :</dt>
						<dd><?=$status?></dd>
						<dt>Nama :</dt>
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
						<th style="min-width:1%;"></th>
					</tr>
				</thead>
			</table>
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
				}
			},
			"columns": [
				{"orderable": false, "searchable": false, "data": "date_added"},
				{"orderable": false, "searchable": false, "data": "status"},
				{"orderable": false, "searchable": false, "data": "comment"},
				{"orderable": false, "searchable": false, "data": "notify"},
			],
			"createdRow": function (row, data, index) {
				if (data.notify == '1') {
					$('td', row).eq(4).addClass('text-center').html('<i class="fa fa-bullhorn" style="color:green;"></i>');
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
</script>