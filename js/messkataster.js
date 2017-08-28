function updateKataster(UID){ //Aktualisiert die Punkte im Messkataster
	katasterArray = [];
	dataTable.clear(); // Leert die Liste aller verfügbaren Optionen
	var data = [];
	data = $(this).serialize() + "&" + $.param(data);
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "php/options.php",
		data: {"action": "updateKataster", "UID": UID},
		success: function(data) {
			var obj = JSON.parse(data[0]);
			
			$.each(obj, function (key, value) {
				dataTable.row.add([value.ID, value.Nummer, value.Bezeichnung, value.Adresse, value.ODL, value.IPS, value.Koordinaten,"<div role='button' class='btn btn-default' onclick='deleteRow(dataTable.row($(this).parents(\"tr\")))'><span class='fa fa-trash'></span></div>"]).draw();
			 	let kat_point = {ID: value.ID, Nummer: value.Nummer, Bezeichnung: value.Bezeichnung, Adresse: value.Adresse, ODL: value.ODL, IPS: value.IPS, Koordinaten: value.Koordinaten};
			 	katasterArray.push(kat_point);
			 });

			//maxRowID = Math.max.apply(Math,obj.map(function(o){return o.ID;})); //Liest die größte gespeicherte ID aus
		},
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	});//Ende Ajax
}//Ende Funktion updateKataster

function saveKataster(UID){ //Speichert das Messkataster
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
		data: {"action": "saveKataster", "data": data, "UID": UID} ,
		success: function(data) {
			updateKataster(userID);
			toastr.success('Änderungen gespeichert.');
		},
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	});//Ende Ajax 
}//Ende Funktion saveKataster

function deleteRow(parent){ //Löscht eine zeile aus dem Messkataster
	parent.remove().draw();
}

function addRow(){ //Fügt eine neue Zeile in das Messkataster ein
	maxRowID += 1;
	dataTable.row.add([ maxRowID, '','','','','','',"<div role='button' class='btn btn-default' data-id='"+maxRowID+"' onclick='deleteRow(this, dataTable.row($(this).parents(\"tr\")))'><span class='fa fa-trash'></span></div>"]).draw();
}

function loadFixpoints(switchMesspunkte){ // Zeigt das Messkataster auf der Karte an
	
	switchMesspunkte.find('i').toggleClass("fa-toggle-off fa-toggle-on"); // Damit Menüpunkt farblich hinterlegt wird
	if(switchMesspunkte.attr('data-click-state') == 1) { 
		switchMesspunkte.attr('data-click-state', 0); // Wenn Schalter aktiviert ist, ihn wieder deaktivieren
		markerArray.forEach(function (elem) {
			elem.setMap(null);
		}); // Alle Fixpunkte von der Karte löschen
		markerArray =[];
		infoWindow.close();
	}
	else {
		switchMesspunkte.attr('data-click-state', 1); // Wenn Schalter deaktiviert ist, ihn wieder aktivieren

		$.each(katasterArray, function (key, value) { // Die Marker an den Messpunkten darstellen
			var pos = value.Koordinaten.split(",");
			var marker = new google.maps.Marker({
			    position: new google.maps.LatLng(Number(pos[0]).toFixed(6),Number(pos[1]).toFixed(6)),
			    icon: 'images/radioactive_flag.png',
			    nummer: value.Nummer,
			    name: value.Bezeichnung,
			    adresse: value.Adresse,
			    odl: value.ODL,
			    ips: value.IPS
			});
			
			markerArray.push(marker);
			marker.setMap(map);

			google.maps.event.addListener(marker,'click',function(){ // Öffnet Infowindow bei Klick auf Marker
				activeObject = this;
				
				infoWindow.setContent("<div > Messpunkt "+ this.nummer +' (' + this.name +')<hr><br/><div class="fa fa-home"></div> ' + this.adresse +
			 	'<br/><br/><div class="fa fa-map-marker"></div> ' + this.getPosition().lat().toFixed(6) +' , ' + this.getPosition().lng().toFixed(6) +' (' + LLtoUSNG(this.getPosition().lat(), this.getPosition().lng(), 5) +
			 	')<br/><br/> Ortsdosisleistung: ' + this.odl + ' nSv/h <br/>Nullrate: ' + this.ips + ' Imp/s</div>');
				infoWindow.setPosition(this.getPosition());
			 	infoWindow.setOptions({pixelOffset: new google.maps.Size(0,-20)});
			 	infoWindow.open(map);
			}); // Ende des Eventlisteners
		}); // Ende each-Loop
	} //Ende else
}//Ende function loadFixpoint