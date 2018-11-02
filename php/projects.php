<?php
if (is_ajax()) {
  if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
    $action = $_POST["action"];
    switch($action) { //Switch case for value of action
		case "create": createProject(); break;
		case "edit": editProject(); break;
		case "delete": deleteProject(); break;
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

function createProject(){ //Erzeugt eine neues Projekt
	include("config.php");
	include("session.php");
	$project_shared = (!empty($_POST['shared']) ? implode(",", $_POST['shared']):''); //Erzeugt kommaseparierten String aus Array von ausgewählten Benutzernamen
	$project_name = (!empty($_POST['projekttitel']) ? $_POST['projekttitel']:'');


	$stmt = $pdo->prepare("INSERT INTO projects (prj_owner, prj_shared, prj_name, prj_created_at, prj_updated_at) VALUES (:project_owner, :project_shared, :project_name, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE prj_updated_at = CURRENT_TIMESTAMP");
	$stmt->bindParam(':project_owner', $_SESSION['userid'], PDO::PARAM_INT);
	$stmt->bindParam(':project_shared', $project_shared, PDO::PARAM_STR);
	$stmt->bindParam(':project_name', $project_name, PDO::PARAM_STR);
	$stmt->execute();
	
	$projekt_id = $pdo->lastInsertId();

	$returnvalues = array('projekttitel' => $project_name, 'projekt_id' => $projekt_id);
  	echo json_encode($returnvalues);
  }

function editProject(){ //Aktualisiert das aktuell geöffnete Projekt
	include("config.php");
	include("session.php");
	$project_shared = (!empty($_POST['shared']) ? implode(",", $_POST['shared']):''); //Erzeugt kommaseparierten String aus Array von ausgewählten Benutzernamen
	$project_name = (!empty($_POST['projekttitel']) ? $_POST['projekttitel']:'');
	$project_id = (!empty($_POST['current_project_id']) ? $_POST['current_project_id']:'0');

	$stmt = $pdo->prepare("SELECT * FROM projects WHERE prj_id = :project_id");
	$stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
	$stmt->execute();
	$projekt = $stmt->fetch(PDO::FETCH_OBJ);
		
	if($_SESSION['userid'] == $projekt->prj_owner) // Prüft, ob der angemeldete Benutzer Besitzer des Projekts ist.
	{
		$stmt = $pdo->prepare("UPDATE projects SET prj_shared = :project_shared, prj_name = :project_name, prj_updated_at = CURRENT_TIMESTAMP WHERE prj_id = :project_id");
		$stmt->bindParam(':project_shared', $project_shared, PDO::PARAM_STR);
		$stmt->bindParam(':project_name', $project_name, PDO::PARAM_STR);
		$stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
		$status = $stmt->execute();
		echo json_encode($_POST);
	}
	else
	{
		echo json_encode('error');
	}	
}

function deleteProject(){ //Löscht dads aktuell geöffnete Projekt
	include("config.php");
	include("session.php");
	$project_id = (!empty($_POST['prj_id']) ? $_POST['prj_id']:'0');

	$stmt = $pdo->prepare("SELECT * FROM projects WHERE prj_id = :project_id");
	$stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
	$stmt->execute();
	$projekt = $stmt->fetch(PDO::FETCH_OBJ);

	if($_SESSION['userid'] == $projekt->prj_owner)
	{
		$stmt = $pdo->prepare("DELETE FROM projects WHERE prj_id = :project_id");
		$stmt->bindParam(':project_id', $project_id, PDO::PARAM_INT);
		$stmt->execute();
		
		$stmt2 = $pdo->prepare("DELETE FROM objects WHERE obj_prj_id = :project_id");
		$stmt2->bindParam(':project_id', $project_id, PDO::PARAM_INT);
		$stmt2->execute();
		echo json_encode('success');
	}
	else{
		echo json_encode('error');
	}
}

function loadProject(){ //noch zu prüfen,ob Sicherheit gegeben ist
	include("config.php");
	include("session.php");

	$projectName = (!empty($_POST['project_open']) ? $_POST['project_open']:'');

	$stmt = $pdo->prepare("SELECT * FROM projects WHERE prj_name = :prjName");
	$stmt->bindParam(':prjName', $projectName, PDO::PARAM_STR);
	$stmt->execute();
	$projekt = $stmt->fetch(PDO::FETCH_OBJ);//Daten des angemeldeten Benutzers abfragen

	if($_SESSION['userid'] == $projekt->prj_owner || in_array($_SESSION['userid'],explode(',',$projekt->prj_shared))) // Prüft, ob der angemeldete Benutzer Besitzer des Projekts ist oder das Projekt für ihn freigegeben wurde.
	{
		$project_level = ($_SESSION['userid'] == $projekt->prj_owner) ? 'own' :'shared';

		$stmt = $pdo->prepare("SELECT MAX(obj_nummer) as maxNummer FROM objects WHERE obj_prj_id = :prjID AND obj_typ = 'marker'");
		$stmt->bindParam(':prjID', $projektID, PDO::PARAM_INT);
		$stmt->execute();
		$max = $stmt->fetch(PDO::FETCH_ASSOC);
		$maxNumber = $max['maxNummer'];

		if (is_null($maxNumber)) // Überprüft, ob bereits Messpunkte gespeichert sind und legt den Zähler anderenfalls auf 0 fest
		{
			$maxNumber = 0;
		}
		$returnvalues = array('projektID' => $projekt->prj_id,'projektName' => $projekt->prj_name, 'maxNum' => $maxNumber, 'project_level' => $project_level);
		echo json_encode($returnvalues);
	}
	else
	{
		echo json_encode("error");
	}
}

function updateProjects(){ // Lädt die Projekte, die der angemeldete Benutzer erstellt hat.
	require('session.php');
	include("config.php");
	$stmt = $pdo->prepare("SELECT * FROM projects WHERE prj_owner = :prjOwner");
	$stmt->bindParam(':prjOwner',  $_SESSION['userid'], PDO::PARAM_STR);
	$stmt->execute();

	$projekte = $stmt->fetchAll();
	echo json_encode($projekte);
}

function updateSharedProjects(){ //Lädt die Projekte, die für den angemeldeten Benutzer freigegeben sind
	require('session.php');
	include("config.php");
	$stmt = $pdo->prepare("SELECT * FROM projects WHERE prj_shared LIKE '%". $_SESSION['userid']."%'");
	$stmt->execute();

	$projekte = $stmt->fetchAll();
	echo json_encode($projekte);
}

function isSharedWith(){ //Lädt alle Benutzer, für die das aktuell geöffnete Projekt freigegeben wurde.
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
?>