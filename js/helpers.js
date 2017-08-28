function printMap(){
    html2canvas($('#map'), {
    	useCORS: true,
    	onrendered: function (canvas) {
    		var img = canvas.toDataURL("image/png");
    		img = img.replace('data:image/png;base64,', '');
        	var finalImageSrc = 'data:image/png;base64,' + img;
        	window.open(finalImageSrc, 'Screenshot');
        }
	});
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