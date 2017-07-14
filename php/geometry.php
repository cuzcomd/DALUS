<?php
if (is_ajax()) {
  	if (isset($_POST["task"]) && !empty($_POST["task"])) { //Checks if task value exists
    $task = $_POST["task"];
    switch($task) { //Switch case for value of task
		case "delete": deleteGeometry(); break;
		case "save": saveGeometry(); break;
		case "load": loadGeometry(); break;
    }
  }
}
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}


function deleteGeometry(){
include("config.php");
	$obj_prj_id = (!empty($_POST['projekt_id']) ? $_POST['projekt_id']:'');
	$obj_nummer= (!empty($_POST['objekt_nummer']) ? $_POST['objekt_nummer']:'');
	$obj_typ= (!empty($_POST['objekt_typ']) ? $_POST['objekt_typ']:'');

	$stmt = $pdo->prepare("DELETE FROM objects WHERE obj_nummer = :obj_nummer AND obj_prj_id = :obj_prj_id AND obj_typ = :obj_typ");
	$stmt->bindParam(':obj_prj_id', $obj_prj_id, PDO::PARAM_INT);
	$stmt->bindParam(':obj_nummer', $obj_nummer, PDO::PARAM_INT);
	$stmt->bindParam(':obj_typ', $obj_typ, PDO::PARAM_STR, 12);
	$stmt->execute();

	$_POST["json"] = json_encode($_POST);
	echo json_encode($_POST);
}

function saveGeometry(){
include("config.php");
	$obj_typ = (!empty($_POST['obj_typ']) ? $_POST['obj_typ']:'');
	$obj_prj_id = (!empty($_POST['obj_prj_id']) ? $_POST['obj_prj_id']:'');
	$obj_nummer = (!empty($_POST['obj_nummer']) ? $_POST['obj_nummer']:'');
	$obj_lat = (!empty($_POST['obj_lat']) ? $_POST['obj_lat']:'');
	$obj_lon = (!empty($_POST['obj_lon']) ? $_POST['obj_lon']:'');
	$obj_color= (!empty($_POST['obj_color']) ? $_POST['obj_color']:'');
	$obj_hinweis= (!empty($_POST['obj_hinweis']) ? $_POST['obj_hinweis']:'');
	$obj_messwert= (!empty($_POST['obj_messwert']) ? $_POST['obj_messwert']:'');
	$obj_parameter= (!empty($_POST['obj_parameter']) ? $_POST['obj_parameter']:'');
	
	$stmt = $pdo->prepare("INSERT INTO objects (obj_typ, obj_nummer, obj_prj_id, obj_lat, obj_lon, obj_farbe, obj_hinweis, obj_messwert, obj_parameter)
	VALUES (:obj_typ, :obj_nummer, :obj_prj_id, :obj_lat, :obj_lon, :obj_color, :obj_hinweis, :obj_messwert, :obj_parameter) 
	ON DUPLICATE KEY UPDATE obj_typ = :obj_typ, obj_nummer = :obj_nummer, obj_prj_id = :obj_prj_id, obj_lat = :obj_lat, obj_lon = :obj_lon, obj_farbe = :obj_color, obj_hinweis = :obj_hinweis, obj_messwert = :obj_messwert, obj_parameter = :obj_parameter");
	
	$stmt->bindParam(':obj_typ', $obj_typ, PDO::PARAM_STR, 12);
	$stmt->bindParam(':obj_nummer', $obj_nummer, PDO::PARAM_INT);
	$stmt->bindParam(':obj_prj_id', $obj_prj_id, PDO::PARAM_INT);
	$stmt->bindParam(':obj_lat', $obj_lat, PDO::PARAM_STR, 12);
	$stmt->bindParam(':obj_lon', $obj_lon, PDO::PARAM_STR, 12);
	$stmt->bindParam(':obj_color', $obj_color, PDO::PARAM_STR, 12);
	$stmt->bindParam(':obj_hinweis', $obj_hinweis, PDO::PARAM_STR, 12);
	$stmt->bindParam(':obj_messwert', $obj_messwert, PDO::PARAM_STR, 12);
	$stmt->bindParam(':obj_parameter', $obj_parameter, PDO::PARAM_STR, 255);
	
	$stmt->execute();

	$_POST["json"] = json_encode($_POST);
	echo json_encode($_POST);
}

function loadGeometry(){
	include("config.php");
	$stmt = $pdo->prepare("SELECT * FROM objects WHERE obj_prj_id = :prj_id");
	$stmt->bindParam(':prj_id', $_POST["prj_id"], PDO::PARAM_INT);
	$stmt->execute();
	$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
  	echo json_encode($rows);
}
?>