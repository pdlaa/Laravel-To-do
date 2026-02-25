<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sensors;

class SensorController extends Controller
{
   
    public function index()
    {
        //$sensors = DB::table('sensors')->get();
       // return view('sensor', compact('sensors'));
       $sensors = Sensors::all();
    }

    
    public function create()
    {
        return view('tambah');
    }

   
    public function store(Request $request)
    {
       /* $request->validate([
            'nama_sensor' => 'required',
            'data' => 'required|numeric',
        ]);*/

        /*DB::table('sensors')->insert([
            'nama_sensor' => $request->nama_sensor,
            'data' => $request->data,
            'status' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);*/

        Sensors::create([
            'nama_sensor' => $request->nama_sensor,
            'data' => $request->data,
            'created_at' => now(),
            'updated_at' => now(),
        ]

        );

        return redirect('/sensor')->with('success', 'Data sensor berhasil ditambahkan');
    }

    
    public function edit($id)
    {
        $sensor = DB::table('sensors')->where('id', $id)->first();
        return view('edit', compact('sensor'));
    }

    public function update(Request $request, $id)
{
    $sensor = Sensors::findOrFail($id);
    $sensor->update([
        'nama_sensor' => $request->nama_sensor,
        'data' => $request->data,
    ]);
    /*$request->validate([
        'nama_sensor' => 'required',
        'data' => 'required|numeric',
    ]);

    /*DB::table('sensors')
        ->where('id', $id)
        ->update([
            'nama_sensor' => $request->input('nama_sensor'),
            'data' => $request->input('data'),
            'updated_at' => now(),
        ]);*/

    return response()->json([
        'message' => 'Sensor berhasil diupdate'
    ]);
}

      public function delete($id)
    {
        $sensor = Sensors::findOrFail($id);
        $sensor->delete();
        return 'Berhasil menghapus data';

        //DB::table('sensors')
           // ->where('id', $id)
            //->delete();
            //return 'Berhasil menghapus data';
    }
}

