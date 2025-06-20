<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to AksaraWalk</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <style>
        body {
            background: url('pre-background.jpg') no-repeat center center fixed; /* Using the same background as register */
            background-size: cover;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .container {
            background: rgba(255, 255, 255, 0.15);
            padding: 25px;
            border-radius: 12px;
            width: 350px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            outline: none;
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }
        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            background: linear-gradient(45deg, #800080, #9400D3);
            color: white;
            transition: 0.3s;
        }
        button:hover {
            background: linear-gradient(45deg, #9400D3, #800080);
            transform: scale(1.05);
        }
        .message { padding: 10px; margin-bottom: 15px; border-radius: 5px; font-weight: 500; }
        .error { background-color: #dc3545; color: white; }
    </style>
</head>
<body>
    <h1 class="title">AksaraWalk</h1>
    <h2 class="subtitle">Welcome Back!</h2>

    <div class="container">
        <h2>Login to Your Account</h2>

        <?php
        session_start();
        if (isset($_SESSION['login_error'])) {
            echo '<div class="message error">' . htmlspecialchars($_SESSION['login_error']) . '</div>';
            unset($_SESSION['login_error']);
        }
        ?>

        <form action="proses-login.php" method="post">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p style="margin-top: 15px; font-size: 0.9em; color: rgba(255,255,255,0.8);">Belum punya akun? <a href="register.php" style="color: #FFD700; text-decoration: underline;">Daftar di sini</a></p>
    </div>
</body>
</html>