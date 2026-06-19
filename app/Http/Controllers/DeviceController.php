<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use Carbon\Carbon;

class DeviceController extends Controller
{
    /**
     * Redirect to the sensor dashboard where devices are shown.
     */
    public function index()
    {
        return redirect()->route('sensor.index');
    }

    /**
     * Redirect to the sensor dashboard.
     */
    public function create()
    {
        return redirect()->route('sensor.index');
    }

    /**
     * Store a newly created device (AJAX-friendly).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:devices,serial_number',
            'topic' => 'required|string|max:255',
            'wokwi_url' => 'nullable|string',
        ]);

        $device = Device::create([
            'name' => $validated['name'],
            'serial_number' => trim($validated['serial_number']),
            'topic' => trim($validated['topic']),
            'wokwi_url' => $validated['wokwi_url'] ? trim($validated['wokwi_url']) : null,
            'time' => now()->format('H:i:s'), // fallback for legacy column
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Device successfully added',
                'device' => $device
            ]);
        }

        return redirect()->route('sensor.index')->with('success', 'Device successfully added');
    }

    /**
     * Update an existing device.
     */
    public function update(Request $request, $id)
    {
        $device = Device::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:devices,serial_number,' . $id,
            'topic' => 'required|string|max:255',
            'wokwi_url' => 'nullable|string',
        ]);

        $device->update([
            'name' => $validated['name'],
            'serial_number' => trim($validated['serial_number']),
            'topic' => trim($validated['topic']),
            'wokwi_url' => $validated['wokwi_url'] ? trim($validated['wokwi_url']) : null,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Device successfully updated',
                'device' => $device
            ]);
        }

        return redirect()->route('sensor.index')->with('success', 'Device successfully updated');
    }

    /**
     * Remove the specified device.
     */
    public function destroy(Request $request, $id)
    {
        $device = Device::findOrFail($id);
        $device->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Device successfully deleted'
            ]);
        }

        return redirect()->route('sensor.index')->with('success', 'Device successfully deleted');
    }

    /**
     * Fetch devices status for live polling.
     */
    public function statusApi()
    {
        $devices = Device::all();
        $now = now();
        $thresholdSeconds = 30; // Device is online if heartbeat received within 30 seconds

        $formattedDevices = $devices->map(function ($device) use ($now, $thresholdSeconds) {
            $isOnline = false;
            $lastSeenFormatted = 'Offline';
            $lastSeenTime = 'Belum pernah';

            if ($device->last_seen) {
                $lastSeen = Carbon::parse($device->last_seen);
                $diffInSeconds = $now->diffInSeconds($lastSeen);
                
                if ($diffInSeconds <= $thresholdSeconds) {
                    $isOnline = true;
                }
                
                $lastSeenFormatted = $lastSeen->timezone('Asia/Jakarta')->diffForHumans();
                $lastSeenTime = $lastSeen->timezone('Asia/Jakarta')->format('d M Y, H:i:s');
            }

            return [
                'id' => $device->id,
                'name' => $device->name,
                'serial_number' => $device->serial_number,
                'topic' => $device->topic,
                'wokwi_url' => $device->wokwi_url,
                'is_online' => $isOnline,
                'last_seen_formatted' => $lastSeenFormatted,
                'last_seen_time' => $lastSeenTime,
            ];
        });

        return response()->json([
            'success' => true,
            'devices' => $formattedDevices
        ]);
    }
}