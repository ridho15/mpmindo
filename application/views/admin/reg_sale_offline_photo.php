<?php $inventory_row = 0; ?>
<script src="<?=base_url('assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.min.js')?>"></script>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Foto Penjualan</h4>
		</div>
		<div class="modal-body">
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
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> <?=lang('button_cancel')?></button>
		</div>
	</div>
</div>