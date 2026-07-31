# SolarFlow Ladevisualisierung

Version 2.1: Umbau mit PV Süd, PV West, Haus-Bus, separatem Batterie-Wechselrichter, Wallbox-Modi und Hausbatterie-Netzladung.

## Struktur

```text
SymconSolarFlowVisu/
├─ library.json
└─ ChargingVisualization/
   ├─ module.json
   ├─ module.php
   ├─ form.json
   └─ module.html
```

## Wallbox

- 0 = Kein Laden
- 1 = Laden aus dem Netz
- 2 = Laden mit PV-Überschuss, wenn SOC der Hausbatterie größer als Grenzwert ist

## Hausbatterie

- Kein Laden aus dem Netz
- Netzladen nach zwei Zeitfenstern mit unterschiedlichem Ziel-SOC
