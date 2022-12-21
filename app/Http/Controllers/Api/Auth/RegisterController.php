<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Modules\Import\ImportProjectData\Enum\RoleEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

#[Group('Register / Login')]
class RegisterController extends Controller
{
    #[Unauthenticated]
    #[Endpoint("Register", "User Register")]
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => RoleEnum::SELLER,
            ]);

            return response()->json([
                'message' => 'User Created Successfully',
                'token' => $user->createToken('AUTH')->plainTextToken,
            ], Response::HTTP_CREATED);

        } catch (Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
