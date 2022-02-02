<?php

interface  WeatherInformation
{

 public function getWeatherApiByCity($city_name);
 public function setWeatherApi($weather_data);
 public function getLastWeatherCity($city_name);
 public function getLastWeatherCityFromDB($city_id);
}