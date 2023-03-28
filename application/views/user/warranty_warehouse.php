<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header" style="padding:20px 20px 10px">
			<h4>Alamat Kirim Untuk Klaim Garansi</h4>
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		</div>
		<div class="modal-body" style="padding:20px">
			<div id="notification"></div>
			<div class="form-group">
				<?="Wilayah ".$warehouse['region']?><hr><br>
				<?=$warehouse['code'].' - '.$warehouse['name']?><br>
				<?=$warehouse['address']?><br>
				<?=$warehouse['telephone']?>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="ps-btn ps-btn--outline ps-btn--sm" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
		</div>
	</div>
</div>