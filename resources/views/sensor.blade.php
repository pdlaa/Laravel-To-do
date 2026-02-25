<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Sensor</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }

        .navbar {
            background-color: #0f172a;
            color: white;
            padding: 15px 30px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
            font-size: 14px;
        }

        .container {
            padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .btn {
            background-color: #2563eb;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background-color: #f8fafc;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="/sensor">Sensor</a>
    <a href="/">Home</a>
</div>

<div class="container">
    <div class="header">
        <h1>Data Sensor</h1>
        <a href="/tambah" class="btn">+ Tambah Data Sensor</a>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Nama Sensor</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @if ($sensors->isEmpty())
                    <tr>
                        <td colspan="2">Belum ada data sensor</td>
                    </tr>
                @else
                    @foreach ($sensors as $sensor)
                        <tr>
                            <td>{{ $sensor->nama_sensor }}</td>
                            <td>{{ $sensor->data }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
