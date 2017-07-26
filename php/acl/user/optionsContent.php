<div id="editProfile" class="tab-pane fade in active">
	<form id ="editUSerProfile" action='' method='POST' class='ajax_edit_user form-horizontal' role='form'>
		<div class="form-group">
			<label for="username" class="col-xs-5 control-label">Benutzername</label>
			<div class="col-xs-7">
				<input class="form-control" id="username" type="text" placeholder="Benutzername" name="username" required>
			</div>
		</div>
		<div class="form-group">
			<label for="passwordOld" class="col-xs-5 control-label">Aktuelles Passwort</label>
			<div class="col-xs-7">     	
				<input type="password" id="passwordOld" name="oldPassword" class="form-control" placeholder="Aktuelles Passwort" required>
			</div>
		</div>
		<div class="form-group">
			<label for="password1" class="col-xs-5 control-label">Neues Passwort</label> 
			<div class="col-xs-7">    	
				<input type="password" id="password1" name="password1" class="form-control" placeholder="Neues Passwort">
			</div>
		</div>
		<div class="form-group">
			<label for="password2" class="col-xs-5 control-label">Passwort wiederholen</label>
			<div class="col-xs-7">
				<input type="password" id="password1" name="password2" class="form-control" placeholder="Neues Passwort">
			</div>
		</div>
		<br>
			<button type='submit' class='btn btn-primary'><span class='fa fa-check-square-o'></span> Änderung speichern</button>
	</form>
</div>