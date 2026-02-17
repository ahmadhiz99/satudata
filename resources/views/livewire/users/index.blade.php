<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- Flash Messages --}}
                    @if (session()->has('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Header --}}
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex-1 pr-4">
                            <input 
                                type="text" 
                                wire:model.live.debounce.300ms="search"
                                placeholder="Search users..." 
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full max-w-md"
                            />
                        </div>
                        
                        <a href="{{ route('users.create') }}" 
                           class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Add User
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Roles
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $user->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ $user->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex gap-1">
                                                @foreach($user->roles as $role)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('users.edit', $user) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Edit
                                            </a>
                                            <button 
                                                wire:click="confirmDelete({{ $user->id }})"
                                                class="text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    @if($confirmingUserDeletion)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Confirm Deletion
                </h3>
                <p class="text-sm text-gray-500 mb-6">
                    Are you sure you want to delete this user? This action cannot be undone.
                </p>
                <div class="flex justify-end gap-3">
                    <button 
                        wire:click="$set('confirmingUserDeletion', false)"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button 
                        wire:click="deleteUser"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>