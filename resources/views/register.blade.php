<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-Order Registration - AksaraWalk</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* (Seluruh CSS dari file register.html Anda diletakkan di sini) */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: url("{{ asset('img/pre-background.gif') }}") no-repeat center center fixed; background-size: cover; color: white; display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 100vh; text-align: center; padding: 20px; }
        .container { background: rgba(255, 255, 255, 0.15); padding: 25px; border-radius: 12px; width: 100%; max-width: 400px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3); backdrop-filter: blur(10px); }
        .title { font-size: 2.5em; text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5); }
        .subtitle { font-size: 1.5em; color: #FFD700; text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5); margin-bottom: 20px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid transparent; border-radius: 8px; font-size: 1em; outline: none; background: rgba(255, 255, 255, 0.3); color: white; }
        button { width: 100%; padding: 12px; border: none; border-radius: 8px; font-size: 1em; font-weight: 600; cursor: pointer; background: linear-gradient(45deg, #800080, #9400D3); color: white; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: left; }
        .alert-danger { background-color: #721c24; color: #f8d7da; border: 1px solid #f5c6cb; }
        .alert-danger ul { margin-left: 20px; }
    </style>
</head>
<body>
    <h1 class="title">AksaraWalk</h1>
    <h2 class="subtitle">Limited Edition Pre-Order</h2>

    <div class="container">
        <h2>Pre-Order Registration</h2>

        {{-- Menampilkan error validasi dari Laravel --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops! Ada beberapa masalah:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST">
            @csrf  {{-- Token keamanan wajib untuk semua form di Laravel --}}
            <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required>
            <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
            <input type="text" name="phone" placeholder="Phone Number" value="{{ old('phone') }}" required>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>