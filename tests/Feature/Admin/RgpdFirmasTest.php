<?php

use App\Models\MailAccount;
use App\Models\RgpdFirma;
use App\Models\User;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    // Create permissions
    $permissions = [
        'firmas.viewAny',
        'firmas.view',
        'firmas.create',
        'firmas.update',
        'firmas.delete',
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

describe('RGPD Firmas Management - Page Access', function () {
    test('admin can view rgpd firmas index page', function () {
        $response = $this->actingAs($this->admin)
            ->get('/rgpd/firmas');

        $response->assertStatus(200);
    });

    test('unauthorized user is denied from admin', function () {
        $response = $this->actingAs($this->user)
            ->get('/rgpd/firmas');

        $response->assertStatus(403);
    });

    test('super admin can view rgpd firmas page', function () {
        $response = $this->actingAs($this->superAdmin)
            ->get('/rgpd/firmas');

        $response->assertStatus(200);
    });
});

describe('RGPD Firma Model', function () {
    test('rgpd firma can be created', function () {
        $mail = MailAccount::factory()->create(['email' => 'mail@example.com']);

        $firma = RgpdFirma::factory()->create([
            'mail_account_id' => $mail->id,
            'firma' => 'My signature',
        ]);

        $this->assertDatabaseHas('rgpd_firmas', [
            'id' => $firma->id,
            'mail_account_id' => $mail->id,
            'firma' => 'My signature',
        ]);
    });

    test('rgpd firma can have is_active toggled', function () {
        $firma = RgpdFirma::factory()->create(['is_active' => true]);

        $this->assertTrue($firma->is_active);

        $firma->update(['is_active' => false]);

        $this->assertFalse($firma->fresh()->is_active);
    });
});

describe('RGPD Firma Policy', function () {
    test('admin can view any rgpd firmas', function () {
        $firma = RgpdFirma::factory()->create();

        $this->assertTrue($this->admin->can('viewAny', RgpdFirma::class));
    });

    test('admin can create rgpd firma', function () {
        $this->assertTrue($this->admin->can('create', RgpdFirma::class));
    });

    test('user without permissions cannot perform actions', function () {
        $firma = RgpdFirma::factory()->create();

        $this->assertFalse($this->user->can('viewAny', RgpdFirma::class));
        $this->assertFalse($this->user->can('create', RgpdFirma::class));
    });
});
