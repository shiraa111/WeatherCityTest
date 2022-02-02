<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING );

include('weather_model.php');

if(isset($_GET["city_name"])){
    $weather = new Weather();
    $weather_data = $weather->getWeatherApiByCity($_GET["city_name"]);
    $city = new City_model($weather_data->city_id);

/*   $weather_data = $weather->getLastWeatherCity('Tel Aviv');
   $city = new City_model($weather_data->city_id);*/

}
?>
<!doctype html>
<html>
<head>
    <title>Forecast Weather using OpenWeatherMap with PHP</title>

    <style>
        body {
            font-family: Arial;
            font-size: 0.95em;
            color: #929292;
        }

        .report-container {
            border: #E0E0E0 1px solid;
            padding: 20px 40px 40px 40px;
            border-radius: 2px;
            width: 550px;
            margin: 0 auto;
        }

        .weather-icon {
            vertical-align: middle;
            margin-right: 20px;
        }

        .weather-forecast {
            color: #212121;
            font-size: 1.2em;
            font-weight: bold;
            margin: 20px 0px;
        }

        span.min-temperature {
            margin-left: 15px;
            color: #929292;
        }

        .time {
            line-height: 25px;
        }
    </style>

</head>
<body>

<div class="report-container">
    <h2><?php echo $city->city_name; ?> Weather Status</h2>
    <?php if (isset($message))
    {
        echo  $message ;
    }
    else{
        ?>
        <div class="time">
            <div><?php echo date("l g:i a", $weather_data->currentTime); ?></div>
            <div><?php echo date("jS F, Y",$weather_data->currentTime); ?></div>
            <div><?php echo ucwords($weather_data->description); ?></div>
        </div>
        <div class="weather-forecast">
            <img
                src="http://openweathermap.org/img/w/<?php echo $weather_data->icon; ?>.png"
                class="weather-icon" /> <?php echo $weather_data->temp_max; ?>&deg;C<span
                class="min-temperature"><?php echo $weather_data->temp_min; ?>&deg;C</span>
        </div>
        <div class="time">
            <div>Humidity: <?php echo $weather_data->humidity; ?> %</div>
            <div>Wind: <?php echo $weather_data->wind_speed; ?> km/h</div>
        </div>
    <?php }?>
</div>


</body>
</html>

