<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Meal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('meals.update', $meal) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Meal Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Meal Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $meal->name) }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('name') border-red-500 @enderror" 
                                required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('description') border-red-500 @enderror">{{ old('description', $meal->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meal Image -->
                        <div class="mb-4">
                            <label for="image_path" class="block text-sm font-medium text-gray-700">Meal Image</label>
                            @if($meal->image_path)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $meal->image_path) }}" alt="{{ $meal->name }}" class="h-32 w-32 object-cover rounded">
                                    <p class="text-sm text-gray-500 mt-1">Current image</p>
                                </div>
                            @endif
                            <input type="file" name="image_path" id="image_path" accept="image/*"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('image_path') border-red-500 @enderror">
                            @error('image_path')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Optional: Upload a new photo to replace the current one</p>
                        </div>

                        <!-- Available Portions -->
                        <div class="mb-4">
                            <label for="available_portions" class="block text-sm font-medium text-gray-700">Available Portions <span class="text-red-500">*</span></label>
                            <input type="number" name="available_portions" id="available_portions" value="{{ old('available_portions', $meal->available_portions) }}" min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('available_portions') border-red-500 @enderror" 
                                required>
                            @error('available_portions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Current claims: {{ $meal->claims()->count() }}</p>
                        </div>

                        <!-- Expires At -->
                        <div class="mb-6">
                            <label for="expires_at" class="block text-sm font-medium text-gray-700">Expiration Date & Time <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at', $meal->expires_at->format('Y-m-d\TH:i')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('expires_at') border-red-500 @enderror" 
                                required>
                            @error('expires_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('meals.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-green-700 hover:to-emerald-700 focus:from-green-700 focus:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Update Meal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
