<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Tests\WithLogin;
use App\Models\Vendor;
use Livewire\Livewire;
use App\Models\Product;
use App\Models\Admin\Category;
use App\Http\Livewire\Admin\Catalog\Product\ProductShowController;
use App\Http\Livewire\Admin\Catalog\Product\ProductIndexController;

class ProductTest extends TestCase
{
    use WithLogin;

    public function test_guest_users_cannot_see_product_listing_page()
    {
        $response = $this->get(
            route('admin.catalog.product.index')
        );

        $response->assertRedirect(
            route('admin.login')
        );
    }

    public function test_authorized_users_can_see_product_listing_page()
    {
        $this->loginStaff(true);

        $response = $this->get(
            route('admin.catalog.product.index')
        );

        $response->assertOk();
    }

    public function test_authorized_admin_can_view_all_products()
    {
        $product = User::factory()
            ->count(1)
            ->has(
                Product::factory(10)
                    ->has(Category::factory(1))
            )
            ->has(
                Vendor::factory(1)
            )
            ->create()
            ->first();

        $response = Livewire::actingAs($product)
            ->test(ProductIndexController::class)
            ->call('render');

        $response->assertSee($product->title)
            ->assertOk();
    }

    public function test_admin_can_sort_product_record(): void
    {
        $user = User::factory()
            ->count(1)
            ->has(
                Product::factory(10)
                    ->has(Category::factory(1))
            )
            ->has(
                Vendor::factory(1)
            )
            ->create()
            ->first();

        $product = Product::all();

        /** Test Sort By latest order */
        $response = Livewire::actingAs($user)
            ->test(ProductIndexController::class)
            ->set('sortBy', 'latest')
            ->call('render');

        $response->assertSeeInOrder([
            $product[0]['09/30/22'],
            $product[1]['09/28/22'],
            $product[2]['09/26/22'],
        ]);

        /** Test Sort By oldest order */
        $response = Livewire::actingAs($user)
            ->test(ProductIndexController::class)
            ->set('sortBy', 'oldest')
            ->call('render');

        $response->assertSeeInOrder([
            $product[0]['09/26/22'],
            $product[1]['09/28/22'],
            $product[2]['09/30/22'],
        ]);

        ///** Test Sort By ascending title order */
        $response = Livewire::actingAs($user)
            ->test(ProductIndexController::class)
            ->set('sortBy', 'asc')
            ->call('render');

        $response->assertSeeInOrder([
            $product[2]['abc'],
            $product[0]['def'],
            $product[6]['ghi'],
        ]);

        /** Test Sort By descending title order */
        $response = Livewire::test(ProductIndexController::class)
            ->set('sortBy', 'desc')
            ->call('render');

        $response->assertSeeInOrder([
            $product[6]['ghi'],
            $product[0]['def'],
            $product[2]['abc'],
        ]);
    }

    public function test_admin_can_search_product_by_name_or_slug()
    {
        $user = User::factory()
            ->count(1)
            ->has(
                Product::factory(5)
                    ->has(Category::factory(1))
            )
            ->has(
                Vendor::factory(1)
            )
            ->create()
            ->first();

        $product = Product::all();

        $response = Livewire::actingAs($user)
            ->test(ProductIndexController::class)
            ->set('search', $product->get(1)->title);

        $response->assertSee($product->get(1)->title);

        $response->assertDontSee($product->get(2)->title);

        $response = Livewire::actingAs($user)
            ->test(ProductIndexController::class)
            ->set('search', 'No record found');

        $response->assertOk();
    }

    public function test_authorized_admin_cannot_update_product_with_invalid_data()
    {
        $user = User::factory()
            ->count(1)
            ->has(
                Product::factory(10)
                    ->has(Category::factory(1))
            )
            ->has(
                Vendor::factory(1)
            )
            ->create()
            ->first();

        $product = Product::first();

        $response = Livewire::actingAs($user)
            ->test(ProductShowController::class, [
                'product' => $product,
            ])
            ->set('images', [])
            ->set('nutritionInputs.0.nutrition_id', '')
            ->set('nutritionInputs.0.unit_id', '')
            ->set('nutritionInputs.0.value', '')
            ->set('product.title', '')
            ->set('product.description', '')
            ->set('product.available_quantity', '')
            ->set('product.unit_id', '')
            ->set('product.price', '')
            ->call('update');

        $response->assertOk();
        $response->assertHasErrors([
            'images',
            'nutritionInputs.*.nutrition_id',
            'nutritionInputs.*.unit_id',
            'nutritionInputs.*.value',
            'product.title',
            'product.description',
            'product.available_quantity',
            'product.unit_id',
            'product.price',
        ]);
    }

    public function test_authorized_admin_can_update_product_with_valid_data()
    {
        $user = User::factory()
            ->count(1)
            ->has(
                Product::factory(10)
                    ->has(Category::factory(1))
            )
            ->has(
                Vendor::factory(1)
            )
            ->create()
            ->first();

        $product = Product::first();

        $response = Livewire::actingAs($user)
            ->test(ProductShowController::class, [
                'product' => $product,
            ])
            ->set('product.title', 'Cupcake updated')
            ->set('product.description', 'Lorem ipsum updated')
            ->set('product.available_quantity', 10)
            ->set('product.is_gluten_free', 1)
            ->set('product.is_vegan', null)
            ->set('product.is_published', 1)
            ->call('update');

        $response->assertOk();

        $this->assertCount(
            1,
            Product::query()->where('title', 'Cupcake updated')->get()
        );
    }
    
}
