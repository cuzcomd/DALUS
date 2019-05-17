<!DOCTYPE html>
<html lang="de">
<head>
	<title>Digitale Ausbreitungsabschätzung Luftgetragener Schadstoffe (DALUS)</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/plugins/jquery.min.js"></script>
	<script src="js/plugins/bootstrap.min.js"></script>
	<script src="js/plugins/alertify.min.js"></script>
	<link href="css/floating-labels.css" rel="stylesheet">
	<link href="css/alertify.bootstrap.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Graduate' rel='stylesheet' type='text/css'>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<link rel="apple-touch-icon" sizes="180x180" href="/dalus/images/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/dalus/images/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/dalus/images/favicon/favicon-16x16.png">
	<link rel="manifest" href="/dalus/images/favicon/manifest.json">
	<link rel="mask-icon" href="/dalus/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
	<link rel="shortcut icon" href="/dalus/images/favicon/favicon.ico">
	<meta name="msapplication-config" content="/dalus/images/favicon/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">
</head>
<body>
	<form class="form-signin" action="" method="post">
      <div class="text-center mb-4">
        <img class="mb-4" src="images/dalus_logo.svg" alt="" width="364" height="182">
        <h1 class="h3 mb-3 font-weight-normal">Installation</h1>
      </div>

      <div class="form-group">
      	<h4>Datenbank anlegen</h4>
       <div class="form-label-group">
        <input type="text" id="input-db-server" class="form-control" name="db-server" value="localhost" required>
        <label for="input-db-server">Servername</label>
      </div>

       <div class="form-label-group">
        <input type="text" id="input-db-user" class="form-control" name="db-user" required>
        <label for="input-db-user">Benutzername</label>
      </div>

      <div class="form-label-group">
        <input type="password" id="input-db-password" class="form-control" name="db-password" required>
        <label for="input-db-password">Passwort</label>
      </div>

      <div class="form-label-group">
        <input type="text" id="input-db-name" class="form-control" name="db-name" required>
        <label for="input-db-name">Datenbankname</label>
      </div>

      <div class="form-label-group">
        <input type="text" id="input-db-prefix" class="form-control" name="db-prefix"value="dalus_">
        <label for="input-db-prefix">Tabellenpräfix</label>
      </div>
  </div>

  <div class="form-group">
  	<h4>Administrator anlegen</h4>
	<div class="form-label-group">
        <input type="text" id="input-user" class="form-control" name="user" required>
        <label for="input-db-user">Benutzername</label>
    </div>
	<div class="form-label-group">
	    <input type="password" id="input-user-password" class="form-control" name="user-password" required>
	    <label for="input-user-password">Passwort</label>
	</div>
  </div>
      

      <button class="btn btn-lg btn-primary btn-block btn-login" type="submit">Installieren</button>
      <p class="mt-5 mb-3 text-muted text-center">Fachdienst ABC - Landeshauptstadt Magdburg</p>
    </form>
</body>
</html>