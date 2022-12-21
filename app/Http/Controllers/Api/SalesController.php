<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleRequest;
use App\Modules\Import\ImportProjectData\Enum\RoleEnum;
use App\Repositories\SaleRepository;
use App\Services\SalesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;

#[Group('Sales')]
class SalesController extends Controller
{
    public function __construct(
        private readonly SaleRepository $saleRepository,
        private readonly SalesService $salesService,
    )
    {
    }

    #[Endpoint("List Sales", "Get List Sales")]
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->saleRepository->getDatatableByUser($request->user())->getPaginator(),
        );
    }

    #[Endpoint("Create Sale", "Create a new Sale")]
    public function store(SaleRequest $request): JsonResponse
    {
        if ($request->user()->role !== RoleEnum::SELLER->value) {
            throw new UnauthorizedException('You are not a seller');
        }

        $sale = $this->saleRepository->create($request->validated())
            ->load(['seller', 'roamingBranchOffice']);

        return response()->json($sale);
    }

    #[Endpoint("Show Sale", "Show Sale Details")]
    public function show(int $id, Request $request): JsonResponse
    {
        $sale = $this->saleRepository->findOrFail($id)
            ->load(['seller', 'roamingBranchOffice']);

        if (!$this->salesService->checkUserCanShow($request->user(), $sale)) {
            throw new UnauthorizedException('You don\'t have permission to view this sale');
        }

        return response()->json($sale);
    }

    #[Endpoint("Update Sale", "Update Existent Sale Details")]
    public function update(int $id, SaleRequest $request): JsonResponse
    {
        $sale = $this->saleRepository->update($id, $request->validated())
            ->load(['seller', 'roamingBranchOffice']);

        if ($sale->seller->id !== $request->user()->id) {
            throw new UnauthorizedException('This sale does not belong to you');
        }

        return response()->json($sale);
    }
}
