<div class="modal fade" id="modal_license" tabindex="-1" role="dialog" aria-labelledby="Lizenzinformationen" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title mx-auto"><img src="images/dalus_logo.svg" width="250px"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
					<div class="panel panel-default">
					<div class="panel-heading text-center">DALUS<br>Copyright <i class="fa fa-copyright" aria-hidden="true"></i> 2018  Marco Trott</div>
					<div class="panel-body">
						<br/>
						Das Projekt "Digitale Ausbreitungsabschätzung Luftgetragener Schadstoffe" (DALUS) dient zur Darstellung von Ausbreitungsabschätzungen luftgetragener Schadstoffemissionen und der Dokumentation von Messeinsätzen im Rahmen der operativen Gefahrenwehr.
						<br/><hr /><br/>

					   	This program is free software: you can redistribute it and/or modify
					    it under the terms of the GNU General Public License as published by
					    the Free Software Foundation, either version 3 of the License, or
					    (at your option) any later version.<br><br>

					    This program is distributed in the hope that it will be useful,
					    but WITHOUT ANY WARRANTY; without even the implied warranty of
					    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
					    GNU General Public License for more details.<br><br>

					    You should have received a copy of the GNU General Public License
					    along with this program.  If not, see <a href="https://www.gnu.org/licenses/" target="_blank" rel="noopener">https://www.gnu.org/licenses/</a>.<br/><br/>
					</div>
				</div>
				<div class="container-fluid">
					<div class="row">
						<div class="col"><a href="CHANGELOG.md" target="_blank" rel="noopener">Version: 2.0.0</a></div>
						<div class="col"><a href="https://github.com/cuzcomd/DALUS" target="_blank" rel="noopener"><i class="fa fa-github" aria-hidden="true"></i> GitHub Repository</a></div>
						<div class="col"><a href="mailto:kontakt@cuzcomd.de">kontakt@trott-md.de</a></div>
					</div>
				</div>
			</div>
		</div><!-- Ende modal-content -->
	</div><!-- Ende modal-dialog -->
</div> <!-- Ende modal fade -->
<div class="modal fade" id="modalMET" tabindex="-1" role="dialog" aria-labelledby="MET Ausbreitungsmodell" aria-hidden="true">
		<div class="modalMET modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">MET Ausbreitungsmodell</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div id="geocoder container">
						<form id="input-form-met" class="form-horizontal" role="form">
							<div class="form-group row" data-toggle="tooltip" title="Freisetzungsort">
								<label class="control-label col-12 col-sm-3" for="addresse">Scha&shy;dens&shy;ort</label>
								<div class="col-8 col-sm-6">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fa fa-home"></i></span>
										</div>
										<input id="addresse" class="form-control" type="textbox" value="Alt Diesdorf 4, Magdeburg">
									</div>
								</div>
								<div class="col-4 col-sm-2 geocoderButtons">
									<button type="button" class="btn btn-default" id="geocode" data-toggle="tooltip" title="MET Freisetzungsort manuell festlegen" onclick="setCoord()"><i class="fa fa-crosshairs"></i> Wählen</button>
								</div>
							</div>
	
							<div class="form-group row" data-toggle="tooltip" title="Ausbreitungswinkel">
								<label class="control-label col-12 col-sm-3" for="winkel">Aus&shy;brei&shy;tungs&shy;winkel</label>
								<div class="col-8 col-sm-4">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fa fa-arrows-h"></i></span>
										</div>
										<select id="winkel" name="winkel" class="form-control">
											<option value="45" label="45&deg;">45&deg;</option>
											<option value="60" label="60&deg;" selected>60&deg;</option>
											<option value="90" label="90&deg;">90&deg;</option>
											<option value="360" label="360&deg;">360&deg;</option>
										</select>
										<div class="input-group-append">
											<span id="ausbreitungsklasse" class="input-group-text">&nbsp;</span>
										</div>
									</div>
								</div>
								<div class="col-4 col-sm-2 offset-sm-2">
									<button type="button" class="btn btn-default" id="setWinkel" data-toggle="tooltip" title="Ausbreitungswinkel bestimmen" onclick="$('#modal_winkel').modal('show');"><i class="fa fa-calculator"></i> Ermitteln</button>
								</div>
							</div>
							<div class="form-group row" data-toggle="tooltip" title="Windrichtung">
								<label class="control-label col-12 col-sm-3" for="windrichtung">Wind&shy;richtung</label>
								<div class="col-8 col-sm-4">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fa fa-location-arrow"></i></span>
										</div>
										<input id="windrichtung" type="number" value="280" class="form-control" onchange="document.getElementById('arrow').style.transform = 'rotate('+(this.value-90)+'deg)';">
										<div class="input-group-append">
											<span class="input-group-text">&deg;</span>
										</div>
									</div>
								</div>
							</div>
	
							<div class="form-group row" data-toggle="tooltip" title="Gefährdung für Personen im Gebäude">
								<label class="control-label col-12 col-sm-3" for="distanz 1">Gefährdung für Personen im Gebäude</label>
								<div class="col-8 col-sm-4">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fa fa-exclamation"></i> <i class="fa fa-home"></i></span>
										</div>
										<input id="distanz1" type="number" value="600" class="form-control">
										<div class="input-group-append">
											<span class="input-group-text">m</span>
										</div>
									</div>
								</div>
							</div>
								
							<div class="form-group row" data-toggle="tooltip" title="Gefährdung für Personen im Freien">
								<label class="control-label col-12 col-sm-3" for="distanz 1">Gefährdung für Personen im Freien</label>
								<div class="col-8 col-sm-4">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fa fa-exclamation"></i> <i class="fa fa-street-view"></i></span>
										</div>
										<input id="distanz2" type="number" value="1300"  class="form-control">
										<div class="input-group-append">
											<span class="input-group-text">m</span>
										</div>
									</div>
								</div>
							</div>
						</form>
						<br>
						<div class="geocoderButtons text-center">
							<button type="button" class="btn btn-primary" id="calcMET" data-toggle="tooltip" title="MET-Freisetzungsort aus Adressfeld lesen" ><i class="fa fa-pencil-square-o"></i> Zeichnen</button>
						</div>
					</div> <!-- Ende Geocoder -->
				</div><!-- Ende modal-body -->
			</div><!-- Ende modal-content -->
		</div><!-- Ende modal-dialog -->
	</div><!-- Ende modalMET -->
