<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Member Dashboard - POJ Music Club')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .glass-morphism {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(234, 179, 8, 0.2);
        }
        .sidebar-link {
            color: #facc15;
            transition: all 0.2s ease;
        }
        .sidebar-link:hover {
            background: rgba(234, 179, 8, 0.2);
            color: #fbbf24;
        }
    </style>

    @stack('head')
</head>
<body class="bg-gray-900 text-white flex min-h-screen">

    {{-- Sidebar (only if logged in) --}}
    @auth('member')
    <aside class="w-64 p-6 bg-gray-800 glass-morphism flex flex-col">
        <div class="text-yellow-400 text-2xl font-bold mb-6">POJ Music</div>
        <nav class="flex-1 flex flex-col space-y-2">
            <a href="{{ route('member.dashboard') }}" class="sidebar-link px-4 py-2 rounded-lg flex items-center gap-3 font-medium transition-all">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('member.profile') }}" class="sidebar-link px-4 py-2 rounded-lg flex items-center gap-3 font-medium transition-all">
                <i class="fas fa-user"></i> Profile
            </a>
            <a href="{{ route('member.change-password') }}" class="sidebar-link px-4 py-2 rounded-lg flex items-center gap-3 font-medium transition-all">
                <i class="fas fa-key"></i> Change Password
            </a>
            <a href="{{ route('member.pay-fee') }}" class="sidebar-link px-4 py-2 rounded-lg flex items-center gap-3 font-medium transition-all">
                <i class="fas fa-money-bill-wave"></i> Pay Fee
            </a>
            <a href="{{ route('member.check-payments') }}" class="sidebar-link px-4 py-2 rounded-lg flex items-center gap-3 font-medium transition-all">
                <i class="fas fa-receipt"></i> Check Payments
            </a>
            <a href="{{ route('member.export') }}" class="sidebar-link px-4 py-2 rounded-lg flex items-center gap-3 font-medium transition-all">
                <i class="fas fa-file-export"></i> Export Data
            </a>
        </nav>

        <form action="{{ route('member.logout') }}" method="POST" class="mt-auto">
            @csrf
            <button type="submit" class="w-full px-4 py-2 rounded-lg bg-red-600 hover:bg-red-500 text-black font-bold transition-all">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </aside>
    @endauth

    {{-- Main Content --}}
    <div class="@auth('member') flex-1 p-8 overflow-y-auto @else w-full flex items-center justify-center p-8 @endauth">
        @yield('content')
    </div>

</body>
</html>
