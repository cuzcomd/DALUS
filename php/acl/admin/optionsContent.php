<div id="newUser" class="tab-pane fade">
    <form id ="addNewUser" action='' method='POST' class='ajax_create_user form-horizontal' role='form'>
    <div class="col-sm-4">
    <h5><b>Daten</b></h5>
		<div class="form-group">
			<label for="newBenutzername" class="col-xs-4 control-label">Benutzername:</label>
			<div class="col-xs-7">
		    	<input type="text" class="form-control" id="newBenutzername" name="benutzername" maxlength="20" required>
		    </div>
		</div>
		<div class="form-group">
		    <label for="newVorname" class="col-xs-4 control-label">Vorname:</label>
		    <div class="col-xs-7">
		    	<input type="test" class="form-control" id="newVorname" name="vorname" maxlength="20" required>
		    </div>
		</div>
		<div class="form-group">
			<label for="newNachname" class="col-xs-4 control-label">Nachname:</label>
			<div class="col-xs-7">
		    	<input type="text" class="form-control" id="newNachname" name="nachname" maxlength="20" required>
		    </div>
		</div>
		<div class="form-group">
		    <label for="newPasswort" class="col-xs-4 control-label">Passwort:</label>
		    <div class="col-xs-7">
		    	<input type="password" class="form-control" id="newPasswort" name="passwort" required>
		    </div>
		</div>
	</div>
	 <div class="col-sm-3">
		<h5><b>Berechtigung</b></h5>
		<div class="row">
	    	<div class="funkyradio">
		        <div class="funkyradio-primary">
		            <input type="radio" name="level" id="levelAdmin" value="admin" required />
		            <label for="levelAdmin">Administrator</label>
		        </div>
		        <div class="funkyradio-primary">
		            <input type="radio" name="level" id="levelUser" value="user" checked required />
		            <label for="levelUser">Benutzer</label>
		        </div>
	    	</div>
    	</div>
    </div>
		 <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
     <button type="submit" class="btn btn-primary"><span class='fa fa-user-plus'></span> Neuen Benutzer anlegen</button>
    </div>
  </div>
		
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

<div id="messkatasterGlobal" class="tab-pane fade">
	Das Messkataster listet vordefinierte Messpunkte auf, die Benutzer in der Karte einblenden können.<br>
	Administratoren haben die Möglichkeit, diese Punkte global für alle Benutzer zu verändern.
    <table id="katasterGlobal" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nummer</th>
                <th>Bezeichnung</th>
                <th>Adresse</th>
                <th>ODL</th>
                <th>IPS</th>
                <th>Koordinaten</th>
                <th></th>
            </tr>
        </thead>
       <tbody>
       </tbody>
    </table>
    <button role="button" class="btn btn-primary" onclick ="addRow(dataTable2,'dataTable2')"><i class="fa fa-plus-square"></i> Neuen Messpunkt hinzufügen</button>
    <button role="button" class="btn btn-primary" onclick="updateKataster(0, dataTable2)"><i class="fa fa-refresh"></i> Vorlage laden</button>
    <button role="button" class="btn btn-danger" onclick="saveKataster(0, '#katasterGlobal')"><i class="fa fa-floppy-o"></i> Vorlage überschreiben</button>
</div> <!-- Ende messkatasterGlobal -->

<div id="truppsGlobal" class="tab-pane fade">
    <table id="messtruppsGlobal" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Abkürzung</th>
                <th>Bezeichnung</th>
                <th>Farbe</th>
                <th></th>
            </tr>
        </thead>
       <tbody>
       </tbody>
    </table>
    <button role="button" class="btn btn-primary" onclick ="addRowMesstrupps(dataTable4,'dataTable4')"><i class="fa fa-plus-square"></i> Neuen Messtrupp hinzufügen</button>
    <button role="button" class="btn btn-primary" onclick="updateMesstrupps(0, dataTable4)"><i class="fa fa-refresh"></i> Vorlage laden</button>
    <button role="button" class="btn btn-danger" onclick="saveMesstrupps(0, '#messtruppsGlobal')"><i class="fa fa-floppy-o"></i> Vorlage überschreiben</button>
</div> <!-- Ende messkatasterGlobal -->