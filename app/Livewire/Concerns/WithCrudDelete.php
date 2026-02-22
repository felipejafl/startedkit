<?php

namespace App\Livewire\Concerns;

/**
 * Base trait for CRUD delete confirmation components.
 * Provides consistent delete handling and confirmation behavior.
 */
trait WithCrudDelete
{
    /** @var bool Modal visibility */
    public bool $show = false;

    /** @var bool Delete is in progress */
    public bool $isDeleting = false;

    /**
     * Open delete confirmation modal.
     * MUST be overridden in component to set $this->model.
     *
     * @param  mixed  $model
     */
    public function open($model): void
    {
        $this->model = $model;
        $this->show = true;
    }

    /**
     * Close the modal.
     */
    public function close(): void
    {
        $this->show = false;
        $this->resetDeleteModel();
    }

    /**
     * Perform the delete operation.
     * Component must override this.
     */
    public function delete(): void
    {
        $this->isDeleting = true;

        try {
            $this->performDelete();

            $this->dispatch('flash', type: 'success', message: 'Deleted successfully!');
            $this->dispatch('itemDeleted');
            $this->close();
        } catch (\Exception $e) {
            $this->isDeleting = false;
            $this->dispatch('flash', type: 'error', message: $e->getMessage());
        }
    }

    /**
     * Perform the actual delete action.
     * MUST be overridden in component.
     */
    protected function performDelete(): void
    {
        // Override in component
    }

    /**
     * Reset the model after delete.
     * Override in component if needed.
     */
    protected function resetDeleteModel(): void
    {
        // Override in component if needed
    }
}
