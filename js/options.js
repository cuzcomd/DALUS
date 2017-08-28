function updateKataster(){ //Aktualisiert die Punkte im Messkataster
	dataTable.clear(); // Leert die Liste aller verfügbaren Optionen
	var data = {"action": "updateKataster"};
	data = $(this).serialize() + "&" + $.param(data);
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "php/options.php",
		data: data,
		success: function(data) {
			var obj = JSON.parse(data[0]);
			
			$.each(obj, function (key, value) {
				dataTable.row.add([value.ID, value.Nummer, value.Bezeichnung, value.Adresse, value.ODL, value.IPS, value.Koordinaten,"<div role='button' class='btn btn-default' data-id='"+value.ID+"' onclick='deleteRow(this, dataTable.row($(this).parents(\"tr\")))'><span class='fa fa-trash'></span></div>"]).draw();
			 });

			maxRowID = Math.max.apply(Math,obj.map(function(o){return o.ID;})); //Liest die größte gespeicherte ID aus
		},
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	});//Ende Ajax
}//Ende Funktion updateKataster

function saveKataster(){ //Speichert das Messkataster
	 var data = [];
	var headers = [];
	$('#kataster > thead th').not(':last').each(function(index, item) {
    	headers[index] = $(item).text();
	});
	$('#kataster > tbody tr').has('td').each(function() {
        var arrayItem = {};
        $('td', $(this)).not(':last').each(function(index, item) {
            arrayItem[headers[index]] = $(item).html();
   		});
    	data.push(arrayItem);
		});
		data = JSON.stringify(data);

	$.ajax({
		type: "POST",
		dataType: "json",
		url: "php/options.php",
		data: {"action": "saveKataster", "data": data} ,
		success: function(data) {
			updateKataster();
			console.log(data);
			toastr.success('Änderungen gespeichert.');
		},
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	});//Ende Ajax 
}//Ende Funktion saveKataster

function deleteRow(obj, parent){ //Löscht eine zeile aus dem Messkataster
	let rowID = obj.getAttribute('data-id');
	deleteRowsArray.push(String(rowID));
	parent.remove().draw();
}

function addRow(){ //Fügt eine neue Zeile in das Messkataster ein
	maxRowID += 1;
	dataTable.row.add([ maxRowID, '','','','','','',"<div role='button' class='btn btn-default' data-id='"+maxRowID+"' onclick='deleteRow(this, dataTable.row($(this).parents(\"tr\")))'><span class='fa fa-trash'></span></div>"]).draw();
}

function loadFixpoints(switchMesspunkte){ // Zeigt das Messkataster auf der Karte an
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
			infoWindow.setOptions({pixelOffset: new google.maps.Size(0,-16)});
			infoWindow.open(map);
		});
	} //Ende else
}//Ende function loadFixpoint