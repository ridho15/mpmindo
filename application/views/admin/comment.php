<div class="page-title">
	<div>
		<h1><?=lang('heading_title')?></h1>
		<p><?=lang('heading_subtitle')?></p>
	</div>
</div>
<section class="content">
	<div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-hover" id="datatable" width="100%">
					<thead>
						<tr>
							<th><?=lang('column_rating_date')?></th>
							<th><?=lang('column_product_name')?></th>
							<th><?=lang('column_user')?></th>
							<th><?=lang('column_rating')?></th>
							<th><?=lang('column_comment')?></th>
							<th><?=lang('column_response_date')?></th>
							<th><?=lang('column_response')?></th>
							<th class="text-right"></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#datatable').DataTable({
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=admin_url('comments')?>",
				"type": "POST",
			},
			"columns": [
				{"data": "rating_date"},
				{"data": "name"},
				{"data": "user_name"},
				{"data": "value"},
				{"data": "notes"},
				{"data": "response_date"},
				{"data": "response"},
				{"orderable": false, "searchable" : false, "data": "id"},
			],
			"sDom":'lfr<"table-responsive" t>ip',
			"createdRow": function (row, data, index) {

				if(data.response_date == '' || data.response_date == '01/01/1970') {
					$('td', row).eq(5).html('');
				}

				html  = '<div class="btn-group-xs">';
				html += '<a onclick="getForm('+data.id+', \'/comments/response\');" class="btn btn-default btn-xs"><span class="fa fa-edit"></span> <?=lang('button_edit')?></a> ';
				html += '</div>';
				
				$('td', row).eq(7).html(html).addClass('text-right');
			},
			"order": [[0, 'desc']]
		});
		
	});
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
	
	function getForm(id, path) {
		$.ajax({
			url : $('base').attr('href')+path,
			data: 'id='+id,
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					$.notify({
						title: '<strong><?=lang('text_error')?></strong><br>',
						message: json['error'],
						icon: 'fa fa-info' 
					},{
						type: "danger"
					});
				} else if (json['content']) {
					$('body').append('<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">'+json['content']+'</div>');
					$('#form-modal').modal('show');
					$('#form-modal').on('hidden.bs.modal', function (e) {
						$('#form-modal').remove();
						refreshTable();
					});	
				}
			}
		});
	}
</script>