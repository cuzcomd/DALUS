<?php require('php/session.php');
  $googleAPIkey = $_SESSION['googleAPIkey'];
?>
<!DOCTYPE html>
<html lang="de">
  <head>
  <meta charset="UTF-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Webanwendung zur Ausbreitungsabschätzung von Schadstoffen in der Atmosphäre für Feuerwehren.">
  <title>DALUS</title>
  <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
  <link rel="manifest" href="images/favicon/manifest.json">
  <link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#5bbad5">
  <link rel="shortcut icon" href="./images/favicon/favicon.ico">
  <meta name="msapplication-config" content="images/favicon/browserconfig.xml">
  <!-- Main CSS-->
  <link rel="stylesheet" href="css/main.css"> <!-- Hauptstylesheet des vali-admin Templates (beinhaltet bootstrap.css) -->
  <link rel="stylesheet" href="css/bootstrap.css"> <!-- Hauptstylesheet des vali-admin Templates (beinhaltet bootstrap.css) -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/alertify.min.css">
  <link rel="stylesheet" href="css/alertify.bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="css/datatables.min.css"/>
  <link rel="stylesheet" href="css/fontawesome.min.css">
  </head>
  <?php
    require "php/modals.php"; //lädt die Modals
    require "php/body.php"; //lädt die DOM-Struktur der Seite
  ?>
  <!-- Essential javascripts for application to work-->
  <script src="js/plugins/jquery.min.js"></script>
  <script src="js/plugins/bootstrap.bundle.min.js"></script>
  <script src="js/main.js"></script>
  <!-- The javascript plugin to display page loading on top-->
  <script src="js/plugins/pace.min.js"></script>
  <!-- Page specific javascripts-->
  <script src="js/plugins/datatables.min.js"></script>
  <script src="js/plugins/dataTables.cellEdit.js"></script>
  <script src="js/plugins/alertify.min.js"></script> <!-- Script zur Anzeige von Popupbenachrichtigungen -->
  <script src="js/OSM.js"></script>
  <script src="js/users.js"></script>
  <script src="js/objects.js"></script>
  <script src="js/messkataster.js"></script>
  <script src="js/messtrupps.js"></script>
  <script src="js/init.js"></script>
  <script src="js/plugins/bootstrap-colorpicker.min.js"></script>
  <script>
    cityName=<?php echo json_encode($_SESSION['city']) ?>;
    OWMAPIkey=<?php echo json_encode($_SESSION['OWMAPIkey']) ?>;
    benutzer = []; //Initialisierung
    optionen = []; //Initialisierung
    arrayOfUsers = []; // Initialisierung
    prj_id = 0; //Initialisierung
    maxRowID = 0; //Initialisierung
    messpunktNummer = 1; //Initialisierung
    objectNummer = 1; // Initialisierung
    metCounter = 1; // Initialisierung
    activeObject = null; // Initialisierung
    activeProjectName = "Unbekanntes Projekt";  //Initialisierung
    ursprungKoordinaten = ""; //Initialisierung
    loadUser(); // Daten des angemeldeten Benutzers laden
    updateProjects(); //Verfügbare Projekte aktualisieren
    updateSharedProjects(); //Verfügbare geteilte Projekte aktualisieren
    isSharedWith(); //Aktualisieren, mit wem das Projekt geteilt wird
    updateAllUsers() //Aktulisiert alle verfügbaren Benutzer
    objectArray = []; //Array für temporär erzeugte Objekte
    deleteArray = []; // Array für temporär gelöschte Objekte
    markerArray =[]; // Array für temporär erzeugte Marker
    messtruppArray = []; // Array für Messtrupps
    var selectedShape; //Initialisierung für aktuell markiertes Geometrieobjekt

    function initMap() {
    loadOSMLayer(); // OSM Kartenbilder laden
    infoWindow = new google.maps.InfoWindow({}); //Globale Initialisierung des Infowindows
    startDrawingManager(map); //Google DrawingManager laden
    dataTables(); // Lädt die Optionen der datatables
    updateKataster('',dataTable); // Lädt die Messpunkte
    updateMesstrupps('',dataTable3); // Lädt die Messtrupps
    alertify.set('notifier','position', 'bottom-center'); // Benachrichtigungen unten zentriert einblenden

    document.getElementById('calcMET').addEventListener('click', function() { // Beim Klick auf "Zeichnen" MET-Modell erzeugen
      generateMET(map);
    });
    
     document.getElementById('switchMesskataster').addEventListener('click', function() {// Messkataster ein-/ausblenden
       loadFixpoints($(this));
     });

     document.getElementById('saveProject').addEventListener('click', function() { // Beim Klick auf "Speichern", aktuelle Änderungen speichern
       saveProjectStatus();  
     });

     document.getElementById('deleteProject').addEventListener('click', function() { // Beim Klick auf "Löschen", aktuelles Projekt löschen
       deleteProject();  
     });

    document.getElementById('startSearch').addEventListener('click', function(){
      var adresse = $('#pac-input').val();
      if (adresse){
        new google.maps.Geocoder().geocode( { 'address': adresse}, function(results, status) {
          if (status == 'OK') {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: map,
              position: results[0].geometry.location
            });
          } 
          else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
        return;
      }
    }); //Ende eventlistener

    var input = /** @type {!HTMLInputElement} */(document.getElementById('pac-input'));
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
    autocomplete.addListener('place_changed', function() {
      var place = autocomplete.getPlace();
      if (!place.geometry) {
        new google.maps.Geocoder().geocode( { 'address': place.name}, function(results, status) {
          if (status == 'OK') {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: map,
              position: results[0].geometry.location
            });
          } 
          else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      return;
      }

      var marker = new google.maps.Marker({
        map: map,
        position: place.geometry.location
      });
      // If the place has a geometry, then present it on a map.
      if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
      } 
      else {
        map.setCenter(place.geometry.location);
        map.setZoom(17);  // Why 17? Because it looks good.
      }

      var address = '';
      if (place.address_components) {
        address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
        ].join(' ');
      }
    }); // Ende addlistener
    }
  </script>
  <script src="js/module.js"></script>
  <script src="js/googleDrawingManager.js"></script>
  <textarea id="kmlString"></textarea>  <!-- unsichbares Textfeld  als Zwischenspeicher für kml-Export-->
  <?php
  echo "<script src = 'https://maps.googleapis.com/maps/api/js?key=".$googleAPIkey."&libraries=geometry,drawing,places&callback=initMap' async defer></script>" 
  ?>
  <script src = "js/plugins/html2canvas.min.js" defer></script>  <!-- Script zum erzuegen eines Screenshots der google-Karte  -->
  <script src = "js/usng.min.js" defer></script> <!-- Script für Umwandlung von Geokoordinaten in UTM-Ref Koordinaten -->
  <script src = "js/MET.js" defer></script> <!-- Adresse des MET-Modells durch Eingabemaske oder manuelle Festlegung bestimmen -->
  <script src = "js/project.js" defer></script> <!--  Script mit Funktionen zur Projektverwaltung-->
  <script src = "js/helpers.js" defer></script> <!-- Script mit Hilfsfunktionen  -->
  <script src = "js/xmlwriter.js" defer></script> <!-- Script zum erzeugen einer kml-Datei -->
  <script src = "js/exportKml.js" defer></script> <!-- Script zum Export der Geometriedaten als kml-Datei -->
  <script src = "js/geocoder.js" defer></script> <!-- Geocoding von Messpunkten -->
  <script src = "js/openweathermap.js" defer></script> <!-- Geocoding von Messpunkten -->
  </body>
</html>