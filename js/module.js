function loadGPS(elementID, car, pathColor){
	let startTrack = $("#startTrackInput").val();
	let endTrack = $("#endTrackInput").val();
	if(elementID.checked === false) { 
		var gpsPath = [];
		let index = objectArray.findIndex(x => x.obj_car == car); // Ermittelt Array-Index des PGS-Pfads
		objectArray[index].setMap(null); //GPS-Pfad von der Karte löschen
		objectArray.splice(index,1); // Löscht das Objekt aus dem Objekt-Array
	}
	else {
		var gpsPath = [];
		var data = {
			"task" : "loadGPS",
			"car" : car,
			"start" : startTrack,
			"end" : endTrack
			};
		data = $(this).serialize() + "&" + $.param(data);
		
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "php/geometry.php",
			data: data,
			success: function(data) {
				for (let value of data) {
					let gpsCoord = new google.maps.LatLng(value.gps_lat, value.gps_lon);
					gpsPath.push(gpsCoord);
				}
				var gpsPolyline = new google.maps.Polyline({
					path: gpsPath,
					strokeOpacity: 0.8,
					strokeColor: pathColor,
					strokeWeight: 10,
					geodesic: true,
					obj_typ: 'gpsPath',
					obj_car: car,
					obj_nummer: objectNummer,
					map: map
				});
				objectArray.push(gpsPolyline);
				objectNummer += 1;
			}, //Ende success
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			} //ende error
		}); //Ende Ajax
	} // Ende else-Funktion
} //Ende function loadGPS