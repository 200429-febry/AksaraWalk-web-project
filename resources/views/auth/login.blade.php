<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>JOIN AKSARA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #f8fafc, #e2e8f0);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            color: #2d3748;
            text-align: center;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 15px;
            margin-top: 8px;
            margin-bottom: 20px;
            border: 1px solid #cbd5e0;
            border-radius: 6px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>JOIN AKSARA</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">MASUK</button>
        </form>
    </div>
</body>
</html>
