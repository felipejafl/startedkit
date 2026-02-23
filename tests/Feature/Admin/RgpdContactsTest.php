<?php

use App\Models\RgpdContact;
use App\Models\User;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    // Create permissions
    $permissions = [
        'contacts.viewAny',
        'contacts.view',
        'contacts.create',
        'contacts.update',
        'contacts.delete',
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

describe('RGPD Contacts Management - Page Access', function () {
    test('admin can view rgpd contacts index page', function () {
        $response = $this->actingAs($this->admin)
            ->get('/rgpd/contacts');

        $response->assertStatus(200);
    });

    test('unauthorized user is denied from admin', function () {
        $response = $this->actingAs($this->user)
            ->get('/rgpd/contacts');

        $response->assertStatus(403);
    });

    test('super admin can view rgpd contacts page', function () {
        $response = $this->actingAs($this->superAdmin)
            ->get('/rgpd/contacts');

        $response->assertStatus(200);
    });
});

describe('RGPD Contact Model', function () {
    test('rgpd contact can be created', function () {
        $contact = RgpdContact::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertDatabaseHas('rgpd_contacts', [
            'id' => $contact->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    });

    test('rgpd contact can have note', function () {
        $contact = RgpdContact::factory()->create([
            'note' => 'This is a test note',
        ]);

        $this->assertEquals('This is a test note', $contact->note);
    });

    test('rgpd contact can have is_active toggled', function () {
        $contact = RgpdContact::factory()->create(['is_active' => true]);

        $this->assertTrue($contact->is_active);

        $contact->update(['is_active' => false]);

        $this->assertFalse($contact->fresh()->is_active);
    });

    test('email must be unique', function () {
        RgpdContact::factory()->create(['email' => 'unique@test.com']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        RgpdContact::factory()->create(['email' => 'unique@test.com']);
    });
});

describe('RGPD Contact Factory', function () {
    test('factory creates valid rgpd contact', function () {
        $contact = RgpdContact::factory()->create();

        $this->assertNotNull($contact->id);
        $this->assertNotNull($contact->name);
        $this->assertNotNull($contact->email);
        $this->assertTrue($contact->is_active);
    });

    test('factory creates contact with optional note', function () {
        $contact = RgpdContact::factory()->create();

        // Note can be null or a string
        $this->assertTrue(
            $contact->note === null || is_string($contact->note)
        );
    });
});

describe('RGPD Contact Policy', function () {
    test('admin can view any rgpd contacts', function () {
        $contact = RgpdContact::factory()->create();

        $this->assertTrue($this->admin->can('viewAny', RgpdContact::class));
    });

    test('admin can view specific rgpd contact', function () {
        $contact = RgpdContact::factory()->create();

        $this->assertTrue($this->admin->can('view', $contact));
    });

    test('admin can create rgpd contact', function () {
        $this->assertTrue($this->admin->can('create', RgpdContact::class));
    });

    test('admin can update rgpd contact', function () {
        $contact = RgpdContact::factory()->create();

        $this->assertTrue($this->admin->can('update', $contact));
    });

    test('admin can delete rgpd contact', function () {
        $contact = RgpdContact::factory()->create();

        $this->assertTrue($this->admin->can('delete', $contact));
    });

    test('super admin can do all actions', function () {
        $contact = RgpdContact::factory()->create();

        $this->assertTrue($this->superAdmin->can('viewAny', RgpdContact::class));
        $this->assertTrue($this->superAdmin->can('view', $contact));
        $this->assertTrue($this->superAdmin->can('create', RgpdContact::class));
        $this->assertTrue($this->superAdmin->can('update', $contact));
        $this->assertTrue($this->superAdmin->can('delete', $contact));
    });

    test('user without permissions cannot perform actions', function () {
        $contact = RgpdContact::factory()->create();

        $this->assertFalse($this->user->can('viewAny', RgpdContact::class));
        $this->assertFalse($this->user->can('view', $contact));
        $this->assertFalse($this->user->can('create', RgpdContact::class));
        $this->assertFalse($this->user->can('update', $contact));
        $this->assertFalse($this->user->can('delete', $contact));
    });
});

describe('RGPD Contact Authorization', function () {
    test('admin with permission can access the page', function () {
        $response = $this->actingAs($this->admin)->get('/rgpd/contacts');

        $response->assertStatus(200);
    });

    test('user without permission cannot access the page', function () {
        $response = $this->actingAs($this->user)->get('/rgpd/contacts');

        $response->assertStatus(403);
    });
});
