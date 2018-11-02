<?php require('php/session.php'); ?>
<!DOCTYPE html>
<html lang="de">
  <head>
    meta charset="UTF-8"/>
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
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="css/bootstrap-editable.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/fh-3.1.4/r-2.2.2/sc-1.5.0/sl-1.2.6/datatables.min.css">
  <link rel="stylesheet" href="css/toastr.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <?php
    require "php/modals.php"
  ?>
  <body class="app sidebar-mini rtl">
    <!-- Navbar-->
    <header class="app-header"><a class="app-header__logo"><img src="images/dalus_logo_header.png" alt="DALUS Logo"></a>
      <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
      <!-- Navbar Right Menu-->
      <ul class="app-nav">
        <li class="setHand" data-toggle="tooltip" data-placement="bottom" title="Auswahl" role="button"><a class="app-nav__item" href="javascript:;"><i class="fa fa-mouse-pointer"></i></a></li>
        <li class="setMarkWhite" data-toggle="tooltip" data-placement="bottom" title="Messpunkt" role="button"><a class="app-nav__item" href="javascript:;"><i class="fa fa-flag-o"></i></a></li>
        <li class="setComment" data-toggle="tooltip" data-placement="bottom" title="Kommentar" role="button"><a class="app-nav__item" href="javascript:;"><i class="fa fa-commenting-o"></i></a></li>
        <li class="dropdown"><a class="app-nav__item" data-toggle="dropdown" href="#" title="WerkzeugeToggle"><i class="fa fa-pencil"></i></a>
          <ul class="dropdown-menu dropdown-menu-right">
            <li class="app-notification__title">Zeichenwerkzeuge</li>
            <div class="app-notification__content">
              <li class="setCirc" data-toggle="tooltip" data-placement="bottom" title="Kreis zeichnen" role="button"><a class="dropdown-item" href="javascript:;"><i class="fa fa-circle-thin"></i> Kreis</a></li>
              <li class="setPoly" data-toggle="tooltip" data-placement="bottom" title="Polygon zeichnen" role="button"><a class="dropdown-item" href="javascript:;"> <i class="fa fa-bookmark-o"></i> Polygon</a></li>
              <li class="setPath" data-toggle="tooltip" data-placement="bottom" title="Pfad zeichnen" role="button"><a class="dropdown-item" href="javascript:;"><i class="fa fa-pencil"></i> Pfad</a></li>
          </ul>
        </li>
        <li class="deleteActiveObject" data-toggle="tooltip" data-placement="bottom" title="Objekt löschen" role="button"><a class="app-nav__item" href="javascript:;"><i class="fa fa-trash"></i></a></li>
        <li id = "switchMesskataster" data-click-state="0" role="button"><a class="app-nav__item" href="javascript:;"><i class="fa fa-thumb-tack icon-inactive" aria-hidden="true"></i></a></li>
        <li class="app-search">
          <input id="pac-input" class="app-search__input" type="search" placeholder="Ort suche ...">
          <button class="app-search__button" id="startSearch"><i class="fa fa-search"></i></button>
        </li>
        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Profiloptionen"><i class="fa fa-user fa-lg"></i></a>
          <ul class="dropdown-menu settings-menu dropdown-menu-right">
            <li><a class="dropdown-item" data-toggle="modal"  href='#modal_options'"><i class="fa fa-cog fa-lg"></i> Einstellungen</a></li>
            <li><a class="dropdown-item" onclick="printMap()" href="javascript:;"><i class="fa fa-print fa-lg"></i> Karte drucken</a></li>
            <li><a class="dropdown-item" data-toggle="modal" href='#modal_license' ><i class="fa fa-info-circle fa-lg"></i> Über DALUS</a></li>
            <li><a class="dropdown-item" href="php/logout"><i class="fa fa-sign-out fa-lg"></i> Abmelden</a></li>
          </ul>
        </li>
      </ul>
    </header>
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><!-- <img class="app-sidebar__user-avatar" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="Benutzerbild"> -->
        <div>
          <span class="fa fa-user-circle" aria-hidden="true">&nbsp;</span>
          <span class="app-sidebar__user-name">Kein Benutzer aktiv</span><br/>
          <span class="fa fa-folder-open" aria-hidden="true">&nbsp;</span>
          <span class="app-sidebar__project-information">Kein Projekt geöffnet</span>
        </div>
      </div>
      <div class="container app-sidebar__weather"><!-- <img class="app-sidebar__user-avatar" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="Benutzerbild"> -->
        <div class="row">
          <div class="col weathercity">Wetterdaten werden geladen ...</div>
        </div>
        <div class="row">
          <div class="temp col"></div>
          <div class="wind-speed col"></div>
           <div class="w-100"></div>
          <div class="wind-direction col"></div>
          <div class="clouds col"></div>
        </div>
      </div> <!-- Ende Wetterinformationen -->
      </div>
      <ul class="app-menu">
        <li class="treeview is-expanded"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Projekt</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li id="newProject"><a class="treeview-item" data-toggle="modal" href="#modal_new_project"><i class="icon fa fa-edit"></i> Neues Projekt</a></li>
            <li id="openProject"><a class="treeview-item" data-toggle="modal" href="#modal_open_project"><i class="icon fa fa-folder-open-o"></i> Projekt öffnen</a></li>
            <li id="editProject"><a class="treeview-item" data-toggle="modal" href="#modal_edit_project"><i class="icon fa fa-edit"></i> Projekt ändern</a></li>
            <li id="saveProject"><a class="treeview-item" href="javascript:;"><i class="icon fa fa-floppy-o"></i> Projekt speichern</a></li>
            <li id="deleteProject"><a class="treeview-item" href="javascript:;"><i class="icon fa fa-trash-o"></i> Projekt löschen</a></li>
            <li id="exportKML" onclick="toKML()"><a class="treeview-item" id="download-link" href="data:;base64," download><i class="icon fa fa-floppy-o"></i> kml-Datei exportieren</a></li>
          </ul>
        </li>
        <li id ="switch_winkel"><a class="app-menu__item" data-toggle="modal" href="#modalMET"><i class="app-menu__icon fa fa-location-arrow"></i><span class="app-menu__label">MET Modell</span></a></li>
      </ul>
    </aside>
    <main class="app-content" id ="map">    
    </main>
    <div id="modul-Kompass" class="modul-Kompass"><img src="images/arrow.png" alt="Windrose" id="arrow"/></div>
    <!-- Essential javascripts for application to work-->
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="js/plugins/pace.min.js"></script>
    <!-- Page specific javascripts-->
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/fh-3.1.4/r-2.2.2/sc-1.5.0/sl-1.2.6/datatables.min.js"></script>
    <script src="js/dataTables.cellEdit.js"></script>
    <script src="js/OSM.js"></script>
    <script src="js/users.js"></script>
    <script src="js/objects.js"></script>
    <script src="js/messkataster.js"></script>
    <script src="js/messtrupps.js"></script>
    <script src="js/init.js"></script>
    <script src="js/bootstrap-colorpicker.min.js"></script>
  <script>
    OWMAPIkey = "";
  GoogleAPIkey = "";
  cityName = "Magdeburg";
  benutzer = []; //Initialisierung
  optionen = []; //Initialisierung
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
    document.getElementById('calcMET').addEventListener('click', function() { // Beim Klick auf "Zeichnen" MET-Modell erzeugen
      generateMET(map);
    });
    
     document.getElementById('switchMesskataster').addEventListener('click', function() {// Messkataster ein-/ausblenden
       loadFixpoints($(this));
     });

    // <?php
    //   if ($accessLevel == 'admin')
    //   {
    //     include_once('php/acl/admin/GPSlistener.php'); //Listener für GPS-Logging laden
    //   }
    // ?>

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
  <script src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDY_NrEuhiUpKDhL7TbzNd-Vmvl266lzaM&libraries=geometry,drawing,places&callback=initMap" async defer></script>
  <script src = "js/bootstrap-editable.min.js"></script>  <!-- Script mit Funktionen zur direkten Bearbeitung des Inhalts von DOM-Elementen  -->
  <script src = "js/html2canvas.min.js" defer></script>  <!-- Script zum erzuegen eines Screenshots der google-Karte  -->
  <script src = "js/usng.min.js" defer></script> <!-- Script für Umwandlung von Geokoordinaten in UTM-Ref Koordinaten -->
  <script src = "js/MET.js" defer></script> <!-- Adresse des MET-Modells durch Eingabemaske oder manuelle Festlegung bestimmen -->
  <script src = "js/project.js" defer></script> <!--  Script mit Funktionen zur Projektverwaltung-->
  <script src = "js/helpers.js" defer></script> <!-- Script mit Hilfsfunktionen  -->
  <script src = "js/xmlwriter.js" defer></script> <!-- Script zum erzeugen einer kml-Datei -->
  <script src = "js/exportKml.js" defer></script> <!-- Script zum Export der Geometriedaten als kml-Datei -->
  <script src = "js/alertify.min.js" defer></script> <!-- Script zur Anzeige von Popupbenachrichtigungen -->
  <script src = "js/toastr.min.js" defer></script> <!-- Script zum dynamischen Anzeigen von Statusmeldungen -->
  <script src = "js/geocoder.js" defer></script> <!-- Geocoding von Messpunkten -->
  <script src = "js/openweathermap.js" defer></script> <!-- Geocoding von Messpunkten -->
  </body>
</html>