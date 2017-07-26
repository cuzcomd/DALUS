<div id="newUser" class="tab-pane fade in active">
								    <form id ="addNewUser" action='' method='POST' class='ajax_create_user form-horizontal' role='form'>
										<div class="form-group">
											<label for="newBenutzername" class="col-xs-4 control-label">Benutzername:</label>
											<div class="col-xs-8">
										    	<input type="text" class="form-control" id="newBenutzername" name="benutzername" required>
										    </div>
										</div>
										<div class="form-group">
										    <label for="newVorname" class="col-xs-4 control-label">Vorname:</label>
										    <div class="col-xs-8">
										    	<input type="test" class="form-control" id="newVorname" name="vorname" required>
										    </div>
										</div>
										<div class="form-group">
											<label for="newNachname" class="col-xs-4 control-label">Nachname:</label>
											<div class="col-xs-8">
										    	<input type="text" class="form-control" id="newNachname" name="nachname" required>
										    </div>
										</div>
										<div class="form-group">
										    <label for="newPasswort" class="col-xs-4 control-label">Passwort:</label>
										    <div class="col-xs-8">
										    	<input type="password" class="form-control" id="newPasswort" name="passwort" required>
										    </div>
										</div>
										<div class="radio">
										 	<label>
										 	<input type="radio" name="level" id="levelAdmin" value="admin" required>
										 	Administrator
										 	</label>
										</div>
										<div class="radio">
										 	<label>
										 	<input type="radio" name="level" id="levelUser" value="user" checked required>
										 	Benutzer
										 	</label>
										</div>
										<br>
										<button type="submit" class="btn btn-primary"><span class='fa fa-user-plus'></span> Neuen Benutzer anlegen</button>
									</form>
								</div> <!-- Ende newUser -->
								<div id="deleteUser" class="tab-pane fade">
								    <form id ="deleteUser" action='' method='POST' class='ajax_delete_user form-horizontal' role='form'>
							     		<select multiple class="form-control listOfAllUsers" type="text" id="deleteUsers" name="users[]" size="10">
										<!-- Liste aller Nutzernamen -->
										</select>
										<br>
										<button type="submit" class="btn btn-primary"><span class='fa fa-user-times'></span> Ausgewählte Benutzer löschen</button>
									</form>
								</div> <!-- Ende deleteUser -->