# DALUS

Das Projekt "Digitale Ausbreitungsabschätzung Luftgetragener Schadstoffe" (DALUS) dient zur Darstellung von Ausbreitungsabschätzungen luftgetragener Schadstoffemissionen und der Dokumentation von Messeinsätzen im Rahmen der operativen Gefahrenabwehr.

Es wurde vom Fachdienst ABC der Landeshauptstadt Magdeburg entwickelt.

## Installation

1. Erzeugen Sie die Datanbank 'dalus', indem Sie die Datei [dalus.sql](files/dalus.sql) in Ihrem Webserver importieren
2. Legen Sie in der Tabelle 'users' einen neuen Benutzer mit dazugehörigem Passwort an. Wichtig: Das Passwort muss als bcrypt-Hash in der Datenbank abgelegt werden. Dazu können Onlinetools wie [dieses](https://bcrypt-generator.com/) genutzt werden.
3. Kopieren Sie den Inhalt des Repositorys auf einen Webserver
4. Tragen Sie die Zugangsdaten Ihrer Datenbank in die Datei [config.php](php/config.php) ein
5. Rufen Sie die Datei index.php auf
