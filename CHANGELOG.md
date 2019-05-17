## Changelog

### 2.0.2
* Feature: DALUS lässt sich jetzt per Installationsskript auf einem Server einrichten.
* Code: Update von JavaScript-Bibliotheken auf die neuste Version
* Deprecated: boostrap-editable wurde durch native Funktionen ersetzt
* Style: Redesign der Infowindows
* Style: Beim Auswählen des MET-Ursprungsortes wird jetzt ein Fadenkreuz angezeigt
* Bug: Änderung des Messpunkttitels hat nicht immer den aktiven Messpunkt umbenannt
* Bug: Tooltip bei manchen Formularfeldern im MET-Menü wurde falsch angezeigt
* Bug: Die Anzahl der in einem Projekt gespeicherten Messpunkte wurde nich korrekt ermittelt
* Usability: Formularfelder sind jetzt besser für Autocomplete Funktionen geeignet


### 2.0.1 (01.03.2019)
* Security: Einlesen von API-Keys über Datenbank, statt bisher hard coded
* Bug: Ändern der Benutzerdaten hat zu einem Reload der Seite gehführt
* Code: Restrukturierung der JavaScript-Bibliotheken und Modularisierung der php-Dateien

### 2.0.0 (03.12.2018)
* Style: Komplettes Update auf Bootstrap 4
* Style: Wechsel auf ein modernes Dashbord-Template
* Security: Update aller Javascript-Bibliotheken auf aktuelle Version
* Security: Unterstützung von Argon2 als Passwort-Hashalgorithmus
* Feature: Einführung Ausbreitungsklasse für MET-Modell
* Bug: Diverse kleine Fehlerkorrekturen
* Style: Google-Kartendaten werden als Standardeinstellung geladen

### 1.6.1 (24.09.2018)
* Feature: Kartenmittelpunkt wird zu Beginn auf den in den Optionen gewählten Ort gesetzt.
* Security: Diverse Anpassungen an den Benutzerrechten
* Deprecated: Password-Hashalgorithmus Argon2 wurde wieder entfernt, da PHP häufig ohne Unterstützung compiled wird
* Style: Anpassung der infowindows an die tatsächliche Fensterbreite

### 1.6.0 (12.09.2018)
* Feature: Messpunkte werden jetzt anhand der zugewiesenen Fahrzeuge farblich hervorgehoben
* Feature: Fahrzeugfarben lassen sich über eine Farbpalette wählen
* Feature: Passworhashes mit Argon2-Algorithmus bei PHP > 7.0 möglich
* Bug: Diverse Bugfixes, die zu fehlerhaftem Verhalten führten


### 1.5.2 (27.07.2018)
* Feature: Wetterdaten werden live eingelesen von Openweathermap.com
* Feature: MET-Modell zeigt jetzt die Ausbreitungsklasse an und berücksichtigt intensiven Brand und tiefkalte Gase
* Bug: Messkatastersymbol war im mobilen Menü noch falsch dargestellt

### 1.5.1 (26.07.2018)
* Bug: Update der Screenshotfunktion auf neue Version
* Bug: Wenn ein Messpunkt des Katasters ausgewählt war, kam es in manchen Fällen zu Fehlermeldungen
* Style: Viele kleine Verbesserungen
* Style: Codebereinigung und -optimierung
* Style: Verschieben des Kompassmoduls in die Karte
* Style: Entschlackung des MET-Fensters
* Style: Openstreetmap Kart ist nicht länger als Standard ausgewählt (Geschwindigkeitsverbesserung)
* Security: Erhöhung der Sicherheit bei Anmelden

### 1.5.0 (12.09.2017)
* Feature: Benutzer können jetzt Messtrupps erstellen und Ihnen Messpunkte zuweisen
* Feature: Der Benutzerlevel "Editor" ist entfallen. Dafür können Nutzer jetzt individuelle Anpassungen am Messkataster und anderen Optionen vornehmen.
* Style: Komplette Überarbeitung des Optionsmenüs
* Style: Komplette Überarbeitung des Infowindows
* Bug: Style-Datei der Datentabellen wurde nicht geladen
* Bug: Rechtschreibkorrekturen

### 1.4.3 (29.08.2017)
* Feature: Infowindows tragen jetzt ein Label, das vom Benutzer individuell festgelegt werden kann
* Feature: Infowindows zeigen jetzt die Adresse an
* Feature: Kommentarfelder können beliebig auf der Karte platziert werden
* Style: Überarbeitung der Werkzeusymbolleiste
* Bug: Messpunkte werden jetzt immer auf einer höheren Ebene platziert als andere Objekte, sodass sie jederzeit sicher ausgewählt werden können.

### 1.4.2 (28.08.2017)
* Feature: Administratoren udn Editoren können das Messkataster zentral bearbeiten 
* Bug: Geteilte Projekte ohne gespeicherte Messpunkte erzeugten einen Fehler bei der Nummerierung
* Code: Auslagerung aller Javascript-Funktionen in eigene Dateien.

### 1.4.1 (31.07.2017)
* Feature: Die Adresssuche lässt sich durch einen Klick auf die Lupe starten. 
* Style: Anpassung der Nutzeroberfläche
* Bug: Laden des GPS-Moduls hat zu einer fehlerhaften Anzeige der verfügbaren Fahrzeuge geführt.
* Bug: Ändern des Benutzernamens hat zu einer falschen Rückmeldung geführt.
* Bug: Unter Chrome für Android wurden modals nicht geschlossen, wenn Buttons im Modal gedrückt wurden.
* Bug: Die Adresssuche hat die automatische Verfollständigung nicht korrekt angezeigt

### 1.4.0 (28.07.2017)
* Feature: Neue Nutzerberechtigung "Editor" (Kann Änderungen an manchen Parametern der Datenbank vornehmen, z. B. das Messkataster bearbeiten)
* Feature: Administratoren haben die Möglichkeit, GPS-Tracking anzuzeigen (Konfiguration über das Frontend ist noch nicht vollständig umgesetzt!!!)
* Feature: Vor dem Löschen eines Projekts erfolgt eine Sicherheitsabfrage
* Style: Off-canvas Menü für mobile Endgeräte eingeführt
* Style: Die Nutzerverwaltung heißt jetzt Optionen und gewährt Konfigurationsmöglichkeiten für die Datenbank und das eigene Nutzerprofil, je nach Berechtigung
* Bug: Die einzelnen Polygone eines MET-Winkels werden nicht mehr separat in der Datenbank gespeichert
* Bug: Das Speichern von kml-Dateien ist jetzt unter iOS und Safari direkt möglich.


### 1.3.1 (24.07.2017)
* Feature: Adressen können jetzt direkt in der Karte gesucht werden
* Bugfix: Öffnen des Messpunkt-Zeichenwerkzeugs ist in iOS und Safari jetzt möglich
* Bugfix: Exportierte kml-Dateien tragen automatisch den aktuellen Projektnamen
* Style: Einige CSS-Parameter sind jetzt in eine externe CSS-Datei verschoben

### 1.3.0 (21.07.2017)
* Feature: Export der Projektgeometrie als kml-Datei

### 1.2.0 (20.07.2017)
* Feature: Einführung von Benutzerrechten (ACL: Access Control Levels) Administrator und Benutzer
* Feature: Administratoren haben Zugriff auf die Benutzerverwaltung und können neue Nutzer anlegen und Bestehende löschen
* Bugfix: Der Menüpunkt "Abmelden" hat zu einer falschen Adresse geführt und dei Sitzun nicht korrekt beendet

### 1.1.2 (19.07.2017)
* Feature: Einbindung der Lagedarstellung entsprechend des Messzugkonzepts NRW
* Bugfix: Das Löschen von neu gezeichneten Objekte ist nicht möglich
* Bugfix: Das manuelle Setzen des Freisetzungsortes erzeugt einen zusätzlichen Marker

### 1.1.1 (14.07.2017)
* Feature: Änderung des Benutzernamens und Passworts im Hauptmenü
* Style: Überarbeitung des Anmeldebildschirms
* Bugfix: Öffnen des MET-modal in iOS und Safari ist nicht möglich
* Bugfix: Beim Klick auf eine Geomtrie oder Karte wird die bisher aktive Geometrie nicht abgewählt

### 1.1.0 (13.07.2017)
* Feature: MET-Freisetzungsort kann manuell gesetzt werden
* Feature: Speicherung von Kreisen, Polygonen und Pfaden in der Datenbank ist jetzt möglich
* Style: Überarbeitung der Benutzeroberfläche
* Bugfix: Diverse Fehler beim Zurücksetzen der Kartengeometrie behoben

### 1.0.0 (04.07.2017)
* Umstrukturierung der App auf datenbankgestützte Projektverwaltung