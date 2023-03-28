<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Konfirmasi Pembayaran</h4>
		</div>
		<div class="modal-body">
			<?php if (isset($pc)) {?>
			<ul class="list-group">
				<li class="list-group-item">No. Invoice:<span class="pull-right"><?=$pc['invoice_no']?></span></li>
				<li class="list-group-item">Jumlah Pembayaran<span class="pull-right"><?=format_money($pc['amount'])?></span></li>
				<li class="list-group-item">Tanggal Transfer<span class="pull-right"><?=format_date($pc['date_transfered'])?></span></li>
			</ul>
			<ul class="list-group">
				<li class="list-group-item">Bank Pengirim<span class="pull-right"><?=$pc['from_bank']?></span></li>
				<li class="list-group-item">Nama Pemilik Rekening<span class="pull-right"><?=$pc['from_name']?></span></li>
				<li class="list-group-item">No. Rekening Pengirim<span class="pull-right"><?=$pc['from_number']?></span></li>
				<li class="list-group-item">Bank Penerima<span class="pull-right"><?=strtoupper($pc['to_bank'])?></span></li>
				<li class="list-group-item">Email<span class="pull-right"><?=$pc['email']?></span></li>
				<li class="list-group-item">Telepon<span class="pull-right"><?=$pc['telephone']?></span></li>
				<li class="list-group-item">Pesan<span class="pull-right"><?=$pc['note']?></span></li>
			</ul>
			<?php } else {?>
			<p>Data konfirmasi pembayaran tidak ditemukan!</p>
			<?php }?>
		</div>
	</div>
</div>