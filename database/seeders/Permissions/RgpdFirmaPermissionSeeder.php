<?php
namespace Database\Seeders\Permissions;
/**
 * Create permissions for RGPD Firmas CRUD resource.
 *
 * Usage: php artisan db:seed --class="Database\Seeders\Permissions\RgpdPlantillaPermissionSeeder"
 */
class RgpdFirmaPermissionSeeder extends CrudPermissionSeeder
{
    protected string $resource = 'firmas';
}
