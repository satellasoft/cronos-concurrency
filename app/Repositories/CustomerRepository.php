<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Throwable;

class CustomerRepository
{

    /**
     * Store new Customer
     *
     * @param  array $data Array with data to store
     * @return Customer Return store Customer or nullable in error case
     */
    public function store(array $data): ?Customer
    {
        try {
            $customer = new Customer();
            $customer->name  = $data['name'];
            $customer->amount  = $data['amount'];

            $customer->save();

            return $customer;
        } catch (Throwable $th) {
            Log::error(
                'Error during STORE customer!',
                [
                    'message' => $th->getMessage()
                ]
            );
            throw $th;

            return null;
        }
    }

    /**
     * Store new Customer
     *
     * @param  int $customerId Customer ID reference
     * @param  array $data Array with data to update
     * @return Customer Return Customer if updated
     */
    public function updateBalance(int $customerId, array $data): Customer
    {
        try {
            DB::beginTransaction();

            $currentAmount = Customer::where('id', $customerId)->lockForUpdate()->value('amount');

            Customer::where('id', $customerId)->update(['before_amount' => $currentAmount]);

            Customer::where('id', $customerId)->update(['amount' => $data['amount']]);

            DB::commit();

            return Customer::find($customerId);
        } catch (Throwable $th) {
            DB::rollBack();

            Log::error('Error during UPDATE Balance!', [
                'message' => $th->getMessage(),
                'id' => $customerId
            ]);

            throw $th;

            return null;
        }
    }

    /**
     * Get Customer current amount
     *
     * @param  int $customerId Customer ID
     * @return Customer Return Customer data
     * 
     * @throws Throwable
     */
    public function getBalance(int $customerId): Customer
    {
        try {
            $customer = Customer::find($customerId);

            if (!$customer)
                throw new BadRequestException(__('customer.not_found'));

            return $customer;
        } catch (Throwable $th) {
            Log::error(
                'Customer not found',
                [
                    'id'      => $customerId,
                    'message' => $th->getMessage()
                ]
            );
            throw $th;

            return null;
        }
    }
}
