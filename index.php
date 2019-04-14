<?php
session_start();
session_regenerate_id();
require('php/config.php');

if(isset($_GET['login'])) { // Überprüft, ob das Loginformular abgeschickt wurde
	$benutzername = filter_var($_POST['benutzername'], FILTER_SANITIZE_STRING); // Filtert den Benutzernamen von Schadcode
	$passwort = $_POST['passwort'];

	$stmt = $pdo->prepare("SELECT id, level, passwort  FROM users WHERE benutzername = :benutzername"); // Lädt die Daten des registrierten Benutzers
	$stmt->bindParam(':benutzername', $benutzername, PDO::PARAM_STR);
	$stmt->execute();
	$user = $stmt->fetch();

	//Überprüfung des Passworts
	if ($user !== false && password_verify($passwort, $user['passwort'])) {
		$_SESSION['userid'] = $user['id']; // User ID
		$_SESSION['accessLevel'] = $user['level']; //Zugriffslevel

		$stmt = $pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = :id"); // Speichert Loginzeit in der Datenbank
		$stmt->bindParam(':id', $_SESSION['userid'], PDO::PARAM_INT);
		$stmt->execute();

		$stmt = $pdo->prepare("SELECT opt_city FROM options WHERE opt_UID = :id AND opt_city > ''");
		$stmt->bindParam(':id', $_SESSION['userid'], PDO::PARAM_INT);
		$stmt->execute();
		$options = $stmt->fetch();
		$_SESSION['city'] = $options['opt_city']; // Gespeicherten Kartenmittelpunkt einlesen

		$stmt = $pdo->prepare("SELECT opt_OWMAPI, opt_googleAPI FROM options WHERE opt_UID = '0'");
		$stmt->execute();
		$apifetch = $stmt->fetch();
		$_SESSION['OWMAPIkey'] = $apifetch['opt_OWMAPI'];
		$_SESSION['googleAPIkey'] = $apifetch['opt_googleAPI'];

		if(!$options)
			{
				$_SESSION['city'] = "Berlin"; //Falls keine Stadt hinterlegt ist, wird Berlin als Standardwert genutzt.
			}

		header("Location: workbench"); /* Browser auf die Workbench umleiten */
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
	<form class="form-signin" action="?login=1" method="post">
      <div class="text-center mb-4">
        <img class="mb-4" src="images/dalus_logo.svg" alt="" width="364" height="182">
        <h1 class="h3 mb-3 font-weight-normal">Digitale Ausbreitungsabschätzung Luftgetragener Schadstoffe</h1>
      </div>

      <div class="form-label-group">
        <input type=text" id="inputName" class="form-control" name="benutzername" placeholder="Benutzername" required autofocus>
        <label for="inputName">Benutzername</label>
      </div>

      <div class="form-label-group">
        <input type="password" id="inputPassword" class="form-control" name="passwort" placeholder="Passwort" required>
        <label for="inputPassword">Passwort</label>
      </div>

      <button class="btn btn-lg btn-primary btn-block btn-login" type="submit">Anmelden</button>
      <p class="mt-5 mb-3 text-muted text-center">Fachdienst ABC - Landeshauptstadt Magdburg</p>
    </form>
    <div>
	<?php 
		if(isset($errorMessage)) {
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			<h4 class="alert-heading">' .$errorMessage .'</h4>
			<hr>
			<p class="mb-0">Bitte erneut versuchen</p> </div>';
		}
	?>
</div>
</body>
</html>