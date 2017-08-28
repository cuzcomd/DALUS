<?php require('php/session.php'); ?>
<!DOCTYPE html>
<!-- This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version. -->
<html lang="de">
<head>
	<meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
	<title>DALUS</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/jasny-bootstrap.min.css" rel="stylesheet" media="screen">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
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
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link type="text/css" rel="stylesheet" href="css/style.css">
	<script src="js/jquery.min.js"></script>
	<link rel="stylesheet" href="css/datetimepicker.css">
	<link rel="stylesheet" href="css/alertify/alertify.core.css" />
	<link rel="stylesheet" href="css/alertify/alertify.bootstrap.css" />
	<link rel="stylesheet" href="css/toastr.min.css" /> <!-- CSS für Script zum dynamischen Anzeigen von Statusmeldungen -->
	<link href="css/dataTables.bootstrap.min.css" rel="stylesheet">
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/dataTables.cellEdit.js"></script>
	<script src="js/OSM.js"></script>
	<script src="js/users.js"></script>
	<script src="js/objects.js"></script>
	<script src="js/messkataster.js"></script>
	<script src="js/init.js"></script>
	<script> // Initialfunktion
	benutzer = []; //Initialisierung
	optionen = []; //Initialisierung
	userAL = ""; //Initialisierung
	userID = 0; //Initialisierung
	prj_id = 0; //Initialisierung
	maxRowID = 0; //Initialisierung
	messpunktNummer = 1; //Initialisierung
	objectNummer = 1; // Initialisierung
	metCounter = 1; // Initialisierung
	activeObject = null; // Initialisierung
	activeProjectName = "Unbekanntes Projekt";  //Initialisierung
	loadUser(); // Daten des angemeldeten Benutzers laden
	updateProjects(); //Verfügbare Projekte aktualisieren
	updateSharedProjects(); //Verfügbare geteilte Projekte aktualisieren
	isSharedWith(); //Aktualisieren, mit wem das Projekt geteilt wird
	updateAllUsers() //Aktulisiert alle verfügbaren Benutzer
	objectArray = []; //Array für temporär erzeugte Objekte
	deleteArray = []; // Array für temporär gelöschte Objekte
	markerArray =[];
	var selectedShape; //Initialisierung für aktuell markiertes Geometrieobjekt

	function myCallbackFunction (updatedCell, updatedRow, oldValue) { //Callback für das Editieren der Messkatasterzellen
	    }

	function initMap() { // Erzeugung der Karte
		loadOSMLayer(); //OSM Kartenbilder laden
		infoWindow = new google.maps.InfoWindow(); //Globale Initialisierung des Infowindows
		startDrawingManager(map); //Google DrawingManager laden
		loadProjectObjects();	// Im Projekt gespeicherte Objekte einlesen
		
		dataTable = $('#kataster').DataTable({
			paging: false,
			scrollY: 400,
			scrollX: false,
			 "order": [[ 1, "asc" ]]
		}).draw();

		dataTable.MakeCellsEditable({
			"onUpdate": myCallbackFunction,
        	"columns": [1,2,3,4,5,6]
    	});

		updateKataster(userID);

		document.getElementById('calcMET').addEventListener('click', function() { // Beim Klick auf "Berechnen" MET-Modell erzeugen
			generateMET(map);
		});
		
		document.getElementById('switchMesskataster').addEventListener('click', function() {// Messkataster ein-/ausblenden
			loadFixpoints($(this));
		});

		document.getElementById('switchKompass').addEventListener('click', function() {// Kompass ein-/ausblenden
			$("#switchKompass").find('i').toggleClass("fa-toggle-off fa-toggle-on");
			$('#module2').toggle();
		});

		document.getElementById('switchGPS').addEventListener('click', function() {// GPS-Tracking ein-/ausblenden
			$('#switchGPS').find('i').toggleClass('fa-toggle-off fa-toggle-on');
			$('#module1').toggle();

			if($('#switchGPS').attr('data-click-state') == 1) { 
				$('#switchGPS').attr('data-click-state', 0) // Wenn Schalter aktiviert ist, ihn wieder deaktivieren
				$('#gpsLoadedCars').children().remove();
			}
			else {
				$('#switchGPS').attr('data-click-state', 1) // Wenn Schalter aktiviert ist, ihn wieder deaktivieren
				var data = {
					"action" : "loadCars",
					"cars" : optionen.opt_cars
				};
				data = $(this).serialize() + "&" + $.param(data);
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "php/options.php",
					data: data,
					success: function(data) {
						for (var key in data) {
						    if (!data.hasOwnProperty(key)) continue; // skip loop if the property is from prototype
						    var obj = data[key];
						    for (var prop in obj) {
						        if(!obj.hasOwnProperty(prop)) continue; // skip loop if the property is from prototype
						        $('<div class=row"><div class="checkbox"><label class="col-xs-10"><input type="checkbox" name="car" onchange="loadGPS(this,\''+obj[prop].car_key+'\',\''+obj[prop].car_color+'\');">'+obj[prop].car_name+' </label><div style="background:'+obj[prop].car_color+';" class="col-xs-1">&nbsp;</div></div></div>').appendTo('#gpsLoadedCars');
						    } //Ende for
						} // Ende for 
					}, //Ende success
					error: function(xhr, desc, err) {
						console.log(xhr);
						console.log("Details: " + desc + "\nError:" + err);
					} //ende error
				}); //Ende Ajax
			} //Ende else
		}); // Ende eventlistener
		
		document.getElementById('saveProject').addEventListener('click', function() { // Beim Klick auf "Speichern", aktuelle Änderungen speichern
			saveProjectStatus();	
		});

		document.getElementById('deleteProject').addEventListener('click', function() { // Beim Klick auf "Löschen", aktuelles Projekt löschen
			deleteProject();	
		});

		document.getElementById('startSearch').addEventListener('click', function(){
			var adresse = $('#pac-input').val();
			if (adresse)
			{
				new google.maps.Geocoder().geocode( { 'address': adresse}, function(results, status) {
		    		if (status == 'OK') {
				        map.setCenter(results[0].geometry.location);
				        var marker = new google.maps.Marker({
				            map: map,
				            position: results[0].geometry.location
				        });
			      	} 
			      	else {
     					alert('Geocode was not successful for the following reason: ' + status);
     				}
	     		});
	     		return;
     		}
		});

		var input = /** @type {!HTMLInputElement} */(document.getElementById('pac-input'));
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        autocomplete.addListener('place_changed', function() {
        	var place = autocomplete.getPlace();
        	if (!place.geometry) {
           		new google.maps.Geocoder().geocode( { 'address': place.name}, function(results, status) {
		    		if (status == 'OK') {
				        map.setCenter(results[0].geometry.location);
				        var marker = new google.maps.Marker({
				            map: map,
				            position: results[0].geometry.location
				        });
			      	} 
			      	else {
     					alert('Geocode was not successful for the following reason: ' + status);
     				}
     			});
     			 return;
     		}

	        var marker = new google.maps.Marker({
	          	map: map,
				position: place.geometry.location
			});
	        // If the place has a geometry, then present it on a map.
	        if (place.geometry.viewport) {
	        	map.fitBounds(place.geometry.viewport);
	        } 
	        else {
	            map.setCenter(place.geometry.location);
	            map.setZoom(17);  // Why 17? Because it looks good.
	        }

	        var address = '';
	        if (place.address_components) {
	            address = [
	            (place.address_components[0] && place.address_components[0].short_name || ''),
	            (place.address_components[1] && place.address_components[1].short_name || ''),
	            (place.address_components[2] && place.address_components[2].short_name || '')
	            ].join(' ');
	        }
        });
	}//Ende Funktion initMap
	</script> <!-- Initialfunktion -->
	<script src="js/module.js"></script>
	<script src="js/googleDrawingManager.js"></script>
