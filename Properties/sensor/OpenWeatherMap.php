<?php
namespace Modules\OpenWeatherMap\Properties\sensor;

use App\PropertyTypes\sensor\sensor;
use App\Helpers\SettingManager;

/**
 * Class OpenWeatherMap
 * @package App\PropertyTypes\sensor
 */
class OpenWeatherMap extends sensor
{
    public $supportedAttributes = [];

    public function __construct($meta){
        $this->meta = $meta;
        $this->features = $this->getFeatures($this);

        $apiKey = SettingManager::get('apikey', 'openweathermap')['value'];
    }

    // API (GET): http://localhost/api/v2/device/(hostname)/state/(value)
    public function state($value){

        // This is how you notify Simple Home of the state change
        $this->setState('state', $value);
    }

}
