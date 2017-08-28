function startDrawingManager(map){
	var marker_color = "white"; //Marker standardmäßig als gelbe Marker zeichnen
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
			markerOptions: {icon: {url:'images/white.png',anchor: new google.maps.Point(16, 16)}, draggable:true}
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
			var newObject = event.overlay;
			newObject.setValues({
				obj_nummer: objectNummer,
				obj_parameter: newObject.getPath().getArray(),
				obj_typ: 'polyline'
				});
			objectArray.push(newObject);
			objectNummer +=1;
		}

		if (event.type == google.maps.drawing.OverlayType.POLYGON) {
			var newObject = event.overlay;
			newObject.setValues({
				obj_nummer: objectNummer,
				obj_parameter: newObject.getPath().getArray(),
				obj_typ: 'polygon'
				});
			objectArray.push(newObject);
			objectNummer +=1;
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
			objectNummer +=1;
		}

		if (event.type == google.maps.drawing.OverlayType.MARKER && marker_typ == 'metManual') {
			var newMarker = event.overlay;
			var latitude= newMarker.getPosition().lat().toFixed(6);
			var longitude= newMarker.getPosition().lng().toFixed(6);
			generateMET(map, latitude, longitude);
			newMarker.setMap(null);
		}

		if (event.type == google.maps.drawing.OverlayType.MARKER && marker_typ == 'messpunkt') {
			var newMarker = event.overlay;
			newMarker.setValues({
				obj_nummer: messpunktNummer,
				obj_lat: newMarker.getPosition().lat().toFixed(6),
				obj_lon: newMarker.getPosition().lng().toFixed(6),
				obj_farbe: marker_color,
				obj_typ: 'marker',
				obj_messwert: '0',
				obj_hinweis: ' '
				});
			newMarker.setLabel({text:newMarker.obj_nummer.toString(), fontWeight: "700"});
			newMarker.content = '';
			objectArray.push(newMarker);
			messpunktNummer += 1; // Messpunktnummer inkrementieren

			google.maps.event.addListener(newMarker,'click',function(){ // Öffnet Infowindow bei Klick auf Marker
				activeObject = this; // Setzt den aktuell ausgewählten marker als aktiv
				let index = objectArray.findIndex(x => x.obj_nummer == this.obj_nummer && x.obj_typ == this.obj_typ); // Ermittelt Array-Index des aktuellen Markers
				this.setValues({messwert: objectArray[index].messwert}); // Aktualisiert den aktullen Messwert aus dem im Array gespeicherten Wert
				infoWindow.setContent('<h5>Messpunkt '+ objectArray[index].obj_nummer + '</h5><span class="fa fa-map-marker" aria-hidden="true"></span> '+	objectArray[index].obj_lat+', '+ objectArray[index].obj_lon+'<hr>'+
					'<form><div class="form-group"> <label for="messwert">Messwert [ppm]</label><input type="text" class="form-control" id="messwert" cols="50" value = "'+objectArray[index].obj_messwert+'" onchange="updateMesswert('+objectArray[index].obj_nummer+', this.value);"></div>'+
					'<div class="form-group"> <label for="hinweis">Hinweise</label><textarea id="hinweis" class="form-control" onchange="updateHinweis('+objectArray[index].obj_nummer+', this.value);" rows="5">'+objectArray[index].obj_hinweis+'</textarea></div></form>'+
					'<div class="btn-group" role="group" aria-label="Optionen">'+
					'<button type="button" class="btn btn-default btn-danger btnInfoWindow" id="deleteButton" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button>'+
					'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',1);"><img src="images/white.png"></button>'+
					'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',2);"><img src="images/green.png"></button>'+
					'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',3);"><img src="images/blue.png"></button>'+
					'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',4);"><img src="images/yellow.png"></button>'+
					'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',5);"><img src="images/red.png"></button></div>');
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
					'<button type="button" class="btn btn-default btn-danger btnInfoWindow" id="deleteButton" onclick="deleteObject();" ><i class="fa fa-trash-o"></i></button>'+
					'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',1);"><img src="images/white.png"></button>'+
					'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',2);"><img src="images/green.png"></button>'+
					'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',3);"><img src="images/blue.png"></button>'+
					'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',4);"><img src="images/yellow.png"></button>'+
					'<button type="button" class="btn btn-default" onclick="changeColor('+this.obj_nummer+',5);"><img src="images/red.png"></button></div>');
				infoWindow.setPosition(new google.maps.LatLng(newMarker.obj_lat , newMarker.obj_lon));
				infoWindow.setOptions({pixelOffset: new google.maps.Size(0,-16)});
				infoWindow.open(map,this);
			});

			google.maps.event.addListener(newMarker,'dragstart', function(){
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