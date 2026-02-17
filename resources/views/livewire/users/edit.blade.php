<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form wire:submit="update">
                        {{-- Name --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name"
                                wire:model="name"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                placeholder="Enter name"
                            />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email"
                                wire:model="email"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                placeholder="Enter email"
                            />
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                Password <span class="text-gray-500 text-xs">(Leave blank to keep current)</span>
                            </label>
                            <input 
                                type="password" 
                                id="password"
                                wire:model="password"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                placeholder="Enter new password"
                            />
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Confirmation --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirm Password
                            </label>
                            <input 
                                type="password" 
                                id="password_confirmation"
                                wire:model="password_confirmation"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                placeholder="Confirm new password"
                            />
                        </div>

                        {{-- Roles --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Roles
                            </label>
                            <div class="space-y-2">
                                @forelse($roles as $role)
                                    <label class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            wire:model="selectedRoles"
                                            value="{{ $role->name }}"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">{{ ucfirst($role->name) }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500">No roles available. Create roles first.</p>
                                @endforelse
                            </div>
                            @error('selectedRoles')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-center gap-3">
                            <button 
                                type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Update User
                            </button>
                            
                            <a 
                                href="{{ route('users.index') }}"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                            >
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>