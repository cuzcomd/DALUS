function startDrawingManager(map){
	var marker_color = "white"; //Marker standardmäßig als weiße Marker zeichnen
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
			markerOptions: {icon: {url:'images/white.png',anchor: new google.maps.Point(16, 16), labelOrigin: new google.maps.Point(16,40)}, draggable:true}
		});
		marker_color = "white";
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
				obj_typ: 'polyline'
				});
			objectArray.push(newShape);
			objectNummer += 1;
		}

		if (event.type == google.maps.drawing.OverlayType.POLYGON) {
			newShape.setValues({
				obj_nummer: objectNummer,
				obj_parameter: newShape.getPath().getArray(),
				obj_typ: 'polygon'
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
				obj_typ: 'circle'
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

		if (event.type == google.maps.drawing.OverlayType.MARKER && marker_typ == 'messpunkt') {
			newShape.setValues({
				obj_nummer: messpunktNummer,
				obj_lat: newShape.getPosition().lat().toFixed(6),
				obj_lon: newShape.getPosition().lng().toFixed(6),
				obj_farbe: marker_color,
				obj_typ: 'marker',
				obj_messwert: '0',
				obj_label: messpunktNummer,
				obj_hinweis: ' '
				});
			newShape.setLabel({text:newShape.obj_label.toString(), fontWeight: "700"});
			newShape.content = '';
			objectArray.push(newShape);
			messpunktNummer += 1; // Messpunktnummer inkrementieren

			google.maps.event.addListener(newShape,'click',function(){ // Öffnet Infowindow bei Klick auf Marker
				activeObject = this; // Setzt den aktuell ausgewählten marker als aktiv
				let index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer && x.obj_typ == this.obj_typ); // Ermittelt Array-Index des aktuellen Markers
				this.setValues({messwert: objectArray[index].messwert}); // Aktualisiert den aktullen Messwert aus dem im Array gespeicherten Wert
				
				reverseGeocode(objectArray[index].position, function(result)
			    {
			        if (result === 0)
			        {
				          infoWindow.setContent('<label for="messpunktLabel">Messpunkt</label><input type="text" class="form-control" id="messpunktLabel" value = "'+objectArray[index].obj_label+'" onchange="updateLabel('+objectArray[index].obj_nummer+', this.value);">'+
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
						infoWindow.open(map,activeObject);
					}
			        else
			        {
				        var adresse = result;
				        infoWindow.setContent('<label for="messpunktLabel">Messpunkt</label><input type="text" class="form-control" id="messpunktLabel" value = "'+objectArray[index].obj_label+'" onchange="updateLabel('+objectArray[index].obj_nummer+', this.value);">'+
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
						infoWindow.open(map,activeObject);
			        }
			    }); // Ende reverseGeocode()
			}); // Ende eventlistener
			
			google.maps.event.addListener(newShape,'dragend', function(){ //Aktualisiert Array und InfoWindow wenn Marker verschoben wird
				activeObject = this;
				let index = objectArray.findIndex(x => x.obj_nummer == newShape.obj_nummer  && x.obj_typ == newShape.obj_typ);  // Ermittelt Array-Index des aktuellen Markers
				objectArray[index].obj_lat = newShape.getPosition().lat().toFixed(6); // Aktualisiert geogr. Position im Array
				objectArray[index].obj_lon = newShape.getPosition().lng().toFixed(6);// Aktualisiert geogr. Position im Array

				reverseGeocode(objectArray[index].position, function(result)
			    {
			        if (result === 0)
			        {
						infoWindow.setContent('<label for="messpunktLabel">Messpunkt</label><input type="text" class="form-control" id="messpunktLabel" value = "'+objectArray[index].obj_label+'" onchange="updateLabel('+objectArray[index].obj_nummer+', this.value);">'+
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
						infoWindow.setPosition(new google.maps.LatLng(newShape.obj_lat , newShape.obj_lon));
						infoWindow.setOptions({pixelOffset: new google.maps.Size(0,-16)});
						infoWindow.open(map,activeObject);
					}
			        else
			        {
				        var adresse = result;
				        infoWindow.setContent('<label for="messpunktLabel">Messpunkt</label><input type="text" class="form-control" id="messpunktLabel" value = "'+objectArray[index].obj_label+'" onchange="updateLabel('+objectArray[index].obj_nummer+', this.value);">'+
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
				        infoWindow.setPosition(new google.maps.LatLng(newShape.obj_lat , newShape.obj_lon));
						infoWindow.setOptions({pixelOffset: new google.maps.Size(0,-16)});
						infoWindow.open(map,activeObject);
					}
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

function updateLabel(ID, value){
	var index = objectArray.findIndex(x => x.obj_nummer == ID);
	objectArray[index].obj_label = value;
	objectArray[index].setLabel({text:value.toString(), fontWeight: "700"});
}