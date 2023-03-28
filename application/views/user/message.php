<div class="page-title">
	<div>
		<h1>Message</h1>
		<p>Manage messages</p>
	</div>
	<div></div>
</div>
<div id="message"></div>
<div class="row">
	<div class="col-md-3">
		<a onclick="getForm(null, '/message/compose');" class="mb-20 btn btn-success btn-block"><i class="fa fa-lg fa-plus"></i> Compose Message</a>
		<div class="card p-0">
			<h4 class="card-title folder-head">Messages</h4>
			<div class="card-body">
				<ul class="nav nav-pills nav-stacked mail-nav">
					<li class="active"><a href="#tab-1" data-toggle="tab"><i class="fa fa-inbox fa-fw"></i> Inbox</a></li>
					<li><a href="#tab-2" data-toggle="tab"><i class="fa fa-envelope-o fa-fw"></i> Sent</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-9">
		<form id="delete-message">
		<div class="tab-content">
			<div class="tab-pane active" id="tab-1">
				<div class="card">
					<div class="card-title-w-btn">
						<div class="btn-group">
							<a onclick="deletes('inbox');" class="btn btn-warning"><i class="fa fa-lg fa-trash"></i> Delete Selected</a>
						</div>
					</div>
					<table class="table table-hover" width="100%" id="datatable1">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all-inbox" value="1"/></th>
								<th>From</th>
								<th>Subject</th>
								<th>Date</th>
								<th>Status</th>
								<th class="text-right"></th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
			<div class="tab-pane" id="tab-2">
				<div class="card">
					<div class="card-title-w-btn">
						<div class="btn-group">
							<a onclick="deletes('outbox');" class="btn btn-warning"><i class="fa fa-lg fa-trash"></i> Delete Selected</a>
						</div>
					</div>
					<table class="table table-hover" width="100%" id="datatable2">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all-outbox" value="1"/></th>
								<th>Sent To</th>
								<th>Subject</th>
								<th>Date</th>
								<th>Status</th>
								<th class="text-right"></th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
		<form>
	</div>
</div>
<div id="modal"></div>
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#datatable1').DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=user_url('message/get_inbox')?>",
				"type": "POST",
			},
			"columns": [
				{"data": "message_id", "orderable": false, "searchable": false},
				{"data": "sender"},
				{"data": "subject"},
				{"data": "date_added"},
				{"data": "read"},
				{"data": "message_id", "orderable": false, "searchable": false},
			],
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html('<input type="checkbox" class="check-inbox" name="inbox_id[]" value="'+data.message_id+'"/>');
				
				if (data.read == '1') {
					$('td', row).eq(4).html('<span class="label label-success">R</span>');
				} else {
					$('td', row).eq(4).html('<span class="label label-default">D</span>');
				}
				
				$('td', row).eq(5).html('<div class="btn-group btn-group-xs"><a href="<?=user_url('message/read/\'+data.message_id+\'')?>" class="btn btn-default">Read</a></div>').addClass('text-right');
			},
			"order": [[3, 'desc']],
		});
			
		var table2 = $('#datatable2').DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=user_url('message/get_outbox')?>",
				"type": "POST",
			},
			"columns": [
				{"data": "message_id", "orderable": false, "searchable": false},
				{"data": "recipient"},
				{"data": "subject"},
				{"data": "date_added"},
				{"data": "read"},
				{"data": "message_id", "orderable": false, "searchable": false},
			],
			"createdRow": function (row, data, index) {
				$('td', row).eq(0).html('<input type="checkbox" class="check-outbox" name="outbox_id[]" value="'+data.message_id+'"/>');
				
				if (data.read == '1') {
					$('td', row).eq(4).html('<span class="label label-success">R</span>');
				} else {
					$('td', row).eq(4).html('<span class="label label-default">D</span>');
				}
				
				$('td', row).eq(5).html('<div class="btn-group btn-group-xs"><a href="<?=user_url('message/read/\'+data.message_id+\'')?>" class="btn btn-default">Read</a></div>').addClass('text-right');
			},
			"order": [[3, 'desc']],
		});
	});
	
	function getForm(message_id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'message_id='+message_id,
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
				} else if (json['content']) {
					$('#modal').html('<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">'+json['content']+'</div>');
					$('#form-modal').modal('show');
					$('#form-modal').on('hidden.bs.modal', function (e) {
						$('a[href=\'#tab-2\']').trigger('click');
					});
				}
			}
		});
	}
	
	$('a[href=\'#tab-1\']').on('click', function() {
		$('#datatable1').DataTable().ajax.reload();
	});
	
	$('a[href=\'#tab-2\']').on('click', function() {
		$('#datatable2').DataTable().ajax.reload();
	});
	
	$('#select-all-inbox').click(function() {
		var checkBoxes = $('.check-inbox');
		checkBoxes.prop('checked', !checkBoxes.prop('checked'));
	});
	
	$('#select-all-outbox').click(function() {
		var checkBoxes = $('.check-outbox');
		checkBoxes.prop('checked', !checkBoxes.prop('checked'));
	});
	
	function deletes(messageType) {
		swal({
			title: "Are you sure?",
			text: "You will not be able to recover this record!",
			type: "warning",
			showCancelButton: true,
			confirmButtonText: "Yes",
			cancelButtonText: "No",
			closeOnConfirm: false,
			closeOnCancel: true
		}, function(isConfirm) {
			if (isConfirm) {
				$.ajax({
					url : '<?=user_url('message/delete')?>',
					type : 'post',
					data: $('#delete-message').serialize()+'&type='+messageType,
					dataType: 'json',
					success: function(json) {
						if (json['success']) {
							swal("Deleted!", json['success'], "success");
						} else if (json['error']) {
							swal("Error!", json['error'], "error");
						} else if (json['redirect']) {
							window.location = json['redirect'];
						}
						
						if (messageType == 'inbox') {
							$('a[href=\'#tab-1\']').trigger('click');
						} else {
							$('a[href=\'#tab-2\']').trigger('click');
						}
					}
				});
			}
		});
	}
</script>