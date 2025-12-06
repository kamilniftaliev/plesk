<?php


$currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
date_default_timezone_set('asia/jakarta');

if (str_contains($currentURL, 'frp62')) {
echo "<h2><center>This content Is Not for you</center><h2>";
die();
}


include 'konak2.php';
$now = date("Y-m-d ");

$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='4' AND status='done' AND tgl >= '$now' ");
$fdl = mysqli_num_rows($query);



$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='1' AND status='done' AND tgl >= '$now' ");
$flash = mysqli_num_rows($query);

$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='2' AND status='done' AND tgl >= '$now' ");
$frp = mysqli_num_rows($query);

$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='6' AND status='done' AND tgl >= '$now' ");
$flashmtk5 = mysqli_num_rows($query);

$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='4' AND status='done'  ");
$allfdl = mysqli_num_rows($query);



$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='1' AND status='done'  ");
$allflash = mysqli_num_rows($query);

$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='2' AND status='done' ");
$allfrp = mysqli_num_rows($query);




$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='4' AND status!='done' AND tgl >= '$now' ");
$failfdl = mysqli_num_rows($query);


$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='2' AND status!='done' AND tgl >= '$now' ");
$failfrp = mysqli_num_rows($query);

$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='1' AND status!='done' AND tgl >= '$now' ");
$failflash = mysqli_num_rows($query);


$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='4' AND status!='done' ");
$allfailfdl = mysqli_num_rows($query);


$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='2' AND status!='done'  ");
$allfailfrp = mysqli_num_rows($query);

$query = mysqli_query($koneksi, "SELECT * FROM data WHERE serviceid='1' AND status!='done' ");
$allfailflash = mysqli_num_rows($query);

$query = mysqli_query($koneksi, "SELECT * FROM price WHERE serviceid='1' ");
$arrayprice = mysqli_fetch_array($query);
$hargaflash = $arrayprice['harga'];


$query = mysqli_query($koneksi, "SELECT * FROM price WHERE serviceid='2' ");
$arrayprice = mysqli_fetch_array($query);
$hargafrp = $arrayprice['harga'];


$query = mysqli_query($koneksi, "SELECT * FROM price WHERE serviceid='4' ");
$arrayprice = mysqli_fetch_array($query);
$hargafdl = $arrayprice['harga'];

$query = mysqli_query($koneksi, "SELECT * FROM price WHERE serviceid='6' ");
$arrayprice = mysqli_fetch_array($query);
$hargamtk5 = $arrayprice['harga'];



$data = mysqli_query($koneksi, "SELECT * FROM savemode WHERE id = '1' ");
$arrayqcom = mysqli_fetch_array($data);
$statusqcom = $arrayqcom['status'];
$serverflash  = "ON";
if($statusqcom == "ON") {
  $serverflash  = "OFF";
    
}



$limitcek =  'limitleftfrp';
$serversupport = "frp";
$sekarang = date('Y-m-d H:i:s');
$data = mysqli_query($koneksi, "SELECT * FROM server WHERE status = 'ON' and delay <= '".$sekarang."' and serversupport LIKE '%".$serversupport."%' and $limitcek > 0 ");
$jumlah = mysqli_num_rows($data);

$serverfrp = "OFF";
if($jumlah > 0){
   $serverfrp = "ON"; 
}

$limitcek =  'limitleftfdl';
$serversupport = "fdl";
$sekarang = date('Y-m-d H:i:s');
$data = mysqli_query($koneksi, "SELECT * FROM server WHERE status = 'ON' and delay <= '".$sekarang."' and serversupport LIKE '%".$serversupport."%' and $limitcek > 0 ");
$jumlah = mysqli_num_rows($data);

$serverfdl = "OFF";
if($jumlah > 0){
   $serverfdl = "ON"; 
}





$hari = date('l', strtotime($now));
$sisa = 0;
  $idcek = 1;
    $data = mysqli_query($koneksi, "SELECT * FROM free  where id = '$idcek' ");
    $cek = mysqli_num_rows($data);
    if($cek > 0){ 

        $user = mysqli_fetch_array($data);
        $jumlah = $user['count'];
        $sisa = 50 - $jumlah;

}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AzeUnlock - Premium Xiaomi Authentication Services">
    <meta name="keywords" content="Xiaomi, Authentication, Flashing, FRP Bypass">
    <meta name="author" content="AzeUnlock Team">
    <title>AzeGsm - Xiaomi Authenticator</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="https://worldwideauth.com/assets/img/favicon.ico" type="image/x-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Medilab
  * Template URL: https://bootstrapmade.com/medilab-free-medical-bootstrap-theme/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header sticky-top">

    <div class="topbar d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
          <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:hikadul@gmail.com">sample@gmail.com</a></i>
          <i class="bi bi-phone d-flex align-items-center ms-4"><span>+62 </span></i>
        </div>
        <div class="social-links d-none d-md-flex align-items-center">
          <a href="https://t.me/+TsPt5jMzI1" class="telegram"><i class="bi bi-telegram"></i></a>
            <a href="https://chat.whatsapp.com/JxZjIF8qXNY9" class="linkedin"><i class="bi bi-whatsapp"></i></a>
          <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
        
        </div>
      </div>
    </div><!-- End Top Bar -->

  <div class="branding d-flex align-items-center">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="" class="logo d-flex align-items-center me-auto">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <!-- <img src="assets/img/logo.png" alt=""> -->
          <h1 class="sitename">
   <img src="https://upload.wikimedia.org/wikipedia/commons/a/ae/Xiaomi_logo_%282021-%29.svg" alt="Logo" class="logo">AzeGsm
