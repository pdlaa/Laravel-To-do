<?php

return [
    'host' => env('MQTT_HOST', 'public.shiftr.io'),
    'port' => (int) env('MQTT_PORT', 1883),
    'username' => env('MQTT_USERNAME', ''),
    'password' => env('MQTT_PASSWORD', ''),
    'client_id_sub' => env('MQTT_CLIENT_ID_SUB', 'siot_dash_sub'),
    'client_id_pub' => env('MQTT_CLIENT_ID_PUB', 'siot_dash_pub'),
];
