<?=form_open($action, 'class="form-horizontal"')?>
	<div class="form-group">
		<label class="col-sm-3 control-label">Nama</label>
		<div class="col-sm-9">
			<input type="text" name="name" value="<?=$name?>" class="form-control" placeholder="Ketikkan nama atau ID" autocomplete="off">
			<input type="hidden" name="user_id" value="<?=$user_id?>" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Email</label>
		<div class="col-sm-9">
			<input type="text" name="email" value="<?=$email?>" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">No. Telepon</label>
		<div class="col-sm-9">
			<input type="text" name="telephone" value="<?=$telephone?>" class="form-control">
		</div>
	</div><hr>
	<div class="form-group">
		<div class="col-sm-12">
			<button class="btn btn-primary pull-right">Lanjut <i class="fa fa-chevron-right"></i></button>
		</div>
	</div>
</form>
<script src="<?=site_url('assets/js/plugins/bootstrap-typehead/bootstrap3-typeahead.js')?>"></script>
<script type="text/javascript">
	$('input[name=\'name\']').typeahead({
		source: function(query, process) {
			$.ajax({
				url: "<?=admin_url('users/autocomplete')?>",
				data: 'name=' + query,
				type: 'get',
				dataType: 'json',
				success: function(json) {
					users = [];
					map = {};
					$.each(json, function(i, user) {
						map[user.name] = user;
						users.push(user.name);
					});
					process(users);
				}
			});
			
			$('input[name=\'user_id\']').val('');
		},
		updater: function (item) {
			$('input[name=\'user_id\']').val(map[item].user_id);
			$('input[name=\'name\']').val(map[item].name);
			$('input[name=\'email\']').val(map[item].email);
			$('input[name=\'telephone\']').val(map[item].telephone);
			
			$.ajax({
				url : "<?=admin_url('users/get_addresses')?>",
				data: 'user_id='+map[item].user_id,
				dataType: 'json',
				beforeSend: function() {
					$('.card').append('<div class="overlay" style="z-index:999;"><div class="m-loader mr-20"><svg class="m-circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"/></svg></div><h3 class="l-text">Loading</h3></div>');
				},
				complete: function() {
					$('.overlay').remove();
				},
				success: function(json) {
					if (json.length > 0) {
						html = '';
						html = '<option value="0">Baru</option>';
						for (i in json) {
							html += '<option value="'+json[i]['address_id']+'">'+json[i]['address']+', '+json[i]['city']+' - '+json[i]['province']+'</option>'; 
						}
						
						$('select[name=\'user_address_id\']').html(html);
					}
				}
			});
			
			return map[item].name;
		},
		minLength: 1
	});
</script>