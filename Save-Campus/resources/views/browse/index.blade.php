<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-stone-800">
            {{ __('Browse Available Meals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-6 card-modern p-4 bg-green-50 border-green-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-green-800 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 card-modern p-4 bg-red-50 border-red-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-red-800 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if($meals->count() > 0)
                <!-- Meals Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in">
                    @foreach($meals as $meal)
                        <div class="card-modern group">
                            <div class="p-6">
                                <!-- Meal Header -->
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-lg font-bold text-stone-900">{{ $meal->name }}</h3>
                                    <span class="badge-modern-success">
                                        {{ $meal->available_portions }} left
                                    </span>
                                </div>

                                <!-- Description -->
                                <p class="text-stone-600 text-sm mb-4">
                                    {{ Str::limit($meal->description, 100) ?: 'No description available.' }}
                                </p>

                                <!-- Meal Info -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-stone-500">
                                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>Posted by {{ $meal->staff->name }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-stone-500">
                                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Expires {{ $meal->expires_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <a href="{{ route('browse.show', $meal) }}" class="btn-primary-modern w-full justify-center text-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $meals->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="card-modern">
                    <div class="p-12 text-center">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-stone-900 mb-2">No meals available</h3>
                        <p class="text-stone-600">There are currently no active meals to claim. Check back later!</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
