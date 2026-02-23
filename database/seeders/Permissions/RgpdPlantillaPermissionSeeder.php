<?php

namespace Database\Seeders\Permissions;

/**
 * Create permissions for RGPD Plantillas CRUD resource.
 *
 * Usage: php artisan db:seed --class="Database\Seeders\Permissions\RgpdPlantillaPermissionSeeder"
 */
class RgpdPlantillaPermissionSeeder extends CrudPermissionSeeder
{
    protected string $resource = 'plantillas';
}
