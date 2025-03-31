<?php

namespace Tests\Traits;

use Database\Factories\UserFactory;
use Illuminate\Testing\TestResponse;

trait TestRequests
{
    protected function tokenRequest(bool $invalid = false): TestResponse
    {
        $user = UserFactory::new([
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ])->create();

        return $this->postJson(route('api.authenticate'), [
            'email' => $user->email,
            'password' => $invalid ? 'password111' : 'password',
        ]);
    }
}
