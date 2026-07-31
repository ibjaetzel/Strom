# SolarFlow Energy & Charging Visualization

IP-Symcon HTML-SDK Tile-Modul für Energiefluss, Wallbox-Steuerung und Hausbatterie-Netzladung.

## Umbau Version 2.0

- Zwei PV-Anlagen: **PV Süd** und **PV West**
- PV-Anlagen speisen in das **Hausnetz / den Haus-Bus**, nicht direkt in die Batterie
- Hausbatterie über eigenen **Batterie-Wechselrichter**
- Separater Bereich **Wallbox** mit drei Modi:
  - Kein Laden
  - Laden aus dem Netz
  - Laden mit PV-Überschuss, wenn SOC der Hausbatterie größer als der eingestellte Grenzwert ist
- Separater Bereich **Hausbatterie-Netzladung**:
  - Kein Laden aus dem Netz
  - Netzladen nach zwei definierbaren Zeitfenstern mit jeweils eigenem Ziel-SOC

## Erwartete Daten an die Kachel

`module.html` akzeptiert sowohl die neuen Feldnamen als auch einige alte Alias-Namen. Typisches JSON:

```json
{
  "pvSouth": 2400,
  "pvWest": 1300,
  "batteryPower": -850,
  "batterySoc": 76,
  "l1": 100, "l2": 200, "l3": -500,
  "wallbox": { "power": 0, "mode": 2, "socMin": 80 },
  "homeBattery": {
    "gridChargeEnabled": true,
    "windows": [
      { "start": "00:00", "end": "06:00", "targetSoc": 60 },
      { "start": "13:00", "end": "15:00", "targetSoc": 80 }
    ]
  }
}
```

Vorzeichen Batterie-WR: **positiv = Entladung Richtung Haus**, **negativ = Ladung der Batterie**.

## Installation

Ordner in das Symcon-`modules`-Verzeichnis kopieren oder als lokales Git-Repository einbinden. Danach Console neu laden und Instanz/Kachel hinzufügen.
