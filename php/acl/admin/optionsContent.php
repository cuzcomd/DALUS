<div id="newUser" class="tab-pane fade">
    <form id ="addNewUser" action='' method='POST' class='ajax_create_user form-horizontal' role='form'>
		<div class="form-group">
			<label for="newBenutzername" class="col-xs-4 control-label">Benutzername:</label>
			<div class="col-xs-8">
		    	<input type="text" class="form-control" id="newBenutzername" name="benutzername" maxlength="20" required>
		    </div>
		</div>
		<div class="form-group">
		    <label for="newVorname" class="col-xs-4 control-label">Vorname:</label>
		    <div class="col-xs-8">
		    	<input type="test" class="form-control" id="newVorname" name="vorname" maxlength="20" required>
		    </div>
		</div>
		<div class="form-group">
			<label for="newNachname" class="col-xs-4 control-label">Nachname:</label>
			<div class="col-xs-8">
		    	<input type="text" class="form-control" id="newNachname" name="nachname" maxlength="20" required>
		    </div>
		</div>
		<div class="form-group">
		    <label for="newPasswort" class="col-xs-4 control-label">Passwort:</label>
		    <div class="col-xs-8">
		    	<input type="password" class="form-control" id="newPasswort" name="passwort" required>
		    </div>
		</div>

		<h5><b>Berechtigung</b></h5>
		<div class="row">
	    	<div class="funkyradio col-sm-6">
		        <div class="funkyradio-primary">
		            <input type="radio" name="level" id="levelAdmin" value="admin" required />
		            <label for="levelAdmin">Administrator</label>
		        </div>
		        <div class="funkyradio-primary">
		            <input type="radio" name="level" id="levelEditor" value="editor" required />
		            <label for="levelEditor">Editor</label>
		        </div>
		        <div class="funkyradio-primary">
		            <input type="radio" name="level" id="levelUser" value="user" checked required />
		            <label for="levelUser">Benutzer</label>
		        </div>
	    	</div>
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

<div id="adminSettings" class="tab-pane fade">
    <div id = "optCars">
    	Hier erscheinen die Einstellungen, die an der Datenbank vorgenommen werden können.
    </div>
</div> <!-- Ende deleteUser -->