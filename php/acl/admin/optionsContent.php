<div id="user-administration" class="tab-pane fade container">
	<div class="row">
		<div class="col-md-6">
			<div class="tile">
	            <h3 class="tile-title">Neuen Benutzer anlegen</h3>
	            <div class="tile-body">
				    <form id ="addNewUser" action='' method='POST' class='ajax_create_user form-horizontal' role='form'>
				    <div class="col-12">
						<div class="form-group">
							<label for="newBenutzername" class="col-4 control-label">Benutzername:</label>
							<div class="col-7">
						    	<input type="text" class="form-control" id="newBenutzername" name="benutzername" maxlength="20" required>
						    </div>
						</div>
						<div class="form-group">
						    <label for="newVorname" class="col-4 control-label">Vorname:</label>
						    <div class="col-7">
						    	<input type="test" class="form-control" id="newVorname" name="vorname" maxlength="20" required>
						    </div>
						</div>
						<div class="form-group">
							<label for="newNachname" class="col-4 control-label">Nachname:</label>
							<div class="col-7">
						    	<input type="text" class="form-control" id="newNachname" name="nachname" maxlength="20" required>
						    </div>
						</div>
						<div class="form-group">
						    <label for="newPasswort" class="col-4 control-label">Passwort:</label>
						    <div class="col-7">
						    	<input type="password" class="form-control" id="newPasswort" name="passwort" required>
						    </div>
						</div>
					</div>
					<div class="col-12">
						<h5><b>Berechtigung</b></h5>
						<div class="form-group">
		                  <div class="form-check">
		                    <label class="form-check-label">
		                      <input class="form-check-input" type="radio" name="level" id="levelAdmin" value="admin" required>Administrator
		                    </label>
		                  </div>
		                  <div class="form-check">
		                    <label class="form-check-label">
		                      <input class="form-check-input" type="radio" name="level" id="levelUser" value="user" checked required>Benutzer
		                    </label>
		                  </div>
		                </div>
				    </div>
				    </form>
			     </div>
	            <div class="tile-footer">
	              <button class="btn btn-primary" type="submit" form="addNewUser"><i class="fa fa-user-plus"></i>Benutzer anlegen</button>
	            </div>
	          </div>
      	</div>
          <div class="col-md-6">
			<div class="tile">
	            <h3 class="tile-title">Benutzer löschen</h3>
	            <div class="tile-body">
				    <form id ="deleteUser" action='' method='POST' class='ajax_delete_user form-horizontal' role='form'>
				 		<select multiple class="form-control listOfAllUsers" type="text" id="deleteUsers" name="users[]" size="10">
						<!-- Liste aller Nutzernamen -->
						</select>
					</form>
				</div>
				<div class="tile-footer">
	              <button class="btn btn-primary" type="submit" form="deleteUser"><i class="fa fa-user-times"></i>Benutzer löschen</button>
	            </div>
	        </div>
	    </div>
	</div>
</div> <!-- Ende user-administration -->

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
    <button role="button" class="btn btn-default" onclick ="addRow(dataTable2,'dataTable2')"><i class="fa fa-plus-square"></i> Neuen Messpunkt hinzufügen</button>
    <button role="button" class="btn btn-primary" onclick="updateKataster('su', dataTable2)"><i class="fa fa-refresh"></i> Systemeinstellungen laden</button>
    <button role="button" class="btn btn-danger" onclick="saveKataster('su', '#katasterGlobal')"><i class="fa fa-floppy-o"></i> Systemeinstellungen überschreiben</button>
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
    <button role="button" class="btn btn-default" onclick ="addRowMesstrupps(dataTable4,'dataTable4',4)"><i class="fa fa-plus-square"></i> Neuen Messtrupp hinzufügen</button>
    <button role="button" class="btn btn-primary" onclick="updateMesstrupps('su', dataTable4,4)"><i class="fa fa-refresh"></i> Systemeinstellungen laden</button>
    <button role="button" class="btn btn-danger" onclick="saveMesstrupps('su', '#messtruppsGlobal',4)"><i class="fa fa-floppy-o"></i> Systemeinstellungen überschreiben</button>
</div> <!-- Ende messkatasterGlobal -->