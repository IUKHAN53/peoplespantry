<?php

namespace Tests\Feature\Frontend\Customer;

use App\Http\Livewire\Frontend\Customer\PaymentMethods\PaymentMethodCreateController;
use App\Http\Livewire\Frontend\Customer\PaymentMethods\PaymentMethodIndexController;
use App\Models\PaymentMethod;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\WithLogin;

class PaymentMethodTest extends TestCase
{
    use WithLogin;

    public function test_guest_user_cannot_access_customer_payments_listing_page()
    {
        $response = $this->get(route('customer.payment.index'));
        $response->assertRedirect(
            route('login')
        );
    }

    public function test_authorized_customers_can_access_payments_listing_page()
    {
        $this->loginUser();

        $response = $this->get(route('customer.payment.index'));
        $response->assertOk();
    }

    public function test_guest_user_cannot_access_customer_create_payment_page()
    {
        $response = $this->get(route('customer.payment.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_authorized_customers_cannot_create_a_new_payment_method_with_invalid_data()
    {
        $this->loginUser();

        $response = Livewire::test(PaymentMethodCreateController::class)
            ->set('name', '')
            ->set('card_number', '')
            ->set('exp_month', '')
            ->set('exp_year', '')
            ->set('cvc', '')
            ->set('isPrimary', '')
            ->call('submit');

        $response->assertHasErrors([
            'name',
            'card_number',
            'exp_year',
            'exp_month',
        ]);
    }

    public function test_authorized_customers_can_create_a_new_payment_method_with_valid_data()
    {
        $this->loginUser();

        $response = Livewire::test(PaymentMethodCreateController::class)
            ->set('name', 'test')
            ->set('card_number', '4242424242424242')
            ->set('exp_month', '8')
            ->set('exp_year', '2025')
            ->set('cvc', '123')
            ->set('isPrimary', true)
            ->call('submit');

        $response->assertHasNoErrors()
            ->assertStatus(200);
    }

    public function test_authorized_customers_can_view_all_of_his_payment_methods()
    {
        $paymentMethod = PaymentMethod::factory()
            ->count(5)
            ->for(User::factory())
            ->create()
            ->first();

        $response = Livewire::actingAs($paymentMethod->user)
            ->test(PaymentMethodIndexController::class)
            ->call('render');

        $response->assertOk();
    }

    public function test_authorized_customer_can_delete_payment_method()
    {
        $paymentMethod = PaymentMethod::factory()
            ->count(1)
            ->for(User::factory())
            ->create()
            ->first();

        $response = Livewire::actingAs($paymentMethod->user)
            ->test(PaymentMethodIndexController::class);

        $response->call('delete', $paymentMethod->id);

        $response->assertOk();

        $response->assertEmitted(
            'alert-success'
        );

        $this->assertCount(0, PaymentMethod::query()->ofUser($paymentMethod->user)->get());
    }

    public function test_authorized_customer_cannot_delete_primary_payment_method()
    {
        $paymentMethod = PaymentMethod::factory()
            ->primary()
            ->count(1)
            ->for(User::factory())
            ->create()
            ->first();

        $response = Livewire::actingAs($paymentMethod->user)
            ->test(PaymentMethodIndexController::class)
            ->call('delete', $paymentMethod->id);

        $response->assertOk();

        $response->assertEmitted(
            'alert-danger'
        );

        $this->assertCount(1, PaymentMethod::query()->ofUser($paymentMethod->user)->get());
    }
}
