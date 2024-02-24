<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Money;
use App\Http\Requests\Customer\CustomerFormRequest;
use App\Http\Resources\Customer\CustomerBalanceResource;
use App\Http\Resources\Customer\CustomerResource;
use App\Repositories\CustomerRepository;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    private CustomerRepository $customerRepository;

    public function __construct()
    {
        $this->customerRepository = new CustomerRepository();
    }

    /**
     * Store new User
     *
     * @param  CustomerFormRequest $request Validate and return validated field
     * @return \Illuminate\Http\JsonResponse Return Json if customer data
     */
    public function store(CustomerFormRequest $request): \Illuminate\Http\JsonResponse
    {
        $form = $request->validated();

        $form['amount'] = Money::toDecimal($form['amount']);

        $customer = $this->customerRepository->store($form);

        if (!$customer || $customer == null)
            return response()->json(
                [
                    'error' => __('customer.error_store')
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );

        return response()->json(new CustomerResource($customer), Response::HTTP_CREATED);
    }

    public function update(int $customerId)
    {
        dd('update ' . $customerId);
    }

    /**
     * Get Customer balance by ID
     *
     * @param  mixed $customerId
     * @return \Illuminate\Http\JsonResponse Return Customer balance
     */
    public function getBalance(int $customerId): \Illuminate\Http\JsonResponse
    {
        $customer = $this->customerRepository->getBalance($customerId);

        if (!$customer || $customer == null)
            return response()->json(
                [
                    'error' => __('customer.not_found')
                ],
                404
            );

        return response()->json(new CustomerBalanceResource($customer));
    }
}
