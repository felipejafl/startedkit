<?php

namespace Database\Seeders\Permissions;

/**
 * Create permissions for RGPD Contacts CRUD resource.
 *
 * Usage: php artisan db:seed --class="Database\Seeders\Permissions\RgpdContactPermissionSeeder"
 */
class RgpdContactPermissionSeeder extends CrudPermissionSeeder
{
    protected string $resource = 'contacts';
}
