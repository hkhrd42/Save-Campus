<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-green-100">
                <div class="p-8">
                    <h3 class="text-3xl font-bold mb-2 text-gray-800">Welcome, {{ Auth::user()->name }}! 👋</h3>
                    
                    @if(Auth::user()->role === 'staff')
                        <!-- Staff Dashboard -->
                        <p class="text-gray-600 mb-8 text-lg">
                            You're logged in as a <span class="font-bold text-green-600">Staff Member</span>. 
                            Help reduce food waste by posting available meals for students.
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <a href="{{ route('meals.index') }}" class="bg-white border-2 border-green-100 rounded-2xl p-6 group hover:border-green-300 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center mb-3">
                                    <div class="bg-green-100 text-green-600 p-3 rounded-xl mr-3 group-hover:bg-green-200 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900">My Meals</h4>
                                </div>
                                <p class="text-sm text-gray-600">Manage your posted meals and view claims</p>
                            </a>

                            <a href="{{ route('meals.create') }}" class="bg-white border-2 border-emerald-100 rounded-2xl p-6 group hover:border-emerald-300 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center mb-3">
                                    <div class="bg-emerald-100 text-emerald-600 p-3 rounded-xl mr-3 group-hover:bg-emerald-200 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900">Post New Meal</h4>
                                </div>
                                <p class="text-sm text-gray-600">Share available food with students</p>
                            </a>
                        </div>
                    @else
                        <!-- Student Dashboard -->
                        <p class="text-gray-600 mb-8 text-lg">
                            You're logged in as a <span class="font-bold text-green-600">Student</span>. 
                            Browse and claim available meals posted by staff members.
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <a href="{{ route('browse.index') }}" class="bg-white border-2 border-green-100 rounded-2xl p-6 group hover:border-green-300 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center mb-3">
                                    <div class="bg-green-100 text-green-600 p-3 rounded-xl mr-3 group-hover:bg-green-200 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900">Browse Meals</h4>
                                </div>
                                <p class="text-sm text-gray-600">Find available meals near you</p>
                            </a>

                            <a href="{{ route('claims.index') }}" class="bg-white border-2 border-emerald-100 rounded-2xl p-6 group hover:border-emerald-300 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center mb-3">
                                    <div class="bg-emerald-100 text-emerald-600 p-3 rounded-xl mr-3 group-hover:bg-emerald-200 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900">My Claims</h4>
                                </div>
                                <p class="text-sm text-gray-600">View your claimed meals</p>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
