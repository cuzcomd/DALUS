# DALUS

Das Projekt "Digitale Ausbreitungsabschätzung Luftgetragener Schadstoffe" (DALUS) dient zur Darstellung von Ausbreitungsabschätzungen luftgetragener Schadstoffemissionen und der Dokumentation von Messeinsätzen im Rahmen der operativen Gefahrenabwehr.

Es wurde vom Fachdienst ABC der Landeshauptstadt Magdeburg entwickelt.

## Installation

1. Erzeugen Sie die Datanbank 'dalus', indem Sie die Datei [dalus.sql](files/dalus.sql) in Ihrem Webserver importieren.
2. Legen Sie in der Tabelle 'users' einen neuen Benutzer mit dazugehörigem Passwort an. Wichtig: Das Passwort muss als bcrypt-Hash in der Datenbank abgelegt werden. Dazu können Onlinetools wie [dieses](https://bcrypt-generator.com/) genutzt werden. In der Spalte "level" tragen Sie "admin" ein.
3. Kopieren Sie den Inhalt des Repositorys auf einen Webserver.
4. Tragen Sie die Zugangsdaten Ihrer Datenbank in die Datei [config.php](php/config.php) ein.
5. Erzeugen Sie einen [Google Maps API-Key](https://developers.google.com/maps/documentation/javascript/get-api-key?hl=de) und tragen Sie Ihn in der [workbench.php](workbench.php) in folgende Zeile ein:
```
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=geometry,drawing&callback=initMap" async defer></script>
```
6. Rufen Sie die Datei index.php auf.

Dieses Projekt wird kostenlos unterstützt durch

![Browserstack Logo](images/Logo-01.png)

<b>testing made easy with browserStack</b>
