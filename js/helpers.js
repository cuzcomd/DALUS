function printMap(){

	html2canvas(document.querySelector("#map"),{useCORS:true}).then(canvas => {
		var img = canvas.toDataURL('application/stream');
    	var win = window.open();
    	win.document.open();
    	win.document.write('<style>body{margin:0;}</style>');
    	win.document.write('<iframe src="' + img + '" width= "100%" height="100%" frameborder="0" style="border:0; margin:0;" allowfullscreen></iframe>');
    	win.document.close();
    }); //Ende Funktion html2canvas
}//Ende Funktion printMap

function clearMap(){
	for (var i = 0; i < objectArray.length; i++ ) {
		objectArray[i].setMap(null);
		if (objectArray[i].poly1 !== undefined && objectArray[i].poly2 !== undefined){ // Überprüft, ob es sich um einen MET-Marker handelt (dieser besitzt zwei Ausbreitungspolygone)
			objectArray[i].poly1.setMap(null);// Löscht das zugehörige Ausbreitungspolygon von der Karte
			objectArray[i].poly2.setMap(null);// Löscht das zugehörige Ausbreitungspolygon von der Karte
			objectArray[i].centerLine.setMap(null);// Löscht das zugehörige Ausbreitungspolygon von der Karte
		}//Ende if-Statement
	}//Ende for-Schleife
	objectArray = [];
	deleteArray = [];
} //Ende Funktion clearMap()

function toggleNav(modalID){ //Schließt das off-canvas Menü und blendet ein Modal ein
	if ($(window).width() < 992) //Überprüft, ob Fenster kleiner als 992px ist (Danach ist kein off-canvas Menü vorhanden)
	{
		$('#myNavmenu').offcanvas('toggle');
		$(modalID).modal('show');
	}
	else
	{
		$(modalID).modal('show');
	}
}