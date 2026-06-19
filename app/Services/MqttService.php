<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use Illuminate\Support\Facades\Log;

class MqttService
{
    protected string $host;
    protected int $port;
    protected ?string $username;
    protected ?string $password;
    protected string $clientIdPub;
    protected string $clientIdSub;

    public function __construct()
    {
        $this->host = config('mqtt.host');
        $this->port = config('mqtt.port');
        $this->username = config('mqtt.username');
        $this->password = config('mqtt.password');
        $this->clientIdPub = config('mqtt.client_id_pub');
        $this->clientIdSub = config('mqtt.client_id_sub');
    }

    /**
     * Publish a message to a topic.
     *
     * @param string $topic
     * @param string $message
     * @return void
     */
    public function publish(string $topic, string $message): void
    {
        try {
            $mqtt = new MqttClient($this->host, $this->port, $this->clientIdPub);
            $settings = (new ConnectionSettings)
                ->setUsername($this->username)
                ->setPassword($this->password);

            $mqtt->connect($settings);
            $mqtt->publish($topic, $message, 0); // QoS 0
            $mqtt->disconnect();
            
            Log::info("MQTT Published: [{$topic}] => {$message}");
        } catch (\Exception $e) {
            Log::error("MQTT Publish Failed: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get a connected MQTT client instance for subscription.
     *
     * @return MqttClient
     */
    public function getSubscriberClient(): MqttClient
    {
        try {
            $mqtt = new MqttClient($this->host, $this->port, $this->clientIdSub);
            $settings = (new ConnectionSettings)
                ->setUsername($this->username)
                ->setPassword($this->password)
                ->setKeepAliveInterval(60);

            $mqtt->connect($settings);
            return $mqtt;
        } catch (\Exception $e) {
            Log::error("MQTT Subscriber Connection Failed: " . $e->getMessage());
            throw $e;
        }
    }
}
