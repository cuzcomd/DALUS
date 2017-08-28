function loadUser(){ // Lädt die Daten des angemeldeten Benutzers
	var data = {"action": "loadUser"};
	data = $(this).serialize() + "&" + $.param(data);
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "php/users.php",
		data: data,
		success: function(data) {
			benutzer = data["benutzer"];
			optionen = data["optionen"];
			$("#activeUser").html('&nbsp; '+benutzer.vorname +' '+ benutzer.nachname+'&nbsp;'); //Zeigt den Namen im Optionsmenü an
			$(".activeUserID").val(benutzer.id); //Setzt die ID des Benutzers als Feldwert
			$("#username").val(benutzer.benutzername);
			userID = benutzer.id; //Speichert die Benutzer-ID in einer globalen Variablen
			userAL = data["accessLevel"]; //Speichert die Zugriffsberechtigung des Benutzers in einer globalen Variablen
		},
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	});//Ende Ajax
}//Ende Funktion loadUser

function updateAllUsers(){ //Aktualisiert die Liste der Projekte, die für den angemeldeten Benutzer sichtbar sind
	$('.listOfAllUsers').children('option').remove(); // Leert die Liste aller verfügbaren Optionen
	var data = {"action": "updateAllUsers"};
	data = $(this).serialize() + "&" + $.param(data);
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "php/users.php",
		data: data,
		success: function(data) {
			$('.listOfAllUsers').children('option').remove();
			$('.listOfAllUsersExceptMe').children('option').remove();
			$.each(data, function (key, value) {
				$('.listOfAllUsers')// Fügt eine neue Option hinzu
				.append($('<option></option>') 
			 	.attr('value', value.id)
			 	.text(value.vorname+' '+value.nachname+' ('+value.benutzername+')'));

			 	if (value.id != userID){
			 		$('.listOfAllUsersExceptMe')// Fügt eine neue Option hinzu
					.append($('<option></option>') 
			 		.attr('value', value.id)
			 		.text(value.benutzername));
			 	}
			});
		},
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	});//Ende Ajax
}//Ende Funktion updateAllUsers