<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

/**
 * Base seeder for creating permissions for a CRUD resource.
 *
 * Usage in your CRUD seeder:
 *   class ProductPermissionSeeder extends CrudPermissionSeeder {
 *       protected $resource = 'products';
 *   }
 */
abstract class CrudPermissionSeeder extends Seeder
{
    /** @var string The resource name (lowercase, snake_case) */
    protected string $resource = '';

    /** @var string The guard name */
    protected string $guard = 'web';

    /**
     * Run the seeder.
     */
    public function run(): void
    {
        if (! $this->resource) {
            throw new \InvalidArgumentException(
                'You must define $resource property in '.static::class
            );
        }

        $this->createPermissions();
    }

    /**
     * Create standard CRUD permissions.
     */
    protected function createPermissions(): void
    {
        $actions = ['viewAny', 'view', 'create', 'update', 'delete'];

        foreach ($actions as $action) {
            $name = "{$this->resource}.{$action}";

            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => $this->guard],
                ['name' => $name, 'guard_name' => $this->guard]
            );
        }

        $this->command->info("Created permissions for '{$this->resource}' resource.");
    }

    /**
     * Get all permission names for this resource.
     *
     * @return array<int, string>
     */
    public function getPermissionNames(): array
    {
        return [
            "{$this->resource}.viewAny",
            "{$this->resource}.view",
            "{$this->resource}.create",
            "{$this->resource}.update",
            "{$this->resource}.delete",
        ];
    }
}
