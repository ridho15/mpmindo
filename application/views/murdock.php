<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$this->config->item('seo_title')?></title>
    <meta name="description" content="<?=$this->config->item('seo_title')?>">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" href="<?php echo base_url();?>/assets/murdock/images/icon.png">
    <!-- styles -->
    <link rel="stylesheet" href="<?php echo base_url();?>/assets/murdock/styles/vendor.css">
    <link rel="stylesheet" href="<?php echo base_url();?>/assets/murdock/styles/main.css?tx=<?php echo date('YmdHis');?>">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Vollkorn:wght@500;600&display=swap" rel="stylesheet">

    <!-- scripts -->
    <script src="<?php echo base_url();?>/assets/murdock/scripts/vendor/modernizr.js"></script>
  </head>
  <style>
    .colorlink {
      color: #efefef;
      font-size: small;
    }
  </style>
  <body>

    <!-- LOADER -->
    <div id="mask">
        <div class="loader-minimal"></div>
    </div>

    <header id="jHeader" class="invert">
      <nav class="navbar navbar-default" role="navigation">

        <div class="navbar-header">
          <!-- <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Desplegar navegación</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button> -->
          <a class="navbar-brand" href="<?php echo base_url();?>"><img src="<?php echo base_url();?>/assets/murdock/images/demo/logo-wahl9.png" alt="logo"></a>
        </div>
        
        <?php if($mobile) { ?>
        <div align="center" style="margin-bottom:10px;margin-top:10px;">
          <a href="<?php echo base_url();?>" class="colorlink">home</a> <span class="colorlink">/</span>
          <a href="#introducing" class="colorlink">about</a> <span class="colorlink">/</span>
          <a href="#portfolio" class="colorlink">shop</a> <span class="colorlink">/</span>
          <?php if(!$islogged) {?><a href="<?php echo base_url('user/login');?>" class="colorlink">sign in</a> <span class="colorlink">/</span><?php } ?>
          <?php if($islogged) {?><a href="<?php echo base_url('user/dashboard');?>" class="colorlink">dashboard</a> <span class="colorlink">/</span><?php } ?>
          <a href="<?php echo base_url('user/reg_sale_offline');?>" class="colorlink">register product</a> <span class="colorlink">/</span>
          <a href="#contact" class="colorlink">contact us</a>
        </div>
        <?php } ?>

        <div id="navbar-murdock" class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav navbar-right invert">
            <li class="active"><a href="<?php echo base_url();?>">home</a></li>
            <li><a href="#introducing">about</a></li>
            <li><a href="#portfolio">shop</a></li>
            <?php if(!$islogged) {?><li><a href="<?php echo base_url('user/login');?>">sign in</a></li><?php } ?>
            <?php if($islogged) {?><li><a href="<?php echo base_url('user/dashboard');?>">dashboard</a></li><?php } ?>
            <li><a href="<?php echo base_url('user/reg_sale_offline');?>">register product</a></li>
            <li><a href="<?php echo base_url('page_distributor');?>">store</a></li>
            <li><a href="#contact">contact us</a></li>
          </ul>
        </div>

      </nav>
    </header>

    <!-- intro -->
    <!-- <section class="intro full-width jIntro" id="home">
      <div class="container-full">
        <div class="row row-no-gutter">
          <div class="col-md-12">
            <div class="slider-intro">
              <div id="slides">
                <div class="slides-container">
                  <img style="opacity: 0.4;" src="<?php echo base_url();?>/assets/murdock/images/demo/home/slider11a.jpg" alt="slide1">
                  <img style="opacity: 0.4;" src="<?php echo base_url();?>/assets/murdock/images/demo/home/slider12a.jpg" alt="slide2">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="vcenter text-center text-overlay">
          <div id="owl-main-text" class="owl-carousel">
            <div class="item">
              <h1 class="primary-title invert">welcome to <strong style="font-family: 'Vollkorn', serif; font-size: 68px;">WAHL MPM Indonesia</strong></h1>
            </div>
          </div>
          <div class="voffset20"></div>
          <a href="#portfolio" class="btn btn-invert">shop now</a>
        </div>

      </div>
    </section> -->
    <section class="intro full-width jIntro" id="anchor00">

        <div class="playerVideo" data-property="{videoURL:'https://youtu.be/TnfvfTE3l90',showControls:false,containment:'self',autoPlay:true, mute:true, startAt:0,opacity:.7,ratio:'auto', addRaster:true, realfullscreen: true}"> <!-- Slider -->
            <a href="#" class="play-video">Play/Pause</a>
            &nbsp;
            <a href="#" class="mute-video">Mute/Unmute</a>
        </div>

    </section>

    <!-- text -->
    <div id="introducing" class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-md-offset-2">
            <div class="voffset90"></div>
            <p class="pretitle">Introducing</p><br>
            <!-- <h1 class="title" style="font-family: 'Vollkorn', serif;">WAHL Indonesia</h1> -->
            <div align="center"><img src="<?php echo base_url();?>/assets/murdock/images/icon-introduction.jpeg" alt="logo" width="25%"></div><br>
            <p class="subtitle colored" style="font-family: Helvetica;">Wahl merupakan pemimpin internasional dalam pembuatan pemangkas rambut profesional. Kualitas dan pengalaman ini berkembang dengan sendirinya ke dalam rangkaian perawatan pribadi pria dan produk penataan rambut.</p><br>
            <p class="subtitle colored" style="font-family: Helvetica;">Sebagai merek yang dapat Anda percaya, kami berdedikasi untuk terus menghasilkan produk-produk melebihi batas inovasi dan kinerja. Berbekal pengalaman lebih dari 100 tahun, kami memproduksi pemangkas rambut menggunakan teknologi <i>grinding</i> presisi tinggi, untuk memberikan pengalaman pemotongan terbaik.</p><br>
            <p class="subtitle colored" style="font-family: Helvetica;">Konsistensi dan ketajaman setiap gigi di <i>ground blade</i> kami yang presisi menghasilkan pemotongan yang lebih baik, lebih merata, menawarkan kualitas yang jauh lebih unggul daripada pesaing mana pun yang menggunakan teknologi <i>stamped blade</i>.</p>
            <!-- <p class="subtitle colored">Wahl is an international leader in the manufacturing of professional barber and hairdresser clippers and trimmers. This quality and experience extends itself into our Men’s personal care range and hair styling products.</p><br>
            <p class="subtitle colored">As a brand you can trust, we are dedicated to producing products which are built to constantly push the boundaries of innovation and performance. Drawing on over 100 years of experience, we produce using high precision grinding technology, to deliver the best cutting performance.</p><br>
            <p class="subtitle colored">The consistency and sharpness of each and every tooth on our precision ground blades creates a better, more even cut offering far superior quality than any competitor using stamped blade technology.</p> -->
            <div class="voffset90"></div>
          </div>
        </div>
      </div>
    </div>


    <!-- tabs -->
    <div id="tab" class="container-full tabsmodule">
      <div class="row row-no-gutter">
        <div class="col-md-6">
          <div class="banner" id="bg-home-grid0">
            <!-- <div class="voffset720"></div> -->
          </div>
        </div>
        <div class="col-md-6">
          <div class="banner-tabs">
            <div class=""> <!-- vcenter -->
              <!-- <ul class="list-horizontal-links carousel-tabs">
                <li class="active"><a href="#">Since 1911</a></li>
              </ul> -->
              <div class="carusel-tabs-text">
                <div class="carousel-cell">
                  <!-- <h2 class="title invert">Our History</h2> -->
                  <h2 class="title invert" style="font-family: 'Vollkorn', serif;">Sejarah Wahl Professional</h2>
                  <p style="margin-bottom: 20px; font-family: Helvetica;">Kami bangga merayakan 100 tahun sebagai standar produk perawatan yang digunakan oleh pemangkas dan penata rambut di seluruh dunia. Profesional dan pengguna rumahan telah mengandalkan pemangkas inovatif, pemangkas, dan produk perawatan pribadi kami selama satu abad penuh.</p>
                  <p style="margin-bottom: 20px; font-size: large; font-family: Helvetica;"><b><u>Pelajari lebih lanjut tentang kami:</u></b></p>
                  <p style="margin-bottom: 20px; font-family: Helvetica;">Pada tahun 1911, Leo J. Wahl menemukan motor elektromagnetik. Beliau menyadari bahwa motor jenis ini dapat digunakan untuk membuat alat pijat medis untuk pamannya, J. Frank Wahl. Sebagai seorang mahasiswa, Leo merancang alat pijat tersebut dan Frank mulai membuatnya di sebuah pabrik kecil di Sterling, Illinois.</p>
                  <p style="margin-bottom: 20px; font-family: Helvetica;">Leo mulai menjual penemuannya ke tukang cukur. Beliau menyadari kebutuhan untuk meningkatkan kinerja peralatan pangkas rambut dengan menggunakan motor jenis ini. Pada tahun 1919 Leo mengambil alih bisnis dan mendirikan Wahl Clipper Corporation. Sejak saat itu, perusahaan Wahl telah menjadi pemimpin dalam industri perawatan rambut.</p>
                  <p style="font-family: Helvetica;">Hari ini, kami terus menawarkan produk dan layanan yang sama baiknya dengan yang diciptakan Leo J. Wahl 100 tahun lalu. Produk kami tetap paling inovatif dan terbaik di industrinya. Layanan kami menetapkan standar global untuk integritas, nilai, dan efisiensi. Kami bangga dengan sejarah kami.</p><br><br>
                  <!-- <p style="margin-bottom: 20px;">In 1911, Leo J. Wahl discovered the electromagnetic motor. He realized that he could use this kind of motor to make a medical massager for his uncle, J. Frank Wahl. As a student, Leo designed the massager and Frank began manufacturing them in a small plant in Sterling, Illinois.</p>
                  <p style="margin-bottom: 20px;">Leo began selling this invention to barbershops. He recognized a need for improving barber tools by using this type of motor. In 1919 he took over the business and founded Wahl Clipper Corporation. Since then, our company has been the leader in the hair grooming industry.</p>
                  <p>Today, we continue offering the same great products and service that Leo J. Wahl created 100 years ago. Our products remain the most innovative and best in the industry. Our services set the global standard for integrity, value, and efficiency. We are proud of our history.</p> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="tab" class="container-full tabsmodule">
      <div class="row row-no-gutter">
        <div class="col-md-6">
          <div class="banner-tabs" style="background-color:#57554f; height: 100%;">
            <div class=""> 
              <div class="carusel-tabs-text">
                <div class="carousel-cell">
                  <h2 class="title invert" style="font-family: 'Vollkorn', serif;">Tentang PT. Moda Pratama Mandiri (PT. MPM)</h2>
                  <p style="margin-bottom: 20px; font-family: Helvetica;">PT. Moda Pratama Mandiri pertama kali didirikan di akhir tahun 2020, berlokasi di Jakarta, Indonesia. Kami adalah distributor resmi produk pemangkas rambut berkualitas; Wahl Professional, yang berada di bawah arahan Wahl South East Asia.</p>
                  <p style="margin-bottom: 20px; font-family: Helvetica;">PT. Moda Pratama Mandiri tidak akan hanya berfokus pada penjualan produk. Sebagai perwakilan Wahl resmi di Indonesia kami ingin turut aktif membangun dan meningkatkan kualitas hidup muda-mudi di Indonesia, dengan cara mengedukasi para barber mengenai pengetahuan produk dan teknik-teknik mencukur. Kami juga berharap untuk dapat melayani pelanggan kami di seluruh Indonesia dengan sebaik-baiknya.</p><br><br>
                  <br>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="banner-tabs" style="background-color:#807350; height: 100%;">
            <div class=""> 
              <div class="carusel-tabs-text">
                <div class="carousel-cell">
                  <h2 class="title invert" style="font-family: 'Vollkorn', serif;">Visi & Misi</h2>
                  <p style="margin-bottom: 20px; font-family: Helvetica;">Visi:</p>
                  <p style="margin-bottom: 20px; font-family: Helvetica;">Bersama WAHL mengembangkan dan membangun Barber bertaraf International.</p>
                  <p style="margin-bottom: 20px; font-family: Helvetica;">Misi:</p>
                  <p style="margin-bottom: 20px; font-family: Helvetica;">- Memfasilitasi Barber dengan pengetahuan teknik barber dan penggunaan alat yang maksimal.</p>
                  <p style="margin-bottom: 20px; font-family: Helvetica;">- Mengedukasi Barber untuk mencapai standar yg lebih baik.</p>
                  <p style="margin-bottom: 20px; font-family: Helvetica;">- Menjadikan Barber suatu karir yang dapat meningkatkan taraf hidup di Indonesia.</p>
                  <p style="margin-bottom: 20px; font-family: Helvetica;">- Bersama pemerintah Indonesia meningkatkan usaha UMKM dalam bidang Barber.</p>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- work -->
    <div id="portfolio" class="section">
      <div class="container-fluid">

        <!-- filter gallery -->
        <div class="row">
          <div class="col-md-8 col-md-offset-2">
          <div class="voffset110"></div>
            <p class="pretitle">Shop</p>
            <h1 class="title" style="font-family: 'Vollkorn', serif;">Shop Now</h1>
            <!-- <p class="subtitle colored">Wash + Cut, $13 . Wash and cut with electric trimmer and / or scissors with a razor finish. Shave, $15 . With blade. Preparation with foam and hot towel.</p> -->
            <div class="voffset80"></div>
          </div>
        </div>

        <!-- gallery photos -->
        <div class="row">
          <div class="thumbnails work5">
            <div class="grid-sizer"></div>
            <?php $i=1;?>
            <?php foreach($products as $product) {?>
              <div class="thumbnail publications <?php if($i > 4) echo 'hide-product';?>" style="padding:50px;margin-bottom:25px;">
                <img  src="<?php echo base_url();?>storage/images/<?php echo $product['thumb'];?>" alt="">
                <div class="rollover rollover5">
                  <div class="vcenter">
                    <div class="title-project"><?php echo $product['name'];?></div>
                    <ul class="tags-project">
                      <li><?php echo $product['category'];?></li>
                    </ul>
                    <a href="<?php echo $product['href'];?>" class="btn btn-default">view details</a>
                  </div>
                </div>
              </div>
              <?php $i++;?>
            <?php } ?>
          </div>
          <?php if($category['slug']) {?>
          <div align="center">
            <a href="<?=site_url('category/'.$category['slug'])?>" class="btn btn-default">view all</a>
          </div>
          <div class="voffset80"></div>
          <?php } ?>
        </div>

      </div>

    </div> 


  <!-- about parallx -->
  <!-- <div class="banner full parallax-section" data-parallax-image="<?php echo base_url();?>/assets/murdock/images/demo/home/parallax-about.jpg">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-md-offset-3">
            <div class="voffset110"></div>
            <h1 class="title colored invert">We are working 24/7</h1>
            <div class="voffset10"></div>
            <p class="subtitle invert">Special opening hours</p>
            <div class="separator invert"></div>
            <p class="subtitle small invert">Saturday 17th December: 9:30am - 8pm; Saturday 24th December: 9:30am - 4pm; Saturday 31th December: 9:30am - 4pm</p>
            <div class="voffset140"></div>
          </div>
        </div>
      </div>
    </div> -->


    <!-- Achievements -->
    <!-- <section class="banner section inverse-color full full-width">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="voffset120"></div>
            <p class="pretitle invert">achievements</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <ul class="counter">
              <li class="fadeInUp horizontal-border">
                <p class="number">
                  <span class="numscroller" data-min="1" data-max="375" data-delay="5" data-increment="5">375</span>
                </p>
                <p class="title-counter">Hair Cut</p>
              </li>
              <li class="fadeInUp horizontal-border">
                <p class="number">
                  <span class="numscroller" data-min="1" data-max="265" data-delay="5" data-increment="5">265</span>
                </p>
                <p class="title-counter">Beard Trimmed</p>
              </li>
              <li class="fadeInUp horizontal-border">
                <p class="number">
                  <span class="numscroller" data-min="1" data-max="235" data-delay="5" data-increment="5">235</span>
                </p>
                <p class="title-counter">Head Massaged</p>
              </li>
              <li class="fadeInUp horizontal-border">
                <p class="number">
                  <span class="numscroller" data-min="1" data-max="310" data-delay="5" data-increment="5">310</span>
                </p>
                <p class="title-counter">Satisfied clients</p>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section> -->


    <!-- banner parallx -->
    <!-- <div class="banner full parallax-section" data-parallax-image="<?php echo base_url();?>/assets/murdock/images/demo/home/parallax.jpg">
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-md-offset-2 text-center">
            <div class="voffset330"></div>
                <img src="<?php echo base_url();?>/assets/murdock/images/demo/home/parallax-logo.png" alt="">
            <div class="voffset60"></div>
            <div class="voffset270"></div>
          </div>
        </div>
      </div>
    </div> -->

    <!-- contact -->
    <div id="contact" class="section">
      <div class="container container-full">
        <div class="row row-no-gutter">
          <div class="col-md-6">
            <div id="map">
              <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3967.01496637567!2d106.74729461476869!3d-6.128687795562463!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6a1d785eeaf125%3A0x9563b479b8ff8c9a!2sWAHL%20INDONESIA!5e0!3m2!1sen!2sid!4v1611325000688!5m2!1sen!2sid" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe> -->
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15868.060635574599!2d106.7495139!3d-6.1286619!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x6aea44db6c518edf!2sPT%20Moda%20Pratama%20Mandiri!5e0!3m2!1sen!2sid!4v1623384742803!5m2!1sen!2sid" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" loading="lazy" aria-hidden="false" tabindex="0"></iframe>
            </div>
          </div>
          <div class="col-md-6">
            <div class="contact-basic" style="background-color: #e1e1e1;">
              <div class="voffset400"></div>
              <div class="vcenter text-center">
                <p class="pretitle">phone</p>
                <div class="voffset20"></div>
                <p><?=$this->config->item('telephone')?></p>
                <div class="voffset50"></div>
                <p class="pretitle">email</p>
                <div class="voffset20"></div>
                <p><a href="mailto:<?=$this->config->item('email')?>" class="mailto"><?=$this->config->item('email')?></a></p>
              </div>
            </div>
            <div class="contact-form bg-cream">
              <div class="voffset400"></div>
              <div class="vcenter text-center">
                <p class="pretitle">Hubungi Kami</p>
                <div class="voffset40"></div>
                <?=form_open(site_url('murdock/send'), 'id="form-send"')?>
                  <div class="form-group inline">
                    <input type="text" class="form-control" name="inputName" placeholder="Nama" maxlength="50">
                  </div>
                  <div class="form-group inline">
                    <input type="email" class="form-control" name="inputEmail" placeholder="Email" maxlength="50">
                  </div>
                  <div class="form-group">
                    <textarea class="form-control" rows="3" name="inputMessage" placeholder="Pesan"></textarea>
                  </div>
                  <div class="voffset20"></div>
                  <button type="button" class="btn btn-arrow" id="button-send">Kirim Pesan</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- contenct with us -->
    <div class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="voffset80"></div>
            <p class="pretitle">Connect with us</p>
            <ul class="social-links">
              <li><a href="https://www.facebook.com/wpro.indo" target="_blank"><i class="fa fa-facebook"></i></a></li>
              <li><a href="https://www.instagram.com/wpro.indo" target="_blank"><i class="fa fa-instagram"></i></a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <footer>
      <div class="container-fluid">
        <div class="voffset30"></div>
        <div class="row">
          <!-- <div class="col-md-4 col-sm-6">
            <div class="voffset30"></div>
            <img src="<?php echo base_url();?>/assets/murdock/images/demo/footer-logo.png" alt="logo">
            <div class="voffset20"></div>
            <p>Murdock is a digital agencystriving to bring your ideas to life by givin it that extra feel.</p>
          </div> -->
          <div class="col-md-6 col-sm-6">
            <div class="voffset30"></div>
            <h5 class="title-small" style="font-family: 'Vollkorn', serif;">Browse</h5>
            <div class="voffset20"></div>
            <ul class="list-menu">
              <li><a href="<?php echo base_url();?>" style="font-family: Helvetica;">Home</a></li>
              <li><a href="#introducing" style="font-family: Helvetica;">About</a></li>
              <li><a href="#portfolio" style="font-family: Helvetica;">Shop</a></li>
              <li><a href="<?php echo base_url('page_distributor');?>" style="font-family: Helvetica;">Store</a></li>
              <?php if(!$islogged) {?><li><a href="<?php echo base_url('user/login');?>" style="font-family: Helvetica;">Sign In</a></li><?php }?>
              <?php if($islogged) {?><li><a href="<?php echo base_url('user/dashboard');?>" style="font-family: Helvetica;">Dashboard</a></li><?php }?>
              <li><a href="<?php echo base_url('user/reg_sale_offline');?>" style="font-family: Helvetica;">Register Product</a></li>
              <li><a href="#contact" style="font-family: Helvetica;">Contact Us</a></li>
            </ul>
          </div>
          <div class="col-md-6 col-sm-6">
            <div class="voffset30"></div>
            <h5 class="title-small" style="font-family: 'Vollkorn', serif;">Contact us</h5>
            <div class="voffset20"></div>
            <p class="contact-link fa fa-map-marker" style="font-family: Helvetica;">
            <?=$this->config->item('address')?>
            </p><br>
            <p class="contact-link fa fa-mobile" style="font-family: Helvetica;"><?=$this->config->item('telephone')?></p><br>
            <p class="contact-link fa fa-envelope-o" style="font-family: Helvetica;"><?=$this->config->item('email')?></p>
          </div>
          
        </div>
        <div class="voffset30"></div>
      </div>
      <div class="copyright">
        <div class="voffset40"></div>
        <p>&copy; <?php echo date('Y');?> PT Moda Pratama Mandiri. all rights reserved. Website design by <a href="http://myhassee.com" target="_blank">MyHassee.com</a>.</p>
        <div class="voffset40"></div>
      </div>
    </footer>

    <!--[if lt IE 7]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    <script>
      (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
      function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
      e=o.createElement(i);r=o.getElementsByTagName(i)[0];
      e.src='https://www.google-analytics.com/analytics.js';
      r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
      ga('create','UA-XXXXX-X');ga('send','pageview');
    </script>

    <script src="<?php echo base_url();?>/assets/murdock/scripts/plugins/jquery.min.js"></script>
    <script src="<?php echo base_url();?>/assets/murdock/scripts/plugins/flickity.pkgd.min.js"></script>
    <script src="<?php echo base_url();?>/assets/murdock/scripts/plugins/twitterFetcher_min.js"></script>
    <script src="<?php echo base_url();?>/assets/murdock/scripts/plugins/jquery.parallax.min.js"></script>
    <script src="<?php echo base_url();?>/assets/murdock/scripts/plugins/isotope.pkgd.min.js"></script>
    <script src="<?php echo base_url();?>/assets/murdock/scripts/plugins/jquery.superslides.min.js"></script>
    <script src="<?php echo base_url();?>/assets/murdock/scripts/plugins/owl.carousel.min.js"></script>
    <script src="<?php echo base_url();?>/assets/murdock/scripts/plugins/jquery.inview.min.js"></script>
    <script src="<?php echo base_url();?>/assets/murdock/scripts/plugins/jquery.numscroller-1.0.js"></script>
    <script src="<?php echo base_url();?>/assets/murdock/scripts/plugins/jquery.countdown/jquery.plugin.min.js"></script>
    <script src="<?php echo base_url();?>/assets/murdock/scripts/plugins/jquery.countdown/jquery.countdown.min.js"></script>
    <script src="<?php echo base_url();?>/assets/murdock/scripts/plugins/YTPlayer.js"></script>
    <script src="<?php echo base_url();?>/assets/murdock/scripts/main.js"></script>

    <script src="<?php echo base_url();?>/assets/murdock/scripts/vendor/bootstrap.js"></script>

    <!-- Google maps -->
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCiIMMCMPwPnZPz-6J2139qSwTo6IAHeR4"></script>
    <script src="<?php echo base_url();?>/assets/murdock/scripts/map.js"></script> -->
    
    <script type="text/javascript">
      $('#button-send').on('click', function() {
        var btn = $(this);
        $.ajax({
          url: $('#form-send').attr('action'),
          data: $('#form-send').serialize(),
          type: 'POST',
          dataType: 'json',
          success: function(json) {
            $('.alert, .error').remove();
				    $('.form-group').removeClass('has-error');
            if (json['redirect']) {
              alert('Email berhasil dikirim');
              window.location = json['redirect'];
            } 
            else if (json['error']) {
              for (i in json['error']) {
                $('input[name=\''+i+'\']').after('<small class="help-block error"><i>'+json['error'][i]+'</i></small>');
                $('input[name=\''+i+'\']').parent().addClass('has-error');
                $('textarea[name=\''+i+'\']').after('<small class="help-block error"><i>'+json['error'][i]+'</i></small>');
                $('textarea[name=\''+i+'\']').parent().addClass('has-error');
              }
            }
          }
        });
      });
      $('.mute-video').on('click', function() {
        if (jQuery('.mute-video').hasClass('playing')) {
          jQuery(".playerVideo").YTPMute();
          jQuery('.mute-video').removeClass('playing');
        } else {
          jQuery(".playerVideo").YTPUnmute();
          jQuery('.mute-video').addClass('playing');
        }
      });
    </script>
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
</body>
</html>
