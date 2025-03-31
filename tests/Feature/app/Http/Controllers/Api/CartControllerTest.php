<?php

namespace Tests\Feature\app\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestRequests;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestRequests;

    /**
     * @return void
     */
    public function test_successfully_added_to_cart(): void
    {
        $response = $this->tokenRequest();
        $token = $response->json('data.id');

        $productTest = Product::factory()->create();

        $this->withToken($token)->postJson(
            route('api.cart.add', $productTest->getKey()),
        )->assertCreated();

        $response = $this->withToken($token)->getJson(
            route('api.cart.index')
        );

        $response
            ->assertJsonIsArray('data')
            ->assertJsonStructure([
                'data' => [
                    ['id', 'type', 'attributes']
                ]
            ]);

        $this->assertEquals($productTest->getKey(), $response->json('data.0.id'));
    }
}
