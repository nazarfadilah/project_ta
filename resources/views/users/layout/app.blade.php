<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPRASA')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 240px;
            --sidebar-collapsed-width: 64px;
            --navbar-height: 50px;
            --gold-primary: #C9A961;
            --gold-dark: #B8953F;
            --gold-light: #D4BA7A;
            --sidebar-text: #3a3a3a;
            --sidebar-hover: rgba(0,0,0,0.08);
            --sidebar-active: rgba(0,0,0,0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            display: flex;
            flex-direction: column;
        }

        .app-container {
            display: flex;
            flex: 1;
        }

        .app-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        #sidebar.collapsed ~ .app-content {
            margin-left: var(--sidebar-collapsed-width);
        }

        .app-main {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }
    </style>
    @yield('css')
</head>
<body>
    <div class="app-container">
        @include('users.layout.navbar')
        
        <div class="app-content">
            <main class="app-main">
                @yield('content')
            </main>
            
            @include('users.layout.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('js')
</body>
</html>
