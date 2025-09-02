<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="5;url=https://www.google.co.jp">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invalid&#160;Access</title>
    <style>
        body {
            background-color: #f8d7da;
            color: #721c24;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
            padding: 50px;
        }

        .container {
            background-color: #f5c6cb;
            border: 1px solid #f1b0b7;
            border-radius: 8px;
            padding: 30px;
            max-width: 600px;
            margin: auto;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        p {
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Invalid&#160;Access</h1>
        <p>不正なアクセスが検出されました。</p>
        <p>5秒後に 初期画面 にリダイレクトされます。</p>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = "{{ config('app.url') }}";
        }, 5000);
    </script>
</body>
</html>
