<div class="page-title">
	<div>
		<h1><?=$template['title']?></h1>
		<p>Kelola daftar pelanggan</p>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div id="message"></div>
				<div id="notification">
				<?php if($this->session->flashdata('success')) {?>
					<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> <?php echo $this->session->flashdata('success');?></div>
				<?php }?>
				</div>
				<div class="form-action">
				<?=form_open(admin_url('users/excel'), 'id="excel-action"')?>
					<div class="row">
						<div class="col-md-4">
							<a onclick="exportExcel();" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export Excel</a>
							<a href="<?=admin_url('users/create')?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?=lang('button_insert')?></a>
							<a onclick="delRecord();" class="btn btn-default"><i class="fa fa-check-square-o"></i> <?=lang('button_remove')?></a>
						</div>
						<div class="col-md-2">
							<select name="job_status" class="form-control">
								<option value="">-- Semua Status Kerja --</option>
								<option value="Barber">Barber</option>
								<option value="Barber Owner">Barber Owner</option>
								<option value="Lainnya">Lainnya</option>
							</select>
						</div>
						<div class="col-md-2">
							<select name="province" class="form-control">
								<option value="">-- Semua Propinsi --</option>
							<?php foreach ($provinces as $province) {?>
								<option value="<?=$province['name']?>"><?=$province['name']?></option>
							<?php }?>
							</select>
						</div>
						<div class="col-md-2">
							<select name="birthday" class="form-control">
								<option value="">-- Semua Ulang Tahun --</option>
								<option value="1">Bulan Ini</option>
								<option value="2">Bulan Depan</option>
							</select>
						</div>
						<div class="col-md-2">
							<select name="active" class="form-control">
								<option value="">-- Semua Status --</option>
								<option value="1">Aktif</option>
								<option value="2">Tidak Aktif</option>
							</select>
						</div>
					</div>
				</form>
				</div>
				<div class="table-responsive">
					<?=form_open(admin_url('users/delete'), 'id="bulk-action"')?>
					<table class="table table-hover" width="100%" id="datatable">
						<thead>
							<tr>
								<th style="width: 20px"><input type="checkbox" id="select-all"></th>
								<th>Nama</th>
								<th>Email</th>
								<th>No. Telepon / Handphone</th>
								<th>Tanggal Daftar</th>
								<th>Tempat Kerja</th>
								<th>Status Kerja</th>
								<th>Alamat</th>
								<th>Ulang Tahun</th>
								<th>Aktif</th>
								<th class="text-right"></th>
							</tr>
						</thead>
					</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="modal"></div>
<script type="text/javascript">
	$(document).ready(function() {
	$('select[name=\'job_status\']').on('change', function() {
		refreshTable('#datatable');
	});

	$('select[name=\'birthday\']').on('change', function() {
		refreshTable('#datatable');
	});

	$('select[name=\'active\']').on('change', function() {
		refreshTable('#datatable');
	});

	$('select[name=\'province\']').on('change', function() {
		refreshTable('#datatable');
	});
	
	var table = $('#datatable').DataTable({
		"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('users')?>",
				"type": "POST",
				"data": function(d) {
					d.job_status = $('select[name=\'job_status\'] option:selected').val();
					d.birthday = $('select[name=\'birthday\'] option:selected').val();
					d.active = $('select[name=\'active\'] option:selected').val();
					d.province = $('select[name=\'province\'] option:selected').val();
				}
			},
			"columns": [
				{"orderable": false, "searchable" : false, "data": "user_id"},
				{"data": "name"},
				{"data": "email"},
				{"data": "telephone"},
				{"data": "date_added"},
				{"data": "job"},
				{"data": "job_status"},
				{"data": "address"},
				{"data": "dob"},
				{"data": "active"},
				{"orderable": false, "searchable" : false, "data": "user_id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html('<input type="checkbox" class="checkbox" name="user_id[]" value="'+data.user_id+'"/>');
				
				html = '<div class="btn-group-xs">';
				html += '<a href="<?=admin_url('users/edit/\'+data.user_id+\'')?>" class="btn btn-default btn-xs"><i class="fa fa-pencil"></i> Edit</a> ';
				html += '</div>';
				
				$('td', row).eq(10).html(html).addClass('text-right');
				
				if (data.active === '1') {
					$('td', row).eq(9).html('<span class="label label-success">Aktif</span>');
				} else {
					$('td', row).eq(9).html('<span class="label label-default">Tidak Aktif</span>');
				}
			},
			"order": [[1, 'asc']]
		});
		
		$('#select-all').click(function() {
			var checkBoxes = $('.checkbox');
			checkBoxes.prop('checked', !checkBoxes.prop('checked'));
		});
	});
	
	function delRecord() {
		swal({
			title: "<?=lang('text_confirm')?>",
			text: "<?=lang('text_delete_confirm')?>",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "<?=lang('text_yes')?>",
			cancelButtonText: "<?=lang('text_no')?>",
			closeOnConfirm: false,
			closeOnCancel: true
		}, function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					url : '<?=admin_url('users/delete')?>',
					type : 'post',
					data: $('#bulk-action').serialize(),
					dataType: 'json',
					success: function(json) {
						if (json['success']) {
							swal("<?=lang('text_success')?>", json['success'], 'success');
						} else if (json['error']) {
							swal("<?=lang('text_error')?>", json['error'], 'error');
						} else if (json['redirect']) {
							window.location = json['redirect'];
						}
						
						refreshTable();
					}
				});
			}
		});
	}
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
	
	function getForm(user_id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'user_id='+user_id,
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
				} else if (json['content']) {
					$('#modal').html('<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">'+json['content']+'</div>');
					$('#form-modal').modal('show');
					$('#form-modal').on('hidden.bs.modal', function (e) {
						refreshTable();
					});	
				}
			}
		});
	}

	function exportExcel() {
		$('#excel-action').submit();
	}
</script>