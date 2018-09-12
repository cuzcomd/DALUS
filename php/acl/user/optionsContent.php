<div id="editProfile" class="tab-pane fade in active">
	<form id ="editUSerProfile" action='' method='POST' class='ajax_edit_user form-horizontal' role='form'>
		<div class="form-group">
			<label for="username" class="col-xs-3 control-label">Benutzername</label>
			<div class="col-xs-4">
				<input class="form-control" id="username" type="text" placeholder="Benutzername" name="username" required>
			</div>
		</div>
		<div class="form-group">
			<label for="passwordOld" class="col-xs-3 control-label">Aktuelles Passwort</label>
			<div class="col-xs-4">     	
				<input type="password" id="passwordOld" name="oldPassword" class="form-control" placeholder="Aktuelles Passwort" required>
			</div>
		</div>
		<div class="form-group">
			<label for="password1" class="col-xs-3 control-label">Neues Passwort</label> 
			<div class="col-xs-4">    	
				<input type="password" id="password1" name="password1" class="form-control" placeholder="Neues Passwort">
			</div>
		</div>
		<div class="form-group">
			<label for="password2" class="col-xs-3 control-label">Passwort wiederholen</label>
			<div class="col-xs-4">
				<input type="password" id="password2" name="password2" class="form-control" placeholder="Neues Passwort">
			</div>
		</div>
		<br>
			<button type='submit' class='btn btn-primary'><span class='fa fa-check-square-o'></span> Änderung speichern</button>
	</form>
</div>

<div id="editKataster" class="tab-pane fade">
Das Messkataster listet vordefinierte Messpunkte auf, die Benutzer in der Karte einblenden können.
    <table id="kataster" class="display" cellspacing="0" width="100%">
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
    <button role="button" class="btn btn-primary" onclick ="addRow(dataTable,'dataTable')"><i class="fa fa-plus-square"></i> Neuen Messpunkt hinzufügen</button>
    <button role="button" class="btn btn-success" onclick="saveKataster(userID, '#kataster')"><i class="fa fa-floppy-o"></i> Speichern</button>
    <button role="button" class="btn btn-danger" onclick="updateKataster(0, dataTable)"><i class="fa fa-refresh"></i> Globale Vorlage laden</button>
</div> <!-- Ende editKataster -->

<div id="editMesstrupps" class="tab-pane fade">
    <table id="messtrupps" class="display" cellspacing="0" width="100%">
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
    <button role="button" class="btn btn-primary" onclick ="addRowMesstrupps(dataTable3,'dataTable3')"><i class="fa fa-plus-square"></i> Neuen Messtrupp hinzufügen</button>
    <button role="button" class="btn btn-success" onclick="saveMesstrupps(userID, '#messtrupps')"><i class="fa fa-floppy-o"></i> Speichern</button>
    <button role="button" class="btn btn-danger" onclick="updateMesstrupps(0, dataTable3)"><i class="fa fa-refresh"></i> Globale Vorlage laden</button>
</div> <!-- Ende editKataster -->