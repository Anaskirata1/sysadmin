<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SysAdmin Panel</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- إضافة مكتبة FontAwesome للأيقونات -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: #ecf0f1;
            height: 100vh;
            padding: 20px;
            transition: all 0.3s ease;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 22px;
            letter-spacing: 1px;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            color: #ecf0f1;
            padding: 12px;
            text-decoration: none;
            margin-bottom: 8px;
            border-radius: 4px;
            transition: background 0.3s, transform 0.2s;
        }
        .sidebar a i {
            margin-right: 10px;
            font-size: 16px;
            transition: transform 0.3s;
        }
        .sidebar a:hover {
            background: #34495e;
            transform: translateX(5px);
        }
        .sidebar a:hover i {
            transform: rotate(20deg);
        }
        .content {
            flex: 1;
            padding: 20px;
            animation: fadeIn 0.5s ease-in-out;
        }
        /* حركة دخول المحتوى */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2><i class="fas fa-tools"></i> SysAdmin</h2>
        <a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <!-- <a href=""><i class="fas fa-server"></i> Servers</a> -->
        <!-- <a href=""><i class="fas fa-users"></i> Users</a> -->
        <!-- <a href=""><i class="fas fa-cogs"></i> Settings</a> -->
        <!-- <a href=""><i class="fas fa-cogs"></i> Apache2 Ports </a> -->
        <a href="{{ route('ports.list') }}"><i class="fas fa-server"></i> Apache2 Ports</a>
        <a href="{{ route('firewall.index') }}"><i class="fas fa-shield-alt"></i> Firewall</a>
        <a href="{{ route('database.index') }}"><i class="fas fa-database"></i> Database</a>
        <a href="{{ route('ssh.index') }}"><i class="fas fa-key"></i> SSH</a>
        <a href="{{ route('users.groups.index') }}"><i class="fas fa-key"></i> Users-Ubuntu</a>


    </div>
    <div class="content">
        @yield('content')
    </div>
</body>
</html>