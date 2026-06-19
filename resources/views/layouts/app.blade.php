<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>@yield('title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f1f5f9;
            color: #000000;
        }

       
        .navbar {
            background-color: #004cff;
            padding: 16px 30px;
            display: flex;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .navbar a:hover {
            opacity: 0.8;
        }

        
        .container {
            padding: 40px;
            max-width: 1000px;
            margin: auto;
        }

       
        .card {
            background: white;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

      
        .btn {
            background: linear-gradient(135deg, #a600ff, #1d4ed8);
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            opacity: 0.9;
        }

       
        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .auth-wrapper {
    min-height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.auth-card {
    width: 400px;
    background: white;
    padding: 35px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
}

.bottom-text {
    margin-top: 20px;
    font-size: 14px;
}

.error-box {
    background: #fee2e2;
    color: #b91c1c;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
}

    </style>

</head>

<body>

    
    <div class="navbar">
        <a href="/sensor">Sensor 88</a>
        <a href="/">Home</a>
    </div>


    
    <div class="container">

        
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        
        @yield('content')

    </div>

</body>

</html>