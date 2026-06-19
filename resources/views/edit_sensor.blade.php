@extends('layouts.app')

@section('title', 'Edit Sensor')

@section('content')

<div class="form-wrapper">

    <div class="form-card">

        <h1 class="form-title">
            Edit Data Sensor
        </h1>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('sensor.update', $sensor->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Sensor</label>
                <input
                    type="text"
                    name="nama_sensor"
                    placeholder="Contoh: Sensor Suhu"
                    value="{{ old('nama_sensor', $sensor->nama_sensor) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label>Nilai Sensor</label>
                <input
                    type="number"
                    name="data"
                    placeholder="Contoh: 25"
                    value="{{ old('data', $sensor->data) }}"
                    required
                >
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-primary">
                    Update Data
                </button>
                <a href="{{ route('sensor.index') }}" class="btn-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<style>
/* CENTER */
.form-wrapper {
    min-height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* CARD */
.form-card {
    background: white;
    padding: 35px;
    border-radius: 14px;
    width: 420px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* TITLE */
.form-title {
    font-size: 26px;
    font-weight: 700;
    margin-bottom: 25px;
    color: #1e293b;
}

/* INPUT */
.form-group {
    margin-bottom: 20px;
}

label {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
    color: #334155;
}

input {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    font-size: 14px;
    transition: 0.2s;
}

input:focus {
    outline: none;
    border-color: #004cff;
    box-shadow: 0 0 0 2px rgba(0,76,255,0.15);
}

/* BUTTON */
.btn-group {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.btn-primary {
    flex: 1;
    padding: 12px;
    background: linear-gradient(135deg, #004cff, #0037b3);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

.btn-secondary {
    flex: 1;
    padding: 12px;
    background: white;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    color: #334155;
    font-weight: 600;
}

.btn-primary:hover {
    opacity: 0.9;
}

.btn-secondary:hover {
    background: #f1f5f9;
}

/* ERROR */
.error-box {
    background: #fee2e2;
    color: #b91c1c;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

.error-box ul {
    margin: 0;
    padding-left: 18px;
}
</style>
@endsection
