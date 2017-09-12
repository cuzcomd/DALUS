function updateMesstrupps(UID, tableName){ //Aktualisiert die Punkte im Messkataster
	tableName.clear(); // Leert die Liste aller verfügbaren Optionen
	var data = [];
	data = $(this).serialize() + "&" + $.param(data);
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "php/options.php",
		data: {"action": "loadMesstrupps", "UID": UID},
		success: function(data) {
			var obj = JSON.parse(data[0]);
			
			$.each(obj, function (key, value) {
				tableName.row.add([value.ID, value.Abkürzung, value.Bezeichnung, value.Farbe,"<div role='button' class='btn btn-default btn-del-row'><span class='fa fa-trash'></span></div>"]).draw();
			});
		
			maxRowIDMesstrupp = Math.max.apply(Math,obj.map(function(o){return o.ID;})); //Liest die größte gespeicherte ID aus

			$('.btn-del-row').click(function(){
			 	tableName.row($(this).parents('tr')).remove().draw();
			});
		},
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	});//Ende Ajax
}//Ende Funktion updateKataster

function saveMesstrupps(UID, tableID){ //Speichert das Messkataster
	var data = [];
	var headers = [];
	$(tableID+'> thead th').not(':last').each(function(index, item) {
    	headers[index] = $(item).text();
	});
	$(tableID+'> tbody tr').has('td').each(function() {
        var arrayItem = {};
        $('td', $(this)).not(':last').each(function(index, item) {
            arrayItem[headers[index]] = $(item).html();
   		});
    	data.push(arrayItem);
	});
	data = JSON.stringify(data);
		
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "php/options.php",
		data: {"action": "saveMesstrupps", "data": data, "UID": UID} ,
		success: function(data) {
			toastr.success('Änderungen gespeichert.');
		},
		error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	});//Ende Ajax 
}//Ende Funktion saveKataster

function addRowMesstrupps(tableName, tableNameString){ //Fügt eine neue Zeile in das Messkataster ein
	maxRowIDMesstrupp += 1;
	tableName.row.add([ maxRowIDMesstrupp, '','','',"<div role='button' class='btn btn-default' onclick='"+tableNameString+".row($(this).parents(\"tr\")).remove().draw();'><span class='fa fa-trash'></span></div>"]).draw();
}