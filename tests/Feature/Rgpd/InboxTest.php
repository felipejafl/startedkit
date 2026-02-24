<?php

use App\Models\MailAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows mail accounts on the rgpd inbox page', function () {
    MailAccount::factory()->create(["name" => "Soporte", "email" => "support@example.com"]);

    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('super-admin');
    $this->actingAs($user);

    $response = $this->get(route('rgpd.inbox'));

    $response->assertStatus(200);
    $response->assertSee('Soporte');
});
