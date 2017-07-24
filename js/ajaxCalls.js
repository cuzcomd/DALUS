		$("document").ready(function(){
			$(".ajax_create_project").submit(function(){
				var data = {
					"action": "create"
				};
				data = $(this).serialize() + "&" + $.param(data);
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "php/projects.php",
					data: data,
					success: function(data) {
					toastr.success('Neues Projekt angelegt.');
					$("#activeProject").html("&nbsp; "+data["projekttitel"]);
					$('.activeProjectName').attr("value",data["projekttitel"]);
					$('.activeProjectID').val(data["projekt_id"]);
					$('.activeUserID').val(userID);
					prj_id = data["projekt_id"]; // In Datenbak erzeugte Projekt ID einlesen
					$('#editProject').show(); // Menüpunkt 'Projekt bearbeiten' anzeigen
					$('#saveProject').show(); // Menüpunkt 'Projekt speichern' anzeigen
					$('#deleteProject').show(); // Menüpunkt 'Projekt speichern' anzeigen
					$('#modal_new_project').modal('hide');
					$("#projekt_titel_new").val(""); //Leert den Projekttitel im Inputfeld für ein neues Projekt
					clearMap();
					updateProjects();
					updateSharedProjects();
					isSharedWith();
					loadProjectObjects();
					updateAllUsers()
					},
					error: function(xhr, desc, err) {
						console.log(xhr);
						console.log("Details: " + desc + "\nError:" + err);
					}
				});
				return false;
			});
			$(".ajax_edit_project").submit(function(){
				var data = {
					"action": "edit"
				};
				data = $(this).serialize() + "&" + $.param(data);
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "php/projects.php",
					data: data,
					success: function(data) {
					toastr.success('Projekt geändert.');
					$("#activeProject").html("&nbsp; "+data["projekttitel"]);
					$('.activeProjectName').attr("value",data["projekttitel"]);
					
					$('#modal_edit_project').modal('hide');
					updateProjects();
					updateSharedProjects();
					isSharedWith();
					updateAllUsers()
					},
					error: function(xhr, desc, err) {
						console.log(xhr);
						console.log("Details: " + desc + "\nError:" + err);
					}
				});
				return false;
			});
		});
		$(".ajax_load_project").submit(function(){
			var data = {
				"action": "load"
			};
			data = $(this).serialize() + "&" + $.param(data);
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "php/projects.php",
				data: data,
				success: function(data) {
					toastr.success('Projekt geladen.');
					$("#activeProject").html("&nbsp; "+data["projektName"]); //Projekttitel anzeigen
					prj_id = parseInt(data["projektID"]); // In Datenbak erzeugte Projekt ID einlesen
					messpunktNummer = parseInt(data["maxNum"])+1;
					$('#editProject').show(); // Menüpunkt 'Projekt bearbeiten' anzeigen
					$('#saveProject').show(); // Menüpunkt 'Projekt speichern' anzeigen
					$('#deleteProject').show(); // Menüpunkt 'Projekt speichern' anzeigen
					$('#modal_open_project').modal('hide'); //Modal schließen
					$('.activeProjectName').attr("value",data["projektName"]);
					$('.activeProjectID').val(data["projektID"]);
					$('.activeUserID').val(userID);
					clearMap();
					loadProjectObjects(); //Objekte einlesen
					updateProjects(); //Verfügbare Projekte aktualiseren
					updateSharedProjects(); //Verfügbare mit dem Benutzer geteilte Projekte aktualiseren
					isSharedWith(); //Aktualisieren, mit wem das geöffnete Projekt geteilt ist
					updateAllUsers()
				},
				error: function(xhr, desc, err) {
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			});
			return false;
		});
		$(".ajax_edit_user").submit(function(){
			var data = {
				"action": "editUser"
			};
			data = $(this).serialize() + "&" + $.param(data);
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "php/projects.php",
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
						case 'PasswordsDontMatch':
							toastr.warning('Die Passwörter stimmen nicht überein.');
							break;
						case 'WrongPassword':
							toastr.warning('Das aktuelle Passwort ist nicht korrekt.');
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
			return false;
		});

		$(".ajax_create_user").submit(function(){
				var data = {
					"action": "createUser"
				};
				data = $(this).serialize() + "&" + $.param(data);
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "php/projects.php",
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
				return false;
			});

		$(".ajax_delete_user").submit(function(){
			var data = {
				"action": "deleteUser"
			};
			data = $(this).serialize() + "&" + $.param(data);
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "php/projects.php",
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
			return false;
		});