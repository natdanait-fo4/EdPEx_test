<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กำลังออกจากระบบ...</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f7f9fc;
            color: #333;
        }
        .container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border-left-color: #7e059c;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="spinner"></div>
        <h2>กำลังออกจากระบบและปิดหน้าต่าง...</h2>
        <p>หากเบราว์เซอร์ไม่ปิดหน้าต่างโดยอัตโนมัติ ระบบจะนำคุณไปยังหน้าหลักของมหาวิทยาลัย</p>
    </div>

    <script>
        // Try closing the tab/window
        window.close();
        
        // Fallback: redirect if close fails
        setTimeout(function() {
            window.location.href = '{{ url("/") }}';
        }, 1000);
    </script>
</body>
</html>
