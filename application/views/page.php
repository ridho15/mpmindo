<div class="ps-page--simple">
	<div class="ps-breadcrumb">
		<div class="ps-container">
			<ul class="breadcrumb">
				<li><a href="<?=site_url()?>">Home</a></li>
				<li>Informasi</li>
			</ul>
		</div>
	</div>
	<div class="ps-section--page" style="padding: 30px 0;">		
		<div class="ps-container">
			<div class="ps-section__content">
				<div class="row">
					<div class="col-lg-8 col-12">
						<div class="blog-single-main">
							<div class="row">
								<div class="col-12">
									<div class="blog-detail">
										<h2 class="blog-title"><?=$title?></h2>
										<div class="content">
											<?=$content?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-12">
						<div class="main-sidebar">
							<div class="single-widget category">
								<h3 class="title">Informasi Lainnya</h3>
								<ul class="categor-list">
									<?php foreach ($pages as $page) {?>
									<li><a href="<?=site_url('page/'.$page['slug'])?>"><?=$page['title']?></a></li>
									<?php }?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>