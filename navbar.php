<?php
/**
 * Created by PhpStorm.
 * User: thom
 * Date: 29-4-2015
 * Time: 12:25
 */
echo '    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="navbar-fixed-top.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- Just for debugging purposes. Dont actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <title>Someleyton animal park</title>
  </head>

  <body>

      <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Somerleyton Animal Park</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li>
            <li><a href="?page=medewerkers">Medewerkers</a></li>
            <li><a href="?page=findAnimal">Dieren</a></li>
            <li><a href="?page=species">Diersoorten</a></li>
            <li><a href="?page=environment">Verblijven</a></li>
            <li><a href="?page=feeding">Voeding</a></li>
            <li><a href="?page=orders">Orders</a></li>
            <li><a href="?page=Leverancier">Leveranciers</a></li>
            <li><a href="?page=items">Producten</a></li>
            </ul>
          <ul class="nav navbar-nav navbar-right">

            <li><a href="logOut.php" >Log uit</a> </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>';