<?php

namespace App\Livewire\Rgpd\Inbox;

use App\Models\MailAccount;
use App\Services\MailFetcher;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;

    public string $search = '';

    public ?MailAccount $selectedAccount = null;

    public array $messages = [];

    public bool $loading = false;

    public ?array $selectedMessage = null;

    public function mount()
    {
        $this->authorize('mail-accounts.viewAny');
    }

    public function loadInbox(MailAccount $account): void
    {
        $this->authorize('mail-accounts.viewAny');
        $this->selectedAccount = $account;
        $this->loading = true;

        // Ensure password is present before attempting to connect
        if (empty($account->password)) {
            $this->dispatch('flash', type: 'danger', message: 'No password configured for this mail account. Please edit the account and provide the IMAP password.');
            $this->messages = [];
            $this->loading = false;
            return;
        }

        try {
            $fetcher = new MailFetcher();
            $this->messages = $fetcher->fetchHeaders($account, 50);
        } catch (\Throwable $e) {
            $this->dispatch('flash', type: 'danger', message: 'Unable to fetch messages: '.$e->getMessage());
            $this->messages = [];
        } finally {
            $this->loading = false;
        }
    }

    public function selectMessage(string $uid): void
    {
        $this->authorize('mail-accounts.viewAny');

        if (! $this->selectedAccount) {
            $this->dispatch('flash', type: 'danger', message: 'No account selected.');
            return;
        }

        $this->loading = true;
        $this->selectedMessage = null;

        try {
            $fetcher = new MailFetcher();
            $this->selectedMessage = $fetcher->fetchMessage($this->selectedAccount, (string) $uid);
        } catch (\Throwable $e) {
            $this->dispatch('flash', type: 'danger', message: 'Unable to fetch message: '.$e->getMessage());
            $this->selectedMessage = null;
        } finally {
            $this->loading = false;
        }
    }

    public function render()
    {
        $query = MailAccount::query();

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%");
        }

        return view('livewire.rgpd.inbox.index', [
            'accounts' => $query->orderBy('name')->get(),
        ]);
    }
}
