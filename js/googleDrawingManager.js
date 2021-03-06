function startDrawingManager(map){	
	marker_scale = 12;
	marker_fillColor = '#FFFFFF'
	marker_fillOpacity = 0.9;
	marker_strokeWeight = 4;
	marker_strokeColor = '#000000'
	marker_labelOrigin = new google.maps.Point(0,2);
	marker_color = marker_fillColor; 

	drawingManager = new google.maps.drawing.DrawingManager({
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
	$('.setHand').click(function() {
		drawingManager.setOptions({
			drawingMode: google.maps.drawing.OverlayType.null
		});
	});
	
	$('.setMarkWhite').click(function() {
		drawingManager.setOptions({
			drawingMode: google.maps.drawing.OverlayType.MARKER,
			markerOptions: {
				icon:{
					path: google.maps.SymbolPath.CIRCLE,
					scale: marker_scale,
					fillColor: marker_fillColor,
					fillOpacity: marker_fillOpacity,
					strokeColor: marker_strokeColor,
					strokeWeight: marker_strokeWeight,
					labelOrigin: marker_labelOrigin
				},
				draggable:true
			}
		});
		marker_color = marker_fillColor;
		marker_typ = 'messpunkt';
	});

	$('.setCirc').click(function() {
 		drawingManager.setOptions({
	  		drawingMode: google.maps.drawing.OverlayType.CIRCLE
  		});
	});

	$('.setPoly').click(function() {
  		drawingManager.setOptions({
	  		drawingMode: google.maps.drawing.OverlayType.POLYGON
  		});
	});

	$('.setPath').click(function() {
  		drawingManager.setOptions({
	  		drawingMode: google.maps.drawing.OverlayType.POLYLINE
  		});
	});

	$('.setComment').click(function() {
		drawingManager.setOptions({
			drawingMode: google.maps.drawing.OverlayType.MARKER,
			markerOptions: {icon: {url:'images/comment.png',anchor: new google.maps.Point(16, 16)}, draggable:true}
		});
		marker_typ = 'comment';
	});

	$('.deleteActiveObject').click(function() {
		deleteObject(); //Löschfunktion für geladene Objekte
		deleteSelectedShape(); //Löschfunktion für neu erzeugte Objekte
	});
	
	google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) { //Funktionen, die nach dem Zeichnen eines Geometrieobjekts aufgerufen werden
		var newShape = event.overlay;
		newShape.type = event.type;
		
		if (event.type == google.maps.drawing.OverlayType.POLYLINE) {
			newShape.setValues({
				obj_nummer: objectNummer,
				obj_parameter: newShape.getPath().getArray(),
				obj_typ: 'polyline',
				zIndex:1
				});
			objectArray.push(newShape);
			objectNummer += 1;
		}

		if (event.type == google.maps.drawing.OverlayType.POLYGON) {
			newShape.setValues({
				obj_nummer: objectNummer,
				obj_parameter: newShape.getPath().getArray(),
				obj_typ: 'polygon',
				zIndex:1
				});
			objectArray.push(newShape);
			objectNummer += 1;
		}

		if (event.type == google.maps.drawing.OverlayType.CIRCLE) {
			newShape.setValues({
				obj_nummer: objectNummer,
				obj_lat: newShape.getCenter().lat().toFixed(6),
				obj_lon: newShape.getCenter().lng().toFixed(6),
				obj_parameter: newShape.getRadius(),
				obj_typ: 'circle',
				zIndex:1
				});
			objectArray.push(newShape);
			objectNummer += 1;
		}

		if (event.type == google.maps.drawing.OverlayType.MARKER && marker_typ == 'metManual') {
			var latitude= newShape.getPosition().lat().toFixed(6);
			var longitude= newShape.getPosition().lng().toFixed(6);
			generateMET(map, latitude, longitude);
			newShape.setMap(null);
		}

		if (event.type == google.maps.drawing.OverlayType.MARKER && marker_typ == 'comment') {
			newShape.setValues({
				obj_nummer: objectNummer,
				obj_lat: newShape.getPosition().lat().toFixed(6),
				obj_lon: newShape.getPosition().lng().toFixed(6),
				obj_typ: 'comment',
				obj_hinweis: '',
				zIndex:10
			});
			objectArray.push(newShape);
			objectNummer += 1;

			google.maps.event.addListener(newShape,'click',function(){ // Öffnet Infowindow bei Klick auf Kommentarmarker
				if (selectedShape && selectedShape.obj_typ != "comment") {
					selectedShape.setEditable(false);
					selectedShape = null;
				}
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
			}); // Ende eventlistener
			
			google.maps.event.addListener(newShape,'dragend', function(){ //Aktualisiert Array und InfoWindow wenn Marker verschoben wird
				if (selectedShape) {
					selectedShape.setEditable(false);
					selectedShape = null;
				}
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
			}); // Ende Eventlistener

			google.maps.event.addListener(newShape,'dragstart', function(){
				activeObject = this;
				infoWindow.setMap(null);
			});

		} // Ende if typ = comment

		if (event.type == google.maps.drawing.OverlayType.MARKER && marker_typ == 'messpunkt') {
			newShape.setValues({
				obj_nummer: messpunktNummer,
				obj_lat: newShape.getPosition().lat().toFixed(6),
				obj_lon: newShape.getPosition().lng().toFixed(6),
				obj_farbe: marker_color,
				obj_typ: 'marker',
				obj_messwert: '0',
				obj_label: messpunktNummer,
				obj_hinweis: ' ',
				obj_messtrupp: '',
				zIndex:10
				});
			newShape.setLabel({text:newShape.obj_label.toString(), fontWeight: "700"});
			newShape.content = '';
			objectArray.push(newShape);
			messpunktNummer += 1; // Messpunktnummer inkrementieren

			google.maps.event.addListener(newShape,'click',function(){ // Öffnet Infowindow bei Klick auf Marker
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
						  <input type="text" class="form-control" value="${activeObject.obj_label}" onchange="updateLabel(${activeObject.obj_nummer}, this.value);" aria-label="Messpunktbezeichnung"></input>
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
			        			<select id="markerMesstrupp" onchange="setMesstrupp(${activeObject.obj_nummer}, this.value)" class="custom-select" aria-label="Messtrupp" aria-describedby="basic-addon1"></select>
			        		</div>

			        		<div class="input-group mb-3">
			        			<div class="input-group-prepend">
			        				<label class="input-group-text" for="button__farbe"><i class="fa fa-tint"></i></label>
			        			</div>
			        			<select id="button__farbe" onchange="changeColor(${activeObject.obj_nummer},this.value);" class="custom-select" aria-label="Farbe" aria-describedby="basic-addon1">
			        				<option value="1"><span class="symbol symbol_white"></span> Vorgeplanter Messpunkt</option>
			        				<option value="2"><span class="symbol symbol_green"></span> Kein Geruch wahrnehmbar</option>
			        				<option value="3"><span class="symbol symbol_blue"></span> Geruch wahrnehmbar</option>
			        				<option value="4"><span class="symbol symbol_yellow"></span> Messung unterhalb des Beurteilungswertes</option>
			        				<option value="5"><span class="symbol symbol_red"></span> Messung oberhalb des Beurteilungswertes</option>
			        			</select>
			        		</div>
				        </div>
				        <div class="col-12 col-sm-5">
			        		<div class="col-1 fa fa-home" aria-hidden="true"></div><div class="col-10" >${adresse}</div><hr>
			        		<div class="col-1 fa fa-map-marker" aria-hidden="true"></div><div class="col-10" >${activeObject.obj_lat}, ${activeObject.obj_lon}<br>
			        		(${LLtoUSNG(activeObject.obj_lat, activeObject.obj_lon, 5)})</div>
			       		</div>
			        </div><br>
			        <hr> Geräte<br><form><div class="form-group"> <label for="messwert">Messwert [ppm]</label><input type="text" class="form-control" id="messwert" cols="50" value = "${activeObject.obj_messwert}" onchange="updateMesswert(${activeObject.obj_nummer}, this.value);"></div></form>
			        <form><div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis(${activeObject.obj_nummer},'marker', this.value);" rows="5">${activeObject.obj_hinweis}</textarea></div></form>`;

					infoWindow.setContent(markerContent);
					updateMesstruppsMarker(); // Aktualisiert die auswählbaren Messtrupps
					infoWindow.open(map,activeObject);
			    }); // Ende reverseGeocode()
			}); // Ende eventlistener
			
			google.maps.event.addListener(newShape,'dragend', function(){ //Aktualisiert Array und InfoWindow wenn Marker verschoben wird
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
						  <input type="text" class="form-control" value="${object.obj_label}" onchange="updateLabel(${object.obj_nummer}, this.value);" aria-label="Messpunktbezeichnung"></input>
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
			}); // Ende Eventlistener

			google.maps.event.addListener(newShape,'dragstart', function(){
				activeObject = this;
				infoWindow.setMap(null);
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
        activeObject = null;
        infoWindow.setMap(null);
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

function updateMesswert(ID, value){
	var index = objectArray.findIndex(x => x.obj_nummer == ID && x.obj_typ == "marker");
	objectArray[index].obj_messwert = value;
}

function updateHinweis(ID, type, value){
	var index = objectArray.findIndex(x => x.obj_nummer == ID && x.obj_typ == type);
	objectArray[index].obj_hinweis = value;
}

function updateLabel(ID, value){
	var index = objectArray.findIndex(x => x.obj_nummer == ID && x.obj_typ == "marker");
	objectArray[index].obj_label = value;
	objectArray[index].setLabel({text:value.toString(), fontWeight: "700"});
}

function setMesstrupp(ID, value){
	let index = objectArray.findIndex(x => x.obj_nummer == ID && x.obj_typ == "marker");
	let indexMesstruppsArray = messtruppArray.findIndex(x => x.abkürzung == value); // Daten des ausgewählten Messtrupps aus dem Array lesen
	objectArray[index].obj_messtrupp = value; // objectArray mit neuem Messtrupp updaten

	let oldSymbol = activeObject.getIcon(); // Bisherige Werte des Markersymbols auslesen
	activeObject.setIcon({
		path: google.maps.SymbolPath.CIRCLE,
		scale: oldSymbol.scale,
		fillColor: oldSymbol.fillColor,
		fillOpacity: oldSymbol.fillOpacity,
		strokeColor: messtruppArray[indexMesstruppsArray].farbe,
		strokeWeight: oldSymbol.strokeWeight,
		labelOrigin: oldSymbol.labelOrigin});

	objectArray[index].icon.strokeColor = messtruppArray[indexMesstruppsArray].farbe; //  objectArray mit neuem Symbol updaten
}