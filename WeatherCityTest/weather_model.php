<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING );
include 'DB.php';
include 'WeatherInformation.php';
include 'City_model.php';

class Weather implements  WeatherInformation{
    private $ID = 0;
    public  $city_id = 0;
    public  $date = "";
    public  $temp_max = NULL;
    public  $temp_min = NULL;
    public  $humidity = NULL;
    public  $wind_speed = NULL;
    public  $description = NULL;
    public  $icon = NULL;

    private $db_table = "weather";
    private  $db ;

    public function __construct($ID = 0){
        $this->db = DB::getconnection();

        if($ID !== 0){
            $sql = "SELECT * FROM $this->db_table WHERE id = $ID";
            $q = $this->db->query($sql);
            if ($q->num_rows > 0){
                $row = $q->fetch_assoc();
                $this->ID = $ID;
                $this->city_id     = $row['city_id'];
                $this->date        = date("Y-d-m h:i:s", $row['currentTime']);
                $this->temp_max    = $row['temp_max'];;
                $this->temp_min    = $row['temp_min'];
                $this->humidity    = $row['humidity'];;
                $this->wind_speed  = $row['wind_speed'];
                $this->description = $row['description'];
                $this->icon        = $row['icon'];
            }
        }
    }
    public function getWeatherApiByCity($city_name){
        $apiKey = "ab0e1d9a37e64c4477153c266096a858";
        $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q=" . $city_name . "&lang=en&units=metric&APPID=" . $apiKey;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        curl_close($ch);
        $weather_data = json_decode($response);

        $weather_data->currentTime = time();

        $this->setWeatherApi($weather_data);
        return json_decode(json_encode($this));
    }
    public function setWeatherApi($weather_data){

        $city = new City_model();
        $city->city_name = $weather_data->name;
        $cityId = $city->save();


        $this->city_id     = $cityId;
        $this->date        = date("Y-d-m h:i:s", $weather_data->currentTime);
        $this->temp_max    = $weather_data->main->temp_max;;
        $this->temp_min    = $weather_data->main->temp_min;
        $this->humidity    = $weather_data->main->humidity;;
        $this->wind_speed  = $weather_data->wind->speed;
        $this->description = $weather_data->weather[0]->description;
        $this->icon        = $weather_data->weather[0]->icon;

        $this->save();

    }
    public function getLastWeatherCity($city_name){
        $city = new City_model();
        $city->city_name = $city_name;
        $cityId = $city->CheckCityExists();
        if ($cityId){
           return json_decode($this->getLastWeatherCityFromDB($cityId));
        }
        else{
            return $this->getWeatherApiByCity($city_name);
        }

    }
    public function getLastWeatherCityFromDB($cityId){
        $sql = "SELECT * from weather WHERE city_id = $cityId ORDER BY id DESC LIMIT 1;";
        $q = $this->db->query($sql);
        if ($q->num_rows > 0){
            $row = $q->fetch_assoc();
            $this->ID = $row['id'];
            $this->city_id     = $cityId;
            $this->date        = date("Y-d-m h:i:s", $row['currentTime']);
            $this->temp_max    = $row['temp_max'];;
            $this->temp_min    = $row['temp_min'];
            $this->humidity    = $row['humidity'];;
            $this->wind_speed  = $row['wind_speed'];
            $this->description = $row['description'];
            $this->icon        = $row['icon'];

            return json_encode($this);

        }
    }
    public function save(){
        $sql = "INSERT INTO `weather`( `city_id`, `date`, `temp_max`, `temp_min`, `humidity`, `wind_speed`, `description` ,`icon`) 
                             VALUES ($this->city_id,'$this->date',$this->temp_max,$this->temp_min,$this->humidity,$this->wind_speed,'$this->description','$this->icon')";
        $q = $this->db->query($sql);

        if($q){
            $this->__construct($this->db->insert_id);
            return $this->ID;
        }
        else
            return false;

    }

}

?>
