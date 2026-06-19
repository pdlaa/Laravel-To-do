<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MqttService;
use App\Models\Sensors;
use Illuminate\Support\Facades\Log;

class MqttController extends Controller
{
    protected MqttService $mqttService;

    public function __construct(MqttService $mqttService)
    {
        $this->mqttService = $mqttService;
    }

    /**
     * Publish a message to a topic from the web interface.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function publish(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string|max:255',
            'message' => 'required|string|max:255'
        ]);

        $topic = trim($validated['topic']);
        $message = trim($validated['message']);

        // Enforce safe topic names for our IoT devices (servo, lcd, or slash-prefixed)
        $allowedTopics = ['servo', 'lcd', '/servo', '/lcd', 'iot/servo', 'iot/lcd'];
        if (!in_array($topic, $allowedTopics)) {
            return response()->json([
                'success' => false,
                'message' => 'Topic not allowed. Only servo and lcd topics are allowed.'
            ], 403);
        }

        // Map simple/legacy topics to the actual custom IoT topics
        if ($topic === 'servo' || $topic === '/servo') {
            $topic = 'iot/servo';
        } elseif ($topic === 'lcd' || $topic === '/lcd') {
            $topic = 'iot/lcd';
        }

        try {
            $this->mqttService->publish($topic, $message);
            return response()->json([
                'success' => true,
                'message' => "Successfully published '{$message}' to topic '{$topic}'"
            ]);
        } catch (\Exception $e) {
            Log::error("Web Publish Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to publish to MQTT broker: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch the latest sensor readings for real-time dashboard updates.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function latestData()
    {
        // Get the latest temperature (suhu) reading
        $latestSuhu = Sensors::where('nama_sensor', 'suhu')
            ->latest()
            ->first();

        // Get the latest humidity (kelembapan) reading
        $latestKelembapan = Sensors::where('nama_sensor', 'kelembapan')
            ->latest()
            ->first();

        return response()->json([
            'suhu' => $latestSuhu ? floatval($latestSuhu->data) : null,
            'kelembapan' => $latestKelembapan ? floatval($latestKelembapan->data) : null,
            'suhu_updated' => $latestSuhu ? $latestSuhu->created_at->timezone('Asia/Jakarta')->format('H:i:s') : 'N/A',
            'kelembapan_updated' => $latestKelembapan ? $latestKelembapan->created_at->timezone('Asia/Jakarta')->format('H:i:s') : 'N/A',
            'suhu_relative' => $latestSuhu ? $latestSuhu->created_at->diffForHumans() : 'No data',
            'kelembapan_relative' => $latestKelembapan ? $latestKelembapan->created_at->diffForHumans() : 'No data'
        ]);
    }
}
