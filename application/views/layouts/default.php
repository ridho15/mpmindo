<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">		
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="author" content="">
	<meta name="keywords" content="">
	<meta name="description" content="">
	<base href="<?=base_url()?>" />
	<title><?=$template['title']?></title>
	<link href="<?=$_favicon?>" rel="shortcut icon">
	<link href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700&amp;subset=latin-ext" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css" rel="stylesheet">
	<!-- <link href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet"> -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
	
	<?php Asset::css('font-awesome.min.css', true);?>
	<?php Asset::css('linearicons.css', true);?>
	<?php Asset::css('bootstrap.min.css', true);?>
	<?php Asset::css('owl.carousel.css', true);?>
	<?php Asset::css('slick.css', true);?>
	<?php Asset::css('lightgallery.min.css', true);?>
	<?php Asset::css('style.css', true);?>
	<?php Asset::css('datepicker.css', true);?>
	
	<?php Asset::js('jquery-1.12.4.min.js', true);?>
	<?php Asset::js('popper.min.js', true);?>
	<?php Asset::js('owl.carousel.min.js', true);?>
	<?php Asset::js('bootstrap.min.js', true);?>
	<?php Asset::js('imagesloaded.pkgd.min.js', true);?>
	<?php Asset::js('masonry.pkgd.min.js', true);?>
	<?php Asset::js('isotope.pkgd.min.js', true);?>
	<?php Asset::js('jquery.matchHeight-min.js', true);?>
	<?php Asset::js('slick.min.js', true);?>
	<?php Asset::js('slick-animation.min.js', true);?>
	<?php Asset::js('lightgallery-all.min.js', true);?>
	<?php Asset::js('sticky-sidebar.min.js', true);?>
	<?php Asset::js('jquery.slimscroll.min.js', true);?>
	<?php Asset::js('main.js', true);?>
	<?php Asset::js('scripts.js', true);?>
	<?php Asset::js('bootstrap3-typeahead.js', true);?>

	
	<script src="https://kit.fontawesome.com/edfbe38184.js" crossorigin="anonymous"></script>
	<?=Asset::render()?>
	
	<script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=5f01d795e9f145001203b909&product=inline-share-buttons" async="async"></script>
	<style>
	.typeahead.dropdown-menu {
		width: 475px;
	}

	.typeahead.dropdown-menu li a, .typeahead.dropdown-menu li.dropdown-header {
		font-size: 14px;
		line-height: 24px;
	}
	.underline-on-hover:hover {
		text-decoration: underline;
	}
