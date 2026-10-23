<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController extends Controller
{
    // Listado de clientes con paginado
    public function index(): AnonymousResourceCollection
    {
        $customers = Customer::latest('id')->paginate(10);

        return CustomerResource::collection($customers);
    }

    // Almacenar un cliente
    public function store(CustomerStoreRequest $request): JsonResponse
    {
        $customer = Customer::create($request->validated());

        return (new CustomerResource($customer))
            ->response()
            ->setStatusCode(201);
    }

    // Consultar un cliente
    public function show(Customer $customer): CustomerResource
    {
        return new CustomerResource($customer);
    }

    // Actualizar un cliente
    public function update(CustomerUpdateRequest $request, Customer $customer): CustomerResource
    {
        $customer->update($request->validated());

        return new CustomerResource($customer);
    }

    // Eliminar un cliente
    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json([
            'message' => 'Cliente eliminado exitosamente.',
        ], 200);
    }
}