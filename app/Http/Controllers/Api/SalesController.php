<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleRequest;
use App\Repositories\SaleRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function __construct(
        private readonly SaleRepository $saleRepository
    )
    {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->saleRepository->getDatatableByUser($request->user())->getPaginator(),
        );
    }

    public function store(SaleRequest $request): JsonResponse
    {
        $sale = $this->saleRepository->create($request->validated())
            ->load(['seller', 'roamingBranchOffice']);

        return response()->json($sale);
    }

    public function show(int $id): JsonResponse
    {
        $sale = $this->saleRepository->find($id)->load(['seller', 'roamingBranchOffice']);
        return response()->json($sale);
    }

    public function update(int $id, SaleRequest $request): JsonResponse
    {
        $sale = $this->saleRepository->update($id, $request->validated())
            ->load(['seller', 'roamingBranchOffice']);

        return response()->json($sale);
    }
}
