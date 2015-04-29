<?php
echo '<!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Dont actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <title>Somerleyton login</title>
  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="loginPost.php" method="post">
        <h2 class="form-signin-heading">Log in</h2>
        <label for="staffID" class="sr-only">Staff id</label>
        <input name="STAFFID" type="" id="staffID" class="form-control" placeholder="Staff id" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input name="PASSWORD" type="password" id="inputPassword" class="form-control" placeholder="Wachtwoord" required>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
      </form>


    </div>
   ';