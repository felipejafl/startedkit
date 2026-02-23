<?php

namespace App\Livewire\Rgpd\Contacts;

use App\Models\RgpdContact;
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

    public ?RgpdContact $editingContact = null;

    public ?RgpdContact $deletingContact = null;

    public string $formName = '';

    public string $formEmail = '';

    public string $formNote = '';

    public bool $formIsActive = true;

    protected $queryString = ['search', 'filterStatus'];

    public function openCreateForm(): void
    {
        $this->authorize('contacts.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditForm(RgpdContact $contact): void
    {
        $this->authorize('contacts.update');
        $this->editingContact = $contact;
        $this->formName = $contact->name;
        $this->formEmail = $contact->email;
        $this->formNote = $contact->note ?? '';
        $this->formIsActive = $contact->is_active;
        $this->showForm = true;
    }

    public function openDeleteConfirm(RgpdContact $contact): void
    {
        $this->authorize('contacts.delete');
        $this->deletingContact = $contact;
        $this->showDeleteConfirm = true;
    }

    public function closeModals(): void
    {
        $this->showForm = false;
        $this->showDeleteConfirm = false;
        $this->resetForm();
    }

    public function saveContact(): void
    {
        $this->validate([
            'formName' => 'required|string|max:255',
            'formEmail' => ['required', 'email', 'max:255',
                $this->editingContact ? 'unique:rgpd_contacts,email,'.$this->editingContact->id : 'unique:rgpd_contacts,email'],
            'formNote' => 'nullable|string|max:1000',
        ], [
            'formName.required' => 'Name is required.',
            'formEmail.required' => 'Email is required.',
            'formEmail.email' => 'Please enter a valid email.',
            'formEmail.unique' => 'This email is already registered.',
            'formNote.max' => 'Note cannot exceed 1000 characters.',
        ]);

        if ($this->editingContact) {
            $this->authorize('contacts.update');
            $this->editingContact->update([
                'name' => $this->formName,
                'email' => $this->formEmail,
                'note' => $this->formNote ?: null,
                'is_active' => $this->formIsActive,
            ]);
            $this->dispatch('flash', type: 'success', message: 'Contact updated successfully!');
        } else {
            $this->authorize('contacts.create');
            RgpdContact::create([
                'name' => $this->formName,
                'email' => $this->formEmail,
                'note' => $this->formNote ?: null,
                'is_active' => $this->formIsActive,
            ]);
            $this->dispatch('flash', type: 'success', message: 'Contact created successfully!');
        }

        $this->closeModals();
    }

    public function deleteContact(): void
    {
        if (! $this->deletingContact) {
            return;
        }

        $this->authorize('contacts.delete');
        $this->deletingContact->delete();
        $this->dispatch('flash', type: 'success', message: 'Contact deleted successfully!');
        $this->closeModals();
    }

    public function toggleActive(RgpdContact $contact): void
    {
        $this->authorize('contacts.update');
        $contact->update(['is_active' => ! $contact->is_active]);
        $this->dispatch('flash', type: 'success', message: 'Contact status updated!');
    }

    public function resetForm(): void
    {
        $this->editingContact = null;
        $this->formName = '';
        $this->formEmail = '';
        $this->formNote = '';
        $this->formIsActive = true;
    }

    public function render()
    {
        $query = RgpdContact::query();

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%");
        }

        if ($this->filterStatus !== '') {
            $query->where('is_active', (bool) $this->filterStatus);
        }

        return view('livewire.rgpd.contacts.index', [
            'contacts' => $query->paginate(15),
        ]);
    }
}
