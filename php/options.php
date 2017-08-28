<?php
if (is_ajax()) {
  if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
    $action = $_POST["action"];
    switch($action) { //Switch case for value of action
		case "loadCars": loadCars(); break;
		case "updateKataster": updateKataster(); break;
		case "saveKataster": saveKataster(); break;
    }
  }
}

//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function loadCars(){
	include("config.php");

	$opt_cars = (!empty($_POST['cars']) ? $_POST['cars']:''); //Erzeugt kommaseparierten String aus Array von ausgewählten Benutzernamen

	$stmt = $pdo->prepare("SELECT * FROM cars WHERE car_id IN (".$opt_cars.")");
	$stmt->execute();

	$cars = $stmt->fetchAll(PDO::FETCH_OBJ);//Daten des angemeldeten Benutzers abfragen

	$returnvalues = array('cars'=>$cars);
	  	echo json_encode($returnvalues);
}

function updateKataster(){
	include("config.php");
	require("session.php");
	$UID = !empty($_POST['UID']) ? $_POST['UID']:'';

	$stmt = $pdo->prepare("SELECT opt_kataster FROM options WHERE opt_UID = :UID");
	$stmt->bindParam(':UID', $UID, PDO::PARAM_INT);
	$stmt->execute();
	$messpunkte = $stmt->fetch();

	if (!$messpunkte) // Überprüft, ob persönliche Messpunkte gespeichert sind und lädt anderenfalls die globalen Vorgaben
	{
		$stmt2 = $pdo->prepare("SELECT opt_kataster FROM options WHERE opt_UID = '0'");
		$stmt2->execute();
		$messpunkte = $stmt2->fetch();
	}

	if (!$messpunkte) // Wenn globale Vorgaben nicht definiert sind, wird ein Standardwert angenommen
	{
		$messpunkte = array('opt_kataster' => '[{"ID":"1","Nummer":"01-00","Bezeichnung":"Mustername","Adresse":"Musterstraße 1, 12345 Musterstadt","ODL":"0","IPS":"0","Koordinaten":"52.514036, 13.404107"}]', "0" => '[{"ID":"1","Nummer":"01-00","Bezeichnung":"Mustername","Adresse":"Musterstraße 1, 12345 Musterstadt","ODL":"0","IPS":"0","Koordinaten":"52.514036, 13.404107"}]');
	}
	
	echo json_encode($messpunkte);
}

function saveKataster(){
	include("config.php");
	require("session.php");
	$kataster = !empty($_POST['data']) ? $_POST['data']:'';
	$UID = !empty($_POST['UID']) ? $_POST['UID']:'';

	$stmt = $pdo->prepare("INSERT INTO options (opt_UID, opt_kataster) VALUES (:UID, :points) ON DUPLICATE KEY UPDATE opt_kataster = :points");
	$stmt->bindParam(':UID', $UID, PDO::PARAM_INT);
	$stmt->bindParam(':points', $kataster, PDO::PARAM_STR);
	$stmt->execute();
    
   echo $kataster;
}
?>