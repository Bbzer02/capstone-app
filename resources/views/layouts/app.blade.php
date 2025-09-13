<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CTU Danao HRMO') - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional CSS -->
    <style>
        [x-cloak] { display: none !important; }
        .ctu-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
        }
        .ctu-blue { color: #1e40af; }
        .ctu-bg-blue { background-color: #1e40af; }
        .ctu-border-blue { border-color: #1e40af; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div id="app" x-data="{ mobileMenuOpen: false }">
        <!-- Navigation -->
        <nav class="ctu-gradient shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center">
                            <img class="h-10 w-10 rounded-full mr-3" src="{{ asset('images/ctu2.jpg') }}" alt="CTU Logo">
                            <div>
                                <h1 class="text-white text-xl font-bold">CTU Danao HRMO</h1>
                                <p class="text-blue-200 text-sm">Human Resource Management Office</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('welcome') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                        <a href="{{ route('jobs.index') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">Job Openings</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                            <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-md text-sm font-medium">Register</a>
                        @endauth
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white hover:text-blue-200 p-2">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-cloak class="md:hidden bg-blue-700">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="{{ route('welcome') }}" class="text-white hover:text-blue-200 block px-3 py-2 rounded-md text-base font-medium">Home</a>
                    <a href="{{ route('jobs.index') }}" class="text-white hover:text-blue-200 block px-3 py-2 rounded-md text-base font-medium">Job Openings</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-white hover:text-blue-200 block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="text-white hover:text-blue-200 w-full text-left px-3 py-2 rounded-md text-base font-medium">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-blue-200 block px-3 py-2 rounded-md text-base font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-blue-50 block px-3 py-2 rounded-md text-base font-medium">Register</a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="min-h-screen">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="ctu-gradient text-white py-8 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">CTU Danao HRMO</h3>
                        <p class="text-blue-200">Cebu Technological University - Danao Campus</p>
                        <p class="text-blue-200">Human Resource Management Office</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('jobs.index') }}" class="text-blue-200 hover:text-white">Job Openings</a></li>
                            <li><a href="{{ route('login') }}" class="text-blue-200 hover:text-white">Login</a></li>
                            <li><a href="{{ route('register') }}" class="text-blue-200 hover:text-white">Register</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact Info</h3>
                        <p class="text-blue-200">Email: hrmo@ctu.edu.ph</p>
                        <p class="text-blue-200">Phone: (032) 123-4567</p>
                        <p class="text-blue-200">Danao City, Cebu</p>
                    </div>
                </div>
                <div class="border-t border-blue-600 mt-8 pt-8 text-center">
                    <p class="text-blue-200">&copy; {{ date('Y') }} CTU Danao HRMO. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @yield('scripts')
</body>
</html>