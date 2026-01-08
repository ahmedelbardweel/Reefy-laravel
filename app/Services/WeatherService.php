<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openweathermap.org/data/2.5';

    public function __construct()
    {
        $this->apiKey = env('OPENWEATHER_API_KEY', 'demo'); // Will use demo if no key
    }

    public function getCurrentWeather($city = 'Gaza', $country = 'PS')
    {
        // Cache for 30 minutes
        return Cache::remember("weather_v7_{$city}_{$country}", 1800, function () use ($city, $country) {
            try {
                // If default is Gaza, use coordinates directly to use the free Open-Meteo API
                if ($city === 'Gaza') {
                    // Pass specific location name
                    return $this->getWeatherByCoordinates(31.5, 34.4667, __('ar') == 'ar' ? 'غزة، فلسطين' : 'Gaza, Palestine');
                }

                // ... (OpenWeatherMap fallback logic remains same but unused for Gaza)
                $response = Http::withoutVerifying()->get("{$this->baseUrl}/weather", [
                    'q' => "{$city},{$country}",
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                    'lang' => 'ar'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $this->formatWeatherData($data);
                }
                
                return $this->getFallbackWeather("API Error: " . $response->status());
            } catch (\Exception $e) {
                return $this->getFallbackWeather($e->getMessage());
            }

            return $this->getFallbackWeather('Unknown error');
        });
    }

    /**
     * Get weather by GPS coordinates
     */
    public function getWeatherByCoordinates($lat, $lon, $locationName = null)
    {
        // Set default location name based on locale
        if ($locationName === null) {
            $locationName = app()->getLocale() == 'ar' ? 'موقعك' : 'Your Location';
        }
        // Cache for 30 minutes
        return Cache::remember("weather_v7_{$lat}_{$lon}_{$locationName}", 1800, function () use ($lat, $lon, $locationName) {
            try {
                // Use Open-Meteo API
                $response = Http::withoutVerifying()->get("https://api.open-meteo.com/v1/forecast", [
                    'latitude' => $lat,
                    'longitude' => $lon,
                    'current' => 'temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m',
                    'daily' => 'weather_code,precipitation_sum,wind_speed_10m_max',
                    'forecast_days' => 3,
                    'timezone' => 'auto'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $this->formatOpenMeteoData($data, $locationName);
                }
                
                return $this->getFallbackWeather("API Error: " . $response->status());
            } catch (\Exception $e) {
                return $this->getFallbackWeather($e->getMessage());
            }

            return $this->getFallbackWeather('Unknown error');
        });
    }

    /**
     * Format Open-Meteo weather data
     */
    protected function formatOpenMeteoData($data, $locationName = 'موقعك')
    {
        $current = $data['current'];
        $temp = round($current['temperature_2m']);
        $humidity = round($current['relative_humidity_2m']);
        $windSpeed = round($current['wind_speed_10m']);
        $weatherCode = $current['weather_code'];
        
        // Map Open-Meteo weather codes to conditions and icons
        $weatherInfo = $this->getWeatherInfoFromCode($weatherCode);
        
        // Check for upcoming weather events (next 2 days)
        $upcomingAlerts = [];
        if (isset($data['daily'])) {
            $upcomingAlerts = $this->checkUpcomingWeather($data['daily']);
        }
        
        return [
            'location' => $locationName, // Use the passed name
            'temp' => $temp,
            'condition' => $weatherInfo['condition'],
            'humidity' => $humidity,
            'wind_speed' => $windSpeed,
            'icon' => $weatherInfo['icon'],
            'alert' => $this->selectMostImportantAlert($temp, $windSpeed, $weatherCode, $upcomingAlerts)
        ];
    }

    // ... (checkUpcomingWeather)
    protected function checkUpcomingWeather($daily)
    {
        $alerts = [];
        
        // Check tomorrow's weather (index 1)
        if (isset($daily['weather_code'][1]) && isset($daily['precipitation_sum'][1])) {
            $tomorrowWeatherCode = $daily['weather_code'][1];
            $tomorrowRain = $daily['precipitation_sum'][1];
            $tomorrowWindMax = isset($daily['wind_speed_10m_max'][1]) ? $daily['wind_speed_10m_max'][1] : 0;
            
            // Check for storms/heavy rain
            if ($tomorrowWeatherCode >= 95) {
                $alerts[] = [
                    'title' => '⚠️ تنبيه عاصفة قادمة',
                    'message' => 'عواصف رعدية متوقعة غداً. يُنصح بتأمين المحاصيل والمعدات مسبقاً.',
                    'level' => 'danger',
                    'priority' => 10
                ];
            } elseif ($tomorrowWeatherCode >= 61 && $tomorrowWeatherCode <= 65) {
                $alerts[] = [
                    'title' => '🌧️ أمطار متوقعة غداً',
                    'message' => 'أمطار قادمة غداً. لا حاجة لري المحاصيل اليوم.',
                    'level' => 'info',
                    'priority' => 5
                ];
            } elseif ($tomorrowRain > 10) {
                $alerts[] = [
                    'title' => '🌧️ أمطار غزيرة متوقعة',
                    'message' => "أمطار غزيرة متوقعة غداً ({$tomorrowRain} مم). تأكد من تصريف المياه.",
                    'level' => 'warning',
                    'priority' => 7
                ];
            }
            
            // Check for strong winds tomorrow
            if ($tomorrowWindMax > 50) {
                $alerts[] = [
                    'title' => '💨 رياح قوية قادمة',
                    'message' => 'رياح قوية متوقعة غداً. يرجى تأمين البيوت المحمية والمعدات.',
                    'level' => 'warning',
                    'priority' => 8
                ];
            }
        }
        
        return $alerts;
    }

    /**
     * Check for weather alerts based on current conditions
     */
    protected function checkWeatherAlertsFromData($temp, $windSpeed, $weatherCode)
    {
        $alerts = [];

        // High wind alert
        if ($windSpeed > 25) {
            $alerts[] = [
                'title' => 'تنبيه رياح',
                'message' => 'رياح نشطة حالياً. راقب البيوت المحمية والنباتات الضعيفة.',
                'level' => 'info',
                'priority' => 4
            ];
        }

        // ... (existing alerts logic) ...

        // Default farming tip (Improved logic for 'Normal' weather)
        if (count($alerts) === 0) {
            if ($weatherCode <= 1) { // Clear/Sunny
                $alerts[] = [
                    'title' => '☀️ أجواء مشمسة',
                    'message' => 'الطقس مشمس ومثالي. ننصح بري المحاصيل باعتدال في الصباح الباكر.',
                    'level' => 'success',
                    'priority' => 1
                ];
            } elseif ($weatherCode <= 3) { // Cloudy
                $alerts[] = [
                    'title' => '🌥️ أجواء غائمة',
                    'message' => 'الطقس معتدل وغائم جزئياً. مناسب للعمل الحقلي ورش الأسمدة.',
                    'level' => 'success',
                    'priority' => 1
                ];
            } elseif ($temp >= 15 && $temp <= 30) {
                $alerts[] = [
                    'title' => '🌱 طقس مثالي',
                    'message' => 'درجات الحرارة ممتازة للنمو. استمر في برنامج العناية الاعتيادي.',
                    'level' => 'success',
                    'priority' => 1
                ];
            } else {
                 $alerts[] = [
                    'title' => '🌡️ حالة الطقس',
                    'message' => 'تابع النشرة الجوية وحافظ على ري المحاصيل حسب الحاجة.',
                    'level' => 'info',
                    'priority' => 1
                ];
            }
        }

        return $alerts[0] ?? null;
    }

    // ...



    // ...

    /**
     * Fallback weather data when API is unavailable
     */
    protected function getFallbackWeather($error = null)
    {
        return [
            'location' => 'غزة، فلسطين',
            'temp' => 22,
            'condition' => 'معتدل',
            'humidity' => 60,
            'wind_speed' => 15,
            'icon' => 'partly_cloudy_day',
            'alert' => [
                'title' => '🌱 نصيحة زراعية',
                'message' => 'الأجواء مناسبة للزراعة في غزة. حافظ على ري المحاصيل بانتظام.',
                'level' => 'success'
            ],
            'is_fallback' => true,
            'error_debug' => $error // Store error for display
        ];
    }

    /**
     * Format Open-Meteo weather data
     */


    /**
     * Check upcoming weather for the next 2 days
     */


    /**
     * Select most important alert from current and upcoming weather
     */
    protected function selectMostImportantAlert($temp, $windSpeed, $weatherCode, $upcomingAlerts)
    {
        $currentAlerts = $this->checkWeatherAlertsFromData($temp, $windSpeed, $weatherCode);
        
        // Combine current and upcoming alerts
        $allAlerts = [];
        if ($currentAlerts) {
            $allAlerts[] = array_merge($currentAlerts, ['priority' => 6]);
        }
        $allAlerts = array_merge($allAlerts, $upcomingAlerts);
        
        // Sort by priority (highest first)
        usort($allAlerts, function($a, $b) {
            return ($b['priority'] ?? 0) - ($a['priority'] ?? 0);
        });
        
        return $allAlerts[0] ?? null;
    }


    /**
     * Map Open-Meteo weather codes to Arabic conditions and icons
     */
    protected function getWeatherInfoFromCode($code)
    {
        $locale = app()->getLocale();
        
        // Open-Meteo weather codes: https://open-meteo.com/en/docs
        $weatherMap = [
            0 => [
                'condition' => $locale == 'ar' ? 'صافٍ' : 'Clear',
                'icon' => 'wb_sunny'
            ],
            1 => [
                'condition' => $locale == 'ar' ? 'صافٍ في الغالب' : 'Mostly Clear',
                'icon' => 'wb_sunny'
            ],
            2 => [
                'condition' => $locale == 'ar' ? 'غائم جزئياً' : 'Partly Cloudy',
                'icon' => 'partly_cloudy_day'
            ],
            3 => [
                'condition' => $locale == 'ar' ? 'غائم' : 'Cloudy',
                'icon' => 'cloud'
            ],
            45 => [
                'condition' => $locale == 'ar' ? 'ضباب' : 'Fog',
                'icon' => 'foggy'
            ],
            48 => [
                'condition' => $locale == 'ar' ? 'ضباب متجمد' : 'Freezing Fog',
                'icon' => 'foggy'
            ],
            51 => [
                'condition' => $locale == 'ar' ? 'رذاذ خفيف' : 'Light Drizzle',
                'icon' => 'water_drop'
            ],
            53 => [
                'condition' => $locale == 'ar' ? 'رذاذ متوسط' : 'Moderate Drizzle',
                'icon' => 'water_drop'
            ],
            55 => [
                'condition' => $locale == 'ar' ? 'رذاذ كثيف' : 'Dense Drizzle',
                'icon' => 'rainy'
            ],
            61 => [
                'condition' => $locale == 'ar' ? 'مطر خفيف' : 'Light Rain',
                'icon' => 'rainy'
            ],
            63 => [
                'condition' => $locale == 'ar' ? 'مطر متوسط' : 'Moderate Rain',
                'icon' => 'rainy'
            ],
            65 => [
                'condition' => $locale == 'ar' ? 'مطر غزير' : 'Heavy Rain',
                'icon' => 'rainy'
            ],
            71 => [
                'condition' => $locale == 'ar' ? 'ثلوج خفيفة' : 'Light Snow',
                'icon' => 'ac_unit'
            ],
            73 => [
                'condition' => $locale == 'ar' ? 'ثلوج متوسطة' : 'Moderate Snow',
                'icon' => 'ac_unit'
            ],
            75 => [
                'condition' => $locale == 'ar' ? 'ثلوج كثيفة' : 'Heavy Snow',
                'icon' => 'ac_unit'
            ],
            80 => [
                'condition' => $locale == 'ar' ? 'زخات مطر خفيفة' : 'Light Rain Showers',
                'icon' => 'rainy'
            ],
            81 => [
                'condition' => $locale == 'ar' ? 'زخات مطر متوسطة' : 'Moderate Rain Showers',
                'icon' => 'rainy'
            ],
            82 => [
                'condition' => $locale == 'ar' ? 'زخات مطر عنيفة' : 'Violent Rain Showers',
                'icon' => 'rainy'
            ],
            95 => [
                'condition' => $locale == 'ar' ? 'عاصفة رعدية' : 'Thunderstorm',
                'icon' => 'thunderstorm'
            ],
            96 => [
                'condition' => $locale == 'ar' ? 'عاصفة رعدية مع برد' : 'Thunderstorm with Hail',
                'icon' => 'thunderstorm'
            ],
            99 => [
                'condition' => $locale == 'ar' ? 'عاصفة رعدية مع برد كثيف' : 'Thunderstorm with Heavy Hail',
                'icon' => 'thunderstorm'
            ],
        ];

        return $weatherMap[$code] ?? [
            'condition' => $locale == 'ar' ? 'صحو' : 'Fair',
            'icon' => 'wb_sunny'
        ];
    }

    /**
     * Check for weather alerts based on current conditions
     */


    /**
     * Get appropriate weather icon
     */
    protected function getWeatherIcon($condition)
    {
        $icons = [
            'Clear' => 'wb_sunny',
            'Clouds' => 'cloud',
            'Rain' => 'rainy',
            'Drizzle' => 'water_drop',
            'Thunderstorm' => 'thunderstorm',
            'Snow' => 'ac_unit',
            'Mist' => 'mist',
            'Fog' => 'foggy',
        ];

        return $icons[$condition] ?? 'wb_sunny';
    }

    /**
     * Check for weather alerts based on conditions
     */
    protected function checkWeatherAlerts($data)
    {
        $alerts = [];

        // High wind alert
        if ($data['wind']['speed'] > 10) { // > 36 km/h
            $alerts[] = [
                'title' => 'تنبيه رياح قوية',
                'message' => 'رياح قوية متوقعة. يرجى تأمين البيوت المحمية والمعدات الخفيفة.',
                'level' => 'warning'
            ];
        }

        // High temperature alert
        if ($data['main']['temp'] > 40) {
            $alerts[] = [
                'title' => 'تنبيه حرارة مرتفعة',
                'message' => 'درجات حرارة مرتفعة جداً. تأكد من ري المحاصيل بشكل كافٍ.',
                'level' => 'warning'
            ];
        }

        // Rain alert
        if (isset($data['rain']) && $data['rain']['1h'] > 0) {
            $alerts[] = [
                'title' => 'تنبيه أمطار',
                'message' => 'أمطار متوقعة. قد لا تحتاج المحاصيل للري اليوم.',
                'level' => 'info'
            ];
        }

        return $alerts[0] ?? null;
    }


}
