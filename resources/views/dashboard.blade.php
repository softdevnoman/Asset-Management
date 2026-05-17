<!doctype html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-skin="default" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Dashboard - Manual Auth System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 antialiased">
    <div class="flex min-h-screen">

        <aside id="layout-menu" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed inset-y-0 left-0 z-20 transition-transform md:translate-x-0 transform -translate-x-full">
            <div class="h-16 flex items-center px-6 border-b border-gray-200 justify-between">
                <a href="#" class="flex items-center space-x-3">
                    <span class="text-blue-600">
                        <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="currentColor" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="currentColor" />
                        </svg>
                    </span>
                    <span class="text-xl font-bold tracking-wide text-gray-900">Vuexy App</span>
                </a>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                <div>
                    <span class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Dashboards</span>
                    <a href="#" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700 mt-1">
                        <span class="mr-3 text-lg">🏠</span> Analytics
                    </a>
                </div>

                <div class="pt-4">
                    <span class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Apps & Pages</span>
                    <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900 mt-1">
                        <span class="mr-3 text-lg">✉️</span> Email
                    </a>
                    <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900 mt-1">
                        <span class="mr-3 text-lg">💬</span> Chat
                    </a>
                    <a href="#" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900 mt-1">
                        <span class="mr-3 text-lg">📅</span> Calendar
                    </a>
                </div>
            </nav>
        </aside>

        <div class="flex-1 pl-0 md:pl-64 flex flex-col min-h-screen">

            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 sticky top-0 z-10">
                <div class="flex items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h2>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold px-4 py-2 rounded-lg transition duration-150 shadow-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 p-6 max-w-7xl w-full mx-auto">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Protected User Dashboard Area</h3>
                    <p class="text-gray-600 mb-4">
                        This interface uses your clean mockup configuration, translated into utilities utilizing Tailwind CSS layouts.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <span class="text-2xl">👤</span>
                            <h4 class="font-bold text-gray-800 mt-2">Account Status</h4>
                            <p class="text-sm text-gray-500">Authenticated Securely</p>
                        </div>
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <span class="text-2xl">🛡️</span>
                            <h4 class="font-bold text-gray-800 mt-2">Manual Controller</h4>
                            <p class="text-sm text-gray-500">Bypassed boilerplate kits</p>
                        </div>
                        <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <span class="text-2xl">⚡</span>
                            <h4 class="font-bold text-gray-800 mt-2">Vite Engine</h4>
                            <p class="text-sm text-gray-500">Active hot reloading</p>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="h-14 bg-white border-t border-gray-200 flex items-center justify-between px-6 text-sm text-gray-500">
                <div>
                    &copy; {{ date('Y') }} Custom Management Framework.
                </div>
                <div class="space-x-4">
                    <a href="#" class="hover:text-gray-900">Documentation</a>
                    <a href="#" class="hover:text-gray-900">Support</a>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>
