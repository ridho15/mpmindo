<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="card-title-w-btn">
					<h3 class="title">Ulasan</h3>
				</div>
				<div id="message"></div>
				<table class="table" width="100%" id="datatable">
					<thead class="hide">
						<tr>
							<th></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
	    var table = $('#datatable').DataTable({
	    	"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=user_url('review')?>",
				"type": "POST",
			},
			"columns": [
				{"orderable": false, "searchable" : false, "data": "review_id"},
			],
			"sDom":'<"table-responsive" t>p',
			"createdRow": function (row, data, index) {
				html   = '';
				html  += '<div class="media">';
				html  += '	<a class="pull-left"><img class="media-object" src="'+data.image_link+'"></a>';
				html  += '	<div class="media-body">';
				html  += '		<div class="row">';
				html  += '			<div class="col-md-6 col-xs-12">';
				html  += '				<div class="media-heading">';
				html  += '					Pada produk <a href="'+data.product_link+'">'+data.product+'</a><br>di toko <a href="<?=site_url('store/\'+data.store_domain+\'')?>">'+data.store+'</a><br><small class="text-muted">'+data.date_added+'</small>';
				html  += '				</div>';
				html  += data.text;
				html  += '			</div>';
				html  += '			<div class="col-md-6 col-xs-12">';
				html  += '				<dl class="dl-horizontal">';
				html  += '					<dt>Kualitas</dt>';
				html  += '					<dd>';
										for (i=1;i<=5;i++) {
										if (i<=parseInt(data.quality)) {
				html  += '				<i class="fa fa-star" style="color:gold;"></i>';
										} else {
				html  += '				<i class="fa fa-star" style="color:gray;"></i>';
										}}
				html  += '					</dd>';
				html  += '					<dt>Akurasi</dt>';
				html  += '					<dd>';
										for (i=1;i<=5;i++) {
										if (i<=parseInt(data.accuracy)) {
				html  += '				<i class="fa fa-star" style="color:gold;"></i>';
										} else {
				html  += '				<i class="fa fa-star" style="color:gray;"></i>';
										}}
				html  += '					</dd>';
				html  += '				</dl>';
				html  += '			</div>';
				html  += '		</div>';
				html  += '	</div>';
				html  += '</div>';
				
				$('td', row).eq(0).html(html);
			},
			"order": [[0, 'desc']]
		});
	});
	
	function refreshTable() {
		$('#datatable').DataTable().ajax.reload();
	}
</script>