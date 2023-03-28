<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header" style="padding:20px 20px 10px">
			<h4>Foto Struk Pembelian</h4>
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		</div>
		<div class="modal-body" style="padding:20px">
			<div id="notification"></div>
				<div class="form-group">
				<div class="row">
					<div class="col-md-12">
					<?php if($invoice_image) { ?>
						<div class="thumbnail">
						<a href="<?=base_url('storage/images/'.$invoice_image)?>">
							<img src="<?=base_url('storage/images/'.$invoice_image)?>" alt="Photo" style="width:100%">
						</a>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="ps-btn ps-btn--outline ps-btn--sm" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
		</div>
	</div>
</div>