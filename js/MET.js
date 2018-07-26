function setCoord(){
	$('#modalMET').modal('hide');
	var metManListener = google.maps.event.addListener(map, 'click', function(e) {
		new google.maps.Geocoder().geocode({'location': e.latLng}, function(results, status) {
				if (status === google.maps.GeocoderStatus.OK) {
					var adresse = results[0].formatted_address;
					document.getElementById('addresse').value = adresse;
					ursprungKoordinaten = e.latLng;
				} 
				else 
				{
					alert('Die Adresse konnte nicht ermittelt werden. Grund: ' + status);
					
				}
			}); //Ende reverse geocoder
		$('#modalMET').modal('show');
		google.maps.event.removeListener(metManListener); //Entfernt den eventListener, damit die manuelle Auswahl nur einmal ausgeführt wird.
});
}

function generateMET(resultsMap) {
	var adresse = document.getElementById('addresse').value;
	if(ursprungKoordinaten == '')
	{
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
	}
	else
	{
		var latitude = ursprungKoordinaten.lat();
		var longitude = ursprungKoordinaten.lng();
		ursprungKoordinaten = '';
		drawPolygon(resultsMap, latitude, longitude, adresse);
	}
	}// Ende function generateMET()

function drawPolygon(map, lat, lon, geoAdresse, winkel, richtung, innen, aussen, counter){
	marker_color ="black";
	$('#modalMET').modal('hide');
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
		obj_typ : 'polygon2',
		zIndex:0
	});
	objectArray.push(polygon2);

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
		obj_typ : 'polygon1',
		zIndex:0
	});
	objectArray.push(polygon1);
	
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
			obj_typ : 'polygonCenter',
			zIndex:0
		});
		objectArray.push(line);
	}

	// Marker am Freisetzungsort erstellen 
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(lat, lon),
		icon:{url:'images/black.png', anchor: new google.maps.Point(16,16)},
		obj_lat: lat,
		obj_lon: lon,
		obj_parameter: metParameter,
		obj_typ: 'met',
		obj_nummer: metCounter,
		obj_farbe: 'black',
		map: map,
		title: 'Freisetzungsort',
		draggable:false,
		poly1: polygon1,
		poly2: polygon2,
		centerLine:line,
		zIndex:10
	});

		objectArray.push(marker);
		metCounter += 1;

		drawingManager.setOptions({
						drawingMode: google.maps.drawing.OverlayType.null
					});

	marker.addListener('click', function() {//Informationsfenster bei Klick auf Marker öffnen
		clearSelectionLoad();
		if (selectedShape)
		{
			selectedShape.setEditable(false);
			selectedShape = null;
		}
		activeObject = this; // Setzt den aktuell ausgewählten Marker als aktiv
		infoWindow.setContent(`<h5>Freisetzungsort</h5>
		<div class="fa fa-home"></div> ${adresse}<br/><hr>
		<div class="fa fa-map-marker"></div> ${this.obj_lat} , ${this.obj_lon}<br/> (${utm_koord})<br/><br/>
		Gefährdung im Gebäude: ${distanz_innen} m<br/>
		Gefährdung im Freien: ${distanz_aussen} m<br/><br/>
		Windrichtung: ${windrichtung_initial}&deg;<br/>
		Ausbreitungswinkel: ${ausbreitungswinkel} &deg;<br/><br/>
		<div class="btn-group" role="group" aria-label="Optionen">
		<button type="button" class="btn btn-default btn-danger" id="deleteButton" ontouchstart="deleteObject()" onclick="deleteObject()"><i class="fa fa-trash-o"></i></button>`)
		infoWindow.open(map,marker);
	});
} //Ende function drawPolygon()

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