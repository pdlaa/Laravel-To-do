<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MqttService;

class MqttPublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:publish {topic : The topic to publish the message to} {message : The message payload to be sent}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a message payload to a specific MQTT topic';

    /**
     * Execute the console command.
     */
    public function handle(MqttService $mqttService): int
    {
        $topic = $this->argument('topic');
        $message = $this->argument('message');

        $this->info("Publishing message to topic '{$topic}'...");
        $this->line("Payload: '{$message}'");

        try {
            $mqttService->publish($topic, $message);
            $this->info("Successfully published message!");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to publish message: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
