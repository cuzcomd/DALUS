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
		url: "php/geometry.php",
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
			case 'white':
			var icon_type = 'images/white.png';
			break;

			case 'green':
			var icon_type = 'images/green.png';
			break;

			case 'blue':
			var icon_type = 'images/blue.png';
			break;

			case 'yellow':
			var icon_type = 'images/yellow.png';
			break;
			
			case 'red':
			var icon_type = 'images/red.png';
			break;

			default:
			var icon_type = 'images/white.png';
		}

		switch(value.obj_typ) {
			case 'marker':
			let geom_obj = new google.maps.Marker({
			map:map,
			position: {lat:Number(value.obj_lat), lng:Number(value.obj_lon)},
			obj_lat: Number(value.obj_lat),
			obj_lon: Number(value.obj_lon),
			icon:{url:icon_type, anchor: new google.maps.Point(16,16), labelOrigin: new google.maps.Point(16,40)},
			label: {text: value.obj_label.toString(), fontWeight: "700", color: "black"},
			obj_nummer: Number(value.obj_nummer),
			obj_farbe: value.obj_farbe,
			obj_messwert: Number(value.obj_messwert),
			obj_hinweis: value.obj_hinweis,
			obj_typ: 'marker',
			obj_parameter : '',
			obj_label: value.obj_label,
			info: infoWindow,
			content: value.obj_parameter,
			draggable:true,
			zIndex:10
			});

			objectArray.push(geom_obj);

			google.maps.event.addListener(geom_obj,'click',function(){ // Öffnet Infowindow bei Klick auf Marker
				clearSelectionLoad();
				activeObject = this;
				var index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer && x.obj_typ == this.obj_typ); // Ermittelt Array-Index des aktuellen Markers
				activeObject.setValues({messwert: objectArray[index].obj_messwert}); // Aktualisiert den aktullen Messwert aus dem im Array gespeicherten Wert

				reverseGeocode(objectArray[index].position, function(result)
			    {
			        if (result === 0)
			        {
				        activeObject.info.setContent('<label for="messpunktLabel">Messpunkt</label><input type="text" class="form-control" id="messpunktLabel" value = "'+objectArray[index].obj_label+'" onchange="updateLabel('+objectArray[index].obj_nummer+', this.value);">'+
						'<br/><br/><span class="fa fa-map-marker" aria-hidden="true"></span> <b>'+	objectArray[index].obj_lat+', '+ objectArray[index].obj_lon+'</b> ('+LLtoUSNG(objectArray[index].obj_lat, objectArray[index].obj_lon, 5)+')'+
						'<br/> Adresse nicht gefunden <hr>'+
						'<form><div class="form-group"> <label for="messwert">Messwert [ppm]</label><input type="text" class="form-control" id="messwert" cols="50" value = "'+objectArray[index].obj_messwert+'" onchange="updateMesswert('+objectArray[index].obj_nummer+', this.value);"></div>'+
						'<div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis('+objectArray[index].obj_nummer+', this.value);" rows="5">'+objectArray[index].obj_hinweis+'</textarea></div></form>'+
						'<div class="btn-group" role="group" aria-label="Optionen">'+
						'<button type="button" class="btn btn-default btn-danger btnInfoWindow" id="deleteButton" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',1);"><img src="images/white.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',2);"><img src="images/green.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',3);"><img src="images/blue.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',4);"><img src="images/yellow.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',5);"><img src="images/red.png"></button></div>');
						activeObject.info.open(map,activeObject);
					}
			        else
			        {
				        var adresse = result;
				        activeObject.info.setContent('<label for="messpunktLabel">Messpunkt</label><input type="text" class="form-control" id="messpunktLabel" value = "'+objectArray[index].obj_label+'" onchange="updateLabel('+objectArray[index].obj_nummer+', this.value);">'+
						'<br/><br/><span class="fa fa-map-marker" aria-hidden="true"></span> <b>'+	objectArray[index].obj_lat+', '+ objectArray[index].obj_lon+'</b> ('+LLtoUSNG(objectArray[index].obj_lat, objectArray[index].obj_lon, 5)+')'+
						'<br/>'+adresse+
						'<hr><form><div class="form-group"> <label for="messwert">Messwert [ppm]</label><input type="text" class="form-control" id="messwert" cols="50" value = "'+objectArray[index].obj_messwert+'" onchange="updateMesswert('+objectArray[index].obj_nummer+', this.value);"></div>'+
						'<div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis('+objectArray[index].obj_nummer+', this.value);" rows="5">'+objectArray[index].obj_hinweis+'</textarea></div></form>'+
						'<div class="btn-group" role="group" aria-label="Optionen">'+
						'<button type="button" class="btn btn-default btn-danger btnInfoWindow" id="deleteButton" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',1);"><img src="images/white.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',2);"><img src="images/green.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',3);"><img src="images/blue.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',4);"><img src="images/yellow.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',5);"><img src="images/red.png"></button></div>');
						activeObject.info.open(map,activeObject);
			        }
			    }); // Ende reverseGeocode()
			}); // Ende des Eventlisteners

			google.maps.event.addListener(geom_obj,'dragend', function(){ //Aktualisiert Array und Infowindow wenn Marker verschoben wird
				activeObject = this;
				let index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer  && x.obj_typ == this.obj_typ);  // Ermittelt Array-Index des aktuellen Markers
				objectArray[index].obj_lat = this.getPosition().lat().toFixed(6); // Aktualisiert geogr. Position im Array
				objectArray[index].obj_lon = this.getPosition().lng().toFixed(6);// Aktualisiert geogr. Position im Array	
				
				reverseGeocode(objectArray[index].position, function(result)
			    {
			        if (result === 0)
			        {
				        activeObject.info.setContent('<label for="messpunktLabel">Messpunkt</label><input type="text" class="form-control" id="messpunktLabel" value = "'+objectArray[index].obj_label+'" onchange="updateLabel('+objectArray[index].obj_nummer+', this.value);">'+
						'<br/><br/><span class="fa fa-map-marker" aria-hidden="true"></span> <b>'+	objectArray[index].obj_lat+', '+ objectArray[index].obj_lon+'</b> ('+LLtoUSNG(objectArray[index].obj_lat, objectArray[index].obj_lon, 5)+')'+
						'<br/> Adresse nicht gefunden <hr>'+
						'<form><div class="form-group"> <label for="messwert">Messwert [ppm]</label><input type="text" class="form-control" id="messwert" cols="50" value = "'+objectArray[index].obj_messwert+'" onchange="updateMesswert('+objectArray[index].obj_nummer+', this.value);"></div>'+
						'<div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis('+objectArray[index].obj_nummer+', this.value);" rows="5">'+objectArray[index].obj_hinweis+'</textarea></div></form>'+
						'<div class="btn-group" role="group" aria-label="Optionen">'+
						'<button type="button" class="btn btn-default btn-danger btnInfoWindow" id="deleteButton" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',1);"><img src="images/white.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',2);"><img src="images/green.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',3);"><img src="images/blue.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',4);"><img src="images/yellow.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',5);"><img src="images/red.png"></button></div>');
						activeObject.info.open(map,activeObject);
					}
			        else
			        {
				        var adresse = result;
				        activeObject.info.setContent('<label for="messpunktLabel">Messpunkt</label><input type="text" class="form-control" id="messpunktLabel" value = "'+objectArray[index].obj_label+'" onchange="updateLabel('+objectArray[index].obj_nummer+', this.value);">'+
						'<br/><br/><span class="fa fa-map-marker" aria-hidden="true"></span> <b>'+	objectArray[index].obj_lat+', '+ objectArray[index].obj_lon+'</b> ('+LLtoUSNG(objectArray[index].obj_lat, objectArray[index].obj_lon, 5)+')'+
						'<br/>'+adresse+
						'<hr><form><div class="form-group"> <label for="messwert">Messwert [ppm]</label><input type="text" class="form-control" id="messwert" cols="50" value = "'+objectArray[index].obj_messwert+'" onchange="updateMesswert('+objectArray[index].obj_nummer+', this.value);"></div>'+
						'<div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis('+objectArray[index].obj_nummer+', this.value);" rows="5">'+objectArray[index].obj_hinweis+'</textarea></div></form>'+
						'<div class="btn-group" role="group" aria-label="Optionen">'+
						'<button type="button" class="btn btn-default btn-danger btnInfoWindow" id="deleteButton" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',1);"><img src="images/white.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',2);"><img src="images/green.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',3);"><img src="images/blue.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',4);"><img src="images/yellow.png"></button>'+
						'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',5);"><img src="images/red.png"></button></div>');
						activeObject.info.open(map,activeObject);
			        }
			    }); // Ende reverseGeocode()
			}); // Ende eventlistener

			google.maps.event.addListener(geom_obj,'dragstart', function(){
				activeObject = this;
				activeObject.info.close();
			});
			break;

			case 'comment':
			let comment_obj = new google.maps.Marker({
			map:map,
			position: {lat:Number(value.obj_lat), lng:Number(value.obj_lon)},
			obj_lat: Number(value.obj_lat),
			obj_lon: Number(value.obj_lon),
			icon:{url:'images/comment.png', anchor: new google.maps.Point(16,16)},
			obj_nummer: Number(value.obj_nummer),
			obj_hinweis: value.obj_hinweis,
			obj_typ: 'comment',
			info: infoWindow,
			draggable:true,
			zIndex:10
			});

			objectArray.push(comment_obj);

			google.maps.event.addListener(comment_obj,'click',function(){ // Öffnet Infowindow bei Klick auf Marker
				clearSelectionLoad();
				activeObject = this;
				var index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer && x.obj_typ == this.obj_typ); // Ermittelt Array-Index des aktuellen Markers

				reverseGeocode(objectArray[index].position, function(result)
			    {
			        if (result === 0)
			        {
				        activeObject.info.setContent('<span class="fa fa-map-marker" aria-hidden="true"></span> <b>'+	objectArray[index].obj_lat+', '+ objectArray[index].obj_lon+'</b> ('+LLtoUSNG(objectArray[index].obj_lat, objectArray[index].obj_lon, 5)+')'+
						'<br/> Adresse nicht gefunden <hr>'+
						'<form><div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis('+objectArray[index].obj_nummer+',\'comment\', this.value);" rows="5">'+objectArray[index].obj_hinweis+'</textarea></div></form>'+
						'<div class="btn-group" role="group" aria-label="Optionen">'+
						'<button type="button" class="btn btn-default btn-danger btnInfoWindow" id="deleteButton" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button></div>');
						activeObject.info.open(map,activeObject);
					}
			        else
			        {
				        var adresse = result;
				        activeObject.info.setContent('<span class="fa fa-map-marker" aria-hidden="true"></span> <b>'+	objectArray[index].obj_lat+', '+ objectArray[index].obj_lon+'</b> ('+LLtoUSNG(objectArray[index].obj_lat, objectArray[index].obj_lon, 5)+')'+
						'<br/>'+adresse+
						'<hr><form><div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis('+objectArray[index].obj_nummer+',\'comment\', this.value);" rows="5">'+objectArray[index].obj_hinweis+'</textarea></div></form>'+
						'<div class="btn-group" role="group" aria-label="Optionen">'+
						'<button type="button" class="btn btn-default btn-danger btnInfoWindow" id="deleteButton" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button></div>');
						activeObject.info.open(map,activeObject);
			        }
			    }); // Ende reverseGeocode()
			}); // Ende des Eventlisteners

			google.maps.event.addListener(comment_obj,'dragend', function(){ //Aktualisiert Array und Infowindow wenn Marker verschoben wird
				activeObject = this;
				let index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer  && x.obj_typ == this.obj_typ);  // Ermittelt Array-Index des aktuellen Markers
				objectArray[index].obj_lat = this.getPosition().lat().toFixed(6); // Aktualisiert geogr. Position im Array
				objectArray[index].obj_lon = this.getPosition().lng().toFixed(6);// Aktualisiert geogr. Position im Array	
				
				reverseGeocode(objectArray[index].position, function(result)
			    {
			        if (result === 0)
			        {
				        activeObject.info.setContent('<span class="fa fa-map-marker" aria-hidden="true"></span> <b>'+	objectArray[index].obj_lat+', '+ objectArray[index].obj_lon+'</b> ('+LLtoUSNG(objectArray[index].obj_lat, objectArray[index].obj_lon, 5)+')'+
						'<br/> Adresse nicht gefunden <hr>'+
						'<form><div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis('+objectArray[index].obj_nummer+',\'comment\', this.value);" rows="5">'+objectArray[index].obj_hinweis+'</textarea></div></form>'+
						'<div class="btn-group" role="group" aria-label="Optionen">'+
						'<button type="button" class="btn btn-default btn-danger btnInfoWindow" id="deleteButton" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button></div>');
						activeObject.info.open(map,activeObject);
					}
			        else
			        {
				        var adresse = result;
				        activeObject.info.setContent('<span class="fa fa-map-marker" aria-hidden="true"></span> <b>'+	objectArray[index].obj_lat+', '+ objectArray[index].obj_lon+'</b> ('+LLtoUSNG(objectArray[index].obj_lat, objectArray[index].obj_lon, 5)+')'+
						'<br/>'+adresse+
						'<hr><form><div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis('+objectArray[index].obj_nummer+',\'comment\', this.value);" rows="5">'+objectArray[index].obj_hinweis+'</textarea></div></form>'+
						'<div class="btn-group" role="group" aria-label="Optionen">'+
						'<button type="button" class="btn btn-default btn-danger btnInfoWindow" id="deleteButton" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button></div>');
						activeObject.info.open(map,activeObject);
			        }
			    }); // Ende reverseGeocode()
			}); // Ende eventlistener

			google.maps.event.addListener(comment_obj,'dragstart', function(){
				activeObject = this;
				activeObject.info.close();
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
			var circleObj = new google.maps.Circle({
				map:map,
				center: {lat:Number(value.obj_lat), lng:Number(value.obj_lon)},
				obj_lat: Number(value.obj_lat),
				obj_lon: Number(value.obj_lon),
				radius: Number(value.obj_parameter),
				obj_nummer: Number(value.obj_nummer),
				obj_typ: 'circle',
				obj_parameter : Number(value.obj_parameter),
				draggable:true,
				zIndex:1
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
				draggable:true,
				zIndex:1
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
				draggable:true,
				zIndex:1
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
	if (activeObject && activeObject.obj_typ != "marker" && activeObject.obj_typ != "met" && activeObject.obj_typ != "comment"){ //Funktion nur ausführen, wenn ein Objekt ausgewählt ist und es kein Marker ist
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
		activeObject.setIcon({url:'images/white.png', anchor: new google.maps.Point(16,16), labelOrigin: new google.maps.Point(16,40)});
		objectArray[index].obj_farbe = "white";
		break;

		case 2:
		activeObject.setIcon({url:'images/green.png', anchor: new google.maps.Point(16,16), labelOrigin: new google.maps.Point(16,40)});
		objectArray[index].obj_farbe = "green";
		break;

		case 3:
		activeObject.setIcon({url:'images/blue.png', anchor: new google.maps.Point(16,16), labelOrigin: new google.maps.Point(16,40)});
		objectArray[index].obj_farbe = "blue";
		break;

		case 4:
		activeObject.setIcon({url:'images/yellow.png', anchor: new google.maps.Point(16,16), labelOrigin: new google.maps.Point(16,40)});
		objectArray[index].obj_farbe = "yellow";
		break;

		case 5:
		activeObject.setIcon({url:'images/red.png', anchor: new google.maps.Point(16,16), labelOrigin: new google.maps.Point(16,40)});
		objectArray[index].obj_farbe = "red";
		break;

		default:
	}//Ende switch(color)	
}//Ende Funktion changeColor()