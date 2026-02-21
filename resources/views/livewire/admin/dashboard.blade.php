<div>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <flux:heading size="lg" level="1">Admin Dashboard</flux:heading>
            <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Overview of your system</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Total Users -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Users</p>
                        <p class="text-2xl font-bold mt-2 text-zinc-900 dark:text-white">{{ $totalUsers }}</p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.856-1.611M15 7a3 3 0 11-6 0 3 3 0 016 0zM16 16a6 6 0 11-12 0 6 6 0 0112 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Active Users</p>
                        <p class="text-2xl font-bold mt-2 text-zinc-900 dark:text-white">{{ $activeUsers }}</p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Roles -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Roles</p>
                        <p class="text-2xl font-bold mt-2 text-zinc-900 dark:text-white">{{ $totalRoles }}</p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Permissions -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Total Permissions</p>
                        <p class="text-2xl font-bold mt-2 text-zinc-900 dark:text-white">{{ $totalPermissions }}</p>
                    </div>
                    <div class="bg-orange-100 dark:bg-orange-900/30 p-3 rounded-full">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m7-3a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-800">
                <flux:heading size="md">Recent Users</flux:heading>
            </div>
            <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @forelse($recentUsers as $user)
                <div class="p-4 flex items-center justify-between hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-zinc-100 dark:bg-zinc-800">
                                <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ \Illuminate\Support\Str::of($user->name)->explode(' ')->take(2)->map(fn($word) => Str::substr($word, 0, 1))->implode('') }}</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $user->name }}</p>
                            <p class="text-xs text-zinc-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div>
                        @if ($user->is_active)
                            <flux:badge color="green" size="sm">Active</flux:badge>
                        @else
                            <flux:badge color="zinc" size="sm">Inactive</flux:badge>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-6 text-center">
                    <p class="text-sm text-zinc-500">No users found</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.users.index') }}"
                class="p-4 bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 hover:shadow-md transition-shadow">
                <flux:heading size="sm">Manage Users</flux:heading>
                <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-1">Add, edit, or remove users</p>
            </a>
            <a href="{{ route('admin.roles.index') }}"
                class="p-4 bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 hover:shadow-md transition-shadow">
                <flux:heading size="sm">Manage Roles</flux:heading>
                <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-1">Create and configure roles</p>
            </a>
            <a href="{{ route('admin.permissions.index') }}"
                class="p-4 bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 hover:shadow-md transition-shadow">
                <flux:heading size="sm">Manage Permissions</flux:heading>
                <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-1">Define system permissions</p>
            </a>
        </div>
    </div>
</div>
