<?php

class ChargingVisualization extends IPSModule
{
    public function Create()
    {
        parent::Create();
        $this->RegisterPropertyInteger('PVSouth', 0);
        $this->RegisterPropertyInteger('PVWest', 0);
        $this->RegisterPropertyInteger('L1', 0);
        $this->RegisterPropertyInteger('L2', 0);
        $this->RegisterPropertyInteger('L3', 0);
        $this->RegisterPropertyInteger('BatteryPower', 0);
        $this->RegisterPropertyInteger('BatterySoC', 0);
        $this->RegisterPropertyInteger('WallboxPower', 0);
        $this->RegisterPropertyInteger('WallboxModeVariable', 0);
        $this->RegisterPropertyInteger('WallboxSocMinVariable', 0);
        $this->RegisterPropertyInteger('HomeGridChargeEnabledVariable', 0);
        $this->RegisterPropertyInteger('HomeWindow1StartVariable', 0);
        $this->RegisterPropertyInteger('HomeWindow1EndVariable', 0);
        $this->RegisterPropertyInteger('HomeWindow1SocVariable', 0);
        $this->RegisterPropertyInteger('HomeWindow2StartVariable', 0);
        $this->RegisterPropertyInteger('HomeWindow2EndVariable', 0);
        $this->RegisterPropertyInteger('HomeWindow2SocVariable', 0);
        $this->RegisterPropertyString('Groups', '[]');
        $this->RegisterPropertyInteger('DayProduction', 0);
        $this->RegisterPropertyInteger('WeekProduction', 0);
        $this->RegisterPropertyInteger('DayGridImport', 0);
        $this->RegisterPropertyInteger('WeekGridImport', 0);
    }

    public function ApplyChanges()
    {
        parent::ApplyChanges();
        $this->SetVisualizationType(1);
        $this->Refresh();
    }

    public function GetVisualizationTile()
    {
        return file_get_contents(__DIR__ . '/module.html');
    }

    public function RequestAction($Ident, $Value)
    {
        switch ($Ident) {
            case 'WallboxMode': $this->SetConfiguredValue('WallboxModeVariable', $Value); break;
            case 'WallboxSocMin': $this->SetConfiguredValue('WallboxSocMinVariable', $Value); break;
            case 'HomeGridChargeEnabled': $this->SetConfiguredValue('HomeGridChargeEnabledVariable', (bool)$Value); break;
            case 'HomeWindow1': $this->SetWindowValues(1, $Value); break;
            case 'HomeWindow2': $this->SetWindowValues(2, $Value); break;
            case 'Refresh': break;
        }
        $this->Refresh();
    }

    public function Refresh()
    {
        $this->UpdateVisualizationValue(json_encode($this->BuildPayload()));
    }

    private function SetWindowValues(int $index, $value): void
    {
        if (is_string($value)) { $value = json_decode($value, true); }
        if (!is_array($value)) { return; }
        $prefix = 'HomeWindow' . $index;
        if (isset($value['start'])) { $this->SetConfiguredValue($prefix . 'StartVariable', $value['start']); }
        if (isset($value['end'])) { $this->SetConfiguredValue($prefix . 'EndVariable', $value['end']); }
        if (isset($value['targetSoc'])) { $this->SetConfiguredValue($prefix . 'SocVariable', $value['targetSoc']); }
    }

    private function SetConfiguredValue(string $property, $value): void
    {
        $id = $this->ReadPropertyInteger($property);
        if ($id > 0 && @IPS_VariableExists($id)) { @SetValue($id, $value); }
    }

    private function V(string $property, $default = 0)
    {
        $id = $this->ReadPropertyInteger($property);
        if ($id > 0 && @IPS_VariableExists($id)) { return @GetValue($id); }
        return $default;
    }

    private function FormatVar(string $property): string
    {
        $id = $this->ReadPropertyInteger($property);
        if ($id > 0 && @IPS_VariableExists($id)) { return @GetValueFormatted($id); }
        return '';
    }

    private function BuildPayload(): array
    {
        $stats = [];
        foreach (['DayProduction'=>'PV heute','WeekProduction'=>'PV Woche','DayGridImport'=>'Netzbezug heute','WeekGridImport'=>'Netzbezug Woche'] as $prop=>$label) {
            $text = $this->FormatVar($prop);
            if ($text !== '') { $stats[] = ['label'=>$label, 'value'=>$text]; }
        }
        return [
            'pvSouth'=>$this->V('PVSouth'), 'pvWest'=>$this->V('PVWest'),
            'batteryPower'=>$this->V('BatteryPower'), 'batterySoc'=>$this->V('BatterySoC'),
            'l1'=>$this->V('L1'), 'l2'=>$this->V('L2'), 'l3'=>$this->V('L3'),
            'stats'=>$stats,
            'wallbox'=>['power'=>$this->V('WallboxPower'), 'mode'=>intval($this->V('WallboxModeVariable',0)), 'socMin'=>floatval($this->V('WallboxSocMinVariable',80))],
            'homeBattery'=>['gridChargeEnabled'=>boolval($this->V('HomeGridChargeEnabledVariable',false)), 'windows'=>[
                ['start'=>strval($this->V('HomeWindow1StartVariable','00:00')), 'end'=>strval($this->V('HomeWindow1EndVariable','06:00')), 'targetSoc'=>floatval($this->V('HomeWindow1SocVariable',60))],
                ['start'=>strval($this->V('HomeWindow2StartVariable','13:00')), 'end'=>strval($this->V('HomeWindow2EndVariable','15:00')), 'targetSoc'=>floatval($this->V('HomeWindow2SocVariable',80))]
            ]]
        ];
    }
}

