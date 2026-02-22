<?php

use App\Models\MailAccount;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_active' => true]);
    $this->admin->assignRole('admin');
    $this->admin->givePermissionTo('admin.access');
    $this->admin->givePermissionTo([
        'mail-accounts.viewAny',
        'mail-accounts.view',
        'mail-accounts.create',
        'mail-accounts.update',
        'mail-accounts.delete',
    ]);

    $this->superAdmin = User::factory()->create(['is_active' => true]);
    $this->superAdmin->assignRole('super-admin');

    $this->user = User::factory()->create(['is_active' => true]);
});

describe('Mail Accounts Management - Page Access', function () {
    test('admin can view mail accounts index page', function () {
        $response = $this->actingAs($this->admin)
            ->get('/admin/mail-accounts');

        $response->assertStatus(200);
    });

    test('unauthorized user is redirect from admin', function () {
        $response = $this->actingAs($this->user)
            ->get('/admin/mail-accounts');

        // Unauthorized users get 403 Forbidden
        $response->assertStatus(403);
    });

    test('super admin can view mail accounts page', function () {
        $response = $this->actingAs($this->superAdmin)
            ->get('/admin/mail-accounts');

        $response->assertStatus(200);
    });
});

describe('Mail Account Model', function () {
    test('mail account can be created', function () {
        $account = MailAccount::factory()->create([
            'name' => 'Company Support',
            'email' => 'support@company.com',
        ]);

        $this->assertDatabaseHas('mail_accounts', [
            'id' => $account->id,
            'name' => 'Company Support',
            'email' => 'support@company.com',
        ]);
    });

    test('mail account password is encrypted', function () {
        $account = MailAccount::factory()->create([
            'password' => 'mySecurePassword123',
        ]);

        // Decrypt and verify
        $this->assertNotEquals('mySecurePassword123', $account->getAttributes()['password']);
        $this->assertEquals('mySecurePassword123', $account->password);
    });

    test('mail account can have is_active toggled', function () {
        $account = MailAccount::factory()->create(['is_active' => true]);

        $this->assertTrue($account->is_active);

        $account->update(['is_active' => false]);

        $this->assertFalse($account->fresh()->is_active);
    });

    test('email must be unique', function () {
        MailAccount::factory()->create(['email' => 'unique@test.com']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        MailAccount::factory()->create(['email' => 'unique@test.com']);
    });
});

describe('Mail Account Factory', function () {
    test('factory creates valid mail account', function () {
        $account = MailAccount::factory()->create();

        $this->assertNotNull($account->id);
        $this->assertNotNull($account->name);
        $this->assertNotNull($account->email);
        $this->assertNotNull($account->server);
        $this->assertNotNull($account->password);
        $this->assertNotNull($account->imap_port);
        $this->assertNotNull($account->imap_security);
        $this->assertNotNull($account->smtp_port);
        $this->assertNotNull($account->smtp_security);
        $this->assertTrue($account->is_active);
    });

    test('factory creates accounts with valid IMAP ports', function () {
        $account = MailAccount::factory()->create();

        $this->assertContains($account->imap_port, [993, 143]);
    });

    test('factory creates accounts with valid SMTP ports', function () {
        $account = MailAccount::factory()->create();

        $this->assertContains($account->smtp_port, [587, 465]);
    });

    test('factory creates accounts with valid security options', function () {
        $account = MailAccount::factory()->create();

        $this->assertContains($account->imap_security, ['none', 'ssl', 'tls']);
        $this->assertContains($account->smtp_security, ['none', 'ssl', 'tls']);
    });
});

describe('Mail Account Policy', function () {
    test('admin can view any mail accounts', function () {
        $account = MailAccount::factory()->create();

        $this->assertTrue($this->admin->can('viewAny', MailAccount::class));
    });

    test('admin can view specific mail account', function () {
        $account = MailAccount::factory()->create();

        $this->assertTrue($this->admin->can('view', $account));
    });

    test('admin can create mail account', function () {
        $this->assertTrue($this->admin->can('create', MailAccount::class));
    });

    test('admin can update mail account', function () {
        $account = MailAccount::factory()->create();

        $this->assertTrue($this->admin->can('update', $account));
    });

    test('admin can delete mail account', function () {
        $account = MailAccount::factory()->create();

        $this->assertTrue($this->admin->can('delete', $account));
    });

    test('unauthorized user cannot view any mail accounts', function () {
        $this->assertFalse($this->user->can('viewAny', MailAccount::class));
    });

    test('super admin can do everything', function () {
        $account = MailAccount::factory()->create();

        $this->assertTrue($this->superAdmin->can('viewAny', MailAccount::class));
        $this->assertTrue($this->superAdmin->can('view', $account));
        $this->assertTrue($this->superAdmin->can('create', MailAccount::class));
        $this->assertTrue($this->superAdmin->can('update', $account));
        $this->assertTrue($this->superAdmin->can('delete', $account));
    });
});
