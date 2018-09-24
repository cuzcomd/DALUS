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
			$("#username").val(benutzer.benutzername);
			OWMAPIkey = data["owmapi"].opt_OWMAPI;
			cityName = optionen.opt_city;
			$("#owmcity").val(cityName);
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
			$.each(data['benutzer'], function (key, value) {
				$('.listOfAllUsers')// Fügt eine neue Option hinzu
				.append($('<option></option>') 
			 	.attr('value', value.id)
			 	.text(value.vorname+' '+value.nachname+' ('+value.benutzername+')'));
			 });
			$.each(data['benutzerom'], function (key, value) {
		 		$('.listOfAllUsersExceptMe')// Fügt eine neue Option hinzu
				.append($('<option></option>') 
		 		.attr('value', value.id)
		 		.text(value.benutzername));
			});
		},
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	});//Ende Ajax
}//Ende Funktion updateAllUsers


$("document").ready(function(){
	$(".ajax_edit_user").submit(function(){
		var data = {"action": "editUser"};
		data = $(this).serialize() + "&" + $.param(data);
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "php/users.php",
			data: data,
			success: function(data) {
				switch(data){
					case 'Success':
						$('#modalUserSettings').modal('hide'); //Modal schließen
						toastr.success('Daten geändert.');
						loadUser(); //Lädt neue Nutzerdaten
						updateProjects(); //Verfügbare Projekte aktualiseren
						updateSharedProjects(); //Verfügbare mit dem Benutzer geteilte Projekte aktualiseren
						isSharedWith(); //Aktualisieren, mit wem das geöffnete Projekt geteilt ist
						updateAllUsers()
						break;
					default:
					toastr.warning('Fehler aufgetreten.');
						break;
				}
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		});//Ende ajax
	}); //Ende submit

	$(".ajax_create_user").on('submit', function (e) {
		e.preventDefault();

		var data = {"action": "createUser"};
		data = $(this).serialize() + "&" + $.param(data);
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "php/users.php",
			data: data,
			success: function(data) {
				switch(data){
					case 'yes':
						$('#modalUserSettings').modal('hide'); //Modal schließen
						toastr.success('Neuen Benutzer angelegt.');
						updateProjects();
						updateSharedProjects();
						isSharedWith();
						updateAllUsers()
						break;

					case 'no':
						toastr.warning('Dieser Benutzername ist schon vorhanden .');
						break;

					case 'noAdmin':
						toastr.warning('Sie verfügen nicht über die notwendigen Rechte, um diese Aktion auszuführen. Wenden Sie sich an einen Administrator.');
						break;

					default:
						toastr.warning('Fehler aufgetreten.');
						break;
				}
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		});
	});

	$(".ajax_delete_user").on('submit', function (e) {
		e.preventDefault();
		
		var data = {"action": "deleteUser"			};
		data = $(this).serialize() + "&" + $.param(data);
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "php/users.php",
			data: data,
			success: function(data) {
				switch(data){
					case 'ok':
						toastr.success('Nutzer gelöscht.');
						loadUser(); //Lädt neue Nutzerdaten
						updateProjects(); //Verfügbare Projekte aktualiseren
						updateSharedProjects(); //Verfügbare mit dem Benutzer geteilte Projekte aktualiseren
						isSharedWith(); //Aktualisieren, mit wem das geöffnete Projekt geteilt ist
						updateAllUsers()
						break;

					case 'noAdmin':
						toastr.warning('Sie verfügen nicht über die notwendigen Rechte, um diese Aktion auszuführen. Wenden Sie sich an einen Administrator.');
						break;

					default:
						toastr.warning('Fehler aufgetreten.');
						break;
				}
			},
			error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			}
		});
	});
}); // Ende document.ready