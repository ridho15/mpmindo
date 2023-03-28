<div class="page-title">
	<div>
		<h1><?=$name?></h1>
	</div>
	<div>
		<?php if ($user_id == $this->user->user_id()) {?>
		<button onclick="window.location = '<?=user_url('profile/edit')?>'" class="btn btn-success"><i class="fa fa-lg fa-cog"></i> Edit Profile</button>
		<?php } else {?>
		<button rel="<?=$user_id?>" class="btn <?php if ($bookmarked) {echo 'btn-success';}else{echo 'btn-default';}?> bookmark"><i class="fa fa-lg fa-bookmark"></i> Bookmark</button>
		<?php if ($type == 'exhibitor' && $meeting_request) {?>
		<button rel="<?=$user_id?>" class="btn btn-xs btn-warning request-meeting"><i class="fa fa-paper-plane"></i> Request Meeting</button>
		<?php }?>
		<?php }?>
	</div>
</div>
<div class="row user">
	<div class="col-md-12">
		<div class="profile">
			<div class="info"><img src="<?=$image?>" class="user-img">
				<h4><?=$name?></h4>
				<p><?=$company?></p>
			</div>
		</div>
	</div>
	<?php if ($type == 'exhibitor') {?>
	<div class="col-md-3">
		<div class="card p-0">
			<ul class="nav nav-tabs nav-stacked user-tabs">
				<li class="active"><a href="#user-timeline" data-toggle="tab">General Information</a></li>
				<li><a href="#user-events" data-toggle="tab">Our Events</a></li>
				<?php if ($feature_product) {?>
				<li><a href="#user-products" data-toggle="tab">Our Products</a></li>
				<?php }?>
				<?php if ($feature_meeting) {?>
				<li><a href="#user-reviews" data-toggle="tab">Reviews</a></li>
				<?php }?>
			</ul>
		</div>
	</div>
	<div class="col-md-9">
		<div class="tab-content">
			<div id="user-timeline" class="tab-pane active">
				<div class="timeline">
					<div class="card">
					<dl>
						<dt>Full Name</dt>
						<dd><?=$name?></dd>
						<hr>
						<dt>Gender</dt>
						<dd><?=$gender?></dd>
						<hr>
						<dt>State</dt>
						<dd><?=$state?></dd>
						<hr>
						<dt>Country</dt>
						<dd><?=$country?></dd>
						<hr>
						<dt>Company Name</dt>
						<dd><?=$company?></dd>
						<hr>
						<dt>Company Profile</dt>
						<dd><?=$about_us?></dd>
						<hr>
						<dt>Product Brand</dt>
						<dd><?=$brand?></dd>
					</dl>
					</div>
				</div>
			</div>
			<div id="user-events" class="tab-pane fade">
				<div class="card user-events">
				<h4 class="line-head">Our Events</h4>
				<div id="event-list"></div>
				</div>
			</div>
			<?php if ($feature_product) {?>
			<div id="user-products" class="tab-pane fade">
				<div class="card user-products">
					<h4 class="line-head">Our Products</h4>
					<div id="product-list"></div>
				</div>
			</div>
			<?php }?>
			<?php if ($feature_meeting) {?>
			<div id="user-reviews" class="tab-pane fade">
			<div class="card user-reviews">
				<table class="table" id="datatable" width="100%">
					<thead>
						<tr>
							<th>Our Reviews</th>
						</tr>
					</thead>
				</table>
			</div>
			</div>
			<?php }?>
		</div>
	</div>
	<?php } else {?>
	<div class="col-md-12">
		<div class="tab-content">
		<div class="card">
			<div class="card-body">
			<dl>
				<dt>Full Name</dt>
				<dd><?=$name?></dd>
				<hr>
				<dt>Gender</dt>
				<dd><?=$gender?></dd>
				<hr>
				<dt>State</dt>
				<dd><?=$state?></dd>
				<hr>
				<dt>Country</dt>
				<dd><?=$country?></dd>
				<hr>
				<dt>About Me</dt>
				<dd><?=$about_us?></dd>
			</dl>
			</div>
		</div>
		</div>
	</div>
	<?php }?>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('.bookmark').on('click', function() {
			var btn = $(this);
			$.ajax({
				url : "<?=user_url('bookmark/add_bookmark')?>",
				type : 'post',
				data: 'user_id='+btn.attr('rel'),
				dataType: 'json',
				beforeSend: function() {
					btn.button('loading');
				},
				complete: function() {
					btn.button('reset');
				},
				success: function(json) {
					if (json['bookmarked'] == true) {
						btn.removeClass('btn-default');
						btn.addClass('btn-success');
					} else {
						btn.removeClass('btn-success');
						btn.addClass('btn-default');
					}
				}
			});
		});
	});
