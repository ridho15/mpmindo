<div class="page-title">
	<div>
		<h1><?=lang('heading_title')?></h1>
		<p><?=lang('heading_subtitle')?></p>
	</div>
</div>
<section class="content">
	<div class="card">
		<div class="card-body">
			<div id="message"></div>
			<div class="row">
				<div class="col-md-12">
		        	<table class="table table-hover" width="100%" id="datatable">
						<thead>
							<tr>
								<th><?=lang('label_name')?></th>
								<th><?=lang('label_description')?></th>
								<th><?=lang('label_version')?></th>
								<th><?=lang('label_status')?></th>
								<th style="width:20%;"></th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

<div id="modal"></div>

<script type="text/javascript">
	$(document).ready(function() {
	    var table = $('#datatable').DataTable({
	    	"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('payment_modules')?>",
				"type": "POST",
			},
			"columns": [
				{"data": "name"},
				{"data": "description"},
				{"data": "version"},
				{"data": "active"},
				{"orderable": false, "searchable" : false, "data": "code"},
			],
			"createdRow": function (row, data, index) {
				if (data.active === '1') {
					$('td', row).eq(3).html('<span class="label label-success"><?=lang('text_enabled')?></span>');
					$('td', row).eq(4).html('<div class="btn-group-xs"><a href="<?=admin_url('payment_modules/setting/\'+data.code+\'')?>" class="btn btn-default btn-xs"><i class="fa fa-cog"></i> <?=lang('button_edit')?></a> <a onclick="uninstall(\''+data.code+'\');" class="btn btn-primary btn-xs"><i class="fa fa-minus-circle"></i> <?=lang('button_uninstall')?></a></div>').addClass('text-right');
					
				} else {
					$('td', row).eq(4).html('<div class="btn-group-xs"><a onclick="install(\''+data.code+'\');" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i> <?=lang('button_install')?></a></div>').addClass('text-right');
					$('td', row).eq(3).html('<span class="label label-default"><?=lang('text_disabled')?></span>');
				}
			},
			"order": [[0, 'asc']]
		});
	});
	
	function install(code) {
		$.ajax({
			url : "<?=admin_url('payment_modules/install')?>",
			type : 'post',
			data: 'code='+code,
			dataType: 'json',
			success: function(json) {
				if (json['success']) {
					$('#message').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> '+json['success']+'</div>');
				} else if (json['error']) {
					$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
				} else if (json['redirect']) {
					window.location = json['redirect'];
				}
				
				refreshTable();
			}
		});
	}
	
	function uninstall(code) {
		if (confirm('Anda yakin ingin uninstall?')) {
			$.ajax({
				url : "<?=admin_url('payment_modules/uninstall')?>",
				type : 'post',
				data: 'code='+code,
				dataType: 'json',
				success: function(json) {
					if (json['success']) {
						$('#message').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> '+json['success']+'</div>');
					} else if (json['error']) {
						$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
					} else if (json['redirect']) {
						window.location = json['redirect'];
					}
					
					refreshTable();
				}
			});
		}
	}
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
	
	function getForm(code, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'code='+code,
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
</script>