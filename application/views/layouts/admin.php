<!DOCTYPE html>
<html lang="id">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
		<link rel="shortcut icon" href="<?=$_favicon?>">
		<title><?=$template['title']?></title>
		<base href="<?=admin_url()?>"/>
		<link href="<?=base_url('assets/css/main.css')?>" rel="stylesheet">
		<link href="<?=base_url('assets/js/plugins/summernote/summernote.css')?>" rel="stylesheet">
		<link href="<?=base_url('assets/js/plugins/font-awesome/css/font-awesome.min.css')?>" rel="stylesheet" type="text/css">
		<?=$template['css']?>
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<script src="<?=base_url('assets/js/jquery-2.1.4.min.js')?>"></script>
		<script src="<?=base_url('assets/js/essential-plugins.js')?>"></script>
		<script src="<?=base_url('assets/js/bootstrap.min.js')?>"></script>
		<script src="<?=base_url('assets/js/plugins/summernote/summernote.min.js')?>"></script>
		<script src="<?=base_url('assets/js/plugins/bootstrap-datepicker.min.js')?>"></script>
		<script src="<?=base_url('assets/js/plugins/bootstrap-datepicker.id.js')?>"></script>
		<script src="<?=base_url('assets/js/plugins/bootstrap-notify.min.js')?>"></script>
		<script src="<?=base_url('assets/js/plugins/pace.min.js')?>"></script>
		<script src="<?=base_url('assets/js/plugins/select2.min.js')?>"></script>
		<?=$template['js']?>
		<style>
			table.table{background-color:#fff}table.table thead tr th{background-color:#f9f9f9;border-bottom-width:0}table.table tbody tr td{vertical-align:middle}table.dataTable{border-collapse:separate!important}.form-action{background-color:#f9f9f9;padding:10px;margin-bottom:15px;border-radius:3px}.modal-header button.close{font-size:30px}.modal-body{background-color:#f6f6f6}ul.nav-tabs li a{font-weight:700}input.input-num-table{border:none;text-align:right}.label-help{font-size:12px;color:#777;font-weight: normal}
			.select2-container{ width: 100% !important; }
			.select2-container .select2-selection--single {
				box-sizing: border-box;
				cursor: pointer;
				display: block;
				height: 38px;
				user-select: none;
				-webkit-user-select: none;
				transition: all 0.15s ease-in-out
			}
			.counter.counter-lg {
				position: absolute;
			    top: 10px !important;
			    left: 24px;
			    font-size: 9px;
			    height: 20px;
			    width: 20px;
			    background-color: red;
			    border-radius: 50%;
			    display: inline-block;
			    text-align: center;
			    vertical-align: middle;
			}
		</style>
		</head>
	<body class="sidebar-mini fixed">
		<div class="wrapper">
			<header class="main-header hidden-print">
			<a href="<?=admin_url()?>" class="logo" style="font-size:14px">
				<!-- <?=$this->config->item('site_name')?> -->
				<img src="<?=$_logo?>" width="50%">
			</a>
				<nav class="navbar navbar-static-top">
					<a href="#" data-toggle="offcanvas" class="sidebar-toggle"></a>
					<div class="navbar-custom-menu">
						<ul class="top-nav">
							<li class="dropdown notification-menu"></li>
							<li class="dropdown"><a href="<?=admin_url()?>" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-user fa-lg"></i></a>
								<ul class="dropdown-menu settings-menu">
								<?php if($this->admin->is_distributor() == '0') {?>
									<li><a href="<?=admin_url('settings')?>"><i class="fa fa-cog fa-lg"></i> Settings</a></li>
								<?php } ?>
									<li><a href="<?=admin_url('profile')?>"><i class="fa fa-user fa-lg"></i> Profile</a></li>
									<li><a href="<?=admin_url('logout')?>"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
								</ul>
							</li>
						</ul>
					</div>
				</nav>
			</header>
			<aside class="main-sidebar hidden-print">
				<section class="sidebar">
					<div class="user-panel">
						<div class="pull-left image"><img src="<?=$this->admin->image()?>" alt="<?=$this->admin->name()?>" class="img-circle"></div>
						<div class="pull-left info">
							<p><?=$this->admin->name()?></p>
							<p class="designation"><?=$this->admin->group()?></p>
							<p class="designation"><?=$this->admin->warehouse_name()?></p>
						</div>
					</div>
					<ul class="sidebar-menu">
						<li><a href="<?=admin_url()?>"><i class="fa fa-dashboard"></i><span>Dashboard</span></a></li>
						<li class="treeview">
							<a href="#"><i class="fa fa-tags"></i><span>Katalog</span><i class="fa fa-angle-right"></i></a>
							<ul class="treeview-menu">
							<?php if($this->admin->is_distributor() == '1') {?>
								<li><a href="<?=admin_url('article')?>">Daftar Stok</a></li>
								<li><a href="<?=admin_url('inventory_trans')?>">Mutasi Stok</a></li>
							<?php } else {?>
								<li><a href="<?=admin_url('product')?>">Produk</a></li>
								<li><a href="<?=admin_url('category')?>">Kategori Produk</a></li>
								<li class="treeview">
									<a href="#"><span>Inventory</span><i class="fa fa-angle-right"></i></a>
									<ul class="treeview-menu">
										<li><a href="<?=admin_url('inventory_trans')?>">Mutasi Stok</a></li>
									<?php if($this->admin->is_distributor() == '0') {?>
										<li><a href="<?=admin_url('warehouse')?>">Gudang / Distributor</a></li>
									<?php } ?>
									</ul>
								</li>
							<?php } ?>
							</ul>
						</li>
						<?php if($this->admin->is_distributor() == '0') {?>
						<li><a href="<?=admin_url('pages')?>"><i class="fa fa-file-o"></i><span>Halaman</span></a></li>
						<li class="treeview">
							<a href="#"><i class="fa fa-bullhorn"></i><span>Marketing</span><i class="fa fa-angle-right"></i></a>
							<ul class="treeview-menu">
								<li><a href="<?=admin_url('comments')?>">Komentar Produk</a></li>
							</ul>
						</li>
						<li><a href="<?=admin_url('order')?>"><i class="fa fa-shopping-cart"></i><span>Pesanan</span></a></li>
						<li><a href="<?=admin_url('users')?>"><i class="fa fa-users"></i><span>Pelanggan</span></a></li>
						<li><a href="<?=admin_url('list_distributor')?>"><i class="fa fa-truck" aria-hidden="true"></i><span>List Distributor</span></a></li>
						<?php } ?>
						<li><a href="<?=admin_url('reg_sale_offline')?>"><i class="fa fa-edit"></i><span>Registrasi Produk</span></a></li>
						<li><a href="<?=admin_url('warranty_claim')?>"><i class="fa fa-medkit"></i><span>Klaim Garansi</span></a></li>
						<?php if($this->admin->is_distributor() == '0') {?>
						<li><a href="<?=admin_url('reports')?>"><i class="fa fa-signal"></i><span>Laporan</span></a></li>
						<li class="treeview">
							<a href="#"><i class="fa fa-key"></i><span>Grup User</span><i class="fa fa-angle-right"></i></a>
							<ul class="treeview-menu">
								<li><a href="<?=admin_url('admins')?>">Admin</a></li>
								<li><a href="<?=admin_url('distributors')?>">Gudang / Distributor</a></li>
							</ul>
						</li>
						<li class="treeview">
							<a href="#"><i class="fa fa-cog"></i><span>Pengaturan</span><i class="fa fa-angle-right"></i></a>
							<ul class="treeview-menu">
								<li><a href="<?=admin_url('settings')?>">General</a></li>
								<li><a href="<?=admin_url('weight_class')?>">Satuan Berat</a></li>
								<li><a href="<?=admin_url('unit_class')?>">Satuan Unit</a></li>
								<li class="treeview">
									<a href="#"><span>Perbankan</span><i class="fa fa-angle-right"></i></a>
									<ul class="treeview-menu">
										<li><a href="<?=admin_url('bank')?>">Bank</a></li>
										<li><a href="<?=admin_url('bank_accounts')?>">Rekening Bank</a></li>
									</ul>
								</li>
								<li><a href="<?=admin_url('payment_modules')?>">Modul Pembayaran</a></li>
								<li><a href="<?=admin_url('shipping_cost')?>">Biaya Pengiriman</a></li>
							</ul>
						</li>
						<?php } ?>
					</ul>
				</section>
			</aside>
			<div class="content-wrapper">
				<?=$template['body']?>
			</div>
		</div>
		
		<script src="<?=base_url('assets/js/plugins/jquery.dataTables.min.js')?>"></script>
		<script src="<?=base_url('assets/js/plugins/dataTables.bootstrap.min.js')?>"></script>
		<script src="<?=base_url('assets/js/plugins/sweetalert.min.js')?>" type="text/javascript"></script>
		<script src="<?=base_url('assets/js/main.js')?>"></script>
		<script>
			$('select').select2();
			$('.notification-menu').load('<?=admin_url('notifications/info')?>');
			$(document).ready(function() {
				let src = "<?=base_url('assets/audio/notification.mp3')?>";
				let audio = new Audio(src);
				
				setInterval(function() {
					$('.notification-menu').load('<?=admin_url('notifications/info')?>');
					// if ($('#notification-badge').length > 0) {
					// 	audio.play();
					// }
				}, 10000);
			});
		</script>
	</body>
</html>