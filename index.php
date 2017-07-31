<?php 
session_start();
require('php/config.php');
 
if(isset($_GET['login'])) {
 $benutzername = $_POST['benutzername'];
 $passwort = $_POST['passwort'];
 
 $stmt = $pdo->prepare("SELECT * FROM users WHERE benutzername = :benutzername");
 $stmt->bindParam(':benutzername', $benutzername, PDO::PARAM_STR,12);
 $stmt->execute();
 $user = $stmt->fetch();
 
 //Überprüfung des Passworts
 if ($user !== false && password_verify($passwort, $user['passwort'])) {
 $_SESSION['userid'] = $user['id'];
  $_SESSION['accessLevel'] = $user['level'];
 $stmt2 = $pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = :id");
 $stmt2->bindParam(':id', $user['id'], PDO::PARAM_INT);
 $stmt2->execute();

 header("Location: workbench"); /* Redirect browser */
exit();
 } else {
 $errorMessage = "Benutzername oder Passwort war ungültig.";
 }
 
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <title>Digitale Ausbreitungsabschätzung Luftgetragener Schadstoffe (DALUS)</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="css/font-awesome.min.css">
	<link href="css/signin.css" rel="stylesheet">
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
	<div class="container-fluid text-center">
		<div class="row">
				<img src="images/dalus_logo.svg" class="img-responsive dalusLogo" alt="DALUS Logo">
			<h1 class="center-block">Digitale Ausbreitungsabschätzung Luftgetragener Schadstoffe</h1>
		</div>
		<br/><br/><br/>
		
		<form class="form-signin panel panel-default" action="?login=1" method="post">
			<label for="inputName" class="sr-only">Benutzername</label>
			<div class="input-group" >
      			<div class="input-group-addon"><i class="fa fa-user-circle" aria-hidden="true"></i></div>
        		<input type="text" id="inputName" class="form-control" size="40" maxlength="20" name="benutzername" placeholder="Benutzername" autofocus><br />
        	</div>
        	<label for="inputPassword" class="sr-only">Passwort</label>
        	<div class="input-group">
      			<div class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></div>
        		<input type="password" id="inputPassword" size="40"  maxlength="20" name="passwort" class="form-control" placeholder="Password">
        	</div>
        	<button class="btn btn-lg btn-block loginButton" type="submit">Anmelden</button>
		</form>

		<?php 
		if(isset($errorMessage)) {
			echo '<div class="alert alert-danger alert-dismissible fade in col-xs-4 col-xs-push-4 alert-trim" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			<h4>' .$errorMessage .'</h4>
			<p>Bitte erneut versuchen</p> </div>';
			}
			?>
	</div>
</body>
</html>