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

class OpenWeatherMapJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $owm = $this->getCurrent("http://api.openweathermap.org/data/2.5/weather");
        $this->createProperty("Outdoor Weather Summary", "fas fa-sun", "currentDescription", ucwords($owm['weather'][0]['description']));
        $this->createProperty("Outdoor Weather", "fas fa-sun", "currentTemp", $owm['main']['temp']);
    }

    private function createProperty($name, $icon, $key, $value){
        // Create property
        $property = Property::where('type', 'owm-'.$key)->first();
        if(!$property){
            $property = new Property;
            $property->icon = $icon;
            $property->type = "owm-".$key;
            $property->feature = "state";
            $property->nick_name = $name;
            $property->room_id = 1;
            $property->device_id = 1;
            $property->history = 0;
            $property->save();
        }

        // Create Record
        Records::insert(['property_id' => $property->id, 'value' => $value]);
    }

    private function getCurrent($url){
        $apiKey = SettingManager::get('apiKey', 'openweathermap')['value'];
        $location = SettingManager::get('location', 'openweathermap')['value'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url."?q=".$location."&appid=".$apiKey,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return "Error";
        } else {
            return json_decode($response, true);
        }
    }
}
