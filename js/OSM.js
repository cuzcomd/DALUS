function loadOSMLayer(){
	//Kartenmittelpunkt auf die Stadt legen, die in den Optionen angegeben wurde
	new google.maps.Geocoder().geocode( { 'address': cityName}, function(results, status) {
		if (status == 'OK') {
			map.setCenter(results[0].geometry.location);
		}
		else {
			alert('Geocode was not successful for the following reason: ' + status);
		}
	});

	var mapTypeIds = [];
	for(var type in google.maps.MapTypeId) {
		mapTypeIds.push(google.maps.MapTypeId[type]);
	}
	mapTypeIds.push("OSM");
	map = new google.maps.Map(document.getElementById('map'), {
		zoom: 14,
		mapTypeId: "roadmap",
		mapTypeControlOptions: {
			mapTypeIds: mapTypeIds,
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
			position: google.maps.ControlPosition.TOP_RIGHT
		}//,
		//center: {lat: 52.13024, lng: 11.56567700000005} // Koordinaten des Kartenmittelpunkts
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
		name: "OSM",
		maxZoom: 18
	}));	
}//Ende Funktion loadOSMLayer