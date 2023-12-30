<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\User;
use Tests\WithLogin;
use App\Models\Order;
use App\Models\Vendor;
use Livewire\Livewire;
use App\Models\OrderPackage;
use Illuminate\Support\Facades\DB;
use App\Http\Livewire\Admin\Dashboard\DashboardController;
use App\Http\Livewire\Admin\Dashboard\SalesPerformance;
use App\Models\Admin\Category;
use App\Models\OrderPackagesItem;
use App\Models\Product;

class DashboardTest extends TestCase
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

        $response = Livewire::test(DashboardController::class, [
            'totalUsers' => $this->test_get_total_users(),
            'totalSales' => $this->test_get_total_sales(),
            'totalPayables' => $this->test_get_total_payables(),
            'totalProducts' => $this->test_get_total_products(),
            'totalOrders' => $this->test_get_total_orders(),
            'recentOrders' => $this->test_get_recent_orders(),
            'topSellingProducts' => $this->test_get_top_selling_products(),
        ])
            ->call('render');

        $response->assertOk();
    }

    public function test_get_total_users()
    {
        $user = $this->loginStaff();

        User::factory()->count(10)->create();

        $totalUsers = User::all()->count();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();

        $this->assertEquals(10, $totalUsers);
    }

    public function test_get_total_sales()
    {
        $this->loginStaff();

        OrderPackage::factory(['status' => 'completed'])
            ->for(Vendor::factory([
                'deliver_products' => 1,
            ]))
            ->for(
                Order::factory()
                    ->for(Cart::factory()->for(User::factory()))
            )
            ->count(5)
            ->create()
            ->first();

        $totalSales = OrderPackage::query()
            ->completed()
            ->select(
                DB::raw('SUM(sub_total + shipping_fee) as total')
            )
            ->get()
            ->sum('total');

        $response = $this->get(route('admin.dashboard'));

        $response->assertOk();

        $response->assertSee($totalSales);
    }

    public function test_get_total_payables()
    {
        $this->loginStaff();

        $totalPayablesOfAllVendors = User::has('vendor')->sum('balance');

        $response = $this->get(route('admin.dashboard'));

        $response->assertOk();

        $response->assertSee($totalPayablesOfAllVendors);
    }

    public function test_get_total_products()
    {
        $user = $this->loginStaff();

        Product::factory()->count(10)->create();

        $totalProducts = Product::all()->count();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();

        $this->assertEquals(10, $totalProducts);
    }

    public function test_get_total_orders()
    {
        $user = $this->loginStaff();

        Order::factory()
            ->for(Cart::factory()->for(User::factory()))
            ->count(6)
            ->create();

        $totalOrders = Order::all()->count();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();

        $response->assertSee($totalOrders);
    }

    public function test_get_recent_orders()
    {
        $user = $this->loginStaff();

        Order::factory()
            ->for(Cart::factory()->for(User::factory()))
            ->count(5)
            ->create();

        $latestOrders = Order::query()
            ->with('customer')
            ->latest()->limit(6)->get();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertSee($latestOrders[0]['order_number']);
    }

    public function test_get_top_selling_products()
    {
        $this->loginStaff();

        $user = User::factory()->count(1)->create()->first();

        Product::factory(10)
            ->has(Category::factory(1))
            ->has(OrderPackagesItem::factory()
                ->for(
                    OrderPackage::factory()
                        ->for(Vendor::factory([
                            'deliver_products' => 1,
                        ]))
                        ->for(
                            Order::factory()
                                ->for(Cart::factory()->for($user))
                        )
                ))

            ->create();

        $topSellingProducts = Product::query()
            ->with('media')
            ->withCount('orderPackagesItems as total_sale')
            ->withAggregate('orderPackagesItems as total_price', 'sum(quantity * price)')
            ->orderByDesc('total_price')
            ->limit(2)
            ->get();

        $response = $this->actingAs($user)->get(route('admin.reports.index'));

        $response->assertSee($topSellingProducts[0]['title']);
    }

    public function test_get_sales_performance()
    {
        $user = $this->loginStaff(true);

        $start = '2022-11-23 09:45:17';
        $end = '2022-11-26 10:40:10';

        $order = Order::factory([
            'created_at' => '2022-11-25 09:45:17'
        ])
            ->for(Cart::factory()->for(User::factory()))
            ->count(5)
            ->create()
            ->first();

        $response = Livewire::actingAs($user)
            ->test(SalesPerformance::class)
            ->set('range', [
                'from' => $start,
                'to' => $end,
            ])->call('render');

        $response->assertOk();

        $this->assertCount(
            1,
            Order::query()->where('order_number', $order->order_number)->get()
        );
    }
}
