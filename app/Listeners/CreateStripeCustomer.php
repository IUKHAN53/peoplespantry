<?php

namespace App\Listeners;

use Exception;
use Illuminate\Support\Str;
use App\DTO\Payments\Customer;
use App\Managers\PaymentManager;
use Illuminate\Auth\Events\Registered;

class CreateStripeCustomer
{
    public PaymentManager $paymentManager;

    /**
     * Create the event listener.
     */
    public function __construct(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(Registered $event)
    {
        /** @var \App\Models\User */
        try {
            $event->user->createStripeCustomer();
        } catch (Exception $ex) {
        }
    }
}