</style>
</head>
<body>
	<header class="header header--1" data-sticky="true">
		<div class="header__top">
			<div class="ps-container">
				<div class="header__left">
					<div class="menu--product-categories">
						<div class="menu__toggle"><i class="icon-menu"></i><span style="color:#fff;"> Pilih Kategori</span></div>
						<div class="menu__content">
							<ul class="menu--dropdown">
								<?php echo "<!-- ". json_encode($_categories). "-->"?>
								<?php foreach ($_categories as $category) {?>
									<?php if (count($category['children']) > 0) {?>
										<li class="current-menu-item menu-item-has-children has-mega-menu">
											<a  href="<?=$category['href']?>"><i class="icon-chevron-right"></i> <?=$category['name']?></a>
											<div class="mega-menu">
												<?php foreach ($category['children'] as $children) {?>
													<ul class="mega-menu__list">
														<div class="mega-menu__column">
															<?php if (count($children['children']) > 0) {?>
																<li class="boldmenu">
																	<a href="<?=$children['href']?>"><?=$children['name']?></a>
																	<ul class="mega-menu__list">
																		<?php foreach ($children['children'] as $subchildren) {?>
																			<li class="current-menu-item"><a href="<?=$subchildren['href']?>"><?=$subchildren['name']?></a></li>
																		<?php }?>
																	</ul>
																</li>
															<?php } else {?>
																<li class=" boldmenu "><a  href="<?=$children['href']?>"> <?=$children['name']?></a>
																<?php }?>
															</div>
														</ul>
													<?php }?>
												</div>
											</li>
										<?php } else {?>
											<li class="current-menu-item "><a href="<?=$category['href']?>"><i class="icon-chevron-right"></i> <?=$category['name']?></a>
											<?php }?>
										<?php }?>
									</ul>	
								</div>
							</div><a class="ps-logo" href="<?=site_url()?>"><img src="<?=$_logo?>" alt="<?=$this->config->item('site_name')?>"></a>
						</div>
						<div class="header__center">
							<form class="ps-form--quick-search" action="<?=site_url('search')?>" method="get">
								<input style="border-radius:3px 0 0 3px" class="form-control" name="term" type="text" value="<?=$this->input->get('term')?>" placeholder="Saya ingin mencari..." autocomplete="off">
								<input type="hidden" name="category_id" value="0">
								<button id="btn-search">Cari</button>
							</form>
						</div>
						<div class="header__right">
							<div class="header__actions">
								<!-- <a class="header__extra" href="<?=user_url('purchase')?>" title="Pesanan Saya" data-toggle="tooltip" data-placement="bottom"><i class="icon-bag"></i></a> -->
								<a class="header__extra" href="<?=user_url('reg_sale_offline')?>" title="Registrasi Produk" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-edit"></i></a>
								<div class="ps-block--user-header">
									<?php if ($this->user->is_logged()) {?>
										<div class="ps-block__left"><i class="icon-user"></i></div>
										<div class="ps-block__right">
											<a href="<?=user_url('dashboard')?>" class="mb-2 underline-on-hover">Dashboard</a>
											<a href="<?=user_url('logout')?>" class="underline-on-hover">Logout</a>
										</div>
									<?php } else {?>
										<div class="ps-block__left"><i class="icon-user"></i></div>
										<div class="ps-block__right">
											<a href="<?=user_url('login')?>" class="mb-2 underline-on-hover">Login</a>
											<a href="<?=user_url('register')?>" class="underline-on-hover">Daftar</a>
										</div>
									<?php }?>
								</div>
								<div class="ps-cart--mini"></div>
							</div>
						</div>
					</div>
				</div>
				<nav class="navigation">
					<div class="ps-container">
						<div class="navigation__left">
							<div class="menu--product-categories">
								<div class="menu__toggle"><i class="icon-menu"></i><span style="color:#fff;"> Pilih Kategori</span></div>
								<div class="menu__content">
									<ul class="menu--dropdown">
										<?php echo "<!-- ". json_encode($_categories). "-->"?>
										<?php foreach ($_categories as $category) {?>
											<?php if (count($category['children']) > 0) {?>
												<li class="current-menu-item menu-item-has-children has-mega-menu">
													<a  href="<?=$category['href']?>"><i class="icon-chevron-right"></i> <?=$category['name']?></a>
													<div class="mega-menu">
														<?php foreach ($category['children'] as $children) {?>
															<ul class="mega-menu__list">
																<div class="mega-menu__column">
																	<?php if (count($children['children']) > 0) {?>
																		<li class="boldmenu current-menu-item menu-item-has-children has-mega-menu">
																			<a href="<?=$children['href']?>"><?=$children['name']?></a>
																			<ul class="mega-menu__list">
																				<?php foreach ($children['children'] as $subchildren) {?>
																					<li class="current-menu-item"><a href="<?=$subchildren['href']?>"><?=$subchildren['name']?></a></li>
																				<?php }?>
																			</ul>
																		</li>
																	<?php } else {?>
																		<li class=" boldmenu current-menu-item "><a  href="<?=$children['href']?>"> <?=$children['name']?></a>
																		<?php }?>
																	</div>
																</ul>
															<?php }?>
														</div>
													</li>
												<?php } else {?>
													<li class="current-menu-item "><a href="<?=$category['href']?>"><i class="icon-chevron-right"></i> <?=$category['name']?></a>
													<?php }?>
												<?php }?>
											</ul>
										</div>
									</div>
								</div>
								<div class="navigation__right">
									<ul class="menu">
										<li class="menu-item-has-children"><a href="javascript:void(0);" style="color:#fff;">Informasi</a><span class="sub-toggle"></span>
											<ul class="sub-menu">
												<?php foreach ($pages as $page) {?>
													<li class="current-menu-item"><a href="<?=site_url('page/'.$page['slug'])?>"><?=$page['title']?></a></li>
												<?php }?>
											</ul>
										</li>
										<li class="menu-item"><a href="<?=site_url('contact')?>" style="color:#fff;">Kontak</a></li>
									</ul>
									<ul class="navigation__extra">
										<li><span class="fa fa-whatsapp"></span> <a href="https://wa.me/<?=$this->config->item('whatsapp')?>" target="_blank" style="color:#fff;"><?=$this->config->item('whatsapp')?></a></li>
										<li><span class="fa fa-phone"></span> <a href="tel:<?=$this->config->item('telephone')?>" style="color:#fff;"><?=$this->config->item('telephone')?></a></li>
										<li><span class="fa fa-envelope-o"></span> <a href="mailto:<?=$this->config->item('email')?>" style="color:#fff;"><?=$this->config->item('email')?></a></li>
									</ul>
								</div>
							</div>
						</nav>
					</header>
					<header class="header header--mobile" data-sticky="true">
						<div class="header__top">
							<div class="header__left">
								<p>Selamat datang di <?=$this->config->item('site_name')?>!</p>
							</div>
							<div class="header__right">
								<ul class="navigation__extra">
									<li><a href="<?=site_url('contact')?>">Kontak Kami</a></li>
									<li><a href="<?=user_url()?>">Lacak Pesanan</a></li>
								</ul>
							</div>
						</div>
						<div class="navigation--mobile">
							<div class="navigation__left"><a class="ps-logo" href="<?=site_url()?>"><img src="<?=$_logo?>" alt="<?=$this->config->item('site_name')?>"></a></div>
							<div class="navigation__right">
								<div class="header__actions">
									<div class="ps-cart--mini"></div>
									<div class="ps-block--user-header">
										<?php if ($this->user->is_logged()) {?>
											<div class="ps-block__left"><i class="icon-user"></i></div>
											<div class="ps-block__right">
												<a href="<?=user_url('dashboard')?>" class="mb-2 underline-on-hover">Dashboard</a>
												<a href="<?=user_url('logout')?>" class="underline-on-hover">Logout</a>
											</div>
										<?php } else {?>
											<div class="ps-block__left"><i class="icon-user"></i></div>
											<div class="ps-block__right">
												<a href="<?=user_url('login')?>" class="mb-2 underline-on-hover">Login</a>
												<a href="<?=user_url('register')?>" class="underline-on-hover">Daftar</a>
											</div>
										<?php }?>
									</div>
								</div>
							</div>
						</div>
						<div class="ps-search--mobile">
							<form class="ps-form--search-mobile" action="<?=site_url('search')?>" method="get">
								<div class="form-group--nest">
									<input class="form-control" type="text" name="term" value="<?=$this->input->get('term')?>" placeholder="Saya ingin mencari..." autocomplete="off">
									<button><i class="icon-magnifier"></i></button>
								</div>
							</form>
						</div>
					</header>
					<div class="ps-panel--sidebar" id="cart-mobile"></div>
					<div class="ps-panel--sidebar" id="navigation-mobile">
						<div class="ps-panel__header">
							<h3>Kategori</h3>
						</div>
						<div class="ps-panel__content">
							<ul class="menu--mobile">
								<?php foreach ($_categories as $category) {?>
									<?php if (count($category['children']) > 0) {?>
										<li class="menu-item-has-children">
											<a href="<?=$category['href']?>"><?=$category['name']?></a><span class="sub-toggle"></span>
											<ul class="sub-menu">
												<?php foreach ($category['children'] as $children) {?>
													<li class="boldmenu"><a href="<?=$children['href']?>"><?=$children['name']?></a></li>
													<?php if (count($category['children']) > 0) {?>
														<?php foreach ($children['children'] as $subchildren) {?>
															<li class="current-menu-item"><a href="<?=$subchildren['href']?>"><?=$subchildren['name']?></a></li>
														<?php } ?>
													<?php } ?>
												<?php }?>
											</ul>

										</li>
									<?php } else {?>
										<li class="menu-item"><a href="<?=$category['href']?>"><?=$category['name']?></a></li>
									<?php }?>
								<?php }?>
							</ul>
						</div>
					</div>
					<div class="navigation--list">
						<div class="navigation__content">
							<a class="navigation__item ps-toggle--sidebar" href="#menu-mobile"><i class="icon-menu"></i><span> Menu</span></a>
							<a class="navigation__item ps-toggle--sidebar" href="#navigation-mobile"><i class="icon-list4"></i><span> Kategori</span></a>
							<!-- <a class="navigation__item ps-toggle--sidebar" href="#search-sidebar"><i class="icon-magnifier"></i><span> Cari</span></a> -->
							<a class="navigation__item ps-toggle--sidebar" href="#cart-mobile"><i class="icon-bag2"></i><span> Keranjang</span></a>
						</div>
					</div>
					<div class="ps-panel--sidebar" id="search-sidebar">
						<div class="ps-panel__header">
							<form class="ps-form--search-mobile" action="<?=site_url('search')?>" method="get">
								<div class="form-group--nest">
									<input class="form-control" name="term" type="text" value="<?=$this->input->get('term')?>" placeholder="Saya ingin mencari..." autocomplete="off">
									<button><i class="icon-magnifier"></i></button>
								</div>
							</form>
						</div>
						<div class="navigation__content"></div>
					</div>
					<div class="ps-panel--sidebar" id="menu-mobile">
						<div class="ps-panel__header">
							<h3>Menu</h3>
						</div>
						<div class="ps-panel__content">
							<ul class="menu--mobile">
								<li class="menu-item"><a href="<?=site_url()?>">Home</a></li>
								<li class="menu-item-has-children"><a href="javascript:void(0)">Informasi</a><span class="sub-toggle"></span>
									<ul class="sub-menu">
										<?php foreach ($pages as $page) {?>
											<li class="current-menu-item"><a href="<?=site_url('page/'.$page['slug'])?>"><?=$page['title']?></a></li>
										<?php }?>
									</ul>
								</li>
								<li class="menu-item"><a href="<?=site_url('contact')?>">Kontak</a></li>
							</ul>
						</div>
					</div>
					<?=$template['body']?>
					<footer class="ps-footer">
						<div class="ps-container">
							<div class="ps-footer__widgets">
								<aside class="widget widget_footer widget_contact-us">
									<h4 class="widget-title">Hubungi Kami</h4>
									<div class="widget_content">
										<h3><?=$this->config->item('telephone')?></h3>
										<ul class="ps-list--link">
											<li>&nbsp;<span class="fa fa-map-marker"></span> <?=$this->config->item('address')?></li>
											<li><span class="fa fa-envelope-o"></span> <a href="mailto:<?=$this->config->item('email')?>"><span class="__cf_email__"><?=$this->config->item('email')?></span></a></li>
										</ul>
										<ul class="ps-list--social">
											<?php if ($this->config->item('facebook_link')) {?>
												<li><a class="facebook" href="<?=$this->config->item('facebook_link')?>"><i class="fa fa-facebook"></i></a></li>
											<?php }?>
											<?php if ($this->config->item('instagram_link')) {?>
												<li><a class="twitter" href="<?=$this->config->item('instagram_link')?>"><i class="fa fa-instagram"></i></a></li>
											<?php }?>
											<?php if ($this->config->item('youtube_link')) {?>
												<li><a class="youtube" href="<?=$this->config->item('youtube_link')?>"><i class="fa fa-youtube"></i></a></li>
											<?php }?>
											<?php if ($this->config->item('twitter_link')) {?>
												<li><a class="instagram" href="<?=$this->config->item('twitter_link')?>"><i class="fa fa-twitter"></i></a></li>
											<?php }?>
										</ul>
									</div>
								</aside>
								<aside class="widget widget_footer">
									<h4 class="widget-title">Informasi</h4>
									<ul class="ps-list--link">
										<?php foreach ($pages as $page) {?>
											<li><a href="<?=site_url('page/'.$page['slug'])?>"><?=$page['title']?></a></li>
										<?php }?>
									</ul>
								</aside>
								<aside class="widget widget_footer">
									<h4 class="widget-title">Informasi Akun</h4>
									<ul class="ps-list--link">
										<li><a href="<?=user_url('login')?>">Masuk Ke Akun</a></li>
										<li><a href="<?=user_url('register')?>">Pendaftaran</a></li>
										<li><a href="<?=user_url('purchase')?>">Pesanan Saya</a></li>
										<li><a href="<?=site_url('payment_confirmation')?>">Konfirmasi Pesanan</a></li>
									</ul>
								</aside>
							</div>
							<div class="ps-footer__copyright" style="vertical-align:middle">
								<!-- <p>&copy;<?=date('Y')?> <a href="<?=site_url()?>"><?=$this->config->item('company')?></a>. All Rights Reserved. Website design by <a href="http://myhassee.com" target="_blank">MyHassee.com</a>.</p> -->
								<p>&copy;<?=date('Y')?> <a href="<?=site_url()?>">PT Moda Pratama Mandiri</a>. All Rights Reserved. Website design by <a href="http://myhassee.com" target="_blank">MyHassee.com</a>.</p>
								<?php if ($_payment_logos) {?>
									<p>
										<span>Metode Pembayaran Aman </span>
										<?php foreach ($_payment_logos as $payment_logo) {?>
											<a><img src="<?=$payment_logo['image']?>"></a>
										<?php }?>
									</p>
								<?php }?>
							</div>
						</div>
					</footer>
					<div id="back2top"><i class="pe-7s-angle-up"></i></div>
					<div class="ps-site-overlay"></div>
	<!--
	<div id="loader-wrapper">
		<div class="loader-section section-left"></div>
		<div class="loader-section section-right"></div>
	</div>
