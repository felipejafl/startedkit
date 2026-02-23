<?php

namespace App\Livewire\Concerns;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithPagination;

/**
 * Base trait for CRUD listing components.
 * Provides consistent pagination, filtering, and query string handling.
 */
trait WithCrudListing
{
    use AuthorizesRequests, WithPagination;

    /** @var string Search query */
    public string $search = '';

    /** @var int Items per page */
    public int $perPage = 15;

    /** @var string Sort column */
    public string $sortBy = 'created_at';

    /** @var string Sort direction (asc, desc) */
    public string $sortDirection = 'desc';

    protected $queryString = ['search', 'sortBy', 'sortDirection', 'page'];

    /**
     * Update sort and reset pagination.
     */
    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    /**
     * Reset all filters and pagination.
     */
    public function resetFilters(): void
    {
        $this->search = '';
        $this->sortBy = 'created_at';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    /**
     * Update items per page.
     */
    public function setPerPage(int $count): void
    {
        $this->perPage = $count;
        $this->resetPage();
    }

    /**
     * Helpful reminder: When building queries in your Index component,
     * ensure you use soft delete queries to exclude deleted records:
     *
     * $query = Model::query();
     * // Soft delete is automatic - Eloquent excludes deleted_at != null by default
     * // Only use ->withTrashed() or ->onlyTrashed() when explicitly needed
     */
    protected function applySoftDeleteFilter(): void
    {
        // Soft deletes are handled automatically by Eloquent
        // Models with SoftDeletes trait exclude deleted_at != null from queries
    }
}
