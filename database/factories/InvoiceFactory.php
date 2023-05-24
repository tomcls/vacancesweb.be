<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Invoice;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'invoice_num' =>  Str::random(20),
            'payment_status' =>  InvoicePaymentStatusEnum::Success,
            'date_payed' =>  Carbon::createFromTimeStamp(fake()->dateTimeBetween('-90 days', '0 days')->getTimestamp()),
        ];
    }
}
