<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController {
    /**
     * @Route("/city")
     */
    public function city(): Response {

//        get the city name and the country.
        $separateData = explode(",", $_POST['address']);
        $cityName = $separateData[0];
        $countryName = end($separateData);

//        set utc time on 0 for future comparaison.
        date_default_timezone_set('Europe/London');
        $utc0 = date('H:i:s', time());

//        call openweathermap api with city name to get the city time zone.
        $curl = curl_init('http://api.openweathermap.org/data/2.5/weather?q=' . $cityName . '&appid=b938b73c572c223864233156d27f432b&units=metric&lang=fr');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);
        if ($data === false) {
            var_dump(curl_error($curl));
        } elseif ($date = true) {
            $data = json_decode($data, true);
        } if (isset($data['timezone'])) {
//            Convert seconds time zone in hour(s)
            $cityTimeZone = $data['timezone'];
            $shiftInHour = $cityTimeZone / 60 / 60;

//            use php function date_parse to get hour(s), minute(s) and second(s) separatly.
            $dateParse = date_parse($utc0);
            $hour = $dateParse['hour'] + $shiftInHour;
            $minute = $dateParse['minute'];
            $seconds = $dateParse['second'];

            if ($hour >= 0 && $hour <= 9) {
                $hour = "0" . $hour;
            }

            if ($minute >= 0 && $minute <= 9) {
                $minute = "0" . $minute;
            }

            if ($seconds >= 0 && $seconds <= 9) {
                $seconds = "0" . $seconds;
            }

            if ($hour >= 24) {
                $hour = $hour - 24;
                $hour = "0".$hour;
            } elseif ($hour < 0) {
                $hour = $hour + 24;
            }
            $finalTime = $hour .":" . $minute . ":" . $seconds;
            $finalZone = " at " . $cityName . " in" . $countryName;
        } elseif (!isset($data['timezone'])) {
            $errorTimezone = "An error was occured, please try again with another city or another one near the one you searched.";
            return $this->render('home/index.html.twig', compact('errorTimezone'));
        } else {
            echo "an error was occured, please try again with another city near the one you searched";
        }
        return $this->render('home/index.html.twig', compact('finalTime', 'finalZone'));
    }

}