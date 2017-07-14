<?php
if (is_ajax()) {
  if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
    $action = $_POST["action"];
    switch($action) { //Switch case for value of action
		case "create": createProject(); break;
		case "edit": editProject(); break;
		case "delete": deleteProject(); break;
		case "loadUser": loadUser(); break;
		case "editUser": editUser(); break;
		case "load": loadProject(); break;
		case "updateProjects": updateProjects(); break;
		case "updateSharedProjects": updateSharedProjects(); break;
		case "isSharedWith": isSharedWith(); break;
    }
  }
}

//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function createProject(){
	include("config.php");
	$project_owner = (!empty($_POST['username']) ? $_POST['username']:'');
	$project_shared = (!empty($_POST['shared']) ? implode(",", $_POST['shared']):''); //Erzeugt kommaseparierten String aus Array von ausgewählten Benutzernamen
	$project_name = (!empty($_POST['projekttitel']) ? $_POST['projekttitel']:'');


	$stmt = $pdo->prepare("INSERT INTO projects (prj_owner, prj_shared, prj_name, prj_created_at, prj_updated_at) VALUES (:project_owner, :project_shared, :project_name, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE prj_updated_at = CURRENT_TIMESTAMP");
	$stmt->bindParam(':project_owner', $project_owner, PDO::PARAM_INT);
	$stmt->bindParam(':project_shared', $project_shared, PDO::PARAM_STR, 12);
	$stmt->bindParam(':project_name', $project_name, PDO::PARAM_STR, 12);
	$stmt->execute();
	
	$projekt_id = $pdo->lastInsertId();

	$returnvalues = array('projekttitel' => $project_name, 'projekt_id' => $projekt_id);
  	echo json_encode($returnvalues);
  }

function editProject(){
	include("config.php");
	$project_shared = (!empty($_POST['shared']) ? implode(",", $_POST['shared']):''); //Erzeugt kommaseparierten String aus Array von ausgewählten Benutzernamen
	$project_name = (!empty($_POST['projekttitel']) ? $_POST['projekttitel']:'');
	$project_id = (!empty($_POST['current_project_id']) ? $_POST['current_project_id']:'0');
	
	$stmt = $pdo->prepare("UPDATE projects SET prj_shared = :project_shared, prj_name = :project_name, prj_updated_at = CURRENT_TIMESTAMP WHERE prj_id = :project_id");
	$stmt->bindParam(':project_shared', $project_shared, PDO::PARAM_STR, 12);
	$stmt->bindParam(':project_name', $project_name, PDO::PARAM_STR, 12);
	$stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
	$stmt->execute();

	$_POST["json"] = json_encode($_POST);
	echo json_encode($_POST);
}

function deleteProject(){
	include("config.php");
	$project_id = (!empty($_POST['prj_id']) ? $_POST['prj_id']:'0');

	$stmt = $pdo->prepare("DELETE FROM projects WHERE prj_id = :project_id");
	$stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
	$stmt->execute();

	$stmt2 = $pdo->prepare("DELETE FROM objects WHERE obj_prj_id = :project_id");
	$stmt2->bindParam(':project_id', $project_id, PDO::PARAM_INT);
	$stmt2->execute();

		$_POST["json"] = json_encode($_POST);
	echo json_encode($_POST);
}

function loadUser(){
require('session.php');
include("config.php");

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :userID");
$stmt->bindParam(':userID', $userid, PDO::PARAM_INT);
$stmt->execute();

$benutzer = $stmt->fetch(PDO::FETCH_OBJ);//Daten des angemeldeten Benutzers abfragen
$benutzerID = $userid;
$benutzername = $benutzer->benutzername;
$vorname = $benutzer->vorname;
$nachname = $benutzer->nachname;

$returnvalues = array('benutzerID' => $benutzerID,'benutzername' => $benutzername, 'vorname' => $vorname, 'nachname' => $nachname);
  	echo json_encode($returnvalues);
}

function loadProject(){
include("config.php");

$projectName = (!empty($_POST['project_open']) ? $_POST['project_open']:'');

$stmt = $pdo->prepare("SELECT * FROM projects WHERE prj_name = :prjName");
$stmt->bindParam(':prjName', $projectName, PDO::PARAM_STR, 12);
$stmt->execute();

$projekt = $stmt->fetch(PDO::FETCH_OBJ);//Daten des angemeldeten Benutzers abfragen
$projektID = $projekt->prj_id;
$projektName = $projekt->prj_name;

$stmt2 = $pdo->prepare("SELECT MAX(obj_nummer) as maxNummer FROM objects WHERE obj_prj_id = :prjID AND obj_typ = 'marker'");
$stmt2->bindParam(':prjID', $projektID, PDO::PARAM_INT);
$stmt2->execute();
$max = $stmt2->fetch(PDO::FETCH_ASSOC);
$maxNumber = $max['maxNummer'];

$returnvalues = array('projektID' => $projektID,'projektName' => $projektName, 'maxNum' => $maxNumber);
  	echo json_encode($returnvalues);
}

function updateProjects(){
	require('session.php');
	include("config.php");
	$stmt = $pdo->prepare("SELECT * FROM projects WHERE prj_owner = :prjOwner");
	$stmt->bindParam(':prjOwner', $userid, PDO::PARAM_STR, 12);
	$stmt->execute();

	$projekte = $stmt->fetchAll();
	echo json_encode($projekte);
}

function updateSharedProjects(){
	require('session.php');
	include("config.php");
	$stmt = $pdo->prepare("SELECT * FROM projects WHERE prj_shared LIKE '%".$userid."%'");
	$stmt->execute();

	$projekte = $stmt->fetchAll();
	echo json_encode($projekte);
}

function isSharedWith(){
	require('session.php');
	include("config.php");
	
	$projectID = (!empty($_POST['projectID']) ? $_POST['projectID']:'0');
	if ($projectID != '0') {
		$stmt = $pdo->prepare("SELECT * FROM projects WHERE prj_id = :projectID");
		$stmt->bindParam(':projectID', $projectID, PDO::PARAM_INT);
		$stmt->execute();

		foreach ($stmt as $benutzer) {
			$user = explode(",", $benutzer['prj_shared']);
		}

	$stmt2 = $pdo->prepare("SELECT * FROM users ");
	$stmt2->bindParam(':userID', $userid, PDO::PARAM_INT);
	$stmt2->execute();
	$result = $stmt2->fetchAll();

	$sharedUsers = array();
	foreach ($result as $nutzer) {
		$usersObj  = new stdClass;
		$usersObj->username = $nutzer['benutzername'];
		$usersObj->id = $nutzer['id'];
		if (in_array($nutzer['id'], $user)){
			$usersObj->shared = "yes";
		}
		else{
			$usersObj->shared = "no";
		}
		array_push($sharedUsers,$usersObj);
	}
	}
	else{
		$sharedUsers = array();
	}
	echo json_encode($sharedUsers);
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
			$return = 'Erfolg';
		}
	}
	else{$return = 'WrongPassword';}
	echo json_encode($return);
}
?>