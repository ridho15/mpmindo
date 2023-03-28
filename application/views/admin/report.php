<div class="page-title">
	<div>
		<h1>Laporan</h1>
		<p>Export laporan-laporan</p>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<h3 class="card-title">Laporan Penjualan</h3>
			<div class="card-body" style="min-height: 168px">
				<?=form_open(admin_url('reports/sales'), 'class="form-horizontal" id="form-sales"')?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Periode</label>
						<div class="col-sm-8">
							<input id="dates-sales" type="text" class="form-control" name="dates" value="<?=date('1/m/Y');?> - <?=date('d/m/Y');?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Tampilan (Khusus Excel)</label>
						<div class="col-sm-8">
							<div class="radio-inline">
								<label><input type="radio" name="view" value="global" checked> Global</label>
							</div>
							<div class="radio-inline">
								<label><input type="radio" name="view" value="detail"> Detail</label>
							</div>
						</div>
					</div>
					<div class="form-action">
						<button type="submit" class="btn btn-success"><span class="fa fa-file-excel-o"></span> Export Excel</button>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="chart" id="sale">
								<canvas id="lineChart" class="embed-responsive-item"></canvas>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div id="area-legend" style="margin-bottom: 20px"></div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?=base_url('assets/js/plugins/chart.js')?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('input[name="dates"]').daterangepicker({
			opens: 'left',
			autoApply: true,
			locale: {
				format: 'DD/MM/YYYY'
			}
		}, function(start, end, label) {
			
		});
		
		getSalesChart();
	});
	
	$('#dates-sales').on('change', function() {
		getSalesChart();
	});
	
	var salesData = {};

	function getSalesChart() {
		if (typeof salesChart === 'object') {
			salesChart.destroy();
		}
		
		$.ajax({
			url: "<?=admin_url('reports/chart_sales')?>",
			data: 'dates=' + $('#dates-sales').val(),
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
					scaleShowVerticalLines: false,
					bezierCurve: true,
					bezierCurveTension: 0.3,
					pointDot: false,
					pointDotRadius: 4,
					pointDotStrokeWidth: 1,
					pointHitDetectionRadius: 20,
					datasetStroke: true,
					datasetStrokeWidth: 2,
					datasetFill: false,
					responsive: true,
					maintainAspectRatio: true,
					legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li style=\"list-style:none; display:inline; float:left; padding:0 10px;\"><span style=\"color:<%=datasets[i].fillColor%>\" class=\"fa fa-circle\"></span> <%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
				});
				
				$('#area-legend').html(salesChart.generateLegend());
			}
		});
	}
</script>