<?php

namespace App\Repositories;

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
        } catch (\Exception $ex) {
            Log::error(
                'Error during STORE customer!',
                [
                    'message' => $ex->getMessage()
                ]
            );
            return null;
        }
    }

    /**
     * Store new Customer
     *
     * @param  int $customerId Customer ID reference
     * @param  array $data Array with data to update
     * @return bool Return return true if update or false otherwise
     */
    public function update(int $customerId, array $data): bool
    {
        try {
            $customer = Customer::find($customerId);
            $customer->name  = $data['name'];
            $customer->amount  = $data['amount'];

            return $customer->save();
        } catch (\Exception $ex) {
            Log::error('Error during UPDATE customer!', $ex->getMessage());
            return false;
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
        }
    }
}