</head>
<body>
	<div class="modal fade" id="modal_license" tabindex="-1" role="dialog" aria-labelledby="License">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title text-center" id="myModalLabel"><img src="images/dalus_logo.svg" width="250px"></h4>
				</div>
				<div class="modal-body">
					Das Projekt "Digitale Ausbreitungsabschätzung Luftgetragener Schadstoffe" (DALUS) dient zur Darstellung von Ausbreitungsabschätzungen luftgetragener Schadstoffemissionen und der Dokumentation von Messeinsätzen im Rahmen der operativen Gefahrenwehr.<br/><hr /><br/>
					<div class="panel panel-default">
						<div class="panel-heading text-center">DALUS<br>Copyright <i class="fa fa-copyright" aria-hidden="true"></i> 2017  Marco Trott</div>
						<div class="panel-body">
						   	This program is free software: you can redistribute it and/or modify
						    it under the terms of the GNU General Public License as published by
						    the Free Software Foundation, either version 3 of the License, or
						    (at your option) any later version.<br><br>

						    This program is distributed in the hope that it will be useful,
						    but WITHOUT ANY WARRANTY; without even the implied warranty of
						    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
						    GNU General Public License for more details.<br><br>

						    You should have received a copy of the GNU General Public License
						    along with this program.  If not, see <a href="https://www.gnu.org/licenses/" target="_blank">https://www.gnu.org/licenses/</a>.<br/><br/>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-xs-4 text-center"><a href="CHANGELOG.md" target="_blank">Version: 1.4.2</a></div>
						<div class="col-xs-4"><a href="https://github.com/cuzcomd/DALUS" target="_blank"><i class="fa fa-github" aria-hidden="true"></i> GitHub Repository</a></div>
						<div class="col-xs-4"><a href="mailto:kontakt@cuzcomd.de">kontakt@cuzcomd.de</a></div>
					</div>
				</div>
			</div><!-- Ende modal-content -->
		</div><!-- Ende modal-dialog -->
	</div> <!-- Ende modal fade -->

	<div class="modal fade" id="modal_new_project" tabindex="-1" role="dialog" aria-labelledby="Neues Projekt">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Neues Projekt erstellen</h4>
				</div>
				<div class="modal-body">
					<form action='' method='POST' class='ajax_create_project' role='form'>
						<input type='hidden' name='username' class="activeUserID" value=''>
						<div class="form-group">
							<label for="projekt_titel_new" class="col-form-label">Projekttitel</label>
							<input class="form-control" type="text" placeholder="Projekttitel" id="projekt_titel_new" name="projekttitel" required>
						</div>
						<div class="form-group">
							<label for="newProjektShared" class="col-form-label">Freigeben für</label>
							<select multiple class="form-control listOfAllUsersExceptMe" type="text" id="newProjektShared" name="shared[]" size="10">
							</select>
						</div>
						<div class="text-center">
							<button type='submit' class='btn btn-primary' onclick="$('#modal_new_project').modal('hide')"><span class='fa fa-check-square-o'></span> Projekt anlegen</button>
						</div>
					</form>
				</div><!-- Ende modal-body -->
			</div><!-- Ende modal-content -->
		</div><!-- Ende modal-dialog -->
	</div><!-- Ende Modal_new_project -->

	<div class="modal fade" id="modal_open_project" tabindex="-1" role="dialog" aria-labelledby="Projekt öffnen">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Projekt öffnen</h4>
				</div>
				<div class="modal-body">
					<h5>Meine Projekte </h5>
					<form action='' class="ajax_load_project" method='POST' role='form'>
						<div class="form-group">
							<select class="form-control" type="text" id="projectOpen" name="project_open"  size="10">
							</select>
						</div>
						<div class="text-center">
							<button type='submit' class='btn btn-primary' onclick="$('#modal_open_project').modal('hide')"><span class='fa fa-check-square-o'></span> Projekt öffnen</button>
						</div>
					</form>
					<h5>Für mich freigegebene Projekte</h5>
					<form action='' class="ajax_load_project" method='POST' role='form'>
						<div class="form-group">
							<select class="form-control" id="projectOpenShared" name="project_open"  size="10">
							</select>
						</div>
						<div class="text-center">
							<button type='submit' class='btn btn-primary' onclick="$('#modal_open_project').modal('hide')"><span class='fa fa-check-square-o'></span> Projekt öffnen</button>
						</div>
					</form>
				</div><!-- Ende modal-body -->
			</div><!-- Ende modal-content -->
		</div><!-- Ende modal-dialog -->
	</div><!-- Ende Modal_open_project -->

	<div class="modal fade" id="modal_edit_project" tabindex="-1" role="dialog" aria-labelledby="Projekt ändern">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Projekt ändern</h4>
				</div>
				<div class="modal-body">
					<form action='' method='POST' class='ajax_edit_project' role='form'>
						<input type='hidden' class='activeUserID' name='current_user_id' value=''>
						<input type='hidden' class='activeProjectID' name='current_project_id' value='0'>
						<div class="form-group">
							<label for="projekt_titel" class="col-form-label">Projekttitel</label>
							<input class="form-control activeProjectName" id="projekt_titel" type="text" placeholder="Projekttitel" name="projekttitel" value="" required>
						</div>
						<div class="form-group">
							<label for="projektShared" class="col-form-label">Freigeben für</label>
							<select multiple class="form-control" type="text" id="projektShared" name="shared[]"  size="10">
								<!-- Hier erscheinen die Benutzernamen, für die das Projekt freigegeben wurde -->
							</select>
						</div>
						<div class="text-center">
							<button type='submit' class='btn btn-primary' onclick="$('#modal_edit_project').modal('hide')"><span class='fa fa-check-square-o'></span> Änderung Speichern</button>
						</div>
					</form>
				</div><!-- Ende modal-body -->
			</div><!-- Ende modal-content -->
		</div><!-- Ende modal-dialog -->
	</div><!-- Ende Modal_edit_project -->

	<div class="modal fade" id="modalWinkel" tabindex="-1" role="dialog" aria-labelledby="MET Ausbreitungswinkel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">MET Ausbreitungswinkel</h4>
				</div>
				<div class="modal-body">
					 <!-- Ende Winkelrechner -->
				</div><!-- Ende modal-body -->
			</div><!-- Ende modal-content -->
		</div><!-- Ende modal-dialog -->
	</div><!-- Ende Modal_edit_project -->

	<div class="modal fade" id="modalMET" tabindex="-1" role="dialog" aria-labelledby="MET Ausbreitungsmodell">
		<div class="modalMET modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">MET Ausbreitungsmodell</h4>
				</div>
				<div class="modal-body">
					<div id="METWrapper" class="row">
						<div id="METPanel" class="col-xs-4">
							<ul class="nav nav-pills nav-stacked">
								<li class="active"><a data-toggle="pill" href="#metAuto">An Adresse zeichnen</a></li>
								<li><a data-toggle="pill" href="#metMan">Manuell zeichnen</a></li>
								<li><a data-toggle="pill" href="#metWinkel">Winkel bestimmen</a></li>
							</ul>
						</div> <!-- Ende adminPanel -->
						<div id="adminContent" class="col-xs-8">
							<div class="tab-content">
								<div id="metAuto" class="tab-pane fade in active ">
									<div id="geocoder">
										<form id="input-form" class="form-horizontal" role="form">
											<div class="form-group" data-toggle="tooltip" title="Freisetzungsort">
												<label class="control-label col-xs-4" for="addresse">Scha&shy;dens&shy;ort</label>
												<div class="col-xs-8">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-home"></i></span>
														<input id="addresse" type="textbox" value="Alt Diesdorf 4, Magdeburg" class="form-control">
													</div>
												</div>
											</div>
					
											<div class="form-group" data-toggle="tooltip" title="Ausbreitungswinkel">
												<label class="control-label col-xs-4" for="winkelAuto">Aus&shy;brei&shy;tungs&shy;winkel</label>
												<div class="col-xs-8">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-arrows-h"></i></span>
														<select id="winkelAuto" name="winkel" class="form-control">
															<option value="45" label="45&deg;">45&deg;</option>
															<option value="60" label="60&deg;" selected>60&deg;</option>
															<option value="90" label="90&deg;">90&deg;</option>
															<option value="360" label="360&deg;">360&deg;</option>
														</select>
													</div>
												</div>
											</div>
		
											<div class="form-group" data-toggle="tooltip" title="Windrichtung">
												<label class="control-label col-xs-4" for="windrichtung">Wind&shy;richtung</label>
												<div class="col-xs-8">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
														<input id="windrichtung" type="number" value="280" class="form-control" onchange="document.getElementById('arrow').style.transform = 'rotate('+(this.value-90)+'deg)';">
														<span class="input-group-addon">&deg;</span>
													</div>
												</div>
											</div>
					
											<div class="form-group" data-toggle="tooltip" title="Gefährdung für Personen im Gebäude">
												<label class="control-label col-xs-4" for="distanz 1">Gefährdung für Personen im Gebäude</label>
												<div class="col-xs-8">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-exclamation"></i> <i class="fa fa-home"></i></span>
														<input id="distanz1" type="number" value="600" class="form-control">
														<span class="input-group-addon">m</span>
													</div>
												</div>
											</div>
												
											<div class="form-group" data-toggle="tooltip" title="Gefährdung für Personen im Freien">
												<label class="control-label col-xs-4" for="distanz 1">Geährdung für Personen im Freien</label>
												<div class="col-xs-8">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-exclamation"></i> <i class="fa fa-street-view"></i></span>
														<input id="distanz2" type="number" value="1300"  class="form-control">
														<span class="input-group-addon">m</span>
													</div>
												</div>
											</div>
										</form>
										<br>
										<div class="geocoderButtons">
											<button type="button" class="btn btn-primary" id="calcMET" data-toggle="tooltip" title="MET-Freisetzungsort aus Adressfeld lesen" ><i class="fa fa-crosshairs"></i> Zeichnen</button>
										</div>
									</div> <!-- Ende Geocoder -->
								</div> <!-- Ende metAuto -->

								<div id="metMan" class="tab-pane fade ">
									<div id="geocoder">
										<form id="input-form" class="form-horizontal" role="form">
											<div class="form-group" data-toggle="tooltip" title="Ausbreitungswinkel">
												<label class="control-label col-xs-4" for="winkelMan">Aus&shy;brei&shy;tungs&shy;winkel</label>
												<div class="col-xs-8">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-arrows-h"></i></span>
														<select id="winkelMan" name="winkel" class="form-control">
															<option value="45" label="45&deg;">45&deg;</option>
															<option value="60" label="60&deg;" selected>60&deg;</option>
															<option value="90" label="90&deg;">90&deg;</option>
															<option value="360" label="360&deg;">360&deg;</option>
														</select>
													</div>
												</div>
											</div>
					
											<div class="form-group" data-toggle="tooltip" title="Windrichtung">
												<label class="control-label col-xs-4" for="windrichtungMan">Wind&shy;richtung</label>
												<div class="col-xs-8">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
														<input id="windrichtungMan" type="number" value="280" class="form-control" onchange="document.getElementById('arrow').style.transform = 'rotate('+(this.value-90)+'deg)';">
														<span class="input-group-addon">&deg;</span>
													</div>
												</div>
											</div>
					
											<div class="form-group" data-toggle="tooltip" title="Gefährdung für Personen im Gebäude">
												<label class="control-label col-xs-4" for="distanz1Man">Gefährdung für Personen im Gebäude</label>
												<div class="col-xs-8">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-exclamation"></i> <i class="fa fa-home"></i></span>
														<input id="distanz1Man" type="number" value="600" class="form-control">
														<span class="input-group-addon">m</span>
													</div>
												</div>
											</div>
												
											<div class="form-group" data-toggle="tooltip" title="Gefährdung für Personen im Freien">
												<label class="control-label col-xs-4" for="distanz2Man">Geährdung für Personen im Freien</label>
												<div class="col-xs-8">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-exclamation"></i> <i class="fa fa-street-view"></i></span>
														<input id="distanz2Man" type="number" value="1300"  class="form-control">
														<span class="input-group-addon">m</span>
													</div>
												</div>
											</div>
										</form>
										<br>
										<div class="geocoderButtons">
											<button type="button" class="btn btn-primary" id="calcMETmanual" data-toggle="tooltip" title="MET Freisetzungsort manuell festlegen" ontouchstart="drawMetMarkerManual()" onclick="drawMetMarkerManual()"></i> Zeichnen</button>
										</div>
									</div> <!-- Ende Geocoder -->
								</div> <!-- Ende metMan -->

								<div id="metWinkel" class="tab-pane fade ">
									<form id="form_winkelrechner" class="form-horizontal">
										<div class="form-group">
											<label for="nebel" class="col-xs-4 form-control-label">Nebel</label>
											<div class="col-xs-8">
												<select id="nebel" name="nebel" class="form-control" onchange="computeAngle();">
													<option value="true" label="Ja">Ja</option>
													<option value="false" label="Nein">Nein</option>
												</select>
											</div>
										</div>
		
										<div class="form-group">	
											<label for="windgeschwindigkeit" class="col-xs-4 form-control-label">Wind&shy;ge&shy;schwin&shy;dig&shy;keit</label>
											<div class="col-xs-8">
												<select id="windgeschwindigkeit" name="windgeschwindigkeit" class="form-control" onchange="computeAngle();">
													<option value="high" label="gr&ouml;&szlig;er 5 m/s (18 km/h)">gr&ouml;&szlig;er 5 m/s (18 km/h)</option>
													<option value="medium" label="zwischen 1 m/s (4 km/h) und 5 m/s (18 km/h)">zwischen 1 m/s (4 km/h) und 5 m/s (18 km/h)</option>
													<option value="low" label="kleiner 1 m/s (4 km/h)">kleiner 1 m/s (4 km/h)</option>
												</select>
											</div>
										</div>

										<div class="form-group">	
											<label for="himmel" class="col-xs-4 form-control-label">Bedeckter Himmel</label>
											<div class="col-xs-8">
												<select id="himmel" name="himmel" class="form-control" onchange="computeAngle();">
													<option value="true" label="mehr als 50 %">mehr als 50 %</option>
													<option value="false" label="weniger als 50 %">weniger als 50 %</option>
												</select>
											</div>
										</div>
		
										<div class="form-group">
											<label for="tageszeit" class="col-xs-4 form-control-label">Tageszeit</label>
											<div class="col-xs-8">
												<select id="tageszeit" name="tageszeit" class="form-control" onchange="computeAngle();">
													<option value="day" label="Tag">Tag</option>
													<option value="night" label="Nacht">Nacht</option>
												</select>
											</div>
										</div>
									
										<div class="form-group">
											<label for="monat" class="col-xs-4 form-control-label">Monat</label>
											<div class="col-xs-8">
												<select id="monat" name="monat" class="form-control" onchange="computeAngle();">
													<option value="om" label="Oktober - M&auml;rz">Oktober - M&auml;rz</option>
													<option value="as" label="April - September">April - September</option>
												</select>
											</div>
										</div>
		
										<div class="form-group">
											<label for="brand" class="col-xs-4 form-control-label">Brand</label>
											<div class="col-xs-8">
												<select id="brand" name="brand" class="form-control" onchange="computeAngle();">
													<option value="true" label="Ja">Ja</option>
													<option value="false" label="Nein">Nein</option>
												</select>
											</div>
										</div>
									</form>
									<div class="alert alert-warning alert-dismissible" role="alert">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<strong>Hinweis:</strong><br/>Der Winkel wird automatisch aktualisiert.
									</div>
								</div><!-- Ende metWinkel -->
							</div> <!-- Ende tab-content -->
						</div> <!-- Ende adminContent -->
					</div> <!-- Ende adminWrapper -->
				</div><!-- Ende modal-body -->
			</div><!-- Ende modal-content -->
		</div><!-- Ende modal-dialog -->
	</div><!-- Ende modalMET -->

	<div class="modal fade" id="modalOptions" tabindex="-1" role="dialog" aria-labelledby="Optionen">
		<div class="modalOptions modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Optionen </h4>
				</div>
				<div class="modal-body">
					<div id="adminWrapper" class="row">
						<div id="adminPanel" class="col-xs-4">
							<ul class="nav nav-pills nav-stacked">
								<?php
									include_once('php/acl/user/optionsPanel.php'); //Optionen für ACL "user" laden
								  	if ($accessLevel == 'admin')
								 	{
								  		include_once('php/acl/admin/optionsPanel.php'); //Optionen für ACL "admin" laden
								  	}
								  	if ($accessLevel == 'editor' || $accessLevel == 'admin')
								  	{
								  		include_once('php/acl/editor/optionsPanel.php'); //Optionen für ACL "editor" laden
								  	}
								?>
							</ul>
						</div> <!-- Ende adminPanel -->
						<div id="adminContent" class="col-xs-8">
							<div class="tab-content">
								<?php
									include_once('php/acl/user/optionsContent.php'); //Optionen für ACL "user" laden
									if ($accessLevel == 'admin')
									{
										include_once('php/acl/admin/optionsContent.php'); //Optionen für ACL "admin" laden
									}
									if ($accessLevel == 'editor' || $accessLevel == 'admin')
									{
									  	include_once('php/acl/editor/optionsContent.php'); //Optionen für ACL "editor" laden
									}
								?>
							</div> <!-- Ende tab-content -->
						</div> <!-- Ende adminContent -->
					</div> <!-- Ende adminWrapper -->
				</div><!-- Ende modal-body -->
			</div><!-- Ende modal-content -->
		</div><!-- Ende modal-dialog -->
	</div><!-- Ende modalOptions -->

	<nav id="myNavmenu" class="navmenu navmenu-default navmenu-fixed-left offcanvas-sm" role="navigation">
		<div class="userInformation">
			<span class="fa fa-user-circle" aria-hidden="true"></span>
			<span id="activeUser">&nbsp; Kein Benutzer aktiv</span>
		</div>
		<div class="projectInformation">
			<span class="fa fa-folder-open" aria-hidden="true"></span>
			<span id="activeProject">&nbsp; Kein Projekt geöffnet</span>
		</div>
  		<!-- <a class="navmenu-brand text-center"><img src="images/dalus_logo.svg" width="150px"></a>  -->		
		<div class="input-group searchbar">
			<input id="pac-input" class="form-control" type="text" placeholder="Ort suchen ...">
			<span id = "startSearch" class="input-group-addon" role="button"><i class="fa fa-search"></i></span>
		</div>
		<div class="werkzeuge hidden-sm hidden-xs">
			<ul class="nav nav-pills nav-werkzeuge">
				<li class="setHand" data-toggle="tooltip" data-placement="bottom" title="Auswahl" role="button"><a data-toggle="tab"><i class="fa fa-mouse-pointer"></i></a></li>
				<li class="setMarkWhite" data-toggle="tooltip" data-placement="bottom" title="Auswahl" role="button"><a data-toggle="tab"><i class="fa fa-flag-o"></i></a></li>
				<li class="setCirc" data-toggle="tooltip" data-placement="bottom" title="Kreis zeichnen" role="button"><a data-toggle="tab"><i class="fa fa-circle-thin"></i></a></li>
				<li class="setPoly" data-toggle="tooltip" data-placement="bottom" title="Polygon zeichnen" role="button"><a data-toggle="tab"><i class="fa fa-bookmark-o"></i></a></li>
				<li class="setPath" data-toggle="tooltip" data-placement="bottom" title="Pfad zeichnen" role="button"><a data-toggle="tab"><i class="fa fa-pencil"></i></a></li>
				<li class="deleteActiveObject" data-toggle="tooltip" data-placement="bottom" title="Objekt löschen" role="button"><a data-toggle="tab"><i class="fa fa-trash"></i></a></li>
			</ul>
		</div> <!-- Ende Werkzeuge -->
		<ul class="nav navmenu-nav">
			<li class="dropdown open" id ="project_options" role="presentation" data-toggle="tooltip" data-placement="bottom" title="Projekt">
				<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bars" aria-hidden="true"></i> Projekt
				<span class="caret"></span></a>
				<ul class="dropdown-menu navmenu-nav" role="menu">
					<li id="newProject" role="button" onclick="toggleNav('#modal_new_project')" ><a><i class="fa fa-pencil-square-o"></i> Neues Projekt</a></li>
					<li id="openProject" role="button" onclick="toggleNav('#modal_open_project')"><a><i class="fa fa-folder-open-o"></i> Projekt öffnen</a></li>
					<li id="editProject" role="button" onclick="toggleNav('#modal_edit_project')" ><a><i class="fa fa-pencil-square-o"></i> Projekt ändern</a></li>
					<li id="saveProject" role="button"><a><i class="fa fa-floppy-o" aria-hidden="true"></i> Projekt speichern</a></li>
					<li id="deleteProject" role="button" ><a><i class="fa fa-trash-o" aria-hidden="true"></i> Projekt löschen</a></li>
					<li id="exportKML" onclick="toKML()" ><a id="download-link" href="data:;base64," download><i class="fa fa-floppy-o" aria-hidden="true"></i> kml-Datei exportieren</a></li>	
				</ul>
			</li>
			<li class="dropdown" id ="parameter" role="presentation" data-toggle="tooltip" data-placement="bottom" title="Ansicht">
				<a class="dropdown-toggle" data-toggle="dropdown"role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-eye-slash" aria-hidden="true"></i> Ansicht
				<span class="caret"></span></a>
				<ul class="dropdown-menu navmenu-nav" role="menu" >
					<li id = "switchMesskataster" data-click-state="0" role="button"><a><i class="fa fa-toggle-off" aria-hidden="true"></i> Messkataster</a></li>
					<li id = "switchKompass" data-click-state="0" role="button"><a><i class="fa fa-toggle-off" aria-hidden="true"></i> Kompass</a></li>
					<li id = "switchGPS" data-click-state="0" role="button"><a><i class="fa fa-toggle-off" aria-hidden="true"></i> GPS Tracking</a></li>
				</ul>
			</li>
			<li class="dropdown" id ="modelle" role="presentation" data-toggle="tooltip" data-placement="bottom" title="Ausbreitungsmodelle">
				<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-location-arrow" aria-hidden="true"></i> Ausbreitungsmodelle
				<span class="caret"></span></a>
				<ul class="dropdown-menu navmenu-nav" role="menu" >
					<li id ="switch_winkel" role="button" onclick="toggleNav('#modalMET')"><a><i class="fa fa-location-arrow"></i> MET</a></li>
				</ul>
			</li>
			</ul>
	
		<div class="moduleWrapper">
			<div id = "module1" class="module gpsLegende">
				<h5><b>GPS Tracking</b></h5>
				<div>
					<form id="gpsLoadedCars" action="" class="form"  role="form">
					<!-- Vom Benutzer gespeicherte Fahrzeuge -->
					</form>
				</div>
				<br><br>
				<div>
					<form action="" class="form"  role="form">
				        <div class="form-group">
				            <label for="startTrack" class="control-label">Von</label>
				            <div class="input-group date form_datetime" id="startTrack" placeholder = "2017-07-17 10:30">
				                <input class="form-control" size="16" type="text" value="" id="startTrackInput">
				                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
								<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
				            </div>
							<input type="hidden" id="dtp_input1" value="" />
				        </div>
				        <div class="form-group">
				            <label for="endTrack" class="control-label">Bis</label>
				            <div class="input-group date form_datetime" id="endTrack" placeholder = "2017-07-17 10:30">
				                <input class="form-control" size="16" type="text" value="" id="endTrackInput">
				                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
								<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
				            </div>
							<input type="hidden" id="dtp_input1" value="" />
				        </div>
					</form>
				</div>
			</div>
			<div id = "module2" class="module windrose" ><img src="images/arrow.png" alt="Windrose" id="arrow"/></div>
			<div id = "module3" class="module"></div>
		</div>
		<div class="nav sidebar-footer">
			<a href='php/logout' data-toggle="tooltip" data-placement="bottom" title="Abmelden"><span class="fa fa-power-off" aria-hidden="true"></span></a>
			<a onclick="toggleNav('#modal_license')" data-toggle="tooltip" data-placement="bottom" title="Informationen über Dalus"><span class="fa fa-info-circle" aria-hidden="true"></span></a>
			<a onclick="toggleNav('#modalOptions')" data-toggle="tooltip" data-placement="bottom" title="Optionen"><span class="fa fa-cogs"></span></a>
			<a onclick="printMap()"  ata-toggle="tooltip" data-placement="bottom" title="Ansicht drucken"><span class="fa fa-print" aria-hidden="true"></span></a></li>
		</div>
	</nav>
