<?php
namespace Modules\OpenWeatherMap\Device;

use App\Helpers\SettingManager;

class Device
{

    public function __construct($device){
        $this->device = $device;
    }

    public function create()
    {
        $id = $this->device->id;

        //All device types get this
        //SettingManager::register('setting1', '', 'string', 'device-'.$id);

        switch ($this->device->type) {
            case "light":
                break;
            case "toggle":
                break;
            case "speaker":
                break;
        }
    }

    public function update()
    {

    }

    public function delete()
    {

    }
}
