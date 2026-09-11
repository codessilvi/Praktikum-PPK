<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jarak')</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 480px; margin: 40px auto; padding: 0 16px; }
        input, button { width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box; }
        button { background: #333; color: #fff; border: none; cursor: pointer; }
        .error { color: red; font-size: 13px; margin: -6px 0 10px; }
        .task { border: 1px solid #ccc; padding: 8px; margin-bottom: 8px; border-radius: 4px; }
        .status-done { text-decoration: line-through; color: #888; }
        nav { display: flex; justify-content: space-between; margin-bottom: 20px; }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