<div class="modal fade" id="modal_winkel" tabindex="-1" role="dialog" aria-labelledby="Winkel bestimmen">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title text-center">Ausbreitungsklasse bestimmen</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form id="form_winkelrechner" class="form-horizontal">
					<div class="form-group">
						<label for="nebel" class="col-4 form-control-label">Nebel</label>
						<div class="col-8">
							<select id="nebel" name="nebel" class="form-control">
								<option value="true" label="Ja">Ja</option>
								<option value="false" label="Nein">Nein</option>
							</select>
						</div>
					</div>

					<div class="form-group">	
						<label for="windgeschwindigkeit" class="col-4 form-control-label">Wind&shy;ge&shy;schwin&shy;dig&shy;keit</label>
						<div class="col-8">
							<select id="windgeschwindigkeit" name="windgeschwindigkeit" class="form-control">
								<option value="high" label="gr&ouml;&szlig;er 5 m/s (18 km/h)">gr&ouml;&szlig;er 5 m/s (18 km/h)</option>
								<option value="medium" label="zwischen 1 m/s (4 km/h) und 5 m/s (18 km/h)">zwischen 1 m/s (4 km/h) und 5 m/s (18 km/h)</option>
								<option value="low" label="kleiner 1 m/s (4 km/h)">kleiner 1 m/s (4 km/h)</option>
							</select>
						</div>
					</div>

					<div class="form-group">	
						<label for="himmel" class="col-4 form-control-label">Bedeckter Himmel</label>
						<div class="col-8">
							<select id="himmel" name="himmel" class="form-control">
								<option value="true" label="mehr als 50 %">mehr als 50 %</option>
								<option value="false" label="weniger als 50 %">weniger als 50 %</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="tageszeit" class="col-4 form-control-label">Tageszeit</label>
						<div class="col-8">
							<select id="tageszeit" name="tageszeit" class="form-control">
								<option value="day" label="Tag">Tag</option>
								<option value="night" label="Nacht">Nacht</option>
							</select>
						</div>
					</div>
				
					<div class="form-group">
						<label for="monat" class="col-4 form-control-label">Monat</label>
						<div class="col-8">
							<select id="monat" name="monat" class="form-control">
								<option value="om" label="Oktober - M&auml;rz">Oktober - M&auml;rz</option>
								<option value="as" label="April - September">April - September</option>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="brand" class="col-4 form-control-label">Brand</label>
						<div class="col-8">
							<select id="brand" name="brand" class="form-control">
								<option value="true" label="Ja">Ja</option>
								<option value="false" label="Nein">Nein</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="intensiverbrand" class="col-4 form-control-label">Intensiver Brand</label>
						<div class="col-8" id="intensiverbrand">
							<label class="radio-inline"><input type="radio" id="intens_brand_ja" name="intens_brand" value="ja">Ja</label>
							<label class="radio-inline"><input type="radio" id="intens_brand_nein" name="intens_brand" value="nein" checked>Nein</label>
						</div>
					</div>
					<div class="form-group">
						<label for="tiefkalt" class="col-4 form-control-label">Tiefkaltes Gas</label>
						<div class="col-8" id ="tiefkalt">
							<label class="radio-inline"><input type="radio" id="tiefkalt_ja" name="tiefkalt" value="ja">Ja</label>
							<label class="radio-inline"><input type="radio" id="tiefkalt_nein" name="tiefkalt" value="nein" checked>Nein</label>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button type="button" class="btn btn-default" onclick="getMETweather();">Wetterdaten laden</button>
					<button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Schließen" onclick="computeAngle();">Übernehmen</button>
				</div>
			</div>
		</div><!-- Ende modal-content -->
	</div><!-- Ende modal-dialog -->
