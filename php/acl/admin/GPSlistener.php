document.getElementById('switchGPS').addEventListener('click', function() {// GPS-Tracking ein-/ausblenden
				$('#switchGPS').find('i').toggleClass('fa-toggle-off fa-toggle-on');
				$('#module1').toggle();

				if($('#switchGPS').attr('data-click-state') == 1) { 
					$('#switchGPS').attr('data-click-state', 0) // Wenn Schalter aktiviert ist, ihn wieder deaktivieren
					$('#gpsLoadedCars').children().remove();
				}
				else {
					$('#switchGPS').attr('data-click-state', 1) // Wenn Schalter aktiviert ist, ihn wieder deaktivieren
					$.ajax({
						type: "POST",
						dataType: "json",
						url: "php/options.php",
						data: {"action": "loadMesstrupps"},
						success: function(data) {
							var obj = JSON.parse(data[0]);
							$.each(obj, function (key, value) {
								$('<div class="row"><div class="checkbox"><label class="col-xs-10"><input type="checkbox" name="car" onchange="loadGPS(this,\''+value.Abkürzung+'\',\''+value.Farbe+'\');">'+value.Bezeichnung+' </label><div style="background:'+value.Farbe+';" class="col-xs-1">&nbsp;</div></div></div>').appendTo('#gpsLoadedCars');
							});
						},					
						error: function(xhr, desc, err) {
							console.log(xhr);
							console.log("Details: " + desc + "\nError:" + err);
						} //ende error
					}); //Ende Ajax
				} //Ende else
			}); // Ende eventlistener