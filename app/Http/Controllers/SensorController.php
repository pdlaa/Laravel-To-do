<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sensors;
use App\Models\Device;

class SensorController extends Controller
{

    /* ===============================
       INDEX
    =============================== */

    public function index()
    {
        $sensors = Sensors::all();
        $devices = Device::all();

        return view('sensor', compact('sensors', 'devices'));
    }


    /* ===============================
       FORM TAMBAH
    =============================== */

    public function create()
    {
        return view('tambah');
    }


   

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sensor' => 'required|string|max:255',
            'data' => 'required|numeric|min:0'
        ], [
            'nama_sensor.required' => 'Nama sensor wajib diisi.',
            'data.required' => 'Nilai sensor wajib diisi.',
            'data.numeric' => 'Nilai harus berupa angka.'
        ]);


        Sensors::create([
            'nama_sensor' => $validated['nama_sensor'],
            'data' => $validated['data'],
        ]);


        return redirect()
            ->route('sensor.index')
            ->with('success', 'Data sensor berhasil ditambahkan');
    }


    /* ===============================
       EDIT
    =============================== */

    public function edit($id)
    {
        $sensor = Sensors::findOrFail($id);

        return view('edit_sensor', compact('sensor'));
    }


    /* ===============================
       UPDATE
    =============================== */

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_sensor' => 'required|string|max:255',
            'data' => 'required|numeric|min:0'
        ]);

        $sensor = Sensors::findOrFail($id);

        $sensor->update([
            'nama_sensor' => $validated['nama_sensor'],
            'data' => $validated['data'],
        ]);

        return redirect()
            ->route('sensor.index')
            ->with('success', 'Sensor berhasil diupdate');
    }


    /* ===============================
       DELETE
    =============================== */

    public function delete($id)
    {
        $sensor = Sensors::findOrFail($id);

        $sensor->delete();

        return redirect()
            ->route('sensor.index')
            ->with('success', 'Sensor berhasil dihapus');
    }

}