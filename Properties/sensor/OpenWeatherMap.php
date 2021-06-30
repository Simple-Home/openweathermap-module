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
    public $supportedAttributes = ["connected"];

    public function __construct($meta){
        $this->meta = $meta;
        $this->features = $this->getFeatures($this);

        $username = SettingManager::get('apikey', 'openweathermap')['value'];

        $this->setAttributes('connected', 0);
    }

    // API (GET): http://localhost/api/v2/device/(hostname)/state/(value)
    public function state($value){

        // This is how you notify Simple Home of the state change
        $this->setState('state', $value);
    }

}