</script>
<?php if ($type == 'exhibitor') {?>
<script type="text/javascript">
	$(document).ready(function() {
		getEvents("<?=user_url('event/get_list')?>");
		<?php if ($feature_product) {?>
		getProducts("<?=user_url('product/get_products')?>");
		<?php }?>
		$('.overlay').hide();
		
		var table = $('#datatable').DataTable({
	    	"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?=user_url('meeting/get_reviews/'.$user_id)?>",
				"type": "POST",
			},
			"columns": [
				{"data": "visitor_review"},
			],
			"sDom":'<t>p',
			"createdRow": function (row, data, index) {
				html   = '<div class="media">';
				html  += '	<a class="pull-left">'+data.image+'</a>';
				html  += '	<div class="media-body"><h4 class="media-heading">'+data.name+'</h4>'+data.visitor_review+'</div>';
				html  += '</div>';
				
				$('td', row).eq(0).html(html);
			},
			"order": [[0, 'desc']]
		});
	});
	
	function getEvents(url) {
		$.ajax({
			url : url,
			data: 'user_id=<?=$user_id?>',
			type : 'get',
			dataType: 'html',
			beforeSend: function() {
				$('#event-list').append('<div class="overlay" style="z-index:999;"><div class="m-loader mr-20"><svg class="m-circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"/></svg></div><h3 class="l-text">Loading</h3></div>');
			},
			complete: function() {
				$('.overlay').remove();
			},
			success: function(html) {
				$('#event-list').html(html);
			}
		});
	}
	
	function getProducts(url) {
		$.ajax({
			url : url,
			data: 'user_id=<?=$user_id?>',
			type : 'get',
			dataType: 'html',
			beforeSend: function() {
				$('#product-list').append('<div class="overlay" style="z-index:999;"><div class="m-loader mr-20"><svg class="m-circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" stroke-miterlimit="10"/></svg></div><h3 class="l-text">Loading</h3></div>');
			},
			complete: function()  {
				$('.overlay').remove();
			},
			success: function(html) {
				$('#product-list').html(html);
			}
		});
	}
	
	$(document).delegate('#event-list .pagination li a', 'click', function(e) {
		e.preventDefault();
		var href = $(this).attr('href');
		if (typeof(href) !== 'undefined') {
			getEvents(href);
		}
	});
	
	$(document).delegate('#product-list .pagination li a', 'click', function(e) {
		e.preventDefault();
		var href = $(this).attr('href');
		if (typeof(href) !== 'undefined') {
			getProducts(href);
		}
	});
	
	$('.request-meeting').on('click', function() {
		var btn = $(this);
		$.ajax({
			url : '<?=user_url('meeting/create')?>',
			type : 'get',
			data: 'exhibitor_id='+btn.attr('rel'),
			dataType: 'json',
			beforeSend: function() {
				btn.button('loading');
			},
			complete: function() {
				btn.button('reset');
			},
			success: function(json) {
				if (json['error']) {
					$('#message').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '+json['error']+'</div>');
				} else if (json['content']) {
					$('#modal').html('<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">'+json['content']+'</div>');
					$('#form-modal').modal('show');
				}
			}
		});
	});
</script>
<?php }?>