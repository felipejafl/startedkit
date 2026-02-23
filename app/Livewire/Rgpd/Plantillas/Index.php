<?php

namespace App\Livewire\Rgpd\Plantillas;

use App\Models\RgpdPlantilla;
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

    public ?RgpdPlantilla $editingPlantilla = null;

    public ?RgpdPlantilla $deletingPlantilla = null;

    public string $formSubject = '';

    public string $formBody = '';

    public bool $formIsActive = true;

    protected $queryString = ['search', 'filterStatus'];

    public function openCreateForm(): void
    {
        $this->authorize('plantillas.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEditForm(RgpdPlantilla $plantilla): void
    {
        $this->authorize('plantillas.update');
        $this->editingPlantilla = $plantilla;
        $this->formSubject = $plantilla->subject;
        $this->formBody = $plantilla->body;
        $this->formIsActive = $plantilla->is_active;
        $this->showForm = true;
    }

    public function openDeleteConfirm(RgpdPlantilla $plantilla): void
    {
        $this->authorize('plantillas.delete');
        $this->deletingPlantilla = $plantilla;
        $this->showDeleteConfirm = true;
    }

    public function closeModals(): void
    {
        $this->showForm = false;
        $this->showDeleteConfirm = false;
        $this->resetForm();
    }

    public function savePlantilla(): void
    {
        $this->validate([
            'formSubject' => 'required|string|max:255',
            'formBody' => 'required|string|max:5000',
        ], [
            'formSubject.required' => 'Subject is required.',
            'formSubject.max' => 'Subject cannot exceed 255 characters.',
            'formBody.required' => 'Body is required.',
            'formBody.max' => 'Body cannot exceed 5000 characters.',
        ]);

        if ($this->editingPlantilla) {
            $this->authorize('plantillas.update');
            $this->editingPlantilla->update([
                'subject' => $this->formSubject,
                'body' => $this->formBody,
                'is_active' => $this->formIsActive,
            ]);
            $this->dispatch('flash', type: 'success', message: 'Template updated successfully!');
        } else {
            $this->authorize('plantillas.create');
            RgpdPlantilla::create([
                'subject' => $this->formSubject,
                'body' => $this->formBody,
                'is_active' => $this->formIsActive,
            ]);
            $this->dispatch('flash', type: 'success', message: 'Template created successfully!');
        }

        $this->closeModals();
    }

    public function deletePlantilla(): void
    {
        if (! $this->deletingPlantilla) {
            return;
        }

        $this->authorize('plantillas.delete');
        $this->deletingPlantilla->delete();
        $this->dispatch('flash', type: 'success', message: 'Template deleted successfully!');
        $this->closeModals();
    }

    public function toggleActive(RgpdPlantilla $plantilla): void
    {
        $this->authorize('plantillas.update');
        $plantilla->update(['is_active' => ! $plantilla->is_active]);
        $this->dispatch('flash', type: 'success', message: 'Template status updated!');
    }

    public function resetForm(): void
    {
        $this->editingPlantilla = null;
        $this->formSubject = '';
        $this->formBody = '';
        $this->formIsActive = true;
    }

    public function render()
    {
        $query = RgpdPlantilla::query();

        if ($this->search) {
            $query->where('subject', 'like', "%{$this->search}%")
                ->orWhere('body', 'like', "%{$this->search}%");
        }

        if ($this->filterStatus !== '') {
            $query->where('is_active', (bool) $this->filterStatus);
        }

        return view('livewire.rgpd.plantillas.index', [
            'plantillas' => $query->paginate(15),
        ]);
    }
}
