<?php require('session.php'); ?>
<!DOCTYPE html>
<!-- Dieses Projekt wurde von cuzcoMD [www.cuzcomd.de] entwickelt. Es ist vom Autor lizenziert unter einer Creative Commons Namennennung - Nicht-kommerziell - Weitergabe unter gleichen Bedingungen 4.0 International Lizenz.
Dies bedeutet, dass jeder Änderungen vornehmen und diese veröffentlichen darf, solange es sich um eine nicht kommerzielle Nutzung handelt und der ursprüngliche Autor genannt wird. Zudem muss die Weitergabe unter der gleichen Lizenz erfolgen. -->
<html lang="de">
<head>
	<meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
	<title>DALUS</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script> <!-- Script zum dynamischen Anzeigen von Statusmeldungen -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" /> <!-- CSS für Script zum dynamischen Anzeigen von Statusmeldungen -->
	<script>
	function loadUser(){ // Lädt die Daten des angemeldeten Benutzers
		var data = {"action": "loadUser"};
		data = $(this).serialize() + "&" + $.param(data);
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "projects.php",
			data: data,
			success: function(data) {
				$("#activeUser").html('&nbsp; '+data["vorname"] +' '+ data["nachname"]+'&nbsp;'); //Zeigt den Namen im Optionsmenü an
				$(".activeUserID").val(data["benutzerID"]); //Setzt die ID des Benutzers als Feldwert
				$("#username").val(data["benutzername"]);
				userID = data["benutzerID"]; //Speichert dei Benutzer-ID in einer globalen Variablen
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		});//Ende Ajax
	}//Ende Funktion loadUser

	function updateProjects(){ //Aktualisiert die Liste der Projekte, die für den angemeldeten Benutzer sichtbar sind
		$('#projectOpen').children('option').remove();// Leert die Liste aller verfügbaren Optionen
		var data = {"action": "updateProjects"};
		data = $(this).serialize() + "&" + $.param(data);
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "projects.php",
			data: data,
			success: function(data) {
				$.each(data, function (key, value) {
				 	$('#projectOpen') // Fügt eine neue Option hinzu
				 	.append($('<option></option>') 
				 	.attr('value', value.prj_name)
				 	.text(value.prj_name));
				});
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		});//Ende Ajax
	}//Ende Funktion updateProjects

	function updateSharedProjects(){ //Aktualisiert die Liste der Projekte, die für den angemeldeten Benutzer sichtbar sind
		$('#projectOpenShared').children('option').remove(); // Leert die Liste aller verfügbaren Optionen
		var data = {"action": "updateSharedProjects"};
		data = $(this).serialize() + "&" + $.param(data);
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "projects.php",
			data: data,
			success: function(data) {
				$.each(data, function (key, value) {
					$('#projectOpenShared')// Fügt eine neue Option hinzu
					.append($('<option></option>') 
				 	.attr('value', value.prj_name)
				 	.text(value.prj_name));
				});
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		});//Ende Ajax
	}//Ende Funktion updateSharedProjects

	function isSharedWith(){ //Aktualisiert die Liste der Projekte, die für den angemeldeten Benutzer sichtbar sind
		$('#projektShared').children('option').remove();// Leert die Liste aller verfügbaren Optionen
		var data = {"action": "isSharedWith", "projectID": prj_id};
		data = $(this).serialize() + "&" + $.param(data);
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "projects.php",
			data: data,
			success: function(data) {
				$.each(data, function (key, value) {
					if (value.shared == "yes") {
						$('#projektShared')// Fügt eine neue Option hinzu
					 	.append($('<option selected selected="selected"></option>') 
					 	.attr('value', value.id)
					 	.text(value.username));
					}
					else{
						$('#projektShared')
						 .append($('<option></option>') 
						 .attr('value', value.id)
						 .text(value.username));
						}
				});//Ende each()
			},//Ende success
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		});//Ende Ajax
	}//Ende Funktion isSharedWith
	</script>
	<script> // Initialfunktion
	userID = 0; //Initialisierung
	prj_id = 0; //Initialisierung
	loadUser(); // Daten des angemeldeten Benutzers laden
	updateProjects(); //Verfügbare Projekte aktualisieren
	updateSharedProjects(); //Verfügbare geteilte Projekte aktualisieren
	isSharedWith(); //Aktualisieren, mit wem das Projekt geteilt wird
	messpunktnummer = 1; //Initialisierung
	objectNummer = 1;
	metCounter = 1; //Zähler für die Anzahl an Freisetzungsmarkern
	objectArray = new Array(); //Array für temporär erzeugte Objekte
	deleteArray = new Array(); // Array für temporär gelöschte Objekte
	activeObject = null;
	var selectedShape; //Initialisierung für aktuell markiertes Geometrieobjekt

	function initMap() { // Erzeugung der Karte
		objectArray = []; //Array für temporär erzeugte Objekte leeren
		deleteArray = []; // Array für temporär gelöschte Objekte leeren
		loadOSMLayer(); //OSM Kartenbilder laden
		infoWindow = new google.maps.InfoWindow(); //Globale Initialisierung des Infowindows
		startDrawingManager(map); //Google DrawingManager laden
		// Rotierenden Pfeil in Windrose zeichnen
		var windrichtung_start = parseInt(document.getElementById('windrichtung').value);
		var windrichtung = windrichtung_start-90;
		document.getElementById('arrow').style.transform = 'rotate('+windrichtung+'deg)';

		loadProjectObjects();	// Im Projekt gespeicherte Objekte einlesen
		
		document.getElementById('calcMET').addEventListener('click', function() { // Beim Klick auf "Berechnen" MET-Modell erzeugen
			generateMET(map);
		});
		
		document.getElementById('switchMesspunkte').addEventListener('click', function() {// Definierte Messpunkte ein-/ausblenden
			loadFixpoints($(this));
		});
		document.getElementById('switchKompass').addEventListener('click', function() {// Definierte Messpunkte ein-/ausblenden
			$("#switchKompass").find('i').toggleClass("fa-toggle-off fa-toggle-on");
			$(".windrose").toggle()
		});
		
		document.getElementById('saveProject').addEventListener('click', function() { // Beim Klick auf "Speichern", aktuelle Änderungen speichern
			saveProjectStatus();	
		});

		document.getElementById('deleteProject').addEventListener('click', function() { // Beim Klick auf "Löschen", aktuelles Projekt löschen
			deleteProject();	
		});
	}//Ende Funktion initMap
	</script> <!-- Initialfunktion -->
	<script > // Google DrawingManager laden
	function startDrawingManager(map){
		var marker_color = "yellow"; //Marker standardmäßig als gelbe Marker zeichnen
		var drawingManager = new google.maps.drawing.DrawingManager({
			drawingMode: google.maps.drawing.OverlayType.null,
			drawingControl: false,
			polylineOptions: {
                    editable: true,
                    draggable: true
                },
                rectangleOptions: {
                    editable: true,
                    draggable: true
                },
                circleOptions: {
                    editable: true,
                    draggable: true
                },
                polygonOptions: {
                    editable: true,
                    draggable: true
                }
		});			
		drawingManager.setMap(map);
	
	// Beim Klick auf Geometriesymbole das jeweilige Werkzeug auswählen
		document.getElementById('setHand').addEventListener('click', function() {
			drawingManager.setOptions({
				drawingMode: google.maps.drawing.OverlayType.null
			});
		});
		
		document.getElementById('setMark').addEventListener('click', function() {
			drawingManager.setOptions({
				drawingMode: google.maps.drawing.OverlayType.MARKER,
				markerOptions: {icon: {url:'images/beachflag.png',anchor: new google.maps.Point(0, 32)}, draggable:true}
			});
			marker_color = "yellow";
			marker_typ = 'messpunkt';
		});
		
		document.getElementById('setMarkGreen').addEventListener('click', function() {
			drawingManager.setOptions({
				drawingMode: google.maps.drawing.OverlayType.MARKER,
				markerOptions: {icon: {url:'images/greenflag.png',anchor: new google.maps.Point(0, 32)}, draggable:true}
			});
			marker_color = "green";
			marker_typ = 'messpunkt';
		});
	
		document.getElementById('setMarkRed').addEventListener('click', function() {
			drawingManager.setOptions({
		  		drawingMode: google.maps.drawing.OverlayType.MARKER,
		 		markerOptions: {icon: {url:'images/redflag.png',anchor: new google.maps.Point(0, 32)}, draggable:true}
	  		});
			marker_color = "red";
			marker_typ = 'messpunkt';
  		});

  		document.getElementById('calcMETmanual').addEventListener('click', function() {
  			$('#modal_MET').modal('hide');
			drawingManager.setOptions({
		  		drawingMode: google.maps.drawing.OverlayType.MARKER,
	  		});
			marker_typ = "metManual";
			marker_color = "red";
  		});
	
		document.getElementById('setCirc').addEventListener('click', function() {
	 		drawingManager.setOptions({
		  		drawingMode: google.maps.drawing.OverlayType.CIRCLE
	  		});
  		});
	
		document.getElementById('setPoly').addEventListener('click', function() {
	  		drawingManager.setOptions({
		  		drawingMode: google.maps.drawing.OverlayType.POLYGON
	  		});
  		});
	
		document.getElementById('setPath').addEventListener('click', function() {
	  		drawingManager.setOptions({
		  		drawingMode: google.maps.drawing.OverlayType.POLYLINE
	  		});
  		});

  		document.getElementById('deleteActiveObject').addEventListener('click', function() {
	  		deleteObject();
  		});
		
		google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) { //Funktionen, die nach dem Zeichnen eines Geometrieobjekts aufgerufen werden
			var newShape = event.overlay;
			newShape.type = event.type;
			
			if (event.type == google.maps.drawing.OverlayType.POLYLINE) {
				var newObject = event.overlay;
				newObject.setValues({
					obj_nummer: objectNummer,
					obj_parameter: newObject.getPath().getArray(),
					obj_typ: 'polyline'
					});
				objectArray.push(newObject);
				objectNummer++;
			}

			if (event.type == google.maps.drawing.OverlayType.POLYGON) {
				var newObject = event.overlay;
				newObject.setValues({
					obj_nummer: objectNummer,
					obj_parameter: newObject.getPath().getArray(),
					obj_typ: 'polygon'
					});
				objectArray.push(newObject);
				objectNummer++;
			}

			if (event.type == google.maps.drawing.OverlayType.CIRCLE) {
				var newObject = event.overlay;
				newObject.setValues({
					obj_nummer: objectNummer,
					obj_lat: newObject.getCenter().lat().toFixed(6),
					obj_lon: newObject.getCenter().lng().toFixed(6),
					obj_parameter: newObject.getRadius(),
					obj_typ: 'circle'
					});
				objectArray.push(newObject);
				objectNummer++;
			}

			if (event.type == google.maps.drawing.OverlayType.MARKER && marker_typ == 'metManual') {
				var newMarker = event.overlay;
				var latitude= newMarker.getPosition().lat().toFixed(6);
				var longitude= newMarker.getPosition().lng().toFixed(6);
				generateMET(map, latitude, longitude);
			}

			if (event.type == google.maps.drawing.OverlayType.MARKER && marker_typ == 'messpunkt') {
				var newMarker = event.overlay;
				newMarker.setValues({
					obj_nummer: messpunktnummer,
					obj_lat: newMarker.getPosition().lat().toFixed(6),
					obj_lon: newMarker.getPosition().lng().toFixed(6),
					obj_farbe: marker_color,
					obj_typ: 'marker',
					obj_messwert: '0',
					obj_hinweis: ' '
					});
				newMarker.content = '';
				objectArray.push(newMarker);
				messpunktnummer++; // Messpunktnummer inkrementieren

				google.maps.event.addListener(newMarker,'click',function(){ // Öffnet Infowindow bei Klick auf Marker
					activeObject = this; // Setzt den aktuell ausgewählten marker als aktiv
					let index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer && x.obj_typ == this.obj_typ); // Ermittelt Array-Index des aktuellen Markers
					this.setValues({messwert: objectArray[index].messwert}); // Aktualisiert den aktullen Messwert aus dem im Array gespeicherten Wert
					infoWindow.setContent('<h5>Messpunkt '+ objectArray[index].obj_nummer + '</h5><span class="fa fa-map-marker" aria-hidden="true"></span> '+	objectArray[index].obj_lat+', '+ objectArray[index].obj_lon+'<hr>'+
						'<form><div class="form-group"> <label for="messwert">Messwert [ppm]</label><input type="text" class="form-control" id="messwert" cols="50" value = "'+objectArray[index].obj_messwert+'" onchange="updateMesswert('+objectArray[index].obj_nummer+', this.value);"></div>'+
						'<div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis('+objectArray[index].obj_nummer+', this.value);" rows="5">'+objectArray[index].obj_hinweis+'</textarea></div></form>'+
						'<div class="btn-group" role="group" aria-label="Optionen">'+
						'<button type="button" class="btn btn-default btn-danger" style="height:46px;" id="deleteButton" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',1);"><img src="images/beachflag.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',2);"><img src="images/greenflag.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',3);"><img src="images/redflag.png"></button></div>');
					infoWindow.open(map,this);
				});//Ende eventlistener
				
				google.maps.event.addListener(newMarker,'dragend', function(){ //Aktualisiert Array und InfoWindow wenn Marker verschoben wird
					let index = objectArray.findIndex(x => x.obj_nummer == newMarker.obj_nummer  && x.obj_typ == newMarker.obj_typ);  // Ermittelt Array-Index des aktuellen Markers
					objectArray[index].obj_lat = newMarker.getPosition().lat().toFixed(6); // Aktualisiert geogr. Position im Array
					objectArray[index].obj_lon = newMarker.getPosition().lng().toFixed(6);// Aktualisiert geogr. Position im Array	
					infoWindow.setContent('<h5>Messpunkt '+ objectArray[index].obj_nummer + '</h5><span class="fa fa-map-marker" aria-hidden="true"></span> '+	objectArray[index].obj_lat+', '+ objectArray[index].obj_lon+'<hr>'+
						'<form><div class="form-group"> <label for="messwert">Messwert [ppm]</label><input type="text" class="form-control" id="messwert" cols="50" value = "'+objectArray[index].obj_messwert+'" onchange="updateMesswert('+objectArray[index].obj_nummer+', this.value);"></div>'+
						'<div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis('+objectArray[index].obj_nummer+', this.value);" rows="5">'+objectArray[index].obj_hinweis+'</textarea></div></form>'+
						'<div class="btn-group" role="group" aria-label="Optionen">'+
						'<button type="button" class="btn btn-default btn-danger" style="height:46px;" id="deleteButton" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',1);"><img src="images/beachflag.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',2);"><img src="images/greenflag.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',3);"><img src="images/redflag.png"></button></div>');
				});
				
				// Add an event listener that selects the newly-drawn shape when the user
                    // mouses down on it.
				google.maps.event.addListener(newShape, 'click', function (e) {
                    if (e.vertex !== undefined) {
                        if (newShape.type === google.maps.drawing.OverlayType.POLYGON) {
                            var path = newShape.getPaths().getAt(e.path);
                            path.removeAt(e.vertex);
                            if (path.length < 3) {
                                newShape.setMap(null);
                            }
                        }
                        if (newShape.type === google.maps.drawing.OverlayType.POLYLINE) {
                            var path = newShape.getPath();
                            path.removeAt(e.vertex);
                            if (path.length < 2) {
                                newShape.setMap(null);
                            }
                        }
                    }
                    setSelection(newShape);
                });//Ende eventlistener
                setSelection(newShape);
            } //Ende if (marker_typ = messpunkt)
            else {
                google.maps.event.addListener(newShape, 'click', function (e) {
                    setSelection(newShape);
                });
                setSelection(newShape);
            }
			
			google.maps.event.addDomListener(document, 'keyup', function (e) { // Durch drücken der Entf-Taste wird ausgewähltes Element gelöscht.
				var code = (e.keyCode ? e.keyCode : e.which);
				if (code === 46) {
					deleteSelectedShape();
				}
			});
		}); //Ende evetlistener drawingmanager
		google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
		google.maps.event.addListener(map, 'click', clearSelection);
	}//Ende function startDrawingmanager()
	
	function clearSelection() {
        if (selectedShape) {
            if (selectedShape.type !== 'marker') {
                selectedShape.setEditable(false);
            }
            selectedShape = null;
        }
    }

    function setSelection(shape) {
        if (shape.type !== 'marker') {
            clearSelection();
            shape.setEditable(true);
        }
        selectedShape = shape;
    }

    function deleteSelectedShape() {
        if (selectedShape) {
            selectedShape.setMap(null);
        }
    }

	function updateMesswert(messwertId, messwertValue){
		var index = objectArray.findIndex(x => x.obj_nummer == messwertId);
		objectArray[index].obj_messwert = messwertValue;
	}

	function updateHinweis(hinweisId, hinweisValue){
		var index = objectArray.findIndex(x => x.obj_nummer == hinweisId);
		objectArray[index].obj_hinweis = hinweisValue;
	}
	</script> <!-- Google DrawingManager laden -->
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
					<div class="text-center">	
						Das Projekt "Digitale Ausbreitungsabschätzung Luftgetragener Schadstoffe" (DALUS) dient zur Darstellung von Ausbreitungsabschätzungen luftgetragener Schadstoffemissionen und der Dokumentation von Messeinsätzen im Rahmen der operativen Gefahrenwehr.<br/><br/>
						Es wurde vom Fachdienst ABC der Landeshauptstadt Magdeburg entwickelt.<br/><hr /><br/>
						<span xmlns:dct="https://purl.org/dc/terms/" property="dct:title">DALUS</span> von <a xmlns:cc="https://creativecommons.org/ns#" href="https://www.cuzcomd.de/" property="cc:attributionName" rel="cc:attributionURL">Marco Trott</a>´ist lizenziert unter einer <a rel="license" href="https://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Namensnennung - Nicht-kommerziell - Weitergabe unter gleichen Bedingungen 4.0 International Lizenz</a>.<br/><br/>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-xs-4">Version: 2.1.1</div>
						<div class="col-xs-4"><a rel="license" href="https://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Creative Commons Lizenzvertrag" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a></div>
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
							<select multiple class="form-control" type="text" id="newProjektShared" name="shared[]">
								<?php 
								foreach ($pdo->query("SELECT * FROM users WHERE id != $userid") as $row) { // Gibt alle Benutzernamen, außer dem Eigenen, in einer List aus
								echo "<option value='".$row['id']."''>".$row['benutzername']."</option>";
								} ?>
							</select>
						</div>
						<div class="text-center">
							<button type='submit' class='btn btn-primary'><span class='fa fa-check-square-o'></span> Projekt anlegen</button>
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
							<select class="form-control" type="text" id="projectOpen" name="project_open">
							</select>
						</div>
						<div class="text-center">
							<button type='submit' class='btn btn-primary'><span class='fa fa-check-square-o'></span> Projekt öffnen</button>
						</div>
					</form>
					<h5>Für mich freigegebene Projekte</h5>
					<form action='' class="ajax_load_project" method='POST' role='form'>
						<div class="form-group">
							<select class="form-control" id="projectOpenShared" name="project_open">
							</select>
						</div>
						<div class="text-center">
							<button type='submit' class='btn btn-primary'><span class='fa fa-check-square-o'></span> Projekt öffnen</button>
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
							<select multiple class="form-control" type="text" id="projektShared" name="shared[]">
								<!-- Hier erscheinen die Benutzernamen, für die das Projekt freigegeben wurde -->
							</select>
						</div>
						<div class="text-center">
							<button type='submit' class='btn btn-primary'><span class='fa fa-check-square-o'></span> Änderung Speichern</button>
						</div>
					</form>
				</div><!-- Ende modal-body -->
			</div><!-- Ende modal-content -->
		</div><!-- Ende modal-dialog -->
	</div><!-- Ende Modal_edit_project -->

	<div class="modal fade" id="modalUserSettings" tabindex="-1" role="dialog" aria-labelledby="Profil">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Daten ändern</h4>
				</div>
				<div class="modal-body">
					<form action='' method='POST' class='ajax_edit_user form-horizontal' role='form'>
						<div class="form-group">
							<label for="username" class="control-label col-sm-4">Benutzername</label>
							<div class="col-sm-8">
								<input class="form-control" id="username" type="text" placeholder="Benutzername" name="username" value="" size="40" maxlength="250" required>
							</div>
							<label for="passwordOld" class="control-label col-sm-4">Aktuelles Passwort</label>
							<div class="col-sm-8">     	
								<input type="password" id="passwordOld" size="40"  maxlength="250" name="oldPassword" class="form-control" placeholder="Aktuelles Passwort" required>
						
							</div>
						</div>
						<div class="form-group">
							<label for="password1" class="control-label col-sm-4">Neues Passwort</label> 
							<div class="col-sm-8">    	
								<input type="password" id="password1" size="40"  maxlength="250" name="password1" class="form-control" placeholder="Neues Passwort">
							</div>
							<label for="password2" class="control-label col-sm-4">Passwort wiederholen</label>
							<div class="col-sm-8">
								<input type="password" id="password1" size="40"  maxlength="250" name="password2" class="form-control" placeholder="Neues Passwort">
							</div>
						</div>
						<div class="text-center">
							<button type='submit' class='btn btn-primary'><span class='fa fa-check-square-o'></span> Änderung speichern</button>
						</div>
					</form>
				</div><!-- Ende modal-body -->
			</div><!-- Ende modal-content -->
		</div><!-- Ende modal-dialog -->
	</div><!-- Ende Modal_UserSettings -->

	<div class="modal fade" id="modal_MET" tabindex="-1" role="dialog" aria-labelledby="MET Ausbreitungsmodell">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">MET Ausbreitungsmodell</h4>
				</div>
				<div class="modal-body">
					<div id="wrapper_winkelrechner">
						<div id="geocoder">
							<div style="padding-bottom:15px;">
								<button type="button" class="btn btn-primary" id="calcMET" data-toggle="tooltip" title="MET-Freisetzungsort aus Adressfeld lesen" style="margin-bottom:5px;"><i class="fa fa-crosshairs"></i> Zeichnen (Adresse)</button>
								<button type="button" class="btn btn-primary" id="calcMETmanual" data-toggle="tooltip" title="MET Freisetzungsort manuell festlegen" style="margin-bottom:5px;"><i class="fa fa-crosshairs"></i> Zeichnen (Manuell)</button>
								<span data-toggle="tooltip" title="MET Ausbreitungswinkel berechnen">
								<button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#winkelrechner" style="margin-bottom:5px;"><i class="fa fa-arrows-h"></i> Winkel bestimmen</button>
								</span>
							</div>
							<form id="input-form" class="form-horizontal" role="form">
								<div class="form-group" data-toggle="tooltip" title="Freisetzungsort">
									<div class="col-xs-12">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-home"></i></span>
											<input id="addresse" type="textbox" value="Alt Diesdorf 4, Magdeburg" class="form-control">
										</div>
									</div>
								</div>
		
								<div class="form-group" data-toggle="tooltip" title="Ausbreitungswinkel">
									<div class="col-xs-12">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-arrows-h"></i></span>
											<select id="winkel" name="winkel" class="form-control">
												<option value="45" label="45&deg;">45&deg;</option>
												<option value="60" label="60&deg;" selected>60&deg;</option>
												<option value="90" label="90&deg;">90&deg;</option>
												<option value="360" label="360&deg;">360&deg;</option>
											</select>
										</div>
									</div>
								</div>
		
								<div class="form-group" data-toggle="tooltip" title="Windrichtung">
									<div class="col-xs-12">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
											<input id="windrichtung" type="number" value="280" class="form-control" onchange="document.getElementById('arrow').style.transform = 'rotate('+(this.value-90)+'deg)';">
											<span class="input-group-addon">&deg;</span>
										</div>
									</div>
								</div>
		
								<div class="form-group" data-toggle="tooltip" title="Gef&auml;hrdung f&uuml;r Personen im Geb&auml;ude">
									<div class="col-xs-12">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-exclamation"></i> <i class="fa fa-home"></i></span>
											<input id="distanz1" type="number" value="600" class="form-control">
											<span class="input-group-addon">m</span>
										</div>
									</div>
								</div>
									
								<div class="form-group" data-toggle="tooltip" title="Gef&auml;hrdung f&uuml;r Personen im Freien">
									<div class="col-xs-12">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-exclamation"></i> <i class="fa fa-street-view"></i></span>
											<input id="distanz2" type="number" value="1300"  class="form-control">
											<span class="input-group-addon">m</span>
										</div>
									</div>
								</div>
							</form>
						</div> <!-- Ende div Geocoder -->
						<div id="winkelrechner" class="collapse panel panel-default">
							<div class="panel-body">
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
										<label for="windgeschwindigkeit" class="col-xs-4 form-control-label">Windgeschwindigkeit</label>
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
							</div> <!-- Ende panel-body -->
							<div class="alert alert-warning alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<strong>Hinweis:</strong><br/>Der Winkel wird automatisch aktualisiert.
							</div>
						</div> <!-- Ende Winkelrechner -->
					</div> <!-- Ende Wrapper_Winkelrechner -->
				</div><!-- Ende modal-body -->
			</div><!-- Ende modal-content -->
		</div><!-- Ende modal-dialog -->
	</div><!-- Ende Modal_edit_project -->

	<div class="hidden-print col-xs-3 floating-panel" id="Wrapper_menue">
		<div class="currentProject">
			<h5><span class="fa fa-folder-open" aria-hidden="true"></span> <span id="activeProject">&nbsp; Kein Projekt geöffnet</span></h5>
		</div>
		<ul class="nav nav-tabs">
			<li class="dropdown" id ="project_options" role="presentation" data-toggle="tooltip" data-placement="bottom" title="Hauptmenü">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bars" aria-hidden="true"></i>
				<span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li style="border-bottom: 1px solid #ccc;" data-placement="bottom" title="Profil bearbeiten" role="button"><a data-toggle="modal" data-target="#modalUserSettings"><i class="fa fa-user-circle" aria-hidden="true"></i><span id="activeUser">&nbsp; Kein Benutzer aktiv</span> <i class='fa fa-cogs' aria-hidden="true"></i></a></li>
					<li id="newProject" data-placement="bottom" title="Neues Projekt" role="button"><a data-toggle="modal" data-target="#modal_new_project"><i class="fa fa-pencil-square-o"></i> Neues Projekt</a></li>
					<li id="openProject" data-placement="bottom" title="Projekt öffnen" role="button"><a data-toggle="modal" data-target="#modal_open_project"><i class="fa fa-folder-open-o"></i> Projekt öffnen</a></li>
					<li id="editProject" data-placement="bottom" title="Projekt ändern" role="button" style="display:none;"><a data-toggle="modal" data-target="#modal_edit_project"><i class="fa fa-pencil"></i> Projekt ändern</a></li>
					<li id="saveProject" role="button" style="display:none;"><a><i class="fa fa-floppy-o" aria-hidden="true"></i> Projekt speichern</a></li>
					<li id="deleteProject" role="button" style="display:none;"><a><i class="fa fa-floppy-o" aria-hidden="true"></i> Projekt löschen</a></li>
					<li id="printMap" role="button" onclick="printMap();"><a><i class="fa fa-print" aria-hidden="true"></i> Ansicht drucken</a></li>
					<li role="button" onclick="location.href='logout'"><a><i class="fa fa-sign-out" aria-hidden="true"></i> Abmelden</a></li>
					<li role="button" ><a data-toggle="modal" data-target="#modal_license"><i class="fa fa-info-circle" aria-hidden="true"></i> Informationen</a></li>
				</ul>
			</li>
			<li class="dropdown" id ="parameter" role="presentation" data-toggle="tooltip" data-placement="bottom" title="Parameter">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-wrench" aria-hidden="true"></i>
				<span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li id = "switchMesspunkte" data-click-state="0"><a><i class="fa fa-toggle-off" aria-hidden="true"></i> Messkataster</a></li>
					<li id = "switchKompass" type="button"><a><i class="fa fa-toggle-off" aria-hidden="true"></i> Kompass</a></li>
				</ul>
			</li>
			<li class="active" type="button" id ="switch_parameter" data-toggle="tooltip" data-placement="bottom" title="Auf Karte zeichnen"><a data-toggle="tab" href="#floating-panel"><i class="fa fa-paint-brush" aria-hidden="true"></i></a></li>
			<li id ="switch_winkel" data-toggle="tooltip" data-placement="bottom" title="Parameter des MET-Modells anpassen"><a href="#" data-toggle="modal" data-target="#modal_MET">MET</a></li>
		</ul>
		<div class="tab-content">
		    <div id ="floating-panel" class="tab-pane fade in active">
				<div class="werkzeuge text-center">
					<ul class="nav nav-pills" style="display:inline-block;">
						<li id="setHand" data-toggle="tooltip" data-placement="bottom" title="Auswahl"><a data-toggle="tab"><i class="fa fa-mouse-pointer"></i></a></li>
						<li id="setCirc" data-toggle="tooltip" data-placement="bottom" title="Kreis zeichnen"><a data-toggle="tab"><i class="fa fa-circle-thin"></i></a></li>
						<li id="setPoly" data-toggle="tooltip" data-placement="bottom" title="Polygon zeichnen"><a data-toggle="tab"><i class="fa fa-bookmark-o"></i></a></li>
						<li id="setPath" data-toggle="tooltip" data-placement="bottom" title="Pfad zeichnen"><a data-toggle="tab"><i class="fa fa-pencil"></i></a></li>
						<li id="deleteActiveObject" data-toggle="tooltip" data-placement="bottom" title="Objekt löschen"><a data-toggle="tab"><i class="fa fa-trash"></i></a></li>
						<li class="dropdown" data-toggle="tooltip" data-placement="bottom" title="Messpunkte setzen">
		    				<a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-flag-o"></i>
		    				<span class="caret"></span></a>
		    				<ul class="dropdown-menu">
								<li id="setMark"><a data-toggle="tab"><img src="images/beachflag.png"> Unbekannt</a></li>
								<li id="setMarkGreen"><a data-toggle="tab"><img src="images/greenflag.png"> In Ordnung</a></li>
								<li id="setMarkRed"><a data-toggle="tab"><img src="images/redflag.png"> Überschritten </a></li>
							</ul>
						</li>
					</ul>
				</div> <!-- Ende Werkzeuge -->
			</div><!-- Ende Floating_Panel -->
		</div>	<!-- Ende Tab_content -->
	</div>	<!-- Hauptmenü - Ende Wrapper_menue -->
	<div class="windrose"><img src="images/arrow.png" alt="Windrose" id="arrow"/></div> <!-- Ende Windrose -->
	<div id="map"></div>

	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABx82S4RteOeTHKqB-yuw9FkdTIrEmHuE&libraries=geometry,drawing&callback=initMap" async defer></script> <!-- GooleAPI laden. Hier muss der API-Schlüssel eingetragen werden. -->
	<script src="js/bootstrap.min.js"></script> <!-- Bootstrap.js laden -->
	<script src="js/html2canvas.min.js"></script>
	<script src="js/usng.js" defer></script> <!-- Script für Umwandlung von Geokoordinaten in UTM-Ref Koordinaten -->
	<script defer> // Ádresse des MET-Modells durch Eingabemaske oder manuelle Festlegung bestimmen
	function generateMET(resultsMap, manualLat, manualLon) {
		if (manualLat !== undefined && manualLon !== undefined) { //Überprüfen, ob MET-Freisetzungsort manuell festgelegt wurde
			var latitude = manualLat;
			var longitude = manualLon;
			var latlng = {lat: Number(latitude), lng: Number(longitude)};
			new google.maps.Geocoder().geocode({'location': latlng}, function(results, status) {
				if (status === google.maps.GeocoderStatus.OK) {
					resultsMap.setCenter(results[0].geometry.location);
					var adresse = results[1].formatted_address;
					drawPolygon(resultsMap, latitude, longitude, adresse);
				} 
				else 
				{
					alert('Die Adresse konnte nicht ermittelt werden. Grund: ' + status);
					
				}
			}); //Ende reverse geocoder
		} // Ende if(manualLat !== undefined)
		else{ //Überprüfen, ob MET-Freisetzungsort im Eingabefeld festgelegt wurde
			var adresse = document.getElementById('addresse').value;
			new google.maps.Geocoder().geocode({'address': adresse}, function(results, status) {
				if (status === google.maps.GeocoderStatus.OK) {
					resultsMap.setCenter(results[0].geometry.location);
					var latitude = resultsMap.getCenter().lat();
					var longitude = resultsMap.getCenter().lng();
					drawPolygon(resultsMap, latitude, longitude, adresse);
				} 
				else 
				{
					alert('Die Adresse konnte nicht ermittelt werden. Grund: ' + status);
				}
			}); //Ende geocoder
		} //Ende if(manualLat !== undefined)
	}// Ende function generateMET()
	</script><!-- MET-Modell generieren -->

	<script defer> // Polygon berechnen und Freisetzungsort-Marker setzen
	function drawPolygon(map, lat, lon, geoAdresse, winkel, richtung, innen, aussen, counter){
		$('#modal_MET').modal('hide');
		if (winkel !== undefined) {
			var ausbreitungswinkel = winkel;
		} 
		else {
			var ausbreitungswinkel = parseInt(document.getElementById('winkel').value);
		}

		if (richtung !== undefined) {
			var windrichtung_initial = richtung; // Eingegebene Windrichtung speichern
			var windrichtung = richtung-180;
		} 
		else {
			var windrichtung = parseInt(document.getElementById('windrichtung').value);
			var windrichtung_initial = windrichtung; // Eingegebene Windrichtung speichern
			windrichtung = windrichtung-180; // Google rechnet mit Windzugrichtung, daher muss Winkel um 180 Grad gedreht werden
		}
		document.getElementById('arrow').style.transform = 'rotate('+(windrichtung+90)+'deg)';
		
		if (innen !== undefined) {
			var distanz_innen = innen;
		} 
		else {
			var distanz_innen = parseInt(document.getElementById('distanz1').value);
		}	

		if (aussen !== undefined) {
			var distanz_aussen = aussen;
		}
		else {
			var distanz_aussen = parseInt(document.getElementById('distanz2').value);
		}

		if (geoAdresse !== undefined) {
			var adresse = geoAdresse;
		}
		else {
			var adresse = document.getElementById('addresse').value;
		}

		if (counter !== undefined) {
			metCounter = parseInt(counter);
		} 
		else {
		}
		
		var metParameter = {ausbreitungswinkel: ausbreitungswinkel,
							windrichtung: windrichtung_initial,
							distanz_innen: distanz_innen,
							distanz_aussen: distanz_aussen,
							adresse: adresse
							};
		var halbwinkel = ausbreitungswinkel / 2; // Winkelhalbierende berechnen
		var utm_koord = LLtoUSNG(lat, lon, 5); // UTM-Ref Koordinaten berechnen
		var ursprung = new google.maps.LatLng(lat, lon);
		var p11 = new google.maps.geometry.spherical.computeOffset(ursprung, distanz_innen, windrichtung+halbwinkel); // Eckpunkt 1 für Polygon 1 berechnen
		var p12 = new google.maps.geometry.spherical.computeOffset(ursprung, distanz_innen, windrichtung-halbwinkel); // Eckpunkt 2 für Polygon 1 berechnen
		var p21 = new google.maps.geometry.spherical.computeOffset(ursprung, distanz_aussen, windrichtung+halbwinkel); // Eckpunkt 1 für Polygon 2 berechnen
		var p22 = new google.maps.geometry.spherical.computeOffset(ursprung, distanz_aussen, windrichtung-halbwinkel); // Eckpunkt 2 für Polygon 2 berechnen
		
		// Polygon 2 (Gefährdung im Freien) zeichnen
		var punkte2 = new Array();
		var i = windrichtung-halbwinkel;
		if(ausbreitungswinkel != 360){
			for(var i; (i <= windrichtung+halbwinkel); i++) {
				punkte2.push(new google.maps.geometry.spherical.computeOffset(ursprung, distanz_innen, i));
			}
			punkte2.push(p11);
		}
		
		var i = windrichtung+halbwinkel;
		for(var i; (i >= windrichtung-halbwinkel); i--) {
			punkte2.push(new google.maps.geometry.spherical.computeOffset(ursprung, distanz_aussen, i));
		}
		
		if(ausbreitungswinkel != 360){
			punkte2.push(p12);
		}
		
		var polygon2 = new google.maps.Polygon({
			map: map,
			paths: [punkte2],
			strokeColor: '#FF9933',
			strokeOpacity: 0.8,
			strokeWeight: 3,
			fillColor: '#FF9933',
			fillOpacity: 0.1,
			geodesic: true,
			obj_nummer : metCounter,
			obj_typ : 'polygon2'
		});

		var punkte = new Array();
		var i =windrichtung-halbwinkel;

		if(ausbreitungswinkel != 360){
			punkte.push(ursprung);
		}

		for(var i; (i <= windrichtung+halbwinkel); i++) {
			punkte.push(new google.maps.geometry.spherical.computeOffset(ursprung, distanz_innen, i));
		}
		
		// Polygon 1 (Gefährdung im Gebäude) zeichnen
		var polygon1 = new google.maps.Polygon({
			map: map,
			paths: [punkte],
			strokeColor: '#FF3333',
			strokeOpacity: 0.8,
			strokeWeight: 3,
			fillColor: '#FF9999',
			fillOpacity: 0.1,
			geodesic: true,
			obj_nummer : metCounter,
			obj_typ : 'polygon1'
		});
		
		if(ausbreitungswinkel != 360){
			var mitte = new google.maps.geometry.spherical.computeOffset(ursprung, distanz_aussen, windrichtung);
			var line = new google.maps.Polyline({
				path: [ursprung, mitte],
				strokeOpacity: 0.8,
				strokeColor: '#333333',
				strokeWeight: 2,
				map: map,
				geodesic:true,
				obj_nummer : metCounter,
				obj_typ : 'polygonCenter'
			});
		}

		// Marker am Freisetzungsort erstellen 
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(lat, lon),
			obj_lat: lat,
			obj_lon: lon,
			obj_parameter: metParameter,
			obj_typ: 'met',
			obj_nummer: metCounter,
  			map: map,
  			title: 'Freisetzungsort',
  			draggable:false,
  			poly1: polygon1,
  			poly2: polygon2,
  			centerLine:line
		});

			objectArray.push(marker);
			metCounter ++;

		marker.addListener('click', function() {//Informationsfenster bei Klick auf Marker öffnen
		activeObject = this; // Setzt den aktuell ausgewählten marker als aktiv
		infoWindow.setContent('<h5>Freisetzungsort</h5>'+
			'<div class="fa fa-home"></div> '+adresse+'<br/><hr>' +
			'<div class="fa fa-map-marker"></div> ' + this.obj_lat +' , ' + this.obj_lon +'<br/> (' + utm_koord + ')<br/><br/>'+
			'Gef&auml;hrdung im Geb&auml;ude: ' + distanz_innen + ' m<br/>' +
			'Gef&auml;hrdung im Freien: ' + distanz_aussen + ' m<br/><br/>' +
			'Windrichtung: ' + windrichtung_initial + '&deg;' + '<br/>' +
			'Ausbreitungswinkel: ' + ausbreitungswinkel + ' &deg;'  + '<br/><br/>'+
			'<div class="btn-group" role="group" aria-label="Optionen">'+
			'<button type="button" class="btn btn-default btn-danger" style="height:46px;" id="deleteButton" onclick="deleteObject();"><i class="fa fa-trash-o"></i></button>')
		infoWindow.open(map,marker);
		});
	} //Ende function drawPolygon()
	</script><!-- Polygon berechnen und Marker setzen -->
	<script defer> // Ausbreitungswinkel berechnen
	function computeAngle(){
		var nebel = document.getElementById('nebel').value;
		var wind = document.getElementById('windgeschwindigkeit').value;
		var himmel = document.getElementById('himmel').value;
		var tageszeit = document.getElementById('tageszeit').value;
		var monat = document.getElementById('monat').value;
		var brand = document.getElementById('brand').value;
		if(nebel=="true")
			met_winkel=45;
		else if(wind == "high")
			met_winkel=60;
		else if(himmel=="true")
			met_winkel=60;
		else if(tageszeit=="night")
		{
			if(wind!="high")
			{
				if(brand=="true")
					met_winkel=60;
				else
					met_winkel=45;
			}
			else 
				met_winkel=45;
		}
		else if(wind=="low")
		{
			if(monat=="om")
			{
				if(brand=="true")
					met_winkel=90;
				else
					met_winkel=60;
			}
			else
				met_winkel=90;
		}
		else if(brand=="true")
			met_winkel=90;
		else
			met_winkel=60;
		
		document.getElementById("winkel").value=met_winkel; // Berechneten Winkel in MET-Auswahlfeld eintragen	
	}
	</script><!-- Ausbreitungswinkel berechnen -->
	<script defer> // Fixpunkte aus Datei laden
	function loadFixpoints(switchMesspunkte){
		switchMesspunkte.find('i').toggleClass("fa-toggle-off fa-toggle-on"); // Damit Menüpunkt farblich hinterlegt wird
		if(switchMesspunkte.attr('data-click-state') == 1) { 
			switchMesspunkte.attr('data-click-state', 0) // Wenn Schalter aktiviert ist, ihn wieder deaktivieren
			map.data.forEach(function (feature) {map.data.remove(feature);}) // Alle Fixpunkte von der Karte löschen
		}
		else {
			switchMesspunkte.attr('data-click-state', 1); // Wenn Schalter deaktiviert ist, ihn wieder aktivieren
			map.data.loadGeoJson('js/messpunkte.json'); // Alle Messpunkte aus json-Datei laden
			map.data.setStyle({icon: 'images/radioactive_flag.png'});
			
			map.data.addListener('click', function(event) {
				var mpNummer = event.feature.getProperty('nummer');
				var mpName = event.feature.getProperty('name');
				var mpAdresse = event.feature.getProperty('adresse');
				var mpODL = event.feature.getProperty('odl');
				var mpIPS = event.feature.getProperty('ips');
				var mpLatitude = event.feature.getGeometry().get().lat();
				var mpLongitude = event.feature.getGeometry().get().lng();

				infoWindow.setContent("<div > Messpunkt "+ mpNummer +' (' + mpName +')<hr><br/><div class="fa fa-home"></div> ' + mpAdresse +
				'<br/><br/><div class="fa fa-map-marker"></div> ' + mpLatitude +' , ' + mpLongitude +' (' + LLtoUSNG(mpLatitude, mpLongitude, 5) +
				')<br/><br/> Ortsdosisleistung: ' + mpODL + ' nSv/h <br/>Nullrate: ' + mpIPS + ' Imp/s</div>');
				infoWindow.setPosition(event.feature.getGeometry().get());
				infoWindow.setOptions({pixelOffset: new google.maps.Size(0,-30)});
				infoWindow.open(map);
			});
		} //Ende else
	}//Ende function loadFixpoint
	</script><!-- Fixpunkte laden -->
	<script type="text/javascript" defer> // Projektgeometrie aus Datenbank laden
	function loadProjectObjects(){
		for (var i = 0; i < objectArray.length; i++ ) {
			objectArray[i].setMap(null);
		}
		objectArray = [];
		var data = {
			"task" : "load",
			"prj_id" : prj_id
			};
		data = $(this).serialize() + "&" + $.param(data);
		
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "geometry.php",
			data: data,
			success: function(data) {
				drawObjects(data);
			}, //Ende success
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			} //ende error
		}); //Ende Ajax
	} //Ende function loadProjectObjects

	function drawObjects(theArray){
		for (const value of theArray) {
			switch(value.obj_farbe) {
				case 'yellow':
				var icon_type = 'images/beachflag.png';
				break;

				case 'green':
				var icon_type = 'images/greenflag.png';
				break;

				case 'red':
				var icon_type = 'images/redflag.png';
				break;

				default:
				var icon_type = 'images/redflag.png';
			}

			switch(value.obj_typ) {
				case 'marker':
				let geom_obj = new google.maps.Marker({
				map:map,
				position: {lat:Number(value.obj_lat), lng:Number(value.obj_lon)},
				obj_lat: Number(value.obj_lat),
				obj_lon: Number(value.obj_lon),
				icon:{url:icon_type, anchor: new google.maps.Point(0, 32)},
				obj_nummer: Number(value.obj_nummer),
				obj_farbe: value.obj_farbe,
				obj_messwert: Number(value.obj_messwert),
				obj_hinweis: value.obj_hinweis,
				obj_typ: 'marker',
				obj_parameter : '',
				info: infoWindow,
				content: value.obj_parameter,
				draggable:true
				});

				objectArray.push(geom_obj);

				google.maps.event.addListener(geom_obj,'click',function(){ // Öffnet Infowindow bei Klick auf Marker
					clearSelectionLoad();
					activeObject = this;
					var index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer); // Ermittelt Array-Index des aktuellen Markers
					this.setValues({messwert: objectArray[index].obj_messwert}); // Aktualisiert den aktullen Messwert aus dem im Array gespeicherten Wert
					this.info.setContent('<h5>Messpunkt '+ objectArray[index].obj_nummer + '</h5><span class="fa fa-map-marker" aria-hidden="true"></span> '+	objectArray[index].obj_lat+', '+ objectArray[index].obj_lon+'<hr>'+
						'<form><div class="form-group"> <label for="messwert">Messwert [ppm]</label><input type="text" class="form-control" id="messwert" cols="50" value = "'+objectArray[index].obj_messwert+'" onchange="updateMesswert('+objectArray[index].obj_nummer+', this.value);"></div>'+
						'<div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis('+objectArray[index].obj_nummer+', this.value);" rows="5">'+objectArray[index].obj_hinweis+'</textarea></div></form>'+
						'<div class="btn-group" role="group" aria-label="Optionen">'+
						'<button type="button" class="btn btn-default btn-danger" style="height:46px;" id="deleteButton" onclick="deleteObject();"><i class="fa fa-trash-o"></i></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',1);"><img src="images/beachflag.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',2);"><img src="images/greenflag.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',3);"><img src="images/redflag.png"></button></div>');
					this.info.open(map,this);
				}); // Ende des Eventlisteners

				google.maps.event.addListener(geom_obj,'dragend', function(){ //Aktualisiert Array und Infowindow wenn Marker verschoben wird
					let index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer  && x.obj_typ == this.obj_typ);  // Ermittelt Array-Index des aktuellen Markers
					objectArray[index].obj_lat = this.getPosition().lat().toFixed(6); // Aktualisiert geogr. Position im Array
					objectArray[index].obj_lon = this.getPosition().lng().toFixed(6);// Aktualisiert geogr. Position im Array	
					infoWindow.setContent('<h5>Messpunkt '+ objectArray[index].obj_nummer + '</h5><span class="fa fa-map-marker" aria-hidden="true"></span> '+	objectArray[index].obj_lat+', '+ objectArray[index].obj_lon+'<hr>'+
						'<form><div class="form-group"> <label for="messwert">Messwert [ppm]</label><input type="text" class="form-control" id="messwert" cols="50" value = "'+objectArray[index].obj_messwert+'" onchange="updateMesswert('+objectArray[index].obj_nummer+', this.value);"></div>'+
						'<div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis('+objectArray[index].obj_nummer+', this.value);" rows="5">'+objectArray[index].obj_hinweis+'</textarea></div></form>'+
						'<div class="btn-group" role="group" aria-label="Optionen">'+
						'<button type="button" class="btn btn-default btn-danger" style="height:46px;" id="deleteButton" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',1);"><img src="images/beachflag.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',2);"><img src="images/greenflag.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',3);"><img src="images/redflag.png"></button></div>');
				});
				break;

				case 'met':
				value.obj_parameter = JSON.parse(value.obj_parameter); //Parst den Parameter-String in ein verwertbares Objekt
				let lat = value.obj_lat;
				let lon = value.obj_lon;
				let winkel = value.obj_parameter.ausbreitungswinkel;
				let richtung = value.obj_parameter.windrichtung;
				let innen = value.obj_parameter.distanz_innen;
				let aussen = value.obj_parameter.distanz_aussen;
				let adresse = value.obj_parameter.adresse;
				let counter = value.obj_nummer; //Zähler für die Anzahl an Freisetzungsmarkern
				drawPolygon(map, lat, lon, adresse, winkel, richtung, innen, aussen, counter); //Zeichnet das Polygon

				google.maps.event.addListener(value,'click',function(){ // Öffnet Infowindow bei Klick auf Marker
					clearSelectionLoad();
					activeObject = this;
				}); // Ende des Eventlisteners
				break;

				case 'circle':
				let circleObj = new google.maps.Circle({
					map:map,
					center: {lat:Number(value.obj_lat), lng:Number(value.obj_lon)},
					obj_lat: Number(value.obj_lat),
					obj_lon: Number(value.obj_lon),
					radius: Number(value.obj_parameter),
					obj_nummer: Number(value.obj_nummer),
					obj_typ: 'circle',
					obj_parameter : Number(value.obj_parameter),
					draggable:true
				});

				objectArray.push(circleObj);

				google.maps.event.addListener(circleObj,'click',function(){ // Öffnet Infowindow bei Klick auf Marker
					clearSelectionLoad();
					activeObject = this;
					activeObject.setEditable(true);
				}); // Ende des Eventlisteners

				google.maps.event.addListener(circleObj,'dragend', function(){ //Aktualisiert Array und Infowindow wenn Marker verschoben wird
					let index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer  && x.obj_typ == this.obj_typ);  // Ermittelt Array-Index des aktuellen Markers
					objectArray[index].obj_lat = this.getCenter().lat().toFixed(6); // Aktualisiert geogr. Position im Array
					objectArray[index].obj_lon = this.getCenter().lng().toFixed(6);// Aktualisiert geogr. Position im Array	
				});

				google.maps.event.addListener(circleObj,'radius_changed', function(){ //Aktualisiert Array und Infowindow wenn Marker verschoben wird
					let index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer  && x.obj_typ == this.obj_typ);  // Ermittelt Array-Index des aktuellen Markers
					objectArray[index].obj_parameter = this.getRadius(); // Aktualisiert geogr. Position im Array
				});
				break;

				case 'polygon':
				let polygonObj = new google.maps.Polygon({
					map:map,
					paths: JSON.parse(value.obj_parameter),
					obj_nummer: Number(value.obj_nummer),
					obj_typ: 'polygon',
					draggable:true
				});
				polygonObj.setValues({
					obj_parameter: polygonObj.getPath().getArray()
				});
				objectArray.push(polygonObj);

				google.maps.event.addListener(polygonObj,'click',function(){ // Öffnet Infowindow bei Klick auf Marker
					clearSelectionLoad();
					activeObject = this;
					activeObject.setEditable(true);
				}); // Ende des Eventlisteners

				google.maps.event.addListener(polygonObj,'dragend', function(){ //Aktualisiert Array und Infowindow wenn Marker verschoben wird
					let index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer  && x.obj_typ == this.obj_typ);  // Ermittelt Array-Index des aktuellen Markers
					objectArray[index].obj_parameter = this.getPath().getArray(); // Aktualisiert geogr. Position im Array
				});
				break;

				case 'polyline':
				let polylineObj = new google.maps.Polyline({
					map:map,
					path: JSON.parse(value.obj_parameter),
					obj_nummer: Number(value.obj_nummer),
					obj_typ: 'polyline',
					draggable:true
				});

				polylineObj.setValues({
					obj_parameter: polylineObj.getPath().getArray()
				});

				objectArray.push(polylineObj);

				google.maps.event.addListener(polylineObj,'click',function(){ // Öffnet Infowindow bei Klick auf Marker
					clearSelectionLoad();
					activeObject = this;
					activeObject.setEditable(true);
				}); // Ende des Eventlisteners

				google.maps.event.addListener(polylineObj,'dragend', function(){ //Aktualisiert Array und Infowindow wenn Marker verschoben wird
					let index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer  && x.obj_typ == this.obj_typ);  // Ermittelt Array-Index des aktuellen Markers
					objectArray[index].obj_parameter = this.getPath().getArray(); // Aktualisiert geogr. Position im Array
				});
				break;

				default:
			} //Ende switch value.obj_typ
            google.maps.event.addListener(map, 'click', clearSelectionLoad); //Beim Klick auf die Karte wird ausgewähltes Objekt abgewählt
            google.maps.event.addDomListener(document, 'keyup', function (e) { // Durch drücken der Entf-Taste wird ausgewähltes Objekt gelöscht.
				var code = (e.keyCode ? e.keyCode : e.which);
				if (code === 46) {
					deleteObject();
				}
			});
		}; // Ende der for-Schleife
	} //Ende function drawObjects

	function clearSelectionLoad() {
		if (activeObject && activeObject.obj_typ != "marker" && activeObject.obj_typ != "met"){ //Funktion nur ausführen, wenn ein Objektz ausgewählt ist und es kein Marker ist
			activeObject.setEditable(false);
		}// Ende if(activeObject)
		activeObject = null; //Aktives Objekt zurücksetzen
	} //Ende der Funktion clearSelection()

	function deleteObject(){
		if(activeObject){ //Führt den Löschvorgang nur aus, wenn ein Objekt ausgewählt wurde
			var index = objectArray.findIndex(x => x.obj_nummer == activeObject.obj_nummer && x.obj_typ == activeObject.obj_typ);
			objectArray.splice(index,1); // Löscht das Objekt aus dem Objekt-Array
			let deleteObject = {nummer:activeObject.obj_nummer, typ:activeObject.obj_typ}; //Erzeugt das zu löschende Objekt
			deleteArray.push(deleteObject); //Verschiebt das zu löschende Objekt in den Lösch-Array

			if (activeObject.info !== undefined){ // Unterscheidet zwischen neu erzeugtem Marker (.info ist nicht definiert) und beim Laden generierten Marker
				activeObject.info.setMap(null);// Löscht das Infowindow von der Karte
			}

			activeObject.setMap(null);// Löscht das Objekt von der Karte

			if (activeObject.poly1 !== undefined && activeObject.poly2 !== undefined){ // Überprüft, ob es sich um einen MET-Marker handelt (dieser besitzt zwei Ausbreitungspolygone)
				activeObject.poly1.setMap(null);// Löscht das zugehörige Ausbreitungspolygon von der Karte
				activeObject.poly2.setMap(null);// Löscht das zugehörige Ausbreitungspolygon von der Karte
				activeObject.centerLine.setMap(null);// Löscht das zugehörige Ausbreitungspolygon von der Karte
			}
			activeObject = null; //Aktives Objekt zurücksetzen
		}// Ende if (activeObject)
	}// Ende Funktion deleteObject()

	function changeColor(markerNummer, color){
		var index = objectArray.findIndex(x => x.obj_nummer == activeObject.obj_nummer && x.obj_typ == activeObject.obj_typ);  //ermittelt Array-Index des aktuellen Markers
		switch(color){
			case 1:
			activeObject.setIcon({url:'images/beachflag.png', anchor: new google.maps.Point(0, 32)});
			objectArray[index].obj_farbe = "yellow";
			break;

			case 2:
			activeObject.setIcon({url:'images/greenflag.png', anchor: new google.maps.Point(0, 32)});
			objectArray[index].obj_farbe = "green";
			break;

			case 3:
			activeObject.setIcon({url:'images/redflag.png', anchor: new google.maps.Point(0, 32)});
			objectArray[index].obj_farbe = "red";
			break;

			default:
		}//Ende switch(color)	
	}//Ende Funktion changeColor()

	function deleteProject(){ // Löscht das aktuelle Projekt, sowie alle dazugehörigen Geometrieobjekte
		var data = {
			"action": "delete",
			"prj_id": prj_id
		};
		data = $(this).serialize() + "&" + $.param(data);
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "projects.php",
			data: data,
			success: function(data) {
				$("#activeProject").html("&nbsp; Kein Projekt geöffnet");
				$('#editProject').hide(); // Menüpunkt 'Projekt bearbeiten' anzeigen
				$('#saveProject').hide(); // Menüpunkt 'Projekt speichern' anzeigen
				$('#deleteProject').hide(); // Menüpunkt 'Projekt speichern' anzeigen
				prj_id = 0;
				clearMap();
				loadProjectObjects();
				updateProjects();
				updateSharedProjects();
				isSharedWith();
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		}); //Ende ajax
		toastr.error('Projekt gelöscht.');
		return false;
	} //Ende Funktion deleteProject()

	function printMap(){
        html2canvas($('#map'), {
        	useCORS: true,
        	onrendered: function (canvas) {
        		var img = canvas.toDataURL("image/png");
        		img = img.replace('data:image/png;base64,', '');
            	var finalImageSrc = 'data:image/png;base64,' + img;
            	window.open(finalImageSrc, 'Screenshot');
            }
    	});
    }//Ende Funktion printMap

	function clearMap(){
		for (var i = 0; i < objectArray.length; i++ ) {
			objectArray[i].setMap(null);
			if (objectArray[i].poly1 !== undefined && objectArray[i].poly2 !== undefined){ // Überprüft, ob es sich um einen MET-Marker handelt (dieser besitzt zwei Ausbreitungspolygone)
				objectArray[i].poly1.setMap(null);// Löscht das zugehörige Ausbreitungspolygon von der Karte
				objectArray[i].poly2.setMap(null);// Löscht das zugehörige Ausbreitungspolygon von der Karte
				objectArray[i].centerLine.setMap(null);// Löscht das zugehörige Ausbreitungspolygon von der Karte
			}//Ende if-Statement
		}//Ende for-Schleife
		objectArray = [];
		deleteArray = [];
	} //Ende Funktion clearMap()
	</script><!-- Projektgeometrie laden -->

	<script type="text/javascript" defer> // Ajax aufruf für Projekte
		$("document").ready(function(){
			$(".ajax_create_project").submit(function(){
				var data = {
					"action": "create"
				};
				data = $(this).serialize() + "&" + $.param(data);
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "projects.php",
					data: data,
					success: function(data) {
					toastr.success('Neues Projekt angelegt.');
					$("#activeProject").html("&nbsp; "+data["projekttitel"]);
					$('.activeProjectName').attr("value",data["projekttitel"]);
					$('.activeProjectID').val(data["projekt_id"]);
					$('.activeUserID').val(userID);
					prj_id = data["projekt_id"]; // In Datenbak erzeugte Projekt ID einlesen
					$('#editProject').show(); // Menüpunkt 'Projekt bearbeiten' anzeigen
					$('#saveProject').show(); // Menüpunkt 'Projekt speichern' anzeigen
					$('#deleteProject').show(); // Menüpunkt 'Projekt speichern' anzeigen
					$('#modal_new_project').modal('hide');
					$("#projekt_titel_new").val(""); //Leert den Projekttitel im Inputfeld für ein neues Projekt
					clearMap();
					updateProjects();
					updateSharedProjects();
					isSharedWith();
					loadProjectObjects();
					},
					error: function(xhr, desc, err) {
						console.log(xhr);
						console.log("Details: " + desc + "\nError:" + err);
					}
				});
				return false;
			});
			$(".ajax_edit_project").submit(function(){
				var data = {
					"action": "edit"
				};
				data = $(this).serialize() + "&" + $.param(data);
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "projects.php",
					data: data,
					success: function(data) {
					toastr.success('Projekt geändert.');
					$("#activeProject").html("&nbsp; "+data["projekttitel"]);
					$('.activeProjectName').attr("value",data["projekttitel"]);
					
					$('#modal_edit_project').modal('hide');
					updateProjects();
					updateSharedProjects();
					isSharedWith();
					},
					error: function(xhr, desc, err) {
						console.log(xhr);
						console.log("Details: " + desc + "\nError:" + err);
					}
				});
				return false;
			});
		});
		$(".ajax_load_project").submit(function(){
			var data = {
				"action": "load"
			};
			data = $(this).serialize() + "&" + $.param(data);
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "projects.php",
				data: data,
				success: function(data) {
					toastr.success('Projekt geladen.');
					$("#activeProject").html("&nbsp; "+data["projektName"]); //Projekttitel anzeigen
					prj_id = parseInt(data["projektID"]); // In Datenbak erzeugte Projekt ID einlesen
					messpunktnummer = parseInt(data["maxNum"])+1;
					$('#editProject').show(); // Menüpunkt 'Projekt bearbeiten' anzeigen
					$('#saveProject').show(); // Menüpunkt 'Projekt speichern' anzeigen
					$('#deleteProject').show(); // Menüpunkt 'Projekt speichern' anzeigen
					$('#modal_open_project').modal('hide'); //Modal schließen
					$('.activeProjectName').attr("value",data["projektName"]);
					$('.activeProjectID').val(data["projektID"]);
					$('.activeUserID').val(userID);
					clearMap();
					loadProjectObjects(); //Objekte einlesen
					updateProjects(); //Verfügbare Projekte aktualiseren
					updateSharedProjects(); //Verfügbare mit dem Benutzer geteilte Projekte aktualiseren
					isSharedWith(); //Aktualisieren, mit wem das geöffnete Projekt geteilt ist
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			});
			return false;
		});
		$(".ajax_edit_user").submit(function(){
				var data = {
					"action": "editUser"
				};
				data = $(this).serialize() + "&" + $.param(data);
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "projects.php",
					data: data,
					success: function(data) {
					switch(data){
						case 'Success':
							$('#modalUserSettings').modal('hide'); //Modal schließen
							toastr.success('Daten geändert.');
							loadUser(); //Lädt neue Nutzerdaten
							updateProjects(); //Verfügbare Projekte aktualiseren
							updateSharedProjects(); //Verfügbare mit dem Benutzer geteilte Projekte aktualiseren
							isSharedWith(); //Aktualisieren, mit wem das geöffnete Projekt geteilt ist
							break;
						case 'PasswordsDontMatch':
							toastr.warning('Die Passwörter stimmen nicht überein.');
							break;
						case 'WrongPassword':
							toastr.warning('Das aktuelle Passwort ist nicht korrekt.');
							break;
						default:
						toastr.warning('Fehler aufgetreten.');
							break;

					}
					
					},
					error: function(xhr, desc, err) {
						console.log(xhr);
						console.log("Details: " + desc + "\nError:" + err);
					}
				});
				return false;
			});
	</script>
	<script type="text/javascript" defer> // Ajax für Speichern und Löschen von Objekten
	function saveProjectStatus(){ // Erzeugt neue Messpunkte oder aktualisiert Vorhandene in der Datenbank
		objectArray.forEach(function(entry) {
			var obj_farbe = entry.obj_farbe;
			var obj_lat = entry.obj_lat;
			var obj_lon = entry.obj_lon;
			var obj_typ = entry.obj_typ;
			var obj_nummer = entry.obj_nummer;
			var obj_hinweis = entry.obj_hinweis;
			var obj_messwert = entry.obj_messwert;
			var obj_parameter =JSON.stringify(entry.obj_parameter);
			var data = {
				"task" : "save",
				"obj_prj_id" : prj_id,
				"obj_color" : obj_farbe,
				"obj_lat" : obj_lat,
				"obj_lon" : obj_lon,
				"obj_nummer" : obj_nummer,
				"obj_hinweis" : obj_hinweis,
				"obj_messwert" : obj_messwert,
				"obj_parameter" : obj_parameter,
				"obj_typ" : obj_typ
			};
			data = $(this).serialize() + "&" + $.param(data);
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "geometry.php",
				data:data,
				success: function(data) {
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			}); //Ende ajax
			return false;
		});//Ende forEach()

		deleteArray.forEach(function(entry) {
			var obj_typ = entry.typ;
			var obj_nummer = entry.nummer;
			data = {"task" : "delete", "objekt_nummer": obj_nummer, "projekt_id": prj_id , "objekt_typ": obj_typ};
			data = $(this).serialize() + "&" + $.param(data);
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "geometry.php",
				data:data,
				success: function(data) {
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			});//Ende ajax
			return false;
		});//Ende forEach()
		setTimeout(function() {
  			toastr.success('Projekt gespeichert. <span class="label label-info"><span class="fa fa-pencil" aria-hidden="true"></span> '+ objectArray.length+'</span> <span class="label label-danger"><span class="fa fa-trash" aria-hidden="true"></span> '+ deleteArray.length+'</span>'); //Zeigt an, wie viele Objekte gespeichert und gelöscht wurden
  			deleteArray.length = 0; // Leert den Array der zu löschenden Elemente nach dem Speichern des Projekts
		}, 100);//Ende setTimeout
	}// Ende Funktiobn saveProjectStatus
	</script>
	<script type="text/javascript" defer> // OSM Layer laden
	function loadOSMLayer(){
		var mapTypeIds = [];
		for(var type in google.maps.MapTypeId) {
			mapTypeIds.push(google.maps.MapTypeId[type]);
		}
		mapTypeIds.push("OSM");
		map = new google.maps.Map(document.getElementById('map'), {
			zoom: 14,
			mapTypeId: "OSM",
			mapTypeControlOptions: {
				mapTypeIds: mapTypeIds,
				style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
				position: google.maps.ControlPosition.TOP_RIGHT
			},
			center: {lat: 52.13024, lng: 11.56567700000005} // Koordinaten des Kartenmittelpunkts
		});
		
		OSM ='OSM'; //Variable OpenStreetMap definieren
		map.mapTypes.set("OSM", new google.maps.ImageMapType({
			getTileUrl: function(coord, zoom) {
            // "Wrap" x (longitude) at 180th meridian properly
            // NB: Don't touch coord.x because coord param is by reference, and changing its x property breakes something in Google's lib 
				var tilesPerGlobe = 1 << zoom;
				var x = coord.x % tilesPerGlobe;
				if (x < 0) {
					x = tilesPerGlobe+x;
				}
            // Wrap y (latitude) in a like manner if you want to enable vertical infinite scroll
				return "https://tile.openstreetmap.org/" + zoom + "/" + x + "/" + coord.y + ".png";
			},
			tileSize: new google.maps.Size(256, 256),
			name: "OpenStreetMap",
			maxZoom: 18
		}));	
	}//Ende Funktion loadOSMLayer
	</script><!-- OSM Layer Laden -->
</body>
</html>