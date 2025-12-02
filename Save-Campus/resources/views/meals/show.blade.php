<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Meal Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('meals.edit', $meal) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit
                </a>
                <a href="{{ route('meals.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Back to My Meals
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Meal Details Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $meal->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ $meal->description ?: 'No description provided.' }}</p>
                            
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <span class="font-semibold text-gray-700 w-40">Available Portions:</span>
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $meal->available_portions > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $meal->available_portions }}
                                    </span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-semibold text-gray-700 w-40">Total Claims:</span>
                                    <span class="text-gray-900">{{ $meal->claims->count() }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-semibold text-gray-700 w-40">Expires At:</span>
                                    <span class="text-gray-900">{{ $meal->expires_at->format('M d, Y H:i') }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="font-semibold text-gray-700 w-40">Status:</span>
                                    @if($meal->isExpired())
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">Expired</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">Active</span>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <span class="font-semibold text-gray-700 w-40">Created:</span>
                                    <span class="text-gray-900">{{ $meal->created_at->format('M d, Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Claims List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Claims ({{ $meal->claims->count() }})</h3>
                    
                    @if($meal->claims->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Student Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Claimed At
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($meal->claims as $claim)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $claim->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $claim->user->email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $claim->created_at->format('M d, Y H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No claims yet for this meal.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