<div class="navbar navbar-default navbar-fixed-top hidden-md hidden-lg text-center">
<span class="werkzeuge-top">
			<ul class="nav nav-pills nav-werkzeuge">
				<li class="setHand" data-toggle="tooltip" data-placement="bottom" title="Auswahl" role="button"><a data-toggle="tab"><i class="fa fa-mouse-pointer"></i></a></li>
				<li class="setMarkWhite" data-toggle="tooltip" data-placement="bottom" title="Auswahl" role="button"><a data-toggle="tab"><i class="fa fa-flag-o"></i></a></li>
				<li class="setCirc" data-toggle="tooltip" data-placement="bottom" title="Kreis zeichnen" role="button"><a data-toggle="tab"><i class="fa fa-circle-thin"></i></a></li>
				<li class="setPoly" data-toggle="tooltip" data-placement="bottom" title="Polygon zeichnen" role="button"><a data-toggle="tab"><i class="fa fa-bookmark-o"></i></a></li>
				<li class="setPath" data-toggle="tooltip" data-placement="bottom" title="Pfad zeichnen" role="button"><a data-toggle="tab"><i class="fa fa-pencil"></i></a></li>
				<li class="deleteActiveObject" data-toggle="tooltip" data-placement="bottom" title="Objekt löschen" role="button"><a data-toggle="tab"><i class="fa fa-trash"></i></a></li>
			</ul>
		</span> <!-- Ende Werkzeuge -->
  <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target="#myNavmenu" data-canvas="body">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
