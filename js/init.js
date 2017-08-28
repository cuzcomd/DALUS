function updateProjects(){ //Aktualisiert die Liste der Projekte, die für den angemeldeten Benutzer sichtbar sind
	$('#projectOpen').children('option').remove();// Leert die Liste aller verfügbaren Optionen
	var data = {"action": "updateProjects"};
	data = $(this).serialize() + "&" + $.param(data);
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "php/projects.php",
		data: data,
		success: function(data) {
			$.each(data, function (key, value) {
			 	$('#projectOpen') // Fügt eine neue Option hinzu
			 	.append($('<option></option>') 
			 	.attr('value', value.prj_name)
			 	.text(value.prj_name));
			});
		},
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	});//Ende Ajax
}//Ende Funktion updateProjects

function updateSharedProjects(){ //Aktualisiert die Liste der Projekte, die für den angemeldeten Benutzer sichtbar sind
	$('#projectOpenShared').children('option').remove(); // Leert die Liste aller verfügbaren Optionen
	var data = {"action": "updateSharedProjects"};
	data = $(this).serialize() + "&" + $.param(data);
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "php/projects.php",
		data: data,
		success: function(data) {
			$.each(data, function (key, value) {
				$('#projectOpenShared')// Fügt eine neue Option hinzu
				.append($('<option></option>') 
			 	.attr('value', value.prj_name)
			 	.text(value.prj_name));
			});
		},
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	});//Ende Ajax
}//Ende Funktion updateSharedProjects

function isSharedWith(){ //Aktualisiert die Liste der Projekte, die für den angemeldeten Benutzer sichtbar sind
	$('#projektShared').children('option').remove();// Leert die Liste aller verfügbaren Optionen
	var data = {"action": "isSharedWith", "projectID": prj_id};
	data = $(this).serialize() + "&" + $.param(data);
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "php/projects.php",
		data: data,
		success: function(data) {
			$.each(data, function (key, value) {
				if (value.shared == "yes") {
					$('#projektShared')// Fügt eine neue Option hinzu
				 	.append($('<option selected selected="selected"></option>') 
				 	.attr('value', value.id)
				 	.text(value.username));
				}
				else{
					$('#projektShared')
					 .append($('<option></option>') 
					 .attr('value', value.id)
					 .text(value.username));
					}
			});//Ende each()
		},//Ende success
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	});//Ende Ajax
}//Ende Funktion isSharedWith