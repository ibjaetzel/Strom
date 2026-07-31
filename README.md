SolarFlow Energiefluss – HTML-SDK Tile-Modul für IP-Symcon

Eine skalierbare, hell/dunkel-fähige Kachel, die den Energiefluss deiner Anlage zeigt:
SolarFlow + Hoymiles speisen die Batterie, die Batterie versorgt das Haus (bzw. speist Überschuss ins Netz),
das Netz hängt am Haus. Rechts hängen bis zu 10 Verbrauchergruppen an einer zentralen Leitung.
Darunter (optional) die editierbaren Parameter deiner PID-Regelung.

Fluss und Richtung werden nur über die wandernden Punkte dargestellt – die Linien sind immer sichtbar.


Installation

1. Ordner `SymconSolarFlowVisu` in das **modules**-Verzeichnis deiner Symcon-Installation kopieren
   (z. B. `/var/lib/symcon/modules/` unter Linux, `%ProgramData%\Symcon\modules\` unter Windows),
   oder den Ordner als lokales Git-Repository ablegen und in der Symcon-Console über
   Kerninstanzen → Modules → Module hinzufügen einbinden.
2. Symcon-Console neu laden (F5), damit das Modul erkannt wird.
3. Instanz hinzufügen → nach „SolarFlow Energiefluss" suchen → anlegen.

> Voraussetzung: Symcon ≥ 7.1 (HTML-SDK). Getestet für die Verwendung mit 9.0.

Konfiguration der Instanz

**Erzeuger & Batterie**
- SolarFlow PV-Leistung → ID *12345*
- Hoymiles PV-Leistung → ID *12345*
- Batterie-Ausgang ins Haus → ID *12345*
- Batterie-Ladezustand (%) → ID *12345*

**Netz (Shelly Pro3EM)** – Netzbezug gesamt wird als *L1 + L2 + L3* vorzeichenrichtig berechnet.
Da L3 (Batterie-Einspeisung) oft negativ ist, ergibt die Summe automatisch Bezug (positiv) bzw.
Einspeisung (negativ), und die Grafik dreht die Flussrichtung entsprechend.
- L1 → ID *12345*
- L2 → ID *12345*
- L3 → ID *12345*

**Verbrauchergruppen (optional)** – Liste, die du jederzeit paarweise erweitern kannst.
Pro Zeile: Name, Leistungs-Variable, Icon. Die Kachel ordnet sie automatisch an:
Zeile 1 = 1. Spalte oben, Zeile 2 = 1. Spalte unten, Zeile 3 = 2. Spalte oben usw.
Für ein sauberes Bild also immer zwei Zeilen (oben/unten) zusammen anlegen. Ohne Einträge bleibt die
rechte Seite leer. Der Hausverbrauch wird NICHT aus den Gruppen berechnet, sondern immer aus
Batterie-Ausgang + Netzbezug – die Gruppen sind nur ein Ausschnitt der größeren Verbraucher.

**SolarFlow-Regelung (optional)** – Kategorie „SolarFlow Einstellungen" auswählen, die dein PID-Skript anlegt.
Dann erscheinen unter der Grafik die Parameter (Sollwerte, SOC-Schwellen, Morgenlogik, PID) und sind direkt
editierbar. Änderungen werden per Ident in die Variablen SF_… geschrieben; dein 5-Sekunden-Regelskript nutzt
sie beim nächsten Lauf. Ohne Auswahl wird der Konfigbereich ausgeblendet.

Darstellung hinzufügen

In der **Tile-Visu** die Instanz „SolarFlow Energiefluss" als Kachel einfügen und die Kachelgröße
nach Geschmack ziehen. Hell/Dunkel folgt automatisch dem Theme.