</div> <!-- Ende modal fade -->

<div class="modal fade" id="modal_options" tabindex="-1" role="dialog" aria-labelledby="Optionen">
	<div class="modalOptions modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Optionen </h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body container">
				<div id="adminWrapper" class="row">
					<div id="adminPanel" class="col-3">
						<div class="nav nav-pills flex-column" role="tablist" aria-orientation="vertical">
							<?php
								include_once('php/acl/user/optionsPanel.php'); //Optionen für ACL "user" laden
								if ($accessLevel == 'admin')
								{
									include_once('php/acl/admin/optionsPanel.php'); //Optionen für ACL "admin" laden
								}
							?>
						</div>
					</div> <!-- Ende adminPanel -->
					<div id="adminContent" class="col-9">
						<div class="tab-content">
							<?php
								include_once('php/acl/user/optionsContent.php'); //Optionen für ACL "user" laden
								if ($accessLevel == 'admin')
								{
									include_once('php/acl/admin/optionsContent.php'); //Optionen für ACL "admin" laden
								}
							?>
						</div> <!-- Ende tab-content -->
					</div> <!-- Ende adminContent -->
				</div> <!-- Ende adminWrapper -->
			</div><!-- Ende modal-body -->
		</div><!-- Ende modal-content -->
	</div><!-- Ende modal-dialog -->
</div><!-- Ende modalOptions -->

<div class="modal fade" id="modal_new_project" tabindex="-1" role="dialog" aria-labelledby="Neues Projekt">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Neues Projekt erstellen</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form action='' method='POST' class='ajax_create_project' role='form'>
					<div class="form-group">
						<label for="projekt_titel_new" class="col-form-label">Projekttitel</label>
						<input class="form-control" type="text" placeholder="Projekttitel" id="projekt_titel_new" name="projekttitel" required>
					</div>
					<div class="form-group">
						<label for="newProjektShared" class="col-form-label">Freigeben für</label>
						<select multiple class="form-control listOfAllUsersExceptMe" type="text" id="newProjektShared" name="shared[]" size="10">
						</select>
					</div>
					<div class="text-center">
						<button type='submit' class='btn btn-primary' onclick="$('#modal_new_project').modal('hide')"><span class='fa fa-check-square-o'></span> Projekt anlegen</button>
					</div>
				</form>
			</div><!-- Ende modal-body -->
		</div><!-- Ende modal-content -->
	</div><!-- Ende modal-dialog -->
</div><!-- Ende Modal_new_project -->

<div class="modal fade" id="modal_open_project" tabindex="-1" role="dialog" aria-labelledby="Projekt öffnen">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Projekt öffnen</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<h5>Meine Projekte </h5>
				<form action='' class="ajax_load_project" method='POST' role='form'>
					<div class="form-group">
						<select class="form-control" type="text" id="projectOpen" name="project_open"  size="10">
						</select>
					</div>
					<div class="text-center">
						<button type='submit' class='btn btn-primary' onclick="$('#modal_open_project').modal('hide')"><span class='fa fa-check-square-o'></span> Projekt öffnen</button>
					</div>
				</form>
				<h5>Für mich freigegebene Projekte</h5>
				<form action='' class="ajax_load_project" method='POST' role='form'>
					<div class="form-group">
						<select class="form-control" id="projectOpenShared" name="project_open"  size="10">
						</select>
					</div>
					<div class="text-center">
						<button type='submit' class='btn btn-primary' onclick="$('#modal_open_project').modal('hide')"><span class='fa fa-check-square-o'></span> Projekt öffnen</button>
					</div>
				</form>
			</div><!-- Ende modal-body -->
		</div><!-- Ende modal-content -->
	</div><!-- Ende modal-dialog -->
</div><!-- Ende Modal_open_project -->

<div class="modal fade" id="modal_edit_project" tabindex="-1" role="dialog" aria-labelledby="Projekt ändern">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Projekt ändern</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form action='' method='POST' class='ajax_edit_project' role='form'>
					<input type='hidden' class='activeProjectID' name='current_project_id' value='0'>
					<div class="form-group">
						<label for="projekt_titel" class="col-form-label">Projekttitel</label>
						<input class="form-control activeProjectName" id="projekt_titel" type="text" placeholder="Projekttitel" name="projekttitel" value="" required>
					</div>
					<div class="form-group">
						<label for="projektShared" class="col-form-label">Freigeben für</label>
						<select multiple class="form-control" type="text" id="projektShared" name="shared[]"  size="10">
							<!-- Hier erscheinen die Benutzernamen, für die das Projekt freigegeben wurde -->
						</select>
					</div>
					<div class="text-center">
						<button type='submit' class='btn btn-primary' onclick="$('#modal_edit_project').modal('hide')"><span class='fa fa-check-square-o'></span> Änderung Speichern</button>
					</div>
				</form>
			</div><!-- Ende modal-body -->
		</div><!-- Ende modal-content -->
	</div><!-- Ende modal-dialog -->
</div><!-- Ende Modal_edit_project -->