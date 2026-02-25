<div class="page-center">
    <form method="POST" action="/sensor/store" class="sensor-form">
        @csrf

        <div class="form-group">
            <label>Nama Sensor</label>
            <input type="text" name="nama_sensor" placeholder="Contoh: Sensor Suhu" required>
        </div>

        <div class="form-group">
            <label>Nilai Sensor</label>
            <input type="number" name="data" placeholder="Contoh: 25" required>
        </div>

        <button type="submit" class="btn">Simpan Data</button>
    </form>
</div>

<style>
.page-center {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f1f5f9;
}

.sensor-form {
    background: white;
    padding: 30px;
    border-radius: 14px;
    width: 360px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.form-group {
    margin-bottom: 18px;
}

label {
    font-size: 14px;
    margin-bottom: 6px;
    display: block;
    color: #334155;
}

input {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #cbd5f5;
    font-size: 14px;
}

input:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,0.15);
}

.btn {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

.btn:hover {
    opacity: 0.9;
}
</style>
