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

	$stmt = $pdo->prepare("SELECT vorname, nachname, benutzername, id FROM users WHERE id = :userID");
	$stmt->bindParam(':userID', $userid, PDO::PARAM_INT);
	$stmt->execute();

	$benutzer = $stmt->fetch(PDO::FETCH_OBJ);//Daten des angemeldeten Benutzers abfragen

	$stmt2 = $pdo->prepare("SELECT * FROM options WHERE opt_userid = :userID");
	$stmt2->bindParam(':userID', $userid, PDO::PARAM_INT);
	$stmt2->execute();

	$optionen = $stmt2->fetch(PDO::FETCH_OBJ);//Daten des angemeldeten Benutzers abfragen

	$returnvalues = array('benutzer'=>$benutzer, 'accessLevel' => $accessLevel, 'optionen' => $optionen);
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
	echo json_encode($benutzer);
}


function editUser(){
	require('session.php');
	include("config.php");
	$username = (!empty($_POST['username']) ? $_POST['username']:'');
	$password1 = (!empty($_POST['password1']) ? $_POST['password1']:'');
	$password2 = (!empty($_POST['password2']) ? $_POST['password2']:'');
	$oldPassword = (!empty($_POST['oldPassword']) ? $_POST['oldPassword']:'');

	$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :userID");
	$stmt->bindParam(':userID', $userid, PDO::PARAM_INT);
 	$stmt->execute();
 	$user = $stmt->fetch();

 	if ($user !== false && password_verify($oldPassword, $user['passwort'])) {
		if(!empty($_POST['password1']) && !empty($_POST['password2'])){
			if( $password1 == $password2){
				$newPassword =  password_hash($password1, PASSWORD_DEFAULT);
 				$stmt2 = $pdo->prepare("UPDATE users SET benutzername = :username , passwort = :newPassword WHERE id = :userID");
				$stmt2->bindParam(':newPassword', $newPassword, PDO::PARAM_STR, 12);
				$stmt2->bindParam(':username', $username, PDO::PARAM_STR, 12);
				$stmt2->bindParam(':userID', $userid, PDO::PARAM_INT);
				$stmt2->execute();
				$return = 'Success';
			}
			else{$return = 'PasswordsDontMatch';}
		}
		else{
			$stmt3 = $pdo->prepare("UPDATE users SET benutzername = :username WHERE id = :userID");
			$stmt3->bindParam(':username', $username, PDO::PARAM_STR, 12);
			$stmt3->bindParam(':userID', $userid, PDO::PARAM_INT);
			$stmt3->execute();
			$return = 'Success';
		}
	}
	else{$return = 'WrongPassword';}
	echo json_encode($return);
}

function createUser(){
	include("config.php");
	require("session.php");
	$benutzername = (!empty($_POST['benutzername']) ? $_POST['benutzername']:'');
	$vorname = (!empty($_POST['vorname']) ? $_POST['vorname']:'');
	$nachname = (!empty($_POST['nachname']) ? $_POST['nachname']:'');
	$passwort = password_hash((!empty($_POST['benutzername']) ? $_POST['benutzername']:''), PASSWORD_DEFAULT);
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