</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="" class="active">Home<br></a></li>
            
           <li><a href="#free">Free Services</a></li>
            <li><a href="#services">Services</a></li>
        
            <li><a href="#doctors">Reseler</a></li>
            <li><a href="#stats">Stats</a></li>
            <li><a href="adm/">Login</a></li>
            <li><a href="register/">Register</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        

      </div>

    </div>
    <style>
        #blink {
            font-size: 20px;
            font-weight: bold;
            font-family: sans-serif;
        }
    </style>

  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section light-background">

     

      <div class="container position-relative">

        <div class="welcome position-relative" data-aos="fade-down" data-aos-delay="100">
          <h2>WELCOME TO AzeGsm AUTH TOOL</h2>
          <p>We are AzeGsm XIAOMI AUTH ,Strong Like AzeGsm</p>
        </div><!-- End Welcome -->

        <div class="content row gy-4">
          <div class="col-lg-4 d-flex align-items-stretch">
            <div class="why-box" data-aos="zoom-out" data-aos-delay="200">
                
           <h3> <p id="blink"> ALLERT </p></h3> 
        <p>
         FLASH LIMITATION ON OFF WITHOUT NOTIFICATION JUST SEE ON TOOL SERVER FLASH ONLINE]
              </p>
             
             

            </div>
          </div><!-- End Why Box -->

          <div class="col-lg-8 d-flex align-items-stretch">
            <div class="d-flex flex-column justify-content-center">
              <div class="row gy-4">

                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="300">
                    <i class="bi bi-clipboard-data"></i>
                    <h4><p id="blink"> SERVER QCOM / MTK RODIN STATUS </p></h4>
                    <p><?php echo $serverflash; ?></p>
                  </div>
                </div><!-- End Icon Box -->
                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="300">
                    <i class="bi bi-clipboard-data"></i>
                    <h4><p id="blink"> SERVER MTK 6 OLD STATUS </p></h4>
                    <p><?php echo "OFF" ; ?></p>
                  </div>
                </div><!-- End Icon Box -->
                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="400">
                    <i class="bi bi-gem"></i>
                    <h4>SERVER FRP</h4>
                    <p><?php echo $serverfrp; ?></p>
                  </div>
                </div><!-- End Icon Box -->

                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="500">
                    <i class="bi bi-inboxes"></i>
                    <h4>SERVER FDL</h4>
                    <p><?php echo $serverfdl; ?></p>
                  </div>
                </div><!-- End Icon Box -->

              </div>
            </div>
          </div>
        </div><!-- End  Content-->

      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
 
    <!-- Stats Section -->
    
  
  <section id="free" class="stats section light-background">
       <div class="container section-title" data-aos="fade-up">
        <h2>Free Services</h2>
      
      </div>
      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fa-solid fa-user-doctor"></i>
            <div class="stats-item">
                 <?php if ($hari == 'Friday' ) {?> 
                <?php if ($sisa>0) {?> 
                  <h3>ON</h3>
                    <?php } ?>
                    <?php if ($sisa==0) {?> 
                  <h3>OFF</h3>
                    <?php } ?> 
                     <?php } else { echo "<h3>OFF</h3>";  }  ?>
              <h1 >FREE FRP FDL</h1>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fa-regular fa-hospital"></i>
            <div class="stats-item">
                
                
                    <?php if ($hari == 'Friday' ) {?>   
            <h3><?php echo $sisa?></h3>
             <?php } else { ?> 
              <h3>0</h3>
            
               <?php } ?> 
            
            <h1 >LEFT FREE</h1>
            
            
            
            
            </div>
          </div><!-- End Stats Item -->




        </div>

      </div>

    </section><!-- /Stats Section -->
    <!-- Services Section -->
    <section id="services" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Services</h2>
      
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item  position-relative">
              <div class="icon">
                <img src="assets/img/mi.ico" alt="Italian Trulli">
                
              </div>
              <a href="https://drive.google.com/file/d/1sTQ1Ydz2qNwnSTHjg-X_0vVXa4ixY4Co/view?usp=drive_link" class="stretched-link">
                <h3>Qualcomm Flash</h3>
              </a>
              <p>Flash Xiaomi Qcom Device Using Miflash.</p>
              <p>Zip Pass 1</p>
              <p>Price <?php echo $hargaflash; ?> </p>
              <p>DOWNLOAD</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item position-relative">
              <div class="icon">
                    <img src="assets/img/v6.png" alt="Italian Trulli">
              </div>
              <a href="https://drive.google.com/file/d/1R3FKsPGhuFcfN7Tvt_Tf4QP2OxEzRKMu/view?usp=drive_link" class="stretched-link">
                <h3>MTK 6 FLASH</h3>
              </a>
                          <p>Zip Pass 1</p>
              <p>Price <?php echo $hargaflash; ?> </p>
              <p>DOWNLOAD</p>
              <p>Flash MTK v6 Devices Using SPFT v6.</p>
              <p>DOWNLOAD</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative">
              <div class="icon">
               <img src="assets/img/v5.png" alt="Italian Trulli">
              </div>
              <a href="#" class="stretched-link">
                <h3>MTK v5</h3>
              </a>
                          <p>Zip Pass 1</p>
              <p>Price <?php echo $hargamtk5; ?> </p>
              <p>DOWNLOAD</p>
              <p>Flash MTK v5 Devices Using SPFT v5</p>
              <p>DOWNLOAD</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item position-relative">
              <div class="icon">
                <img src="assets/img/assistant.png" alt="Italian Trulli">
              </div>
              <a href="https://drive.google.com/file/d/1cedLGoIGONNo-Tmx_Fm_smP_lGFWm1AZ/view?usp=drive_link" class="stretched-link">
                <h3>FRP RECOVERY</h3>
              </a>
              <p>Erase recovery Mi Assistant Mode</p>
                          <p>Zip Pass 1</p>
              <p>Price <?php echo $hargafrp; ?> </p>
          
             <p>DOWNLOAD</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
            <div class="service-item position-relative">
              <div class="icon">
                 <img src="assets/img/fastboot.jpg" alt="Italian Trulli">
              </div>
              <a href="https://drive.google.com/file/d/1cedLGoIGONNo-Tmx_Fm_smP_lGFWm1AZ/view?usp=drive_link" class="stretched-link">
                <h3>FASTBOOT EDL</h3>
                 <p>Fastboot To Edl Service </p>
                <p>Zip Pass 1</p>
              <p>Price <?php echo $hargafdl; ?> </p>
      
                 <p>DOWNLOAD</p>
              </a>
            
             
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="fas fa-notes-medical"></i>
              </div>
              <a href="https://drive.google.com/file/d/1r92SpYH_8WSe79n9sRXDGiIGghjGXBab/view?usp=drive_link" class="stretched-link">
                <h3>ALL IN TOOL</h3>
              </a>
              <p>ALL IN TOOL , Can Flash MTK v5,  v6, QCOM , OLD and NEW MODEL</p>
               <p>Zip Pass 1</p>
              <p>DOWNLOAD</p>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Services Section -->





  <section id="doctors" class="doctors section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Reseller</h2>
        <p>Reseler</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="team-member d-flex align-items-start">
              <div class="pic"><img src="assets/img/garuda.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>Garuda Unlock</h4>
                <span>World Wide Reseller</span>
                <p>Contact</p>
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""> <i class="bi bi-linkedin"></i> </a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="team-member d-flex align-items-start">
              <div class="pic"><img src="assets/img/ahmad.jpg" class="img-fluid" alt=""></div>
              <div class="member-info">
                <h4>Ahmad Service Center</h4>
                <span>Indonesian Resseler</span>
                <p>Contacts</p>
                <div class="social">
                  <a href="https://wa.me/6287831977375"><i class="bi bi-whatsapp"></i></a>
                  <a href="https://www.facebook.com/ahmadnurkabib/"><i class="bi bi-facebook"></i></a>
                  <a href="https://t.me/ascfiles"><i class="bi bi-telegram"></i></a>
                  <a href="https://shop.ahmadservicecenter.com"> <i class="bi bi-site"></i> </a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

           <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="team-member d-flex align-items-start">
              <div class="pic"><img src="assets/img/halab.png" class="img-fluid" alt=""></div>
                <h4>HALAB TECH</h4>
                <span>World Wide Reseller</span>
                <p>Contact</p>
                <div class="social">
                  <a href="https://server.halabtech.com/contactus"><i class="bi bi-whatsapp"></i></a>
                 
                  <a href="https://telegram.me/htsupport"><i class="bi bi-telegram"></i></a>
                 
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

        

        </div>

      </div>

    </section><!-- /Doctors Section -->







    <!-- Appointment Section -->
  <section id="stats" class="stats section light-background">
      <div class="container section-title" data-aos="fade-up">
        <h2>Stats</h2>
      
      </div>
      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fa-solid fa-user-doctor"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end='<?php echo $flash?>' data-purecounter-duration="1" class="purecounter"></span>
              <p>Success Flash Today</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fa-regular fa-hospital"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="<?php echo $frp ?>" data-purecounter-duration="1" class="purecounter"></span>
              <p>Success FRP Today</p>
            </div>
          </div><!-- End Stats Item -->



          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fas fa-flask"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="<?php echo $fdl ?>" data-purecounter-duration="1" class="purecounter"></span>
              <p>Success FDL Today</p>
            </div>
          </div><!-- End Stats Item -->
          
          
          <div></div>
          
          
          
                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fa-solid fa-user-doctor"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end='<?php echo $allflash?>' data-purecounter-duration="1" class="purecounter"></span>
              <p>ALL Success Flash</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fa-regular fa-hospital"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="<?php echo $allfrp ?>" data-purecounter-duration="1" class="purecounter"></span>
              <p>ALL Success FRP</p>
            </div>
          </div><!-- End Stats Item -->



          <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fas fa-flask"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="<?php echo $allfdl ?>" data-purecounter-duration="1" class="purecounter"></span>
              <p>ALL Success FDL</p>
            </div>
          </div><!-- End Stats Item -->
          
          
          <div></div>
          
          
          
          
          
          
          
        <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fas fa-flask"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="<?php echo $failflash ?>" data-purecounter-duration="1" class="purecounter"></span>
        <p>Fail Flash Today</p>
            </div>
          </div><!-- End Stats Item -->  
          
                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fas fa-flask"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="<?php echo $failfrp ?>" data-purecounter-duration="1" class="purecounter"></span>
    <p>Fail FRP Today</p>
            </div>
          </div><!-- End Stats Item -->
          
         <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fa-solid fa-user-doctor"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end='<?php echo $failfdl?>' data-purecounter-duration="1" class="purecounter"></span>
              <p>Fail FDL Today</p>
            </div>
          </div><!-- End Stats Item -->
          
           <div></div>
          
            <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fas fa-flask"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="<?php echo $allfailflash ?>" data-purecounter-duration="1" class="purecounter"></span>
        <p>ALL Fail Flash</p>
            </div>
          </div><!-- End Stats Item -->  
          
                <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fas fa-flask"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end="<?php echo $allfailfrp ?>" data-purecounter-duration="1" class="purecounter"></span>
    <p>ALL Fail FRP</p>
            </div>
          </div><!-- End Stats Item -->
          
         <div class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
            <i class="fa-solid fa-user-doctor"></i>
            <div class="stats-item">
              <span data-purecounter-start="0" data-purecounter-end='<?php echo $allfailfdl?>' data-purecounter-duration="1" class="purecounter"></span>
              <p> ALL Fail FDL</p>
            </div>
          </div><!-- End Stats Item -->
          

        </div>

      </div>

    </section><!-- /Stats Section -->
    
    
    <!-- Departments Section -->

    <!-- Doctors Section -->
  


    <!-- Gallery Section -->
    <section id="gallery" class="gallery section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Gallery</h2>
        <p>Screen Shoot Tool </p>
      </div><!-- End Section Title -->

      <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-0">

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/pic1.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/pic1.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/fbedl.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/fbedl.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/qcom.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/qcom.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/miflashqcom.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/miflashqcom.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/spftv6.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/spftv6.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/spftv5.png" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/spftv5.png" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
                  <a href="assets/img/gallery/frp.jpg" class="glightbox" data-gallery="images-gallery">
                   <img src="assets/img/gallery/frp.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/frp2.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/frp2.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

        </div>

      </div>

    </section><!-- /Gallery Section -->

    <!-- Contact Section -->
  
  </main>

  <footer id="footer" class="footer light-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">AzeGsm Auth</span>
          </a>
          <div class="footer-contact pt-3">
            <p>A108 Adam Street</p>
            <p>New York, NY 535022</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+</span></p>
            <p><strong>Email:</strong> <span>info@example.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href="https://t.me/+TsPtw"><i class="bi bi-telegram"></i></a>
            <a href="https://chat.whatsapp.com/JxZj2jY9"><i class="bi bi-whatsapp"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="#">Home</a></li>
       
            <li><a href="#">Services</a></li>
        
          </ul>
        </div>

        

        <


      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">AzeGsm Auth</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
      <!--   Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> Distributed by <a href=“https://themewagon.com>ThemeWagon -->
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>
  <script type="text/javascript">
        let blink =
            document.getElementById('blink');

        setInterval(function () {
            blink.style.opacity =
                (blink.style.opacity == 0 ? 1 : 0);
        }, 1000); 
    </script>
  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>