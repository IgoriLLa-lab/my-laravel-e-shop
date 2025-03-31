<?php

namespace Tests\Feature\app\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestRequests;

class CatalogControllerTest extends TestCase
{
    use RefreshDatabase;
    use TestRequests;


    public function test_successfully_index_response(): void
    {
        $token = $this->tokenRequest()->json('data.id');

        Product::factory()->count(5)->create();

        $this->withToken($token)->getJson(
            route('catalog.index')
        );

    }
}
