<?php

namespace App\Jobs;

use App\Http\Helpers\Money;
use App\Repositories\CustomerRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $customerId;
    protected array $payload;

    /**
     * Create a new job instance.
     */
    public function __construct(int $customerId, array $payload)
    {
        $this->customerId = $customerId;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->payload['amount'] = Money::toDecimal($this->payload['amount']);

        if (!is_numeric($this->payload['amount'])) {
            throw new \InvalidArgumentException(__('customer.invalid_amount_value'));
        }

        $customer = (new CustomerRepository())->updateBalance($this->customerId, $this->payload);
    }
}
