<?php

use App\Models\User;

it('allows admins into the dashboard and blocks regular users', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});
