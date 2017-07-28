<?php
if (is_ajax()) {
  if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
    $action = $_POST["action"];
    switch($action) { //Switch case for value of action
		case "loadCars": loadCars(); break;
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
?>