<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MqttService;
use App\Models\Sensors;
use App\Models\Device;
use Illuminate\Support\Facades\Log;

class MqttSubscribeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to suhu and kelembapan topics on the MQTT broker and save data to the database';

    /**
     * Execute the console command.
     */
    public function handle(MqttService $mqttService): int
    {
        $this->info("Starting MQTT Subscriber Daemon...");
        Log::info("MQTT Subscriber command started.");

        while (true) {
            try {
                $this->info("Attempting to connect to MQTT Broker...");
                $mqtt = $mqttService->getSubscriberClient();
                $this->info("Successfully connected to MQTT Broker!");

                // Handler function for suhu data
                $suhuHandler = function (string $topic, string $message) {
                    $this->saveSensorData('suhu', $message);
                    $this->updateDeviceStatus($topic);
                };

                // Handler function for kelembapan data
                $kelembapanHandler = function (string $topic, string $message) {
                    $this->saveSensorData('kelembapan', $message);
                    $this->updateDeviceStatus($topic);
                };

                // Handler function for status/heartbeat data
                $statusHandler = function (string $topic, string $message) {
                    $this->handleStatusMessage($message);
                };

                // Subscribe to suhu topics
                $mqtt->subscribe('iot/suhu', $suhuHandler, 0);
                $mqtt->subscribe('suhu', $suhuHandler, 0);

                // Subscribe to kelembapan topics
                $mqtt->subscribe('iot/kelembaban', $kelembapanHandler, 0);
                $mqtt->subscribe('kelembapan', $kelembapanHandler, 0);

                // Subscribe to status/heartbeat topics
                $mqtt->subscribe('iot/status', $statusHandler, 0);
                $mqtt->subscribe('status', $statusHandler, 0);

                $this->info("Subscribed to 'iot/suhu', 'iot/kelembaban', and status topics. Listening for messages...");

                // Run the loop to keep the process alive and process incoming messages
                $mqtt->loop(true);

            } catch (\Exception $e) {
                $this->error("MQTT Subscriber encountered an error: " . $e->getMessage());
                Log::error("MQTT Subscriber daemon error: " . $e->getMessage());
                $this->warn("Attempting reconnection in 5 seconds...");
                sleep(5);
            }
        }

        return Command::SUCCESS;
    }

    /**
     * Parse and save the sensor data to the database.
     *
     * @param string $sensorName
     * @param string $message
     * @return void
     */
    protected function saveSensorData(string $sensorName, string $message): void
    {
        $this->line("Received message on topic: [{$sensorName}] => {$message}");
        Log::info("MQTT Received: [{$sensorName}] => {$message}");

        // Filter and sanitize the message to ensure it's numeric
        $dataVal = trim($message);
        if (is_numeric($dataVal)) {
            try {
                Sensors::create([
                    'nama_sensor' => $sensorName,
                    'data' => $dataVal,
                    'status' => true
                ]);
                $this->info("Saved to database: {$sensorName} = {$dataVal}");
            } catch (\Exception $e) {
                $this->error("Failed to save data: " . $e->getMessage());
                Log::error("Failed saving MQTT data to database: " . $e->getMessage());
            }
        } else {
            $this->warn("Skipping non-numeric payload: '{$dataVal}'");
        }
    }

    /**
     * Update the last seen timestamp of devices matching the given topic.
     *
     * @param string $topic
     * @return void
     */
    protected function updateDeviceStatus(string $topic): void
    {
        try {
            $updated = Device::where('topic', $topic)->update([
                'last_seen' => now()
            ]);
            
            if ($updated > 0) {
                $this->line("Updated last_seen for devices on topic: {$topic}");
            }
        } catch (\Exception $e) {
            $this->error("Failed to update device status for topic {$topic}: " . $e->getMessage());
            Log::error("Failed to update device status for topic {$topic}: " . $e->getMessage());
        }
    }

    /**
     * Handle incoming status / heartbeat messages.
     *
     * @param string $message
     * @return void
     */
    protected function handleStatusMessage(string $message): void
    {
        $payload = json_decode($message, true);
        $serialNumber = null;
        
        if (is_array($payload) && isset($payload['serial_number'])) {
            $serialNumber = $payload['serial_number'];
        } else {
            // fallback to raw message as serial number
            $serialNumber = trim($message);
        }
        
        if (!empty($serialNumber)) {
            try {
                $updated = Device::where('serial_number', $serialNumber)->update([
                    'last_seen' => now()
                ]);
                
                if ($updated > 0) {
                    $this->info("Received heartbeat from device: {$serialNumber}");
                    Log::info("MQTT Heartbeat: {$serialNumber}");
                } else {
                    $this->line("Received heartbeat from unregistered device: {$serialNumber}");
                }
            } catch (\Exception $e) {
                $this->error("Failed to update device status for serial number {$serialNumber}: " . $e->getMessage());
                Log::error("Failed to update device status for serial number {$serialNumber}: " . $e->getMessage());
            }
        }
    }
}
