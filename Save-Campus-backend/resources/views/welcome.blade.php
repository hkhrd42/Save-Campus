<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Save Campus - Reduce Food Waste</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-outfit bg-white">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.67c.67-2.08 1.59-4.65 3.29-7.01C10 13 11 11 13 10c2-1 4-2 6-2v-1h-2z"/>
                                <path d="M16.07 3.05l-1.41 1.41C14.9 4.24 15 4.12 15 4s-.1-.24-.24-.46l1.41-1.41c1.95 1.95 2.2 4.99.58 7.24l1.45 1.45c2.2-3.29 1.81-7.75-1.13-10.68z"/>
                            </svg>
                        </div>
                        <span class="text-3xl font-extrabold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Save Campus</span>
                    </div>

                    <!-- Auth Links -->
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="px-7 py-3 bg-white text-green-600 font-semibold rounded-xl hover:bg-gray-50 transition-all duration-300 border-2 border-green-600">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-7 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                                    Register
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="pt-36 pb-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-green-50 to-emerald-50">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-2 gap-16 items-center mb-16 fade-in">
                    <!-- Left: Text Content -->
                    <div>
                        <div class="inline-flex items-center px-4 py-2 bg-white border-2 border-green-200 rounded-full text-sm font-semibold mb-6 shadow-sm">
                            <span class="text-green-600">🌱 Sustainable Campus Initiative</span>
                        </div>
                        <h1 class="text-6xl md:text-7xl font-extrabold text-gray-900 mb-6 leading-tight">
                            Save Food,<br>
                            <span class="bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Save Our Planet</span>
                        </h1>
                        <p class="text-xl text-gray-600 mb-10 leading-relaxed">
                            Join the movement to eliminate food waste on campus. Connect surplus meals with students who need them—because every meal matters.
                        </p>
                        @guest
                            <div class="flex flex-col sm:flex-row gap-5">
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-10 py-5 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Get Started Free
                                </a>
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-10 py-5 bg-white text-green-600 font-bold rounded-2xl hover:bg-gray-50 transition-all duration-300 border-2 border-green-600 shadow-lg">
                                    Sign In
                                </a>
                            </div>
                        @endguest
                        <div class="mt-10 flex items-center space-x-8 text-sm text-gray-600">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-2">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Free to join</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-2">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Real-time updates</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right: Hero Image -->
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-green-400 to-emerald-500 rounded-3xl transform rotate-3 shadow-2xl"></div>
                        <img src="https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=800&h=600&fit=crop" 
                             alt="Fresh healthy food" 
                             class="relative rounded-3xl shadow-2xl w-full h-[500px] object-cover border-4 border-white">
                        <div class="absolute -bottom-8 -left-8 bg-white rounded-2xl shadow-2xl p-8 max-w-xs border border-gray-100">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-3xl font-extrabold text-gray-900">12</p>
                                    <p class="text-sm text-gray-600 font-medium">Meals saved today</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-24 mt-32">
                    <div class="bg-white rounded-3xl shadow-xl p-10 text-center hover:scale-105 transition-transform duration-300 border-t-4 border-green-500">
                        <div class="text-5xl font-extrabold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent mb-3">1K</div>
                        <div class="text-gray-700 font-semibold text-lg">Meals Saved</div>
                    </div>
                    <div class="bg-white rounded-3xl shadow-xl p-10 text-center hover:scale-105 transition-transform duration-300 border-t-4 border-emerald-500">
                        <div class="text-5xl font-extrabold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent mb-3">340</div>
                        <div class="text-gray-700 font-semibold text-lg">Students Helped</div>
                    </div>
                    <div class="bg-white rounded-3xl shadow-xl p-10 text-center hover:scale-105 transition-transform duration-300 border-t-4 border-teal-500">
                        <div class="text-5xl font-extrabold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent mb-3">89%</div>
                        <div class="text-gray-700 font-semibold text-lg">Less Waste</div>
                    </div>
                    <div class="bg-white rounded-3xl shadow-xl p-10 text-center hover:scale-105 transition-transform duration-300 border-t-4 border-green-600">
                        <div class="text-5xl font-extrabold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent mb-3">5+</div>
                        <div class="text-gray-700 font-semibold text-lg">Partner Cafes</div>
                    </div>
                </div>

                <!-- Features Grid with Images -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-24 mt-24">
                    <!-- For Students -->
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl hover:scale-105 transition-all duration-300">
                        <div class="h-48 bg-gradient-to-br from-green-400 to-emerald-500 relative overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?w=600&h=400&fit=crop" 
                                 alt="Students dining" 
                                 class="w-full h-full object-cover opacity-80">
                        </div>
                        <div class="p-8">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                                <h3 class="text-2xl font-bold text-gray-900">For Students</h3>
                            </div>
                            <ul class="space-y-3 text-gray-600">
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Browse available meals in real-time</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Claim portions instantly</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Track your claimed meals</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-green-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Never miss a meal opportunity</span>
                                </li>
                            </ul>
                            @guest
                                <a href="{{ route('register') }}" class="mt-6 inline-flex items-center text-green-600 font-semibold hover:text-green-700 transition-colors group">
                                    Register as Student 
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            @endguest
                        </div>
                    </div>

                    <!-- For Staff -->
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl hover:scale-105 transition-all duration-300">
                        <div class="h-48 bg-gradient-to-br from-emerald-400 to-teal-500 relative overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=600&h=400&fit=crop" 
                                 alt="Cafe staff" 
                                 class="w-full h-full object-cover opacity-80">
                        </div>
                        <div class="p-8">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                                <h3 class="text-2xl font-bold text-gray-900">For Staff</h3>
                            </div>
                            <ul class="space-y-3 text-gray-600">
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-emerald-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Post surplus meals easily</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-emerald-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Manage meal listings</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-emerald-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Track distribution metrics</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-emerald-600 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Reduce waste efficiently</span>
                                </li>
                            </ul>
                            @guest
                                <a href="{{ route('register') }}" class="mt-6 inline-flex items-center text-emerald-600 font-semibold hover:text-emerald-700 transition-colors group">
                                    Register as Staff 
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>

                <!-- How It Works -->
                <div class="mb-32 mt-24">
                    <h2 class="text-4xl font-extrabold text-center mb-16 text-gray-900">How It Works</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                        <div class="text-center group">
                            <div class="relative inline-block mb-8">
                                <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-emerald-500 rounded-3xl flex items-center justify-center mx-auto shadow-2xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                    <span class="text-4xl font-bold text-white">1</span>
                                </div>
                                <div class="absolute -top-3 -right-3 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-900">Post or Browse</h3>
                            <p class="text-gray-600 text-lg px-4 leading-relaxed">Staff post surplus meals, students browse available options in real-time</p>
                        </div>
                        <div class="text-center group">
                            <div class="relative inline-block mb-8">
                                <div class="w-24 h-24 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-3xl flex items-center justify-center mx-auto shadow-2xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                    <span class="text-4xl font-bold text-white">2</span>
                                </div>
                                <div class="absolute -top-3 -right-3 w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-900">Claim Instantly</h3>
                            <p class="text-gray-600 text-lg px-4 leading-relaxed">Students claim portions before they expire with one-click booking</p>
                        </div>
                        <div class="text-center group">
                            <div class="relative inline-block mb-8">
                                <div class="w-24 h-24 bg-gradient-to-br from-teal-500 to-green-600 rounded-3xl flex items-center justify-center mx-auto shadow-2xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                    <span class="text-4xl font-bold text-white">3</span>
                                </div>
                                <div class="absolute -top-3 -right-3 w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-900">Make Impact</h3>
                            <p class="text-gray-600 text-lg px-4 leading-relaxed">Reduce waste and help your campus community thrive sustainably</p>
                        </div>
                    </div>
                </div>

                <!-- CTA Section -->
                @guest
                    <div class="relative overflow-hidden text-center rounded-3xl p-24 bg-gradient-to-r from-green-600 via-emerald-600 to-green-600 text-white shadow-2xl mt-32 border-4 border-white">
                        <div class="absolute inset-0 bg-black opacity-5"></div>
                        <div class="relative z-10">
                            <div class="inline-flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm rounded-full text-base font-bold mb-8 shadow-lg">
                                🌍 Join the Movement
                            </div>
                            <h2 class="text-5xl md:text-6xl font-extrabold mb-8">Ready to Make a Difference?</h2>
                            <p class="text-2xl mb-12 max-w-3xl mx-auto opacity-95 leading-relaxed">Join Save Campus today and become part of the solution to eliminate food waste on your campus</p>
                            <a href="{{ route('register') }}" class="inline-flex items-center px-12 py-6 bg-white text-green-600 font-extrabold text-lg rounded-2xl shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:scale-105">
                                <span>Get Started Free</span>
                                <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                        </div>
                        <!-- Decorative Elements -->
                        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full opacity-10 -translate-x-1/2 -translate-y-1/2"></div>
                        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full opacity-10 translate-x-1/2 translate-y-1/2"></div>
                    </div>
                @endguest
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gradient-to-r from-green-600 to-emerald-600 border-t-4 border-green-700 py-16 mt-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="mb-6">
                    <div class="inline-flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.67c.67-2.08 1.59-4.65 3.29-7.01C10 13 11 11 13 10c2-1 4-2 6-2v-1h-2z"/>
                                <path d="M16.07 3.05l-1.41 1.41C14.9 4.24 15 4.12 15 4s-.1-.24-.24-.46l1.41-1.41c1.95 1.95 2.2 4.99.58 7.24l1.45 1.45c2.2-3.29 1.81-7.75-1.13-10.68z"/>
                            </svg>
                        </div>
                        <span class="text-3xl font-extrabold text-white">Save Campus</span>
                    </div>
                </div>
                <p class="text-white text-lg font-medium opacity-90">&copy; {{ date('Y') }} Save Campus. Reducing food waste, saving our planet.</p>
                <p class="text-white text-sm opacity-75 mt-2">Every meal saved makes a difference.</p>
            </div>
        </footer>
    </body>
</html>
