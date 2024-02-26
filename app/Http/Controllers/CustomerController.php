<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Money;
use App\Http\Requests\Customer\CustomerFormRequest;
use App\Http\Requests\Customer\CustomerUpdateBalanceRequest;
use App\Http\Resources\Customer\CustomerBalanceResource;
use App\Http\Resources\Customer\CustomerResource;
use App\Jobs\UpdateBalanceJob;
use App\Repositories\CustomerRepository;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    private CustomerRepository $customerRepository;

    /**
     * @OA\Tag(
     *     name="Customer",
     *     description="Operações relacionadas a clientes"
     * )
     */

    public function __construct()
    {
        $this->customerRepository = new CustomerRepository();
    }

    /**
     * @OA\Post(
     *     path="/api/customer",
     *     summary="Cadastra um novo cliente",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados do cliente",
     *         @OA\JsonContent(
     *             required={"name", "amount"},
     *             @OA\Property(property="name", type="string", example="Leide das Neves"),
     *             @OA\Property(property="amount", type="numeric", format="float", example=99.98)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cliente cadastrado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     tags={"Customer"}
     * )
     *
     * Cadastra um novo cliente.
     *
     * @param  CustomerFormRequest $request Validate and return validated field
     * @return \Illuminate\Http\JsonResponse Return Json if customer data
     */
    public function store(CustomerFormRequest $request): \Illuminate\Http\JsonResponse
    {
        $payload = $request->validated();

        $payload['amount'] = Money::toDecimal($payload['amount']);

        $customer = $this->customerRepository->store($payload);

        if (!$customer || $customer == null)
            return response()->json(
                [
                    'error' => __('customer.error_store')
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );

        return response()->json(new CustomerResource($customer), Response::HTTP_CREATED);
    }

    /**
     *
     * @OA\Put(
     *     path="/api/customer/{customerId}/update-balance",
     *     summary="Atualiza o saldo do cliente",
     *     @OA\Parameter(
     *         name="customerId",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados para atualizar o saldo",
     *         @OA\JsonContent(
     *             required={"amount"},
     *             @OA\Property(property="amount", type="number", format="float", example=599.9)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Depósito enviado para a fila de processamento",
     *         @OA\JsonContent()
     *     ),
     *     tags={"Customer"}
     * )
     * 
     * Update customer balance
     *
     * @param  int $customerId ID from customer ID
     * @param  CustomerUpdateBalanceRequest $request $validated fields
     * @return Illuminate\Http\JsonResponse
     */
    public function updateBalance(int $customerId, CustomerUpdateBalanceRequest $request): \Illuminate\Http\JsonResponse
    {
        UpdateBalanceJob::dispatch($customerId, $request->validated())->onQueue('balance-updates');

        return response()->json(__('customer.sent_to_proccess'), Response::HTTP_OK);
    }

    /**
     * * @OA\Get(
     *     path="/api/customer/{customerId}",
     *     summary="Obtém o saldo do cliente por ID",
     *     @OA\Parameter(
     *         name="customerId",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retorna o saldo do cliente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="amount", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cliente não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cliente não encontrado")
     *         )
     *     ),
     *     tags={"Customer"}
     * )
     * 
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
                Response::HTTP_NOT_FOUND
            );

        return response()->json(new CustomerBalanceResource($customer));
    }
}
