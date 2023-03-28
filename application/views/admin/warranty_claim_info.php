<?php $inventory_row = 0; ?>
<script src="<?=base_url('assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.min.js')?>"></script>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Informasi Klaim Garansi</h4>
		</div>
		<div class="modal-body">
			<div id="notification"></div>
			<?=form_open($action, 'id="form" role="form" class="form-horizontal"')?>
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab-1" data-toggle="tab" id="default">Informasi</a></li>
				<li><a href="#tab-2" data-toggle="tab" id="tabdetail">History</a></li>
			</ul>
			<div class="tab-content" style="padding-top:20px;">
				<div class="tab-pane active" id="tab-1">
					<div class="form-group">
						<label class="control-label col-sm-3"><?=lang('column_claim_date')?></label>
						<div class="col-sm-9">
							<input type="text" name="productcode" value="<?=date('d-m-Y',strtotime($claim['claim_date']))?>" class="form-control" maxLength="100" readonly/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3"><?=lang('column_product_code')?></label>
						<div class="col-sm-9">
							<input type="text" name="product_code" value="<?=$claim['product_code']?>" class="form-control" maxLength="100" readonly/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3"><?=lang('column_product_name')?></label>
						<div class="col-sm-9">
							<input type="text" name="product_name" value="<?=$claim['product_name']?>" class="form-control" maxLength="100" readonly/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3"><?=lang('column_reason')?></label>
						<div class="col-sm-9">
							<input type="text" name="reason" value="<?=$claim['reason']?>" class="form-control" maxLength="100" readonly/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3"><?=lang('column_claim_description')?></label>
						<div class="col-sm-9">
							<textarea name="claim_description" class="form-control" readonly><?=$claim['claim_description']?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3"><?=lang('column_destination')?></label>
						<div class="col-sm-9">
							<input type="text" name="reason" value="<?=$warehouse?>" class="form-control" maxLength="100" readonly/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3"><?=lang('column_claim_by')?></label>
						<div class="col-sm-9">
							<input type="text" name="reason" value="<?=$claim['claim_by']?>" class="form-control" maxLength="100" readonly/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3"><?=lang('column_email')?></label>
						<div class="col-sm-9">
							<input type="text" name="reason" value="<?=$claim['user_email']?>" class="form-control" maxLength="100" readonly/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3"><?=lang('column_phone')?></label>
						<div class="col-sm-9">
							<input type="text" name="reason" value="<?=$claim['user_phone']?>" class="form-control" maxLength="100" readonly/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3"><?=lang('column_status')?></label>
						<div class="col-sm-9">
							<input type="text" name="reason" value="<?=$claim['status']?>" class="form-control" maxLength="100" readonly/>
						</div>
					</div>
					<?php if($claim['new_product_code']) {?>
					<div class="form-group">
						<label class="control-label col-sm-3"><?=lang('column_new_product_code')?></label>
						<div class="col-sm-9">
							<input type="text" name="reason" value="<?=$claim['new_product_code']?>" class="form-control" maxLength="100" readonly/>
						</div>
					</div>
					<?php } ?>
					<?php if($claim['evidence_photo']) { ?>
					<div class="form-group">
							<label class="control-label col-sm-3"><?=lang('column_evidence_photo')?></label>
							<div class="col-sm-9">
								<div class="thumbnail">
								<a href="<?=base_url('storage/images/'.$claim['evidence_photo'])?>">
									<img src="<?=base_url('storage/images/'.$claim['evidence_photo'])?>" alt="Photo" style="width:15%">
								</a>
								</div>
							</div>
					</div>
					<?php } ?>
					<?php if($claim['response_note']) { ?>
					<div class="form-group">
						<label class="control-label col-sm-3"><?=lang('column_response')?></label>
						<div class="col-sm-9">
							<textarea name="claim_description" class="form-control" readonly><?=$claim['response_note']?></textarea>
						</div>
					</div>
					<?php } ?>
					<?php if($claim['response_photo']) { ?>
					<div class="form-group">
							<label class="control-label col-sm-3"><?=lang('column_upload_support_photo')?></label>
							<div class="col-sm-9">
								<div class="thumbnail">
								<a href="<?=base_url('storage/images/'.$claim['response_photo'])?>">
									<img src="<?=base_url('storage/images/'.$claim['response_photo'])?>" alt="Photo" style="width:15%">
								</a>
								</div>
							</div>
					</div>
					<?php } ?>
					<?php if($claim['transfer_photo']) { ?>
					<div class="form-group">
							<label class="control-label col-sm-3"><?=lang('column_transfer_photo')?></label>
							<div class="col-sm-9">
								<div class="thumbnail">
								<a href="<?=base_url('storage/images/'.$claim['transfer_photo'])?>">
									<img src="<?=base_url('storage/images/'.$claim['transfer_photo'])?>" alt="Photo" style="width:15%">
								</a>
								</div>
							</div>
					</div>
					<?php } ?>
					<?php if($claim['update_date']) { ?>
					<div class="form-group">
						<label class="control-label col-sm-3"><?=lang('column_last_update_date')?></label>
						<div class="col-sm-9">
							<input type="text" name="reason" value="<?=date('d-m-Y H:i:s',strtotime($claim['update_date']))?>" class="form-control" maxLength="100" readonly/>
						</div>
					</div>
					<?php } ?>
					<?php if($claim['update_by']) { ?>
					<div class="form-group">
						<label class="control-label col-sm-3"><?=lang('column_last_update_by')?></label>
						<div class="col-sm-9">
							<input type="text" name="reason" value="<?=$claim['update_by']?>" class="form-control" maxLength="100" readonly/>
						</div>
					</div>
					<?php } ?>
				</div>
				<div class="tab-pane" id="tab-2">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Status</th>
								<th>Diproses Oleh</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($histories as $history) { ?>
							<tr>
								<td><?=date('d-m-Y H:i:s',strtotime($history['process_date']))?></td>
								<td><?=$history['status']?></td>
								<td><?=$history['process_by']?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> <?=lang('button_cancel')?></button>
		</div>
	</div>
</div>