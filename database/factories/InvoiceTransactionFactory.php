<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Support\Str;
use App\Http\Livewire\Admin\House\House;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceTransaction>
 */
class InvoiceTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'invoice_id' => Invoice::factory(),
            'invoice_num' =>  Str::random(20),
            'price' =>  149.00,
            'reference' =>  House::factory(),
            'type' =>  'house_publication',
        ];
    }
}
