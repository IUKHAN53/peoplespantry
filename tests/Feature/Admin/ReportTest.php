<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\User;
use Tests\WithLogin;
use App\Models\Order;
use App\Models\Vendor;
use Livewire\Livewire;
use App\Models\Product;
use App\Models\ProductView;
use App\Models\OrderPackage;
use App\Models\Admin\Category;
use App\Models\OrderPackagesItem;
use App\Http\Livewire\Admin\Report\ReportIndexController;

class ReportTest extends TestCase
{
    use WithLogin;

    public function test_admin_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
    }

    public function test_authorized_admin_can_see_reports_page(): void
    {
        $this->loginStaff();

        $response = Livewire::test(ReportIndexController::class, [
            'topTenProducts' => $this->test_get_top_ten_products(),
            'mostViewedProducts' => $this->test_get_most_viewed_products(),
            'lastTenOrders' => $this->test_get_last_ten_orders(),
            'topTenCustomers' => $this->test_get_top_ten_customers(),
            'topTenVendors' => $this->test_get_top_ten_vendors(),
        ])->call('render');

        $response->assertOk();
    }

    public function test_get_top_ten_products()
    {
        $user =  $this->loginStaff();

        User::factory()
            ->count(1)
            ->has(
                Product::factory(10)
                    ->has(Category::factory(1))
                    ->has(OrderPackagesItem::factory()
                        ->for(OrderPackage::factory()
                            ->for(Vendor::factory([
                                'deliver_products' => 1,
                            ]))
                            ->for(
                                Order::factory()
                                    ->for(Cart::factory()->for(User::factory()))
                            )))
            )
            ->create();

        $topTenProducts = Product::with('media')
            ->withCount('orderPackagesItems as total_sale')
            ->withAggregate('orderPackagesItems as total_price', 'sum(quantity * price)')
            ->orderByDesc('total_price')->limit(5)->get();

        $response = $this->actingAs($user)->get(route('admin.reports.index'));

        $response->assertSee($topTenProducts[0]['title']);
    }

    public function test_get_most_viewed_products()
    {
        $user =  $this->loginStaff();

        User::factory()
            ->count(1)
            ->has(
                Product::factory(10)
                    ->has(Category::factory(1))
                    ->has(ProductView::factory())
            )
            ->has(
                Vendor::factory(1)
            )
            ->create();

        $mostviewedProducts = Product::query()
            ->withCount('productViews')
            ->orderByDesc('product_views_count')->limit(5)->get();

        $response = $this->actingAs($user)->get(route('admin.reports.index'));

        $response->assertSee($mostviewedProducts[0]['title']);
    }

    public function test_get_last_ten_orders()
    {
        $user =  $this->loginStaff();

        Order::factory()
            ->for(Cart::factory()->for(User::factory()))
            ->count(20)
            ->create();

        $lastTenOrders = Order::query()
            ->with('customer')
            ->latest()->limit(10)->get();

        $response = $this->actingAs($user)->get(route('admin.reports.index'));

        $response->assertSee($lastTenOrders[0]['id']);
    }

    public function test_get_top_ten_customers()
    {
        $user =  $this->loginStaff();

        User::factory()
            ->has(Order::factory()
                ->for(Cart::factory()->for(User::factory())))
            ->count(20)
            ->create();

        $topTenCustomers = User::query()
            ->withSum('orders as revenue', 'total_amount')
            ->orderByDesc('revenue')
            ->limit(10)->get();

        $response = $this->actingAs($user)->get(route('admin.reports.index'));

        $response->assertSee($topTenCustomers[0]['id']);
        $response->assertSee($topTenCustomers[1]['stripe_customer_id']);
    }

    public function test_get_top_ten_vendors()
    {
        $user =  $this->loginStaff();

        User::factory()
            ->has(Vendor::factory())
            ->count(20)
            ->create();

        $topTenVendors = User::query()
            ->withSum('vendorOrders as revenue', 'sub_total')
            ->orderByDesc('revenue')
            ->limit(10)->get();

        $response = $this->actingAs($user)->get(route('admin.reports.index'));

        $response->assertSee($topTenVendors[0]['email']);
    }
}
