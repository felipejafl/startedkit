<?php

namespace App\Livewire\Admin\MailAccounts;

use App\Models\MailAccount;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public string $search = '';

    public string $filterStatus = '';

    public bool $showForm = false;

    public bool $showDeleteConfirm = false;

    public ?MailAccount $editingAccount = null;

    public ?MailAccount $deletingAccount = null;

    public string $formName = '';

    public string $formEmail = '';

    public string $formServer = '';

    public string $formPassword = '';

    public int $formImapPort = 993;

    public string $formImapSecurity = 'ssl';

    public int $formSmtpPort = 587;

    public string $formSmtpSecurity = 'tls';

    public bool $formIsActive = true;

    protected $queryString = ['search', 'filterStatus'];

    public function openCreateForm(): void
    {
        $this->authorize('mail-accounts.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditForm(MailAccount $account): void
    {
        $this->authorize('mail-accounts.update');
        $this->editingAccount = $account;
        $this->formName = $account->name;
        $this->formEmail = $account->email;
        $this->formServer = $account->server;
        $this->formImapPort = $account->imap_port;
        $this->formImapSecurity = $account->imap_security;
        $this->formSmtpPort = $account->smtp_port;
        $this->formSmtpSecurity = $account->smtp_security;
        $this->formIsActive = $account->is_active;
        $this->showForm = true;
    }

    public function openDeleteConfirm(MailAccount $account): void
    {
        $this->authorize('mail-accounts.delete');
        $this->deletingAccount = $account;
        $this->showDeleteConfirm = true;
    }

    public function closeModals(): void
    {
        $this->showForm = false;
        $this->showDeleteConfirm = false;
        $this->resetForm();
    }

    public function saveAccount(): void
    {
        $this->validate([
            'formName' => 'required|string|max:255',
            'formEmail' => ['required', 'email', 'max:255',
                $this->editingAccount ? 'unique:mail_accounts,email,'.$this->editingAccount->id : 'unique:mail_accounts,email'],
            'formServer' => 'required|string|max:255',
            'formPassword' => $this->editingAccount ? 'nullable|string|min:6' : 'required|string|min:6',
            'formImapPort' => 'required|integer|between:1,65535',
            'formImapSecurity' => 'required|in:none,ssl,tls',
            'formSmtpPort' => 'required|integer|between:1,65535',
            'formSmtpSecurity' => 'required|in:none,ssl,tls',
        ], [
            'formName.required' => 'Account name is required.',
            'formEmail.required' => 'Email is required.',
            'formEmail.email' => 'Please enter a valid email.',
            'formEmail.unique' => 'This email is already registered.',
            'formServer.required' => 'Server address is required.',
            'formPassword.required' => 'Password is required.',
            'formPassword.min' => 'Password must be at least 6 characters.',
            'formImapPort.required' => 'IMAP port is required.',
            'formImapPort.between' => 'IMAP port must be between 1 and 65535.',
            'formSmtpPort.required' => 'SMTP port is required.',
            'formSmtpPort.between' => 'SMTP port must be between 1 and 65535.',
        ]);

        if ($this->editingAccount) {
            $this->authorize('mail-accounts.update');
            $data = [
                'name' => $this->formName,
                'email' => $this->formEmail,
                'server' => $this->formServer,
                'imap_port' => $this->formImapPort,
                'imap_security' => $this->formImapSecurity,
                'smtp_port' => $this->formSmtpPort,
                'smtp_security' => $this->formSmtpSecurity,
                'is_active' => $this->formIsActive,
            ];

            if ($this->formPassword) {
                $data['password'] = $this->formPassword;
            }

            $this->editingAccount->update($data);
            $this->dispatch('flash', type: 'success', message: 'Mail account updated successfully!');
        } else {
            $this->authorize('mail-accounts.create');
            MailAccount::create([
                'name' => $this->formName,
                'email' => $this->formEmail,
                'server' => $this->formServer,
                'password' => $this->formPassword,
                'imap_port' => $this->formImapPort,
                'imap_security' => $this->formImapSecurity,
                'smtp_port' => $this->formSmtpPort,
                'smtp_security' => $this->formSmtpSecurity,
                'is_active' => $this->formIsActive,
            ]);
            $this->dispatch('flash', type: 'success', message: 'Mail account created successfully!');
        }

        $this->closeModals();
    }

    public function deleteAccount(): void
    {
        if (! $this->deletingAccount) {
            return;
        }

        $this->authorize('mail-accounts.delete');
        $this->deletingAccount->delete();
        $this->dispatch('flash', type: 'success', message: 'Mail account deleted successfully!');
        $this->closeModals();
    }

    public function toggleActive(MailAccount $account): void
    {
        $this->authorize('mail-accounts.update');
        $account->update(['is_active' => ! $account->is_active]);
        $this->dispatch('flash', type: 'success', message: 'Mail account status updated!');
    }

    public function resetForm(): void
    {
        $this->editingAccount = null;
        $this->formName = '';
        $this->formEmail = '';
        $this->formServer = '';
        $this->formPassword = '';
        $this->formImapPort = 993;
        $this->formImapSecurity = 'ssl';
        $this->formSmtpPort = 587;
        $this->formSmtpSecurity = 'tls';
        $this->formIsActive = true;
    }

    public function render()
    {
        $query = MailAccount::query();

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%");
        }

        if ($this->filterStatus !== '') {
            $query->where('is_active', (bool) $this->filterStatus);
        }

        return view('livewire.admin.mail-accounts.index', [
            'accounts' => $query->paginate(15),
        ]);
    }
}
