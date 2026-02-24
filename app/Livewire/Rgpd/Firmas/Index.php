<?php

namespace App\Livewire\Rgpd\Firmas;

use App\Models\MailAccount;
use App\Models\RgpdFirma;
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

    public ?RgpdFirma $editingFirma = null;

    public ?RgpdFirma $deletingFirma = null;

    public ?int $formMailAccountId = null;

    public string $formFirma = '';

    public bool $formIsActive = true;

    protected $queryString = ['search', 'filterStatus'];

    public function openCreateForm(): void
    {
        $this->authorize('firmas.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditForm(RgpdFirma $firma): void
    {
        $this->authorize('firmas.update');
        $this->editingFirma = $firma;
        $this->formMailAccountId = $firma->mail_account_id;
        $this->formFirma = $firma->firma;
        $this->formIsActive = $firma->is_active;
        $this->showForm = true;
    }

    public function openDeleteConfirm(RgpdFirma $firma): void
    {
        $this->authorize('firmas.delete');
        $this->deletingFirma = $firma;
        $this->showDeleteConfirm = true;
    }

    public function closeModals(): void
    {
        $this->showForm = false;
        $this->showDeleteConfirm = false;
        $this->resetForm();
    }

    public function saveFirma(): void
    {
        $this->validate([
            'formMailAccountId' => 'required|exists:mail_accounts,id',
            'formFirma' => 'required|string|max:2000',
        ], [
            'formMailAccountId.required' => 'Mail account is required.',
        ]);

        if ($this->editingFirma) {
            $this->authorize('firmas.update');
            $this->editingFirma->update([
                'mail_account_id' => $this->formMailAccountId,
                'firma' => $this->formFirma,
                'is_active' => $this->formIsActive,
            ]);

            $this->dispatch('flash', type: 'success', message: 'Firma updated successfully!');
        } else {
            $this->authorize('firmas.create');
            RgpdFirma::create([
                'mail_account_id' => $this->formMailAccountId,
                'firma' => $this->formFirma,
                'is_active' => $this->formIsActive,
            ]);

            $this->dispatch('flash', type: 'success', message: 'Firma created successfully!');
        }

        $this->closeModals();
    }

    public function deleteFirma(): void
    {
        if (! $this->deletingFirma) {
            return;
        }

        $this->authorize('firmas.delete');
        $this->deletingFirma->delete();
        $this->dispatch('flash', type: 'success', message: 'Firma deleted successfully!');
        $this->closeModals();
    }

    public function toggleActive(RgpdFirma $firma): void
    {
        $this->authorize('firmas.update');
        $firma->update(['is_active' => ! $firma->is_active]);
        $this->dispatch('flash', type: 'success', message: 'Firma status updated!');
    }

    public function resetForm(): void
    {
        $this->editingFirma = null;
        $this->formMailAccountId = null;
        $this->formFirma = '';
        $this->formIsActive = true;
    }

    public function render()
    {
        $query = RgpdFirma::with('mailAccount');

        if ($this->search) {
            $query->where('firma', 'like', "%{$this->search}%")
                ->orWhereHas('mailAccount', function ($q) {
                    $q->where('email', 'like', "%{$this->search}%");
                });
        }

        if ($this->filterStatus !== '') {
            $query->where('is_active', (bool) $this->filterStatus);
        }

        return view('livewire.rgpd.firmas.index', [
            'firmas' => $query->paginate(15),
            'mailAccounts' => MailAccount::orderBy('email')->get(),
        ]);
    }
}
