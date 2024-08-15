<?php
$apiKey = 'a52bae14db254c6caae194156241508'; // Replace with your WeatherAPI key
$cities = [
    "New York", "London", "Paris", "Tokyo", "Beijing", "Moscow", "Los Angeles",
    "Rio de Janeiro", "Mumbai", "Dubai", "Istanbul", "Sydney", "Shanghai",
    "Mexico City", "Cairo", "Lagos", "Buenos Aires", "Sao Paulo", "Jakarta",
    "Karachi", "Seoul", "Bangkok", "Delhi", "Lima", "Tehran", "Kinshasa",
    "Bogota", "Hong Kong", "Riyadh", "Kuala Lumpur", "Singapore", "Cape Town",
    "Madrid", "Rome", "Berlin", "Toronto", "Vancouver", "Melbourne",
    "Santiago", "Athens", "Brussels", "Vienna", "Stockholm", "Copenhagen",
    "Dublin", "Oslo", "Helsinki", "Warsaw", "Budapest", "Prague"
];

function getWeatherData($city) {
    global $apiKey;
    $url = "https://api.weatherapi.com/v1/current.json?key={$apiKey}&q={$city}&aqi=no";
    $response = @file_get_contents($url);
    if ($response === false) {
        error_log("Failed to fetch weather data for {$city}");
        return [
            'city' => $city,
            'temperature' => 'N/A',
            'description' => 'No data available',
            'icon' => 'https://via.placeholder.com/64'
        ];
    }
    $data = json_decode($response, true);
    return [
        'city' => $data['location']['name'],
        'temperature' => $data['current']['temp_c'],
        'description' => $data['current']['condition']['text'],
        'icon' => "https:{$data['current']['condition']['icon']}"
    ];
}

$weatherData = [];
foreach ($cities as $city) {
    $weatherData[] = getWeatherData($city);
}

// Sort the weather data by temperature (highest to lowest)
usort($weatherData, function($a, $b) {
    if ($a['temperature'] === 'N/A' && $b['temperature'] === 'N/A') return 0;
    if ($a['temperature'] === 'N/A') return 1;
    if ($b['temperature'] === 'N/A') return -1;
    return $b['temperature'] - $a['temperature'];
});

function displayWeather($weatherData) {
    foreach ($weatherData as $weather) {
        echo "<div class='weather-card'>";
        echo "<h2>" . htmlspecialchars($weather['city']) . "</h2>";
        echo "<img src='" . htmlspecialchars($weather['icon']) . "' alt='Weather Icon'>";
        echo "<p>Temperature: " . htmlspecialchars($weather['temperature']) . " °C</p>";
        echo "<p>" . htmlspecialchars($weather['description']) . "</p>";
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Weather (Sorted by Temperature)</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Top 50 Cities Weather (Sorted by Temperature of Cities)</h1>
    </header>
    <main id="weather-container">
        <?php displayWeather($weatherData); ?>
    </main>
    <footer>
        <p>Powered by WeatherAPI</p>
    </footer>
</body>
</html>
