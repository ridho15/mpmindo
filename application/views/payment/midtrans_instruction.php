<div class="ps-page--simple">
	<div class="ps-breadcrumb">
		<div class="ps-container">
			<ul class="breadcrumb">
				<li><a href="<?=site_url()?>">Home</a></li>
				<li>Status Pembayaran</li>
			</ul>
		</div>
	</div>
	<div class="ps-section--page" style="padding: 30px 0;">		
		<div class="ps-container">
			<div class="ps-section__content">
				<div class="row">
					<div class="col-12">
						<h3>Rincian transaksi telah kami kirimkan ke email Anda</h3>
						<h4>Anda belum menyelesaikan pembayaran!</h4>
						<?php
						switch ($data["payment_type"]) {
						case "bank_transfer":
						?>
							Pesanan anda dengan <?=$data['payment_method']?> telah diterima namun belum dilakukan pembayaran.<br/>
							No. Virtual Account anda : <b><?=$data['payment_code']?></b><br/>
							Untuk penjelasan cara pembayaran, silakan klik di <a href="<?=$data['instruction']?>" style="font-weight:bold;text-decoration:underline" target="_blank">sini</a><br/>
						<?php
						break;
						case "echannel"
						?>
							Pesanan anda dengan <?=$data['payment_method']?> telah diterima namun belum dilakukan pembayaran.<br/>
							Kode pembayaran anda : <b><?=$data['payment_code']?></b><br/>
							Kode perusahaan : <b><?=$data['company_code']?></b><br/>
							Untuk penjelasan cara pembayaran, silakan klik di <a href="<?=$data['instruction']?>" style="font-weight:bold;text-decoration:underline" target="_blank">sini</a><br/>	
						<?php
						break;
						case "cstore"
						?>
							Pesanan anda dengan <?=$data['payment_method']?> telah diterima namun belum dilakukan pembayaran.<br/>
							Kode pembayaran anda : <b><?=$data['payment_code']?></b><br/>
							Untuk penjelasan cara pembayaran, silakan klik di <a href="<?=$data['instruction']?>" style="font-weight:bold;text-decoration:underline" target="_blank">sini</a><br/>
						<?php
						break;
						case "xl_tunai"
						?>
							Pesanan anda dengan <?=$data['payment_method']?> telah diterima namun belum dilakukan pembayaran.<br/>
							ID Pesanan XL Tunai : <b><?=$data['xl_tunai_order_id']?></b><br/>
							Kode Merchant XL Tunai : <b><?=$data['merchant_code']?></b><br/>
							<?=$data['instruction']?>
						<?php
						break;
						}
						?>
						<p><b>Harap untuk melakukan pembayaran dalam 24 jam untuk menghindari pembatalan!</b></p>
						<hr/>
						<button class="ps-btn" onclick="window.location = '<?=site_url()?>'">Kembali ke halaman depan</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>