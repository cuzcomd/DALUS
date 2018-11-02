<?php
if (is_ajax()) {
  if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
    $action = $_POST["action"];
    switch($action) { //Switch case for value of action
		case "loadUser": loadUser(); break;
		case "editUser": editUser(); break;
		case "createUser": createUser(); break;
		case "deleteUser": deleteUser(); break;
		case "updateAllUsers": updateAllUsers(); break;
    }
  }
}

//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function loadUser(){
	require('session.php');
	include("config.php");

	$stmt = $pdo->prepare("SELECT vorname, nachname, benutzername FROM users WHERE id = :userID");
	$stmt->bindParam(':userID', $_SESSION['userid'], PDO::PARAM_INT);
	$stmt->execute();
	$benutzer = $stmt->fetch(PDO::FETCH_OBJ);//Daten des angemeldeten Benutzers abfragen

	$stmt2 = $pdo->prepare("SELECT opt_city FROM options WHERE opt_UID = :userID AND opt_city > ''");
	$stmt2->bindParam(':userID', $_SESSION['userid'], PDO::PARAM_INT);
	$stmt2->execute();
	$optionen = $stmt2->fetch(PDO::FETCH_OBJ);//Optionen des angemeldeten Benutzers abfragen

	if(!$optionen) // Prüft, ob Stadt in den persönlichen Benutzereinstellungen hinterlegt war. Falls nicht wird die globale Konfiguration geladen
	{
		$stmt3 = $pdo->prepare("SELECT opt_city FROM options WHERE opt_UID = '0'");
		$stmt3->execute();
		$optionen = $stmt3->fetch(PDO::FETCH_OBJ);
	}

	$stmt4 = $pdo->prepare("SELECT opt_OWMAPI FROM options WHERE opt_UID = '0'");
	$stmt4->execute();
	$owmapi = $stmt4->fetch(PDO::FETCH_OBJ);//Openweathermap API-key aus globaler Konfiguration laden

	$returnvalues = array('benutzer'=>$benutzer, 'accessLevel' => $accessLevel, 'optionen' => $optionen, 'owmapi' => $owmapi);
	  	echo json_encode($returnvalues);
}

function deleteUser(){
	require('session.php');
	include("config.php");
	$users = (!empty($_POST['users']) ? $_POST['users'] :'');

	if ($accessLevel == "admin")
	{
		$stmt = $pdo->prepare("DELETE FROM users WHERE id = :userID");
		foreach ($users as $value) {
			$stmt->bindParam(':userID', $value, PDO::PARAM_INT);
			$stmt->execute();
		}
		$return = "ok";
	}
	else {
		$return = "noAdmin";
	}
	echo json_encode($return);
}



function updateAllUsers(){
	require('session.php');
	include("config.php");
	$stmt = $pdo->prepare("SELECT * FROM users");
	$stmt->execute();
	$benutzer = $stmt->fetchAll();

	$stmt2 = $pdo->prepare("SELECT * FROM users WHERE NOT id = :UID");
	$stmt2->bindParam(':UID', $_SESSION['userid'], PDO::PARAM_INT);
	$stmt2->execute();
	$benutzerom = $stmt2->fetchAll();
	
	echo json_encode(array('benutzer'=>$benutzer, 'benutzerom' => $benutzerom));
}


function editUser(){
	require('session.php');
	include("config.php");
	$username = (!empty($_POST['username']) ? $_POST['username']:'');
	$newPassword = (!empty($_POST['newPassword']) ? $_POST['newPassword']:'');
	$owmcity = (!empty($_POST['owmcity']) ? $_POST['owmcity']:'Magdeburg');

	$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :userID");
	$stmt->bindParam(':userID', $_SESSION['userid'], PDO::PARAM_INT);
 	$stmt->execute();
 	$user = $stmt->fetch();

 	if ($user !== false) {
		if(!empty($_POST['newPassword']))
		{
			$newPasswordHash =  password_hash($newPassword, PASSWORD_BCRYPT);
			$stmt2 = $pdo->prepare("UPDATE users SET benutzername = :username , passwort = :newPasswordHash WHERE id = :userID");
			$stmt2->bindParam(':newPasswordHash', $newPasswordHash, PDO::PARAM_STR);
			$stmt2->bindParam(':username', $username, PDO::PARAM_STR, 12);
			$stmt2->bindParam(':userID', $_SESSION['userid'], PDO::PARAM_INT);
			$stmt2->execute();
			$return = 'Success';
			
		}
		else
		{
			$stmt3 = $pdo->prepare("UPDATE users SET benutzername = :username WHERE id = :userID");
			$stmt3->bindParam(':username', $username, PDO::PARAM_STR, 12);
			$stmt3->bindParam(':userID', $_SESSION['userid'], PDO::PARAM_INT);
			$stmt3->execute();
			$return = 'Success';
		}

		$stmt4 = $pdo->prepare("UPDATE options SET opt_city = :owmcity WHERE opt_UID = :userID");
			$stmt4->bindParam(':owmcity', $owmcity, PDO::PARAM_STR, 12);
			$stmt4->bindParam(':userID', $_SESSION['userid'], PDO::PARAM_INT);
			$stmt4->execute();
	}
	echo json_encode($return);
}

function createUser(){
	include("config.php");
	require("session.php");
	$benutzername = (!empty($_POST['benutzername']) ? $_POST['benutzername']:'');
	$vorname = (!empty($_POST['vorname']) ? $_POST['vorname']:'');
	$nachname = (!empty($_POST['nachname']) ? $_POST['nachname']:'');
	$passwort = password_hash((!empty($_POST['benutzername']) ? $_POST['benutzername']:''), PASSWORD_BCRYPT);
	$level = (!empty($_POST['level']) ? $_POST['level']:'');
	
	if ($accessLevel == "admin")
	{
		$stmt = $pdo->prepare("INSERT IGNORE INTO users (benutzername, passwort, level, vorname, nachname, created_at) VALUES (:benutzername, :passwort, :level, :vorname, :nachname, CURRENT_TIMESTAMP)");
		$stmt->bindParam(':benutzername', $benutzername, PDO::PARAM_STR, 12);
		$stmt->bindParam(':vorname', $vorname, PDO::PARAM_STR, 12);
		$stmt->bindParam(':nachname', $nachname, PDO::PARAM_STR, 12);
		$stmt->bindParam(':passwort', $passwort, PDO::PARAM_STR, 12);
		$stmt->bindParam(':level', $level, PDO::PARAM_STR, 12);
		$stmt->execute();

		if($stmt->rowCount() > 0)
		{
			$return = 'yes';
		}
		else
		{
			$return = 'no';
		}
	}
	else{
	  $return = 'noAdmin';
	}
	echo json_encode($return);
  }
?>