</div>
	<div id="map"></div>
	<textarea id="kmlString"></textarea>
	<script src = "https://maps.googleapis.com/maps/api/js?libraries=geometry,drawing,places&callback=initMap" async defer></script> <!-- GooleAPI laden. Hier muss der API-Schlüssel eingetragen werden. -->
	<script src = "js/bootstrap.min.js"></script> <!-- Bootstrap.js laden -->
	<script src="js/jasny-bootstrap.min.js"></script>
	<script src = "js/html2canvas.min.js" defer></script>
	<script src = "js/usng.min.js" defer></script> <!-- Script für Umwandlung von Geokoordinaten in UTM-Ref Koordinaten -->
	<script src = "js/MET.js" defer></script> <!-- Adresse des MET-Modells durch Eingabemaske oder manuelle Festlegung bestimmen -->
	<script src = "js/datetimepicker.js"></script>
	<script src = "js/datetimepicker.de.js" defer></script>
	<script src = "js/datetimepickerOptions.js" defer></script>
	<script src = "js/project.js" defer></script>
	<script src = "js/helpers.js" defer></script>
	<script src = "js/ajaxCalls.js" defer> // Ajax aufruf für Projekte </script>
	<script src = "js/xmlwriter.js" defer></script>
	<script src = "js/exportKml.js" defer></script>
	<script src = "js/alertify.min.js" defer></script>
	<script src = "js/toastr.min.js" defer></script> <!-- Script zum dynamischen Anzeigen von Statusmeldungen -->
</body>
</html>