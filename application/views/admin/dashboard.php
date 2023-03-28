<style>
	.widget-small.default {
		background-color:#f5f5f5;
		box-shadow:none;
	}
	
	.widget-small.default .info {
		color:#333;
		position:relative;
	}
	
	.widget-small.default .info h4 {
		text-transform:none;
	}
</style>
<div class="page-title">
	<div>
		<h1>Dashboard</h1>
	</div>
</div>
<?php if($this->admin->is_distributor() == '1') {?>
	<div class="row" style="margin-top:20px;">
	<div class="col-xs-12">
		<div class="card">
			<h3 class="card-title">Welcome</h3>
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<div class="widget-small default"><i class="icon fa fa-tags fa-3x"></i>
							<div class="info">
								<h4><small>Jumlah Stok</small><br><?=$count_stock?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="widget-small default"><i class="icon fa fa-tags fa-3x"></i>
							<div class="info">
								<h4><small>Jumlah Mutasi Stok</small><br><?=$count_mutations?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="widget-small default"><i class="icon fa fa-edit fa-3x"></i>
							<div class="info">
								<h4><small>Jumlah Registrasi Penjualan Offline</small><br><?=$count_regs?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="widget-small default"><i class="icon fa fa-medkit fa-3x"></i>
							<div class="info">
								<h4><small>Jumlah Klaim Garansi</small><br><?=$count_claim?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="widget-small default"><i class="icon fa fa-medkit fa-3x"></i>
							<div class="info">
								<h4><small>Jumlah Klaim Garansi In Progress</small><br><?=$count_claim_in?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="widget-small default"><i class="icon fa fa-medkit fa-3x"></i>
							<div class="info">
								<h4><small>Jumlah Klaim Garansi Selesai / Ditolak</small><br><?=$count_claim_out?></h4>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } else { ?>
<div class="row" style="margin-top:20px;">
	<div class="col-xs-12">
		<div class="card">
			<h3 class="card-title">Overview</h3>
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<div class="widget-small default"><i class="icon fa fa-truck fa-3x"></i>
							<div class="info">
								<h4><small>Produk Terjual</small><br><?=$product_sold?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="widget-small default"><i class="icon fa fa-clock-o fa-3x"></i>
							<div class="info">
								<h4><small>Transaksi Pending</small><br><?=$count_pending_orders?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="widget-small default"><i class="icon fa fa-check fa-3x"></i>
							<div class="info">
								<h4><small>Transaksi Selesai</small><br><?=$count_paid_orders?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="widget-small default"><i class="icon fa fa-eye fa-3x"></i>
							<div class="info">
								<h4><small>Produk Dilihat</small><br><?=$product_viewed?></h4>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="widget-small default"><i class="icon fa fa-exchange fa-3x"></i>
							<div class="info">
								<h4><small>Conversion Rate</small><br><?=$conversion_rate?>%</h4>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="widget-small default"><i class="icon fa fa-comments fa-3x"></i>
							<div class="info">
								<h4><small>Durasi Chat (Average)</small><br><?=$chat_time?></h4>
							</div>
						</div>
					</div>
				</div>
				<div class="alert">
					<b>Catatan:</b><br>
					<small>
						<ul class="list-styled">
							<li>Conversion Rate hanya menghitung perbandingan rata-rata antara penjualan produk dengan jumlah view produk</li>
							<li>Durasi Chat hanya menghitung rata-rata durasi per sesi livechat dengan pelanggan</li>
						</ul>
					</small>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="card">
			<h3 class="card-title">Statistik Penjualan</h3>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-4">
						<div id="sales-pending" class="widget-small default"><i class="icon fa fa-database fa-3x"></i>
							<div class="info"></div>
						</div>
						<div id="sales-completed" class="widget-small default"><i class="icon fa fa-money fa-3x"></i>
							<div class="info"></div>
						</div>
						<!-- <div id="sales-tax"  class="widget-small default"><i class="icon fa fa-percent fa-3x"></i>
							<div class="info"></div>
						</div>
						<div id="sales-no-tax" class="widget-small default"><i class="icon fa fa-cube fa-3x"></i>
							<div class="info"></div>
						</div> -->
					</div>
					<div class="col-lg-8">
						<div class="row">
							<div class="col-md-6 form-horizontal">
								<div class="form-group">
									<label class="col-sm-4 control-label">Periode</label>
									<div class="col-sm-8">
										<select name="sales_range" class="form-control">
											<option value="week" selected="selected">Minggu Ini</option>
											<option value="month">Bulan Ini</option>
											<option value="year">Tahun Ini</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div id="area-legend"></div>
							</div>
						</div>
						<div class="chart" id="sale">
							<canvas id="lineChart" class="embed-responsive-item"></canvas>
						</div>
					</div>
				</div>
				<div class="alert">
					<b>Catatan:</b><br>
					<small>
						<ul class="list-styled">
							<li>Potensi Pendapatan dihitung berdasarkan jumlah seluruh pesanan yang tercatat pada periode ini</li>
							<li>Pendapatan Aktual dihitung berdasarkan jumlah seluruh pesanan yang <b>selesai</b> pada periode ini</li>
							<li>Dengan Pajak menghitung nilai penjualan (hanya item) yang dikenakan pajak pada periode ini</li>
							<li>Non Pajak menghitung nilai penjualan (hanya item) yang tidak dikenakan pajak pada periode ini</li>
						</ul>
					</small>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="card">
			<h3 class="card-title">Statistik Pelanggan</h3>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-4">
						<div id="customers-buyer" class="widget-small default"><i class="icon fa fa-users fa-3x"></i>
							<div class="info"></div>
						</div>
						<div id="customers-user" class="widget-small default"><i class="icon fa fa-address-card-o fa-3x"></i>
							<div class="info"></div>
						</div>
						<div id="customers-guest" class="widget-small default"><i class="icon fa fa-user fa-3x"></i>
							<div class="info"></div>
						</div>
					</div>
					<div class="col-lg-8">
						<div class="row">
							<div class="col-md-6 form-horizontal">
								<div class="form-group">
									<label class="col-sm-4 control-label">Berdasarkan</label>
									<div class="col-sm-8">
										<select name="customers_type" class="form-control">
											<option value="type" selected="selected">Tipe Pelanggan</option>
											<option value="gender">Jenis Kelamin</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-6 form-horizontal">
								<div class="form-group">
									<label class="col-sm-3 control-label">Periode</label>
									<div class="col-sm-9">
										<select name="customers_range" class="form-control">
											<option value="week" selected="selected">Minggu Ini</option>
											<option value="month">Bulan Ini</option>
											<option value="year">Tahun Ini</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-8">
								<div class="chart" id="customers">
									<canvas id="donutChart2" class="embed-responsive-item"></canvas>
								</div>
							</div>
							<div class="col-md-4">
								<div id="donut-legend2" style="margin-top:20px;"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="alert">
					<b>Catatan:</b><br>
					<small>
						<ul class="list-styled">
							<li>Total Pembeli dihitung per orang, bukan kuantitas transaksi</li>
							<li>Pelanggan Terdaftar adalah jumlah pelanggan yang mendaftar akun pada periode ini</li>
							<li>Pelanggan Tamu adalah jumlah pelanggan yang melakukan transaksi tapi tidak mendaftar akun pada periode ini</li>
						</ul>
					</small>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="card">
			<h3 class="card-title">Statistik Produk</h3>
			<div class="card-body">
				<div class="row">
					<div class="col-md-6">
						<legend><h4>Produk Terlaris <small>(10 Teratas)</small></h4></legend>
						<div class="row">
							<div class="col-md-6 form-horizontal">
								<div class="form-group">
									<label class="col-sm-3 control-label">Periode</label>
									<div class="col-sm-9">
										<select name="featured_range" class="form-control">
											<option value="week" selected="selected">Minggu Ini</option>
											<option value="month">Bulan Ini</option>
											<option value="year">Tahun Ini</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="chart" id="featured">
							<canvas id="donutChart" class="embed-responsive-item"></canvas>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div id="donut-legend" style="margin-top:20px;"></div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<legend><h4>Produk Dilihat <small>(10 Teratas)</small></h4></legend>
						<div class="row">
							<div class="col-md-6 form-horizontal">
								<div class="form-group">
									<label class="col-sm-3 control-label">Periode</label>
									<div class="col-sm-9">
										<select name="views_range" class="form-control">
											<option value="date" selected="selected">Hari Ini</option>
											<option value="month">Bulan Ini</option>
											<option value="year">Tahun Ini</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="chart" id="featured">
							<canvas id="barChart2" class="embed-responsive-item"></canvas>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div id="bar2-legend" style="margin-top:20px;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php }?>
<script type="text/javascript" src="<?=base_url('assets/js/plugins/chart.js')?>"></script>
<?php if($this->admin->is_distributor() == '0') {?>
<script type="text/javascript">
	$(document).ready(function(){
		getSalesChart();
		getFeaturedChart();
		getViewsChart();
		getCustomersChart();
	});
	
	$('select[name=\'sales_range\']').bind('change', function() {
		getSalesChart();
	});
	
	$('select[name=\'featured_range\']').bind('change', function() {
		getFeaturedChart();
	});
	
	$('select[name=\'views_range\']').bind('change', function() {
		getViewsChart();
	});
	
	$('select[name=\'customers_range\'], select[name=\'customers_type\']').bind('change', function() {
		getCustomersChart();
	});
	
	var salesData = {};
	var featuredData = {};
	var viewsData = {};
	var customersData = {};

	function getSalesChart() {
		if (typeof salesChart === 'object') {
			salesChart.destroy();
		}
		
		$.ajax({
			url: "<?=admin_url('dashboard/chart_sales')?>",
			data: 'range=' + $('select[name=\'sales_range\'] option:selected').val(),
			dataType: 'json',
			success: function(d) {
				salesData = {
					labels: d.labels,
					datasets: d.datasets,
				};
				
				salesChart = new Chart($("#lineChart").get(0).getContext("2d")).Line(salesData, {
					showScale: true,
					scaleShowGridLines: true,
					scaleGridLineColor: "rgba(0,0,0,.05)",
					scaleGridLineWidth: 1,
					scaleShowHorizontalLines: true,
					scaleShowVerticalLines: true,
					bezierCurve: true,
					bezierCurveTension: 0.3,
					pointDot: true,
					pointDotRadius: 4,
					pointDotStrokeWidth: 1,
					pointHitDetectionRadius: 20,
					datasetStroke: true,
					datasetStrokeWidth: 2,
					datasetFill: true,
					responsive: true,
					maintainAspectRatio: true,
					legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend list-unstyled\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"color:<%=datasets[i].fillColor%>\" class=\"fa fa-circle\"></span> <%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
				});
				
				$('#area-legend').html(salesChart.generateLegend());
				$('#sales-pending .info').html('<h4><small>Potensi Pendapatan</small><br>'+d.sales_pending.amount+'</h4><small class="text-muted">'+d.sales_pending.percentage+'% dari periode sebelumnya</small>');
				$('#sales-completed .info').html('<h4><small>Pendapatan Aktual</small><br>'+d.sales_completed.amount+'</h4><small class="text-muted">'+d.sales_completed.percentage+'% dari periode sebelumnya</small>');
				//$('#sales-tax .info').html('<h4><small>Dengan Pajak</small><br>'+d.sales_tax.amount+'</h4><small class="text-muted">'+d.sales_tax.percentage+'% dari periode sebelumnya</small>');
				//$('#sales-no-tax .info').html('<h4><small>Non Pajak</small><br>'+d.sales_no_tax.amount+'</h4><small class="text-muted">'+d.sales_no_tax.percentage+'% dari periode sebelumnya</small>');
			}
		});
	}
	
	function getCustomersChart() {
		if (typeof customersChart === 'object') {
			customersChart.destroy();
		}
		
		$.ajax({
			url: "<?=admin_url('dashboard/chart_customers')?>",
			data: 'range=' + $('select[name=\'customers_range\'] option:selected').val() + '&type=' + $('select[name=\'customers_type\'] option:selected').val(),
			dataType: 'json',
			success: function(d) {
				customersData = d.datasets;
				
				customersChart = new Chart($("#donutChart2").get(0).getContext("2d")).Pie(customersData, {
					segmentShowStroke: true,
					segmentStrokeColor: "#fff",
					segmentStrokeWidth: 2,
					percentageInnerCutout: 30,
					animationSteps: 100,
					animationEasing: "easeOutBounce",
					animateRotate: true,
					animateScale: false,
					responsive: true,
					maintainAspectRatio: true,
					tooltipTemplate: "<%=label%>: <%=value%>%",
					legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend list-unstyled\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"color:<%=segments[i].fillColor%>\" class=\"fa fa-circle\"></span> <%if(segments[i].label){%><%=segments[i].label%><%}%> (<%=segments[i].value%>%)</li><%}%></ul>"
				});
				
				$('#donut-legend2').html(customersChart.generateLegend());
				$('#customers-buyer .info').html('<h4><small>Total Pembeli</small><br>'+d.customers_buyer.amount+'</h4><small class="text-muted">'+d.customers_buyer.percentage+'% dari periode sebelumnya</small>');
				$('#customers-user .info').html('<h4><small>Pelanggan Terdaftar</small><br>'+d.customers_user.amount+'</h4><small class="text-muted">'+d.customers_user.percentage+'% dari periode sebelumnya</small>');
				$('#customers-guest .info').html('<h4><small>Pelanggan Tamu</small><br>'+d.customers_guest.amount+'</h4><small class="text-muted">'+d.customers_guest.percentage+'% dari periode sebelumnya</small>');
			}
		});
	}
	
	function getFeaturedChart() {
		if (typeof FeaturedChart === 'object') {
			FeaturedChart.destroy();
		}
		
		$.ajax({
			url: "<?=admin_url('dashboard/chart_featured_products')?>",
			data: 'range=' + $('select[name=\'featured_range\'] option:selected').val(),
			dataType: 'json',
			success: function(d) {
				featuredData = d.datasets;
				
				FeaturedChart = new Chart($("#donutChart").get(0).getContext("2d")).Pie(featuredData, {
					segmentShowStroke: true,
					segmentStrokeColor: "#fff",
					segmentStrokeWidth: 2,
					percentageInnerCutout: 30,
					animationSteps: 100,
					animationEasing: "easeOutBounce",
					animateRotate: true,
					animateScale: false,
					responsive: true,
					maintainAspectRatio: true,
					tooltipTemplate: "<%=label%>: <%=value%>%",
					legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend list-unstyled\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"color:<%=segments[i].fillColor%>\" class=\"fa fa-circle\"></span> <%if(segments[i].label){%><%=segments[i].label%><%}%> (<%=segments[i].value%>%)</li><%}%></ul>"
				});
				
				$('#donut-legend').html(FeaturedChart.generateLegend());
			}
		});
	}
	
	function getViewsChart() {
		if (typeof viewsChart === 'object') {
			viewsChart.destroy();
		}
		
		$.ajax({
			url: "<?=admin_url('dashboard/chart_product_views')?>",
			data: 'range=' + $('select[name=\'views_range\'] option:selected').val(),
			dataType: 'json',
			success: function(d) {
				viewsData = {
					labels: d.labels,
					datasets: d.datasets,
				};
				
				viewsChart = new Chart($("#barChart2").get(0).getContext("2d")).Bar(viewsData, {
					showScale: true,
					scaleShowGridLines: true,
					scaleGridLineColor: "rgba(0,0,0,.05)",
					scaleGridLineWidth: 1,
					scaleShowHorizontalLines: true,
					scaleShowVerticalLines: true,
					bezierCurve: true,
					bezierCurveTension: 0.3,
					pointDot: true,
					pointDotRadius: 4,
					pointDotStrokeWidth: 1,
					pointHitDetectionRadius: 20,
					datasetStroke: true,
					datasetStrokeWidth: 2,
					datasetFill: true,
					responsive: true,
					maintainAspectRatio: true,
					legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend list-unstyled\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"color:<%=datasets[i].fillColor%>\" class=\"fa fa-circle\"></span> <%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
				});
				
				$('#bar2-legend').html(viewsChart.generateLegend());
			}
		});
	}
</script>
<?php } ?>