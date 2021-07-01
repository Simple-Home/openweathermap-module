<?php

namespace Modules\OpenWeatherMap\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Helpers\SettingManager;
use App\Models\Records;
use App\Models\Property;
use Modules\Openweathermap\getOpenWeatherMapData;

class OpenWeatherMapJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $apiKey = SettingManager::get('apikey', 'openweathermap')['value'];
        $location = SettingManager::get('location', 'openweathermap')['value'];
        $this->owm = new getOpenWeatherMapData($apiKey, $location);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $temp = $this->owm->getCurrent();
        $exists = Property::where('feature', 'owm-current-temp')->first();
        
        //create property
        if(!$exists){
            $property = new Property;
            $property->type = "sensor";
            $property->icon = "fas fa-sun";
            $property->feature = "owm-current-temp";
            $property->nick_name = "OWM Current Temp";
            $property->room_id = 1;
            $property->device_id = 1;
            $property->history = mt_rand(100,600);
            $property->save();
        }

        Records::insert(['property_id' => $property->id, 'value' => $temp]);
    }
}
