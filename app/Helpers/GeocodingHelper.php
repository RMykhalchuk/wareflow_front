<?php

namespace App\Helpers;

final class GeocodingHelper
{
    public static function haversineDistance($point1, $point2): float
    {
        $earthRadius = 6371000; // Радіус Землі в метрах

        $latFrom = deg2rad($point1['lat']);
        $lonFrom = deg2rad($point1['lng']);
        $latTo = deg2rad($point2['lat']);
        $lonTo = deg2rad($point2['lng']);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    /**
     * @psalm-param 'с.Муроване вул.Тополина 1'|'с.Старий Яричів вул.Заводська 1' $address
     *
     * @psalm-return array{lat: mixed, lng: mixed}|false
     */
    public static function getCoordinates(string $address): array|false
    {
        // Replace the space with "+" in the address
        $formattedAddress = str_replace(' ', '+', $address);


        // Google Maps Geocoding API URL
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddress . '&key=' . config('services.google.maps_api_key');

        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Execute cURL and get the JSON response
        $response = curl_exec($ch);

        // Close cURL
        curl_close($ch);

        // Decode the JSON response
        $response = json_decode($response, true);

        // Check if response is valid
        if ($response['status'] == 'OK') {
            // Get the coordinates
            $latitude = $response['results'][0]['geometry']['location']['lat'];
            $longitude = $response['results'][0]['geometry']['location']['lng'];

            return ['lat' => $latitude, 'lng' => $longitude];
        } else {
            return false;
        }
    }
}
