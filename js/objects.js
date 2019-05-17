function loadProjectObjects(){
	for (var i = 0; i < objectArray.length; i++ ) {
		objectArray[i].setMap(null);
	}
	objectArray = [];
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "php/geometry.php",
		data: {"task" : "load","prj_id" : prj_id},
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
		switch(value.obj_typ) {
			case 'marker':
			let indexMesstruppsArray = messtruppArray.findIndex(x => x.abkürzung == value.obj_messtrupp); // Daten des ausgewählten Messtrupps aus dem Array lesen
			if(indexMesstruppsArray < 0) // Fallback, falls keine Farbe für einen nicht zugeordneten Messtrupp angegeben wurde
			{
				savedStrokeColor = marker_strokeColor; 
				alertify.warning('Ein in diesem Projekt genutzter Messtrupp wurde aus der Datenbank gelöscht.');
			}
			else{
				savedStrokeColor =  messtruppArray[indexMesstruppsArray].farbe
			}
			let geom_obj = new google.maps.Marker({
			map:map,
			position: {lat:Number(value.obj_lat), lng:Number(value.obj_lon)},
			obj_lat: Number(value.obj_lat),
			obj_lon: Number(value.obj_lon),
			icon:{
					path: google.maps.SymbolPath.CIRCLE,
					scale: marker_scale,
					fillColor: value.obj_farbe,
					fillOpacity: marker_fillOpacity,
					strokeColor: savedStrokeColor,
					strokeWeight:marker_strokeWeight,
					labelOrigin: marker_labelOrigin},
			label: {text: value.obj_label.toString(), fontWeight: "700", color: "#000"},
			obj_nummer: Number(value.obj_nummer),
			obj_farbe: value.obj_farbe,
			obj_messwert: Number(value.obj_messwert),
			obj_hinweis: value.obj_hinweis,
			obj_typ: 'marker',
			obj_parameter : '',
			obj_label: value.obj_label,
			obj_messtrupp: value.obj_messtrupp,
			content: value.obj_parameter,
			draggable:true,
			zIndex:10
			});

			objectArray.push(geom_obj);

			google.maps.event.addListener(geom_obj,'click',function(){ // Öffnet Infowindow bei Klick auf Marker
				clearSelectionLoad();
				activeObject = this; // Setzt den aktuell ausgewählten marker als aktiv
				let index = objectArray.findIndex(x => x.obj_nummer == activeObject.obj_nummer && x.obj_typ == activeObject.obj_typ); // Ermittelt Array-Index des aktuellen Markers
				var object = objectArray[index];
				activeObject.setValues({messwert: object.messwert}); // Aktualisiert den aktullen Messwert aus dem im Array gespeicherten Wert
				
				reverseGeocode(object.position, function(result)
			    {
			        if (result === 0)
			        {
			        	var adresse = 'Adresse nicht gefunden';
					}
			        else
			        {
				        var adresse = result;   
			        }
			        var markerContent =`
			        <div class="text-center "id="messpunktLabel">
				        <div class="input-group mb-3">
						  <input type="text" class="form-control" value="${object.obj_label}" onchange="updateLabel(${activeObject.obj_nummer}, this.value);" aria-label="Messpunktbezeichnung"></input>
						  <div class="input-group-append">
						    <span class="input-group-text"><i class="fa fa-pencil"></i></span>
						  </div>
						</div>
			        </div><br>
			        <div class="header row" style="width:80vw; max-width:450px">
			        	<div class="col-12 col-sm-7">
			        		<div class="input-group mb-3">
			        			<div class="input-group-prepend">
			        				<label class="input-group-text" for="markerMesstrupp"><i class="fa fa-car"></i></label>
			        			</div>
			        			<select id="markerMesstrupp" onchange="setMesstrupp(${object.obj_nummer}, this.value)" class="custom-select" aria-label="Messtrupp" aria-describedby="basic-addon1"></select>
			        		</div>
				        	<div class="btn-group" role="group" aria-label="Optionen">
								<button type="button" class="btn btn-default btn-danger btnInfoWindow" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button>
								<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" id="dropdownMenuButton__farbe" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-tint">&nbsp;</i></button>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton__farbe">
					      				<a class="dropdown-item" onclick="changeColor(${activeObject.obj_nummer},1);" href="#"><span class="symbol symbol_white"></span> Vorgeplanter Messpunkt</a>
					      				<a class="dropdown-item" onclick="changeColor(${activeObject.obj_nummer},2);" href="#"><span class="symbol symbol_green"></span> Kein Geruch wahrnehmbar</a>
					      				<a class="dropdown-item" onclick="changeColor(${activeObject.obj_nummer},3);" href="#"><span class="symbol symbol_blue"></span> Geruch wahrnehmbar</a>
					      				<a class="dropdown-item" onclick="changeColor(${activeObject.obj_nummer},4);" href="#"><span class="symbol symbol_yellow"></span> Messung unterhalb des Beurteilungswertes</a>
					      				<a class="dropdown-item" onclick="changeColor(${activeObject.obj_nummer},5);" href="#"><span class="symbol symbol_red"></span> Messung oberhalb des Beurteilungswertes</a>
	   								</div>
	   							</div>
	   						</div>
				        </div>
				        <div class="col-12 col-sm-5">
			        		<div class="col-1 fa fa-home" aria-hidden="true"></div><div class="col-10" >${adresse}</div><hr>
			        		<div class="col-1 fa fa-map-marker" aria-hidden="true"></div><div class="col-10" >${object.obj_lat}, ${object.obj_lon}<br>
			        		(${LLtoUSNG(object.obj_lat, object.obj_lon, 5)})</div>
			       		</div>
			        </div><br>
			        <hr> Geräte<br><form><div class="form-group"> <label for="messwert">Messwert [ppm]</label><input type="text" class="form-control" id="messwert" cols="50" value = "${object.obj_messwert}" onchange="updateMesswert(${object.obj_nummer}, this.value);"></div></form>
			        <form><div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis(${object.obj_nummer},'marker', this.value);" rows="5">${object.obj_hinweis}</textarea></div></form>`;

					infoWindow.setContent(markerContent);
					updateMesstruppsMarker(); // Aktualisiert die auswählbaren Messtrupps
					infoWindow.open(map,activeObject);
			    }); // Ende reverseGeocode()
			}); // Ende des Eventlisteners

			google.maps.event.addListener(geom_obj,'dragend', function(){ //Aktualisiert Array und Infowindow wenn Marker verschoben wird
				activeObject = this;
				let index = objectArray.findIndex(x => x.obj_nummer == activeObject.obj_nummer  && x.obj_typ == activeObject.obj_typ);  // Ermittelt Array-Index des aktuellen Markers
				var object = objectArray[index];
				object.obj_lat = activeObject.getPosition().lat().toFixed(6); // Aktualisiert geogr. Position im Array
				object.obj_lon = activeObject.getPosition().lng().toFixed(6);// Aktualisiert geogr. Position im Array

				reverseGeocode(object.position, function(result)
			    {
			        if (result === 0)
			        {
						var adresse = 'Adresse nicht gefunden';
					}
			        else
			        {
				        var adresse = result;
					}

					var markerContent =`
			        <div class="text-center "id="messpunktLabel">
				        <div class="input-group mb-3">
						  <input type="text" class="form-control" value="${object.obj_label}" onchange="updateLabel(${activeObject.obj_nummer}, this.value);" aria-label="Messpunktbezeichnung"></input>
						  <div class="input-group-append">
						    <span class="input-group-text"><i class="fa fa-pencil"></i></span>
						  </div>
						</div>
			        </div><br>
			        <div class="header row" style="width:80vw; max-width:450px">
			        	<div class="col-12 col-sm-7">
			        		<div class="input-group mb-3">
			        			<div class="input-group-prepend">
			        				<label class="input-group-text" for="markerMesstrupp"><i class="fa fa-car"></i></label>
			        			</div>
			        			<select id="markerMesstrupp" onchange="setMesstrupp(${object.obj_nummer}, this.value)" class="custom-select" aria-label="Messtrupp" aria-describedby="basic-addon1"></select>
			        		</div>
				        	<div class="btn-group" role="group" aria-label="Optionen">
								<button type="button" class="btn btn-default btn-danger btnInfoWindow" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button>
								<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" id="dropdownMenuButton__farbe" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-tint">&nbsp;</i></button>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton__farbe">
					      				<a class="dropdown-item" onclick="changeColor(${activeObject.obj_nummer},1);" href="#"><span class="symbol symbol_white"></span> Vorgeplanter Messpunkt</a>
					      				<a class="dropdown-item" onclick="changeColor(${activeObject.obj_nummer},2);" href="#"><span class="symbol symbol_green"></span> Kein Geruch wahrnehmbar</a>
					      				<a class="dropdown-item" onclick="changeColor(${activeObject.obj_nummer},3);" href="#"><span class="symbol symbol_blue"></span> Geruch wahrnehmbar</a>
					      				<a class="dropdown-item" onclick="changeColor(${activeObject.obj_nummer},4);" href="#"><span class="symbol symbol_yellow"></span> Messung unterhalb des Beurteilungswertes</a>
					      				<a class="dropdown-item" onclick="changeColor(${activeObject.obj_nummer},5);" href="#"><span class="symbol symbol_red"></span> Messung oberhalb des Beurteilungswertes</a>
	   								</div>
	   							</div>
	   						</div>
				        </div>
				        <div class="col-12 col-sm-5">
			        		<div class="col-1 fa fa-home" aria-hidden="true"></div><div class="col-10" >${adresse}</div><hr>
			        		<div class="col-1 fa fa-map-marker" aria-hidden="true"></div><div class="col-10" >${object.obj_lat}, ${object.obj_lon}<br>
			        		(${LLtoUSNG(object.obj_lat, object.obj_lon, 5)})</div>
			       		</div>
			        </div><br>
			        <hr> Geräte<br><form><div class="form-group"> <label for="messwert">Messwert [ppm]</label><input type="text" class="form-control" id="messwert" cols="50" value = "${object.obj_messwert}" onchange="updateMesswert(${object.obj_nummer}, this.value);"></div></form>
			        <form><div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis(${object.obj_nummer},'marker', this.value);" rows="5">${object.obj_hinweis}</textarea></div></form>`;

					infoWindow.setContent(markerContent);
					updateMesstruppsMarker(); // Aktualisiert die auswählbaren Messtrupps
					infoWindow.setPosition(new google.maps.LatLng(activeObject.obj_lat , activeObject.obj_lon));
					infoWindow.open(map,activeObject);
				}); // Ende reverseGeocode()
			}); // Ende eventlistener

			google.maps.event.addListener(geom_obj,'dragstart', function(){
				activeObject = this;
				infoWindow.setMap(null);
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
				draggable:true,
				zIndex:10
			});

			objectArray.push(comment_obj);

			google.maps.event.addListener(comment_obj,'click',function(){ // Öffnet Infowindow bei Klick auf Marker
				clearSelectionLoad();
				activeObject = this; // Setzt den aktuell ausgewählten marker als aktiv
				let index = objectArray.findIndex(x => x.obj_nummer == activeObject.obj_nummer && x.obj_typ == activeObject.obj_typ); // Ermittelt Array-Index des aktuellen Markers
				var object = objectArray[index];

				reverseGeocode(object.position, function(result)
			    {
			        if (result === 0)
			        {
				        var adresse = ' Adresse nicht gefunden '
					}
			        else
			        {
				        var adresse = result;
				    }
					var commentContent = `<span class="fa fa-map-marker" aria-hidden="true"></span> <b> ${object.obj_lat} ,${object.obj_lon}</b><br>
						(${LLtoUSNG(objectArray[index].obj_lat, objectArray[index].obj_lon, 5)})<br/>
						${adresse}
						<hr>
						<form>
							<div class="form-group">
								<label for="hinweis">Hinweise</label>
								<textarea id="hinweis" class="form-control" onchange="updateHinweis(${object.obj_nummer},'comment', this.value);" rows="5">${object.obj_hinweis}</textarea>
							</div>
						</form>
						<div class="btn-group" role="group" aria-label="Optionen">
							<button type="button" class="btn btn-default btn-danger btnInfoWindow" id="deleteButton" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button>
						</div>`;
					infoWindow.setContent(commentContent);
					infoWindow.open(map,activeObject)
			    }); // Ende reverseGeocode()
			}); // Ende des Eventlisteners

			google.maps.event.addListener(comment_obj,'dragend', function(){ //Aktualisiert Array und Infowindow wenn Marker verschoben wird
				activeObject = this;
				let index = objectArray.findIndex(x => x.obj_nummer == activeObject.obj_nummer  && x.obj_typ == activeObject.obj_typ);  // Ermittelt Array-Index des aktuellen Markers
				var object = objectArray[index];
				object.obj_lat = activeObject.getPosition().lat().toFixed(6); // Aktualisiert geogr. Position im Array
				object.obj_lon = activeObject.getPosition().lng().toFixed(6);// Aktualisiert geogr. Position im Array

				reverseGeocode(object.position, function(result)
			    {
			        if (result === 0)
			        {
				        var adresse = ' Adresse nicht gefunden '
					}
			        else
			        {
				        var adresse = result;
				    }
					var commentContent = `<span class="fa fa-map-marker" aria-hidden="true"></span> <b> ${object.obj_lat} ,${object.obj_lon}</b><br>
						(${LLtoUSNG(object.obj_lat, object.obj_lon, 5)})<br/>
						${adresse}
						<hr>
						<form>
							<div class="form-group">
								<label for="hinweis">Hinweise</label>
								<textarea id="hinweis" class="form-control" onchange="updateHinweis(${object.obj_nummer},'comment', this.value);" rows="5">
									${object.obj_hinweis}
								</textarea>
							</div>
						</form>
						<div class="btn-group" role="group" aria-label="Optionen">
							<button type="button" class="btn btn-default btn-danger btnInfoWindow" id="deleteButton" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button>
						</div>`;
					infoWindow.setContent(commentContent);
					infoWindow.setPosition(new google.maps.LatLng(activeObject.obj_lat , activeObject.obj_lon));
					infoWindow.open(map,activeObject)
			    }); // Ende reverseGeocode()
			}); // Ende eventlistener

			google.maps.event.addListener(comment_obj,'dragstart', function(){
				activeObject = this;
				infoWindow.setMap(null);
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

			google.maps.event.addListener(circleObj,'click',function(){ // Öffnet Infowindow bei Klick auf Kreis
				clearSelectionLoad();
				activeObject = this;
				activeObject.setEditable(true);
				infoWindow.setMap(null);
			}); // Ende des Eventlisteners

			google.maps.event.addListener(circleObj,'dragend', function(){ //Aktualisiert Array und Infowindow wenn Kreis verschoben wird
				let index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer  && x.obj_typ == this.obj_typ);  // Ermittelt Array-Index des Kreises
				objectArray[index].obj_lat = this.getCenter().lat().toFixed(6); // Aktualisiert geogr. Position im Array
				objectArray[index].obj_lon = this.getCenter().lng().toFixed(6);// Aktualisiert geogr. Position im Array	
			});

			google.maps.event.addListener(circleObj,'radius_changed', function(){ //Aktualisiert Array und Infowindow wenn Kreisradius verändert wird
				let index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer  && x.obj_typ == this.obj_typ);  // Ermittelt Array-Index des Kreises
				objectArray[index].obj_parameter = this.getRadius(); // Aktualisiert Radius im Array
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

			google.maps.event.addListener(polygonObj,'click',function(){ // Öffnet Infowindow bei Klick auf Polygon
				clearSelectionLoad();
				activeObject = this;
				activeObject.setEditable(true);
				infoWindow.setMap(null);
			}); // Ende des Eventlisteners

			google.maps.event.addListener(polygonObj,'dragend', function(){ //Aktualisiert Array und Infowindow wenn Polygon verschoben wird
				let index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer  && x.obj_typ == this.obj_typ);  // Ermittelt Array-Index des Polygons
				objectArray[index].obj_parameter = this.getPath().getArray(); // Aktualisiert Pfad im Array
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

			google.maps.event.addListener(polylineObj,'click',function(){ // Öffnet Infowindow bei Klick auf Polyline
				clearSelectionLoad();
				activeObject = this;
				activeObject.setEditable(true);
				infoWindow.setMap(null);
			}); // Ende des Eventlisteners

			google.maps.event.addListener(polylineObj,'dragend', function(){ //Aktualisiert Array und Infowindow wenn Polyline verschoben wird
				let index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer  && x.obj_typ == this.obj_typ);  // Ermittelt Array-Index der Polyline
				objectArray[index].obj_parameter = this.getPath().getArray(); // Aktualisiert Pfad im Array
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
	if (activeObject && activeObject.obj_typ != "marker" && activeObject.obj_typ != "met" && activeObject.obj_typ != "comment"  && activeObject.typ != "katasterpunkt"){ //Funktion nur ausführen, wenn ein Objekt ausgewählt ist und es kein Marker, MET-Objekt oder Kommentarfeld ist
		activeObject.setEditable(false);
	}// Ende if(activeObject)
	activeObject = null; //Aktives Objekt zurücksetzen
} //Ende der Funktion clearSelection()

function deleteObject(){
	if(activeObject && activeObject.typ != "katasterpunkt"){ //Führt den Löschvorgang nur aus, wenn ein Objekt ausgewählt wurde und es sich nicht um einen Punkt des Messkatasters handelt.
		var index = objectArray.findIndex(x => x.obj_nummer == activeObject.obj_nummer && x.obj_typ == activeObject.obj_typ);
		objectArray.splice(index,1); // Löscht das Objekt aus dem Objekt-Array
		let deleteObject = {nummer:activeObject.obj_nummer, typ:activeObject.obj_typ}; //Erzeugt das zu löschende Objekt
		deleteArray.push(deleteObject); //Verschiebt das zu löschende Objekt in den Lösch-Array
		infoWindow.setMap(null); // Löscht das Infowindow
		activeObject.setMap(null);// Löscht das Objekt von der Karte
		activeObject.poly1 !== undefined ? activeObject.poly1.setMap(null) : '';// Löscht das zugehörige Ausbreitungspolygon von der Karte
		activeObject.poly2 !== undefined ? activeObject.poly2.setMap(null) : '';// Löscht das zugehörige Ausbreitungspolygon von der Karte
		activeObject.centerLine !== undefined ? activeObject.centerLine.setMap(null) : '';// Löscht das zugehörige Ausbreitungspolygon von der Karte
		activeObject = null; //Aktives Objekt zurücksetzen
	}// Ende if (activeObject)
}// Ende Funktion deleteObject()

function changeColor(markerNummer, farbe){
	var index = objectArray.findIndex(x => x.obj_nummer == activeObject.obj_nummer && x.obj_typ == activeObject.obj_typ);  //ermittelt Array-Index des aktuellen Markers
	var oldSymbol = activeObject.getIcon();
	switch(farbe){
		case "1":

		activeObject.setIcon({
			path: oldSymbol.path,
			scale: oldSymbol.scale,
			fillColor: '#FFFFFF',
			fillOpacity: oldSymbol.fillOpacity,
			strokeColor: oldSymbol.strokeColor,
			strokeWeight: oldSymbol.strokeWeight,
			labelOrigin: oldSymbol.labelOrigin});
		objectArray[index].obj_farbe = "#FFFFFF";
		break;

		case "2":
		activeObject.setIcon({
			path: oldSymbol.path,
			scale: oldSymbol.scale,
			fillColor: '#7BC790',
			fillOpacity: oldSymbol.fillOpacity,
			strokeColor: oldSymbol.strokeColor,
			strokeWeight: oldSymbol.strokeWeight,
			labelOrigin: oldSymbol.labelOrigin});
		objectArray[index].obj_farbe = "#7BC790";
		break;

		case "3":
		activeObject.setIcon({
			path: oldSymbol.path,
			scale: oldSymbol.scale,
			fillColor: '#7DA2C9',
			fillOpacity: oldSymbol.fillOpacity,
			strokeColor: oldSymbol.strokeColor,
			strokeWeight: oldSymbol.strokeWeight,
			labelOrigin: oldSymbol.labelOrigin});
		objectArray[index].obj_farbe = "#7DA2C9";
		break;

		case "4":
		activeObject.setIcon({
			path: oldSymbol.path,
			scale: oldSymbol.scale,
			fillColor: '#F0F484',
			fillOpacity: oldSymbol.fillOpacity,
			strokeColor: oldSymbol.strokeColor,
			strokeWeight: oldSymbol.strokeWeight,
			labelOrigin: oldSymbol.labelOrigin});
		objectArray[index].obj_farbe = "#F0F484";
		break;

		case "5":
		activeObject.setIcon({
			path: oldSymbol.path,
			scale: oldSymbol.scale,
			fillColor: '#DE6868',
			fillOpacity: oldSymbol.fillOpacity,
			strokeColor: oldSymbol.strokeColor,
			strokeWeight: oldSymbol.strokeWeight,
			labelOrigin: oldSymbol.labelOrigin});
		objectArray[index].obj_farbe = "#DE6868";
		break;

		default:
	}//Ende switch(farbe)	
}//Ende Funktion changeColor()