<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CTU Danao HRMO Admin') - {{ config('app.name', 'Laravel') }}</title>
    
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
        
        /* Ensure proper positioning of main content */
        .main-content {
            position: relative;
            z-index: 1;
        }
        
        /* Ensure search bar is properly positioned */
        .search-container {
            position: relative;
            z-index: 10;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div id="app" x-data="{ sidebarOpen: false }" x-init="sidebarOpen = false" class="min-h-screen bg-gray-50">
        <!-- Backdrop for all screen sizes -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75"
             x-cloak></div>
        
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 ctu-gradient shadow-lg transform transition-transform duration-300 ease-in-out" 
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex items-center justify-between h-16 px-4">
                <div class="flex items-center">
                    <img class="h-10 w-10 rounded-full mr-3" src="{{ asset('images/ctu2.jpg') }}" alt="CTU Logo">
                    <div>
                        <h1 class="text-white text-lg font-bold">CTU Danao</h1>
                        <p class="text-blue-200 text-xs">HRMO Admin</p>
                    </div>
                </div>
                <!-- Close button -->
                <button @click="sidebarOpen = false" class="text-white hover:text-blue-200 focus:outline-none focus:text-blue-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <nav class="mt-5 px-2">
                <a href="{{ route('admin.dashboard') }}" 
                   @click="sidebarOpen = false"
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-900' : 'text-white hover:bg-blue-600 hover:text-white' }}">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.jobs.index') }}" 
                   @click="sidebarOpen = false"
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.jobs.*') ? 'bg-blue-100 text-blue-900' : 'text-white hover:bg-blue-600 hover:text-white' }}">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                    Job Management
                </a>
                
                <a href="{{ route('admin.applicants.index') }}" 
                   @click="sidebarOpen = false"
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.applicants.*') ? 'bg-blue-100 text-blue-900' : 'text-white hover:bg-blue-600 hover:text-white' }}">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    Applicants
                </a>
                
                <a href="{{ route('admin.applicants.index') }}" 
                   @click="sidebarOpen = false"
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.applicants.*') ? 'bg-blue-100 text-blue-900' : 'text-white hover:bg-blue-600 hover:text-white' }}">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Schedule Interview
                </a>
            </nav>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 min-h-screen">
            <!-- Top navigation -->
            <div class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white shadow">
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="px-4 border-r border-gray-200 text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-colors duration-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <div class="flex-1 px-4 flex justify-between items-center">
                    <div class="flex-1 flex items-center">
                        <h1 class="text-lg font-semibold text-gray-900">CTU Danao HRMO</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Profile dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 ctu-bg-blue rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">A</span>
                                </div>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-cloak 
                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="flex-1">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        @if(session('success'))
                            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                {{ session('error') }}
                            </div>
                        @endif

                @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @yield('scripts')
</body>
</html>