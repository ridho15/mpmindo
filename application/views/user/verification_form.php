<div class="modal-dialog">
	<div class="modal-content">
		<?=form_open($action, 'id="form-request-code"')?>
		<div class="modal-body">
			<h2>Penting!</h2>
			<p>Akun kamu belum terverikasi. Untuk keamanan dan kenyamanan dalam bertransaksi di Mpmindo.id, silakan verifikasikan akun kamu.</p>
			<div class="form-group">
				<input type="text" name="telephone" class="form-control" placeholder="No. Handphone. Contoh: 081234567890" value="<?=$telephone?>">
			</div>
			<div class="form-group">
				<button class="btn btn-warning" id="button-request-code">Request Code</button>
			</div>
		</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	$(document).delegate('#button-request-code', 'click', function(e) {
		e.preventDefault();
		var btn = $(this);
		$.ajax({
			url: $('#form-request-code').attr('action'),
			data: $('#form-request-code').serialize(),
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				btn.button('loading');
			},
			complete: function() {
				btn.button('reset');
			},
			success: function(json) {
				$('.alert, .error').remove();
				$('.form-group').removeClass('has-error');
				if (json['success']) {
					html  = '<div class="alert alert-warning">';
					html += '<div class="alert alert-success" id="notification">'+json['success']+'</div>';
					html += '<div class="input-group">';
					html += '	<input type="text" name="code" class="form-control" placeholder="Masukkan kode di sini...">';
					html += '	<span class="input-group-btn">';
					html += '		<button class="btn btn-primary" id="button-verify">Verifikasi</button>';
					html += '	</span>';
					html += '</div>';
					html += '</div>';
					
					$('.modal-body').append(html);
				} else if (json['errors']) {
					for (i in json['errors']) {
						$('input[name=\''+i+'\']').after('<small class="help-block error"><i>'+json['errors'][i]+'</i></small>');
						$('input[name=\''+i+'\']').parent().addClass('has-error');
					}
				}
			}
		});
	});
	
	$(document).delegate('#button-verify', 'click', function(e) {
		e.preventDefault();
		var btn = $(this);
		$.ajax({
			url: "<?=user_url('verification/confirm')?>",
			data: 'code='+$('#form-request-code input[name=\'code\']').val(),
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				btn.button('loading');
			},
			complete: function() {
				btn.button('reset');
			},
			success: function(json) {
				if (json['error']) {
					$('#notification').removeClass('alert-success');
					$('#notification').addClass('alert-danger');
					$('#notification').html(json['error']);
				} else {
					window.location = json['redirect'];
				}
			}
		});
	});
</script>