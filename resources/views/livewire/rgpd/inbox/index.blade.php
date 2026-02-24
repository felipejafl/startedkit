<div>
    <div class="mb-6">
        <flux:heading size="lg" level="1">RGPD Inbox</flux:heading>
        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">List of messages from configured mail accounts. Select an account to load its inbox.</p>
    </div>

    <div class="mb-6 flex gap-4">
        <flux:input
            type="text"
            wire:model.live.debounce-500ms="search"
            placeholder="Search accounts by name or email..."
            icon="magnifying-glass"
            class="flex-1"
        />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="md:col-span-1 bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4">
            <div class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-3">Mail Accounts</div>
            <div class="space-y-2">
                @foreach($accounts as $account)
                    <div class="flex items-center justify-between p-2 rounded hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <div>
                            <div class="text-sm font-medium text-zinc-900 dark:text-white">{{ $account->name }}</div>
                            <div class="text-xs text-zinc-600 dark:text-zinc-400">{{ $account->email }}</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <flux:badge :color="$account->is_active ? 'green' : 'zinc'" size="sm">{{ $account->is_active ? 'Active' : 'Inactive' }}</flux:badge>
                            <flux:button wire:click="loadInbox({{ $account->id }})" size="sm" variant="ghost" icon="inbox">View</flux:button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="md:col-span-1 bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <div class="px-4 py-3 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                <div>
                    <div class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Inbox</div>
                    @if($selectedAccount)
                        <div class="text-xs text-zinc-600 dark:text-zinc-400">Account: {{ $selectedAccount->name }} — {{ $selectedAccount->email }}</div>
                    @else
                        <div class="text-xs text-zinc-600 dark:text-zinc-400">No account selected</div>
                    @endif
                </div>
                <div>
                    @if($loading)
                        <flux:icon.loading variant="mini" />
                    @endif
                </div>
            </div>
            <div class="overflow-y-auto max-h-[60vh]">
                <table class="w-full">
                    <thead>
                        <tr class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">From</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">Subject</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-zinc-700 dark:text-zinc-200 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        @if(count($messages) === 0)
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-sm text-zinc-500">No messages loaded. Select an account to view its inbox.</td>
                            </tr>
                        @else
                            @foreach($messages as $msg)
                                @php $isActive = $selectedMessage && (string)($selectedMessage['uid'] ?? '') === (string)$msg['uid']; @endphp
                                <tr wire:click="selectMessage('{{ $msg['uid'] }}')" class="cursor-pointer transition-colors {{ $isActive ? 'bg-zinc-100 dark:bg-zinc-800/60' : 'hover:bg-zinc-50 dark:hover:bg-zinc-800/50' }}">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-zinc-700 dark:text-zinc-200">{{ $msg['from'] }}</td>
                                    <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $msg['subject'] }}</td>
                                    <td class="px-4 py-3 text-sm text-zinc-600 dark:text-zinc-400">{{ $msg['date'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="md:col-span-2 bg-white dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4 overflow-auto max-h-[70vh]">
            <div class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-3">Message</div>
            @if($selectedMessage)
                <div class="text-xs text-zinc-600 dark:text-zinc-400 mb-3">From: {{ $selectedMessage['from'] }} — {{ $selectedMessage['date'] }}</div>
                <div class="prose max-w-none dark:prose-invert">{!! $selectedMessage['html'] ? $selectedMessage['html'] : nl2br(e($selectedMessage['text'])) !!}</div>
            @else
                <div class="text-sm text-zinc-500">Select a message to preview its content here.</div>
            @endif
        </div>
    </div>
</div>
