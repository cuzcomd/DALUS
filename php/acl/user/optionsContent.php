<div id="editProfile" class="tab-pane fade in active">
	<form id ="editUSerProfile" action='' method='POST' class='ajax_edit_user col-xs-12 col-sm-4' role='form'>
		<div class="form-group">
			<label for="username" class="control-label">Benutzername</label>
            <input class="form-control" id="username" type="text" placeholder="Benutzername" name="username" required>
		</div>
		<div class="form-group">
			<label for="newPassword" class="control-label">Neues Passwort</label> 
			<input type="password" id="newPassword" name="newPassword" class="form-control" placeholder="Neues Passwort">
		</div>
        <div class="form-group">
            <label for="owmcity" class="control-label">Stadt</label> 
            <input class="form-control" id="owmcity" type="text" placeholder="Stadt" name="owmcity">
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
    <button role="button" class="btn btn-success" onclick="saveKataster('', '#kataster')"><i class="fa fa-floppy-o"></i> Speichern</button>
    <button role="button" class="btn btn-danger" onclick="updateKataster('su', dataTable)"><i class="fa fa-refresh"></i> Globale Vorlage laden</button>
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
    <button role="button" class="btn btn-success" onclick="saveMesstrupps('', '#messtrupps')"><i class="fa fa-floppy-o"></i> Speichern</button>
    <button role="button" class="btn btn-danger" onclick="updateMesstrupps('su', dataTable3)"><i class="fa fa-refresh"></i> Globale Vorlage laden</button>
</div> <!-- Ende editKataster -->