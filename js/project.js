function saveProjectStatus(){ // Erzeugt neue Messpunkte oder aktualisiert Vorhandene in der Datenbank
	objectArray.forEach(function(entry) {
		if (entry.obj_typ != "polygon1" && entry.obj_typ != "polygon2" && entry.obj_typ != "polygonCenter" )
		{
			var obj_farbe = entry.obj_farbe;
			var obj_lat = entry.obj_lat;
			var obj_lon = entry.obj_lon;
			var obj_typ = entry.obj_typ;
			var obj_nummer = entry.obj_nummer;
			var obj_hinweis = entry.obj_hinweis;
			var obj_messwert = entry.obj_messwert;
			var obj_parameter =JSON.stringify(entry.obj_parameter);
			var obj_label = entry.obj_label;
			var obj_messtrupp = entry.obj_messtrupp;
			var data = {
				"task" : "save",
				"obj_prj_id" : prj_id,
				"obj_color" : obj_farbe,
				"obj_lat" : obj_lat,
				"obj_lon" : obj_lon,
				"obj_nummer" : obj_nummer,
				"obj_hinweis" : obj_hinweis,
				"obj_messwert" : obj_messwert,
				"obj_parameter" : obj_parameter,
				"obj_typ" : obj_typ,
				"obj_label" : obj_label,
				"obj_messtrupp" : obj_messtrupp
			};
			data = $(this).serialize() + "&" + $.param(data);
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "php/geometry.php",
				data:data,
				success: function(data) {
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			}); //Ende ajax
			return false;
		} //Ende if-Anweisung
	});//Ende forEach()

	deleteArray.forEach(function(entry) {
		var obj_typ = entry.typ;
		var obj_nummer = entry.nummer;
		data = {"task" : "delete", "objekt_nummer": obj_nummer, "projekt_id": prj_id , "objekt_typ": obj_typ};
		data = $(this).serialize() + "&" + $.param(data);
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "php/geometry.php",
			data:data,
			success: function(data) {
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		});//Ende ajax
		return false;
	});//Ende forEach()
	setTimeout(function() {
			toastr.success('Projekt gespeichert. <span class="label label-info"><span class="fa fa-pencil" aria-hidden="true"></span> '+ objectArray.length+'</span> <span class="label label-danger"><span class="fa fa-trash" aria-hidden="true"></span> '+ deleteArray.length+'</span>'); //Zeigt an, wie viele Objekte gespeichert und gelöscht wurden
			deleteArray.length = 0; // Leert den Array der zu löschenden Elemente nach dem Speichern des Projekts
	}, 100);//Ende setTimeout
}// Ende Funktion saveProjectStatus

function deleteProject(){ // Löscht das aktuelle Projekt, sowie alle dazugehörigen Geometrieobjekte
	var data = {
		"action": "delete",
		"prj_id": prj_id
	};
	data = $(this).serialize() + "&" + $.param(data);
	alertify.confirm("Soll das Projekt wirklich gelöscht werden?", function (e) {
	    if (e) {
	    	 $.ajax({
				type: "POST",
				dataType: "json",
				url: "php/projects.php",
				data: data,
				success: function(data) {
					$("#activeProject").html("&nbsp; Kein Projekt geöffnet");
					$('#editProject').hide(); // Menüpunkt 'Projekt bearbeiten' anzeigen
					$('#saveProject').hide(); // Menüpunkt 'Projekt speichern' anzeigen
					$('#deleteProject').hide(); // Menüpunkt 'Projekt speichern' anzeigen
					prj_id = 0;
					clearMap();
					loadProjectObjects();
					updateProjects();
					updateSharedProjects();
					isSharedWith();
					updateAllUsers()
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			}); //Ende ajax
			toastr.error('Projekt gelöscht.');
			return false;
		} // Ende if
	}); // Ende alerify
} //Ende Funktion deleteProject()