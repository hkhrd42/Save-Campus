<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white border-b border-green-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.67c.67-2.08 1.59-4.65 3.29-7.01C10 13 11 11 13 10c2-1 4-2 6-2v-1h-2z"/>
                                <path d="M16.07 3.05l-1.41 1.41C14.9 4.24 15 4.12 15 4s-.1-.24-.24-.46l1.41-1.41c1.95 1.95 2.2 4.99.58 7.24l1.45 1.45c2.2-3.29 1.81-7.75-1.13-10.68z"/>
                            </svg>
                        </div>
                        <span class="text-2xl font-extrabold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Save Campus</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-medium">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(Auth::user()->role === 'staff')
                        <!-- Staff Navigation -->
                        <x-nav-link :href="route('meals.index')" :active="request()->routeIs('meals.*')" class="font-medium">
                            {{ __('My Meals') }}
                        </x-nav-link>
                    @endif

                    @if(Auth::user()->role === 'student')
                        <!-- Student Navigation -->
                        <x-nav-link :href="route('browse.index')" :active="request()->routeIs('browse.*')" class="font-medium">
                            {{ __('Browse Meals') }}
                        </x-nav-link>
                        <x-nav-link :href="route('claims.index')" :active="request()->routeIs('claims.*')" class="font-medium">
                            {{ __('My Claims') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-green-600 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(Auth::user()->role === 'staff')
                <!-- Staff Navigation -->
                <x-responsive-nav-link :href="route('meals.index')" :active="request()->routeIs('meals.*')">
                    {{ __('My Meals') }}
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->role === 'student')
                <!-- Student Navigation -->
                <x-responsive-nav-link :href="route('browse.index')" :active="request()->routeIs('browse.*')">
                    {{ __('Browse Meals') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('claims.index')" :active="request()->routeIs('claims.*')">
                    {{ __('My Claims') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