-->
<div class="ps-search" id="site-search"><a class="ps-btn--close" href="#"></a>
	<div class="ps-search__content">
		<form class="ps-form--primary-search" action="<?=site_url('search')?>" method="get">
			<input class="form-control" type="text" name="term" value="<?=$this->input->get('term')?>" placeholder="Saya ingin mencari..." autocomplete="off">
			<button><i class="aroma-magnifying-glass"></i></button>
		</form>
	</div>
</div>
<script src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
<!-- <script src="<?=base_url('assets/js/plugins/jquery.dataTables.min.js')?>"></script>
	<script src="<?=base_url('assets/js/plugins/dataTables.bootstrap.min.js')?>"></script> -->
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/62679f4a7b967b11798c864a/1g1iccf6l';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
	<script>
		$('input[name=\'term\']').typeahead({
			delay: 500,
			source: function(query, process) {
				$.ajax({
					url: "<?=site_url('search/ajax')?>",
					data: 'query=' + query,
					type: 'get',
					dataType: 'json',
					beforeSend: function() {
						$('#btn-search').html('<i class="fa fa-spinner fa-spin fa-lg"></i>');
					},
					complete: function() {
						$('#btn-search').html('Cari');
					},
					success: function(json) {
						process(json);
					}
				});
			},
			updater: function (item) {
				$('input[name=\'category_id\']').val(item.category_id);

				return item.name.replace('"', '');
			},
			afterSelect: function(item) {
				this.$element.closest('form').submit();
			},
			minLength: 1
		});
	</script>

	<style>

	/* Style header */
	.owl-carousel .owl-item img{
		width:100vw;  
		max-height:60vh;
	}
	.mega-menu{
		height:100%
	}
	.boldmenu>a{
		color: #000 !important;
		font-size: 16px !important;
		font-weight: 600 !important;
	}
	.ps-carousel--nav-inside .owl-nav .owl-prev,
	.ps-carousel--nav-inside .owl-nav .owl-next
	{
		background-color:#d8d831bd!important;
	}
	.typeahead.dropdown-menu {
		width: 475px;
	}

	.typeahead.dropdown-menu li a, .typeahead.dropdown-menu li.dropdown-header {
		font-size: 14px;
		line-height: 24px;
	}
	a[class*="product__title"] {
		font-weight:800 !important;
		color:black !important;
	}	
</style>
</body>

</html>