<?php

use App\Models\RgpdPlantilla;
use App\Models\User;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    // Create permissions
    $permissions = [
        'plantillas.viewAny',
        'plantillas.view',
        'plantillas.create',
        'plantillas.update',
        'plantillas.delete',
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
    }

    $this->admin = User::factory()->create(['is_active' => true]);
    $this->admin->assignRole('admin');
    $this->admin->givePermissionTo('admin.access');
    $this->admin->givePermissionTo($permissions);

    $this->superAdmin = User::factory()->create(['is_active' => true]);
    $this->superAdmin->assignRole('super-admin');

    $this->user = User::factory()->create(['is_active' => true]);
});

describe('RGPD Plantillas Management - Page Access', function () {
    test('admin can view rgpd plantillas index page', function () {
        $response = $this->actingAs($this->admin)
            ->get('/rgpd/plantillas');

        $response->assertStatus(200);
    });

    test('unauthorized user is denied from admin', function () {
        $response = $this->actingAs($this->user)
            ->get('/rgpd/plantillas');

        $response->assertStatus(403);
    });

    test('super admin can view rgpd plantillas page', function () {
        $response = $this->actingAs($this->superAdmin)
            ->get('/rgpd/plantillas');

        $response->assertStatus(200);
    });
});

describe('RGPD Plantilla Model', function () {
    test('rgpd plantilla can be created', function () {
        $plantilla = RgpdPlantilla::factory()->create([
            'subject' => 'Test Subject',
            'body' => 'Test Body Content',
        ]);

        $this->assertDatabaseHas('rgpd_plantillas', [
            'id' => $plantilla->id,
            'subject' => 'Test Subject',
            'body' => 'Test Body Content',
        ]);
    });

    test('rgpd plantilla can have is_active toggled', function () {
        $plantilla = RgpdPlantilla::factory()->create(['is_active' => true]);

        $this->assertTrue($plantilla->is_active);

        $plantilla->update(['is_active' => false]);

        $this->assertFalse($plantilla->fresh()->is_active);
    });

    test('subject and body are required on creation', function () {
        $this->expectException(\Illuminate\Database\QueryException::class);

        RgpdPlantilla::factory()->create([
            'subject' => null,
            'body' => null,
        ]);
    });
});

describe('RGPD Plantilla Factory', function () {
    test('factory creates valid rgpd plantilla', function () {
        $plantilla = RgpdPlantilla::factory()->create();

        $this->assertNotNull($plantilla->id);
        $this->assertNotNull($plantilla->subject);
        $this->assertNotNull($plantilla->body);
        $this->assertIsBool($plantilla->is_active);
    });

    test('factory creates plantilla with active status', function () {
        $plantilla = RgpdPlantilla::factory()->create();

        // is_active should be set by factory (default true or random)
        $this->assertIsBool($plantilla->is_active);
    });
});

describe('RGPD Plantilla Policy', function () {
    test('admin can view any rgpd plantillas', function () {
        $plantilla = RgpdPlantilla::factory()->create();

        $this->assertTrue($this->admin->can('viewAny', RgpdPlantilla::class));
    });

    test('admin can view specific rgpd plantilla', function () {
        $plantilla = RgpdPlantilla::factory()->create();

        $this->assertTrue($this->admin->can('view', $plantilla));
    });

    test('admin can create rgpd plantilla', function () {
        $this->assertTrue($this->admin->can('create', RgpdPlantilla::class));
    });

    test('admin can update rgpd plantilla', function () {
        $plantilla = RgpdPlantilla::factory()->create();

        $this->assertTrue($this->admin->can('update', $plantilla));
    });

    test('admin can delete rgpd plantilla', function () {
        $plantilla = RgpdPlantilla::factory()->create();

        $this->assertTrue($this->admin->can('delete', $plantilla));
    });

    test('super admin can do all actions', function () {
        $plantilla = RgpdPlantilla::factory()->create();

        $this->assertTrue($this->superAdmin->can('viewAny', RgpdPlantilla::class));
        $this->assertTrue($this->superAdmin->can('view', $plantilla));
        $this->assertTrue($this->superAdmin->can('create', RgpdPlantilla::class));
        $this->assertTrue($this->superAdmin->can('update', $plantilla));
        $this->assertTrue($this->superAdmin->can('delete', $plantilla));
    });

    test('user without permissions cannot perform actions', function () {
        $plantilla = RgpdPlantilla::factory()->create();

        $this->assertFalse($this->user->can('viewAny', RgpdPlantilla::class));
        $this->assertFalse($this->user->can('view', $plantilla));
        $this->assertFalse($this->user->can('create', RgpdPlantilla::class));
        $this->assertFalse($this->user->can('update', $plantilla));
        $this->assertFalse($this->user->can('delete', $plantilla));
    });
});

describe('RGPD Plantilla Authorization', function () {
    test('admin with permission can access the page', function () {
        $response = $this->actingAs($this->admin)->get('/rgpd/plantillas');

        $response->assertStatus(200);
    });

    test('user without permission cannot access the page', function () {
        $response = $this->actingAs($this->user)->get('/rgpd/plantillas');

        $response->assertStatus(403);
    });
});
