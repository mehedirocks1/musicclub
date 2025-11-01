<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Member Panel</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @filamentStyles
  <style>
    body {
      background-color: #f9fafb; /* light background */
      color: #1f2937; /* dark text */
      font-family: 'Inter', sans-serif;
    }

    a {
      text-decoration: none;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background: #ffffff;
      border-radius: 1rem 0 0 1rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
      padding: 2rem 1rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .sidebar h1 {
      font-size: 1.75rem;
      font-weight: 700;
      color: #f59e0b; /* amber */
      margin-bottom: 2rem;
      text-align: center;
    }

    .sidebar nav a {
      display: flex;
      align-items: center;
      padding: 0.75rem 1rem;
      border-radius: 0.5rem;
      color: #374151;
      font-weight: 500;
      margin-bottom: 0.5rem;
      transition: all 0.3s;
    }

    .sidebar nav a:hover {
      background-color: #fef3c7; /* soft amber */
      color: #b45309;
      transform: translateX(5px);
    }

    .sidebar nav a.active {
      background-color: #f59e0b; /* amber highlight */
      color: white;
    }

    .logout-btn {
      padding: 0.75rem 1rem;
      color: #dc2626;
      font-weight: 500;
      border-radius: 0.5rem;
      margin-top: 1rem;
      transition: all 0.3s;
    }

    .logout-btn:hover {
      background-color: #fee2e2;
      color: #b91c1c;
      transform: translateX(3px);
    }

    /* Cards */
    .card {
      background-color: #ffffff;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
      transition: all 0.3s;
    }

    .card:hover {
      box-shadow: 0 8px 25px rgba(0,0,0,0.08);
      transform: translateY(-2px);
    }

    /* Header */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .header h2 {
      font-size: 2rem;
      font-weight: 700;
      color: #111827;
    }

    .header .user-info {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .header .user-info img {
      width: 40px;
      height: 40px;
      border-radius: 9999px;
      border: 1px solid #d1d5db;
    }

    .header .user-info span {
      font-weight: 500;
      color: #4b5563;
    }

  </style>
</head>
<body>

<div class="flex min-h-screen">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div>
      <h1>Member Panel</h1>
      <nav>
        <a href="{{ route('member.dashboard') }}" class="active">Dashboard</a>
        <a href="{{ route('member.profile') }}">Profile</a>
        <a href="{{ route('member.change-password') }}">Change Password</a>
        <a href="{{ route('member.pay-fee') }}">Pay Fee</a>
        <a href="{{ route('member.check-payments') }}">Check Payments</a>
      </nav>
    </div>
    <form method="POST" action="{{ route('member.logout') }}">
      @csrf
      <button type="submit" class="logout-btn">Logout</button>
    </form>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-8">
    <div class="max-w-7xl mx-auto">

      <!-- Header -->
      <div class="header">
        <h2>Welcome, {{ Auth::user()->name }}</h2>
        <div class="user-info">
          <img src="{{ Auth::user()->avatar ?? 'https://i.pravatar.cc/40' }}" alt="avatar">
          <span>{{ Auth::user()->email }}</span>
        </div>
      </div>

      <!-- Dynamic Slot -->
      <div class="card">
        {{ $slot }}
      </div>

    </div>
  </main>

</div>

@filamentScripts

</body>
</html>
