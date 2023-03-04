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

    # docker-compose exec api php artisan test --filter=ProductControllerTest::test_store
    public function test_store()
    {
        User::factory()->count(2)->has(
            Store::factory()->count(1)->has(
                Product::factory()->count(1)
            )
        )->create();

        $response = $this->post('api/products', [
            "store_id" => 1,
            "name" => "test product",
            "description" => "test product description",
            "price" => 2,
            "stock" => 100
        ]);

        $response->assertExactJson([
            "data" => [
                "message" => $response['data']['message'],
                "collection" => $response['data']['collection']
            ]
        ])->assertStatus(201);
    }

    # docker-compose exec api php artisan test --filter=ProductControllerTest::test_index
    public function test_show()
    {
        User::factory()->count(2)->has(
            Store::factory()->count(1)->has(
                Product::factory()->count(1)
            )
        )->create();

        $response = $this->get('api/products/1');

        $response->assertExactJson([
            "data" => [
                "message" => $response['data']['message'],
                "collection" => $response['data']['collection']
            ]
        ])->assertStatus(200);
    }
}
