<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - SmartHarvest</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto p-8">
        <header class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-semibold text-green-700">Admin Dashboard</h1>
            <a href="{{ url('/') }}" class="text-sm text-gray-600 hover:text-green-600">Back to site</a>
        </header>

        <div class="p-6 bg-white rounded-lg shadow">
            <p class="text-gray-700">You are signed in to the admin area (demo). Implement real authentication and middleware for production.</p>
            <div class="mt-6">
                <p class="text-sm text-gray-500">Session info (debug):</p>
                <pre class="bg-gray-100 p-4 rounded mt-2">{{ var_export(session()->all(), true) }}</pre>
            </div>
        </div>
    </div>
</body>
</html>
