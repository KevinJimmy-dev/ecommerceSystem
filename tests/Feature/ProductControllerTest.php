<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Tests\TestCase;

# docker-compose exec api php artisan test --filter=ProductControllerTest
class ProductControllerTest extends TestCase
{
    # docker-compose exec api php artisan test --filter=ProductControllerTest::test_index
    public function test_index() 
    {
        User::factory()->count(2)->has(
            Store::factory()->count(1)->has(
                Product::factory()->count(1)
            )
        )->create();

        $response = $this->get('api/products');

        $response->assertExactJson([
            "data" => [
                "message" => $response['data']['message'],
                "collection" => $response['data']['collection']
            ]
        ])->assertStatus(200);
    }
}
