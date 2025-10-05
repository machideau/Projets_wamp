<?php
  include '../php/db.php';
  if (isset($_POST['valider'])) {
    $nom = mysqli_real_escape_string($db, $_POST['nom']);
    $select = mysqli_query($db, "SELECT * FROM `among` WHERE nom = '$nom'") or die('query failed');

    if (mysqli_num_rows($select) > 0) {
      $row = mysqli_fetch_assoc($select);
      $_SESSION['idA'] = $row['id'];
      // $_SESSION['idA'] = 2;
      // echo $id;
      // $produit_update = $_SESSION['produit_update'];
      header('location: index.php');
    }else {
      header('location: ../index.php');
    }
  }
?>
<!DOCTYPE html>
<html lang="fr">
    
<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">

  <title>EPI_STORE</title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Poppins:400,600,700&display=swap" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="../css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="../css/responsive.css" rel="stylesheet" />
  <link rel="stylesheet" href="admin.css">
</head>

<body class="sub_page">
  <div class="hero_area">
    <!-- header section strats -->
    <div class="hero_bg_box">
      <div class="img-box">
        <!-- back-ground-image top -->
        <img src="../images/bg-2.jpg" alt="">
      </div>
    </div>

    <header class="header_section">
      <div class="header_top">
        <div class="container-fluid">
          <div class="contact_link-container">
            <a href="" class="contact_link1">
              <i class="fa fa-map-marker" aria-hidden="true"></i>
              <span>
                <!-- localisation -->
				        Agbalepedo
              </span>
            </a>
            <a href="" class="contact_link2">
              <i class="fa fa-phone" aria-hidden="true"></i>
              <span>
                Tel : +228 93 11 20 60
              </span>
            </a>
            <a href="mailto:epi_store@gmail.com" class="contact_link3">
              <i class="fa fa-envelope" aria-hidden="true"></i>
              <span>
                epi_store@gmail.com
              </span>
            </a>
          </div>
        </div>
      </div>
      <div class="header_bottom">
        <div class="container-fluid">
          <nav class="navbar navbar-expand-lg custom_nav-container">
            <a class="navbar-brand" href="">
              <span>
                EPI_STORE
              </span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class=""></span>
            </button>

            <div class="collapse navbar-collapse ml-auto" id="navbarSupportedContent">
              <ul class="navbar-nav  ">
                
              </ul>
            </div>
          </nav>
        </div>
      </div>
    </header>
    <!-- end header section -->
  </div>
  <br><br><br><br><br>
    
  <!-- start adminnistration section -->
  <div class="update-profile">
    <form action="" method="post">
    <div class="flex">
        <div class="inputBox">
            <input type="text" name="nom" placeholder="Enter" class="box">
        </div>
        <input type="submit" name="valider" value="ok" class="btn">
    </div>
  </div>
  
</form>
<br><br><br><br><br>
  <!-- end adminnistration section -->

  <!-- info section -->
  <section class="info_section ">
    <div class="container">
      <div class="row">
        <div class="col-md-3">
          <div class="info_logo">
            <a class="navbar-brand" href="">
              <span>
                EPI_STORE
              </span>
            </a>
            <p>
              Meilleurs et jolis plats, notre responsabilité
            </p>
          </div>
        </div>
        <!-- <div class="col-md-3">
          <div class="info_links">
            <h5>
              Menu
            </h5>
            <ul>
              <li>
                <a href="index.php">
                  Ajouter un produit
                </a>
              </li>
              <li>
                <a href="modifier.php">
                  Modifier un produit
                </a>
              </li>
              <li>
                <a href="supprimer.php">
                  Supprimer un produit
                </a>
              </li>
              <li>
                <a href="../index.php">
                  Voir le site
                </a>
              </li>
            </ul>
          </div>
        </div> -->
        <div class="col-md-3">
          <div class="info_info">
            <h5>
              Vous pouvez nous contacter par : 
            </h5>
          </div>
          <div class="info_contact">
            <a href="" class="">
              <i class="fa fa-map-marker" aria-hidden="true"></i>
              <span>
                Agbalepedo
              </span>
            </a>
            <a href="" class="">
              <i class="fa fa-phone" aria-hidden="true"></i>
              <span>
                Tel : +228 93 11 20 60
              </span>
            </a>
            <a href="mailto:epi_store@gmail.com" class="">
              <i class="fa fa-envelope" aria-hidden="true"></i>
              <span>
                epi_store@gmail.com
              </span>
            </a>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info_form ">
            <h5>
              Laissez votre email ici
            </h5>
            <form>
              <input type="email" class="mailPlace" placeholder="Enter votre email">
              <input type="submit" class="suscribe" value="Suscribe">
            </form>
            <div class="social_box">
              <a href="">
                <i class="fa fa-facebook" aria-hidden="true"></i>
              </a>
              <a href="">
                <i class="fa fa-whatsapp" aria-hidden="true"></i>
              </a>
              <a href="">
                <i class="fa fa-youtube" aria-hidden="true"></i>
              </a>
              <a href="">
                <i class="fa fa-instagram" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end info_section -->

  <!-- footer section -->
  <footer class="container-fluid footer_section">
    <p>
      &copy; <span id="currentYear"></span> All Rights Reserved. Design by
      <a href="https://php.design/">Free Html Templates</a>
    </p>
  </footer>
  <!-- footer section -->

  <script src="../js/jquery-3.4.1.min.js"></script>
  <script src="../js/bootstrap.js"></script>
  <script src="../js/custom.js"></script>
</body>
</html>