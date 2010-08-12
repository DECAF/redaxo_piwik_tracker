README
======


Über decaf\_piwik\_tracker
--------------------------

Dieses REDAXO Addon fügt den notwendigen Javascript-, bzw. PHP-Code in das Frontend um Besucher mit dem Piwik Server zu tracken. Im Backend wird eine Besucher-Statistik abgezeigt.


Changelog
---------

* **1.0.4:** 
  * Automatische Farbschema Anpassung für REDAXO 4.1
  * Deutsches LIESMICH
* **1.0.3:** 
  * Erstveröffentlichung

Voraussetzungen
---------------

* Piwik Server
* PHP 5.2+
* REDAXO 4.2+

Wenn die Besucher per PHP include getrackt werden sollen, muss 'allow\_url\_fopen' angeschaltet sein.

Mehr Information zu Piwik gibt es unter http://piwik.org. Dort gibt es eine gute Doku, wie der Piwik Server zu installieren und einzurichten ist.

Installation
------------

* Addon entpacken
* Den decaf\_piwik\_tracker Ordner in redaxo/include/addons legen
* Gehen Sie sicher, das die Ordner decaf\_piwik\_tracker und decaf\_piwik\_tracker/config für den Webserver beschreibbar sind.
* Benutzen Sie das Addon Panel, um das Addon zu installieren und zu aktivieren


**Hinweis für REDAXO 4.1 User:**

Wenn Sie das Addon unter **REDAXO 4.1** benutzen möchten, ist ein wenig Handarbeit nötig. Sie müssen den Ordner /files/addons/decaf\_piwik\_tracker/ anlegen und die Dateien aus /redaxo/include/addons/decaf\_piwik\_tracker/files/ dorthin kopieren.


Konfiguration
-------------

Wenn das Addon installiert ist, müssen Sie die notwendigen Konfigurations-Paramter eingeben.

**Tracker URL:**
Die URL Ihres Piwik Server, ohne Slash (/) am Ende. Z.B.: http://stats.your-server.tpl

**Site Id:**
Die ID der Site. Wird in Piwik unter Einstellungen » Websites angezeigt.

**Tracking Methode:**
Sie können wählen zwischen Javascript (default) und PHP.

Mit Javascript können Sie mehr Informationen über Ihre Besucher bekommen, während das PHP include dezenter ist.

**Auth Token:**
Das Auth Token ist nötig um Piwik über die API anzusprechen, Damit werden die Statistik-Daten in das Redaxo Backend geholt. Das Auth Token wird in Piwik unter Einstellungen » Benutzer angezeigt.

**Benutzername:**
Optional. Wenn Sie Ihren Benutzern die Möglichkeit geben möchten von REDAXO direkt auf die Piwik Seite zu wechseln müssen Sie den Benutzernamen und das Passwort (MD5) angeben.

**Password (MD5):**
Optional. Das Passwort finden Sie in der MySQL Tabelle piwik\_user.


Widget Konfiguration
--------------------

Um die Ansicht der Statistik im REDAXO Backend zu konfigurieren können Sie die widgets.ini.php im config/ Ordner anpassen.

Um mehrere Widgets anzuzeigen legen Sie mehrere Einträge in der widgets.ini.php an.

**api_period:** Der Zeitraum für den die Werte zusammengefasst werden sollen. Möglich sind day, week, month und year.

**api_date:** Der Zeitraum, für den Werte geholt werden sollen. Momentan ist nur lastX implementiert. Um z.B. die letzten 6 Wochen zu holen benutzen Sie api\_date = last6 und api\_period = week.

**columns:** Welche Werte angezeigt werden sollen. Sie können nb\_visits, nb\_uniq\_visitors und nb\_actions benutzen. Mehrere Werte werden durch Komma (,) getrennt. Bitte benutzen Sie **keine Leerzeichen**. 

**width:** Die Breite des Widgets in Pixel. Standard ist 745. Wenn die Breite kleiner ist, werden mehrere Widgets in einer Reihe angezeigt.

**widget\_title** Hiermit können Sie den automatisch generierten Titel überschreiben. Dieser ist dann allerdings nicht mehr lokalisierbar.


