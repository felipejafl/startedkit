<?php

namespace App\Livewire\Concerns;

use Livewire\Component;

/**
 * Base trait for CRUD form components (create/edit).
 * Provides consistent form handling, validation, and modal behavior.
 */
trait WithCrudForm
{
    /** @var bool Modal visibility */
    public bool $show = false;

    /** @var bool Form is submitting */
    public bool $isSubmitting = false;

    /**
     * Get validation rules.
     * Override in component.
     *
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * Get custom validation messages.
     * Override in component.
     *
     * @return array<string, mixed>
     */
    protected function messages(): array
    {
        return [];
    }

    /**
     * Close the modal and reset form.
     */
    public function close(): void
    {
        $this->show = false;
        $this->resetFormFields();
    }

    /**
     * Reset all form fields.
     * MUST be overridden in component.
     * Example: $this->reset(['formName', 'formEmail']);
     */
    protected function resetFormFields(): void
    {
        // Override in component
    }

    /**
     * Typical save method structure.
     * Component should override with specific logic.
     */
    public function save(): void
    {
        $this->isSubmitting = true;

        try {
            $this->validate();

            // Component handles actual save logic
            $this->performSave();

            $this->dispatch('flash', type: 'success', message: 'Saved successfully!');
            $this->dispatch('itemSaved');
            $this->close();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->isSubmitting = false;

            throw $e;
        }
    }

    /**
     * Perform the actual save operation.
     * MUST be overridden in component.
     */
    protected function performSave(): void
    {
        // Override in component
    }
}
