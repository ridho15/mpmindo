<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title"><?=$heading_title?></h4>
		</div>
		<div class="modal-body">
			<?=form_open($action, 'id="store-group-form" role="form" class="form-horizontal"')?>
				<input type="hidden" name="store_group_id" value="<?=$store_group_id?>">
				<div class="form-group">
					<label class="control-label col-sm-3">Nama</label>
					<div class=" col-sm-9">
						<input type="text" class="form-control" name="name" value="<?=$name?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3">Admin Fee</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input type="text" name="admin_fee" value="<?=$admin_fee?>" class="form-control">
							<span class="input-group-addon">%</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3">Biaya Bulanan</label>
					<div class="col-sm-4">
						<div class="input-group">
							<span class="input-group-addon">IDR</span>
							<input type="text" name="monthly_fee" value="<?=$monthly_fee?>" class="form-control">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3">Level<br><span class="help">Isikan level dalam bentuk angka, misal 1, 2, 3 dan seterusnya</span></label>
					<div class="col-sm-2">
						<input type="text" name="level" value="<?=$level?>" class="form-control">
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> Batal</button>
			<button type="button" class="btn btn-success" id="store_group-account-submit"><i class="fa fa-check"></i> Simpan</button>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#store_group-account-submit').bind('click', function() {
		$.ajax({
			url: $('#store-group-form').attr('action'),
			data: $('#store-group-form').serialize(),
			type: 'post',
			dataType: 'json',
			success: function(json) {
				$('.form-group').removeClass('has-error');
				$('.error').remove();
				if (json['error']) {
					for (i in json['error']) {
						$('input[name=\''+i+'\']').closest('.form-group').addClass('has-error');
						$('input[name=\''+i+'\']').after('<span class="error text-danger">' + json['error'][i] + '</span>');	
					}
				} else if (json['success']){
					$('#form-modal').modal('hide');
					parent.$('#message').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> '+json['success']+'</div>');
				}
			}
		});
	});
</script>