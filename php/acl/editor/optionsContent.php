<div id="editKataster" class="tab-pane fade">
Das Messkataster listet vordefinierte Messpunkte auf, die Benutzer in der Karte einblenden können.<br>
Editoren und Administratoren haben die Möglichkeit, diese Punkte global für alle Benutzer zu verändern sowie persönliche Punkte festzulegen.
    <table id="kataster" class="table table-striped table-bordered" cellspacing="0" width="100%">
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
    <button id="addRow" role="button" class="btn btn-primary" onclick ="addRow()"><i class="fa fa-plus-square"></i> Neuen Messpunkt hinzufügen</button>
    <button id="saveTable" role="button" class="btn btn-primary" onclick="saveKataster(userID)"><i class="fa fa-floppy-o"></i> Speichern (nur für mich)</button>
    <button id="saveTableAdmin" role="button" class="btn btn-danger" onclick="saveKataster(0)"><i class="fa fa-floppy-o"></i> Vorlage überschreiben (für alle Benutzer)</button>
    <button id="loadTableAdmin" role="button" class="btn btn-danger" onclick="updateKataster(0)"><i class="fa fa-refresh"></i> Vorlage laden</button>
</div> <!-- Ende deleteUser -->