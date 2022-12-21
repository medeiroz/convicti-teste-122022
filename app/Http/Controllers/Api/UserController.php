<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;

#[Group('User')]
class UserController extends Controller
{
    #[Endpoint("Get User", "Get logged user")]
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
