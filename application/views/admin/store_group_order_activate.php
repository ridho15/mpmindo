<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Aktivasi Membership</h4>
		</div>
		<div class="modal-body">
			<?=form_open($action, 'id="form" role="form" class="form-horizontal"')?>
				<input type="hidden" name="store_group_order_id" value="<?=$store_group_order_id?>">
				<div class="form-group">
					<label class="control-label col-sm-6">Tanggal Start<br><span class="help">Dapat diisi tanggal pembayaran</span></label>
					<div class=" col-sm-6">
						<input type="text" name="date_start" class="form-control" readonly="readonly" id="datepicker" data-date-format="dd-mm-yyyy" value="<?=$date_start?>">
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban"></i> Batal</button>
			<button type="button" class="btn btn-success" id="submit"><i class="fa fa-check"></i> Simpan</button>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#datepicker').datepicker({language:'id', autoclose:true});
	$('#submit').bind('click', function() {
		$.ajax({
			url: $('#form').attr('action'),
			data: $('#form').serialize(),
			type: 'post',
			dataType: 'json',
			success: function(json) {
				$('.form-group').removeClass('has-error');
				$('.text-danger').remove();
				if (json['error']) {
					for (i in json['error']) {
						$('input[name=\''+i+'\']').parent().addClass('has-error');
						$('input[name=\''+i+'\']').after('<span class="text-danger">' + json['error'][i] + '</span>');	
					}
				} else if (json['success']){
					$('#form-modal').modal('hide');
					parent.$('#message').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> '+json['success']+'</div>');
				}
			}
		});
	});
</script>