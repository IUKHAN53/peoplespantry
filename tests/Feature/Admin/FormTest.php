<?php

namespace Tests\Feature\Admin;

use App\Http\Livewire\Admin\Form\FormIndexController;
use App\Http\Livewire\Admin\Form\FormShowController;
use App\Models\Form;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\WithLogin;

class FormTest extends TestCase
{
    use WithLogin;

    public function test_admin_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
    }

    public function test_authorized_admin_can_see_forms_page(): void
    {
        $this->loginStaff();

        $response = Livewire::test(FormIndexController::class)
            ->call('render');

        $response->assertOk();
    }

    public function test_admin_can_search_customer_by_first_name(): void
    {
        $this->loginStaff();

        $forms = Form::factory()->times(10)->create();

        $randomFormField = $forms->random();

        /** Test Search by first name */
        $response = Livewire::test(FormIndexController::class)
            ->set('search', $randomFormField->first_name)
            ->call('render');

        $response->assertOk();
        $response->assertSee($randomFormField->first_name);

        /** Test Search by invalid keyword */
        $response = Livewire::test(FormIndexController::class)
            ->set('search', 'No record found')
            ->call('render');

        $response->assertOk();
    }

    public function test_authorized_admin_can_see_detail_of_each_single_form(): void
    {
        $this->loginStaff();

        $form = Form::factory()->count(1)->create()->first();

        $response = Livewire::test(
            FormShowController::class,
            [
                'form' => $form
            ]
        )->call('render');
        
        $response->assertOk();

        $this->assertCount(
            1,
            Form::query()->where('first_name', $form->first_name)->get()
        );
    }
}
