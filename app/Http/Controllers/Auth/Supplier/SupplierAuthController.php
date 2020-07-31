<?php

namespace App\Http\Controllers\Auth\Supplier;

use App\Supplier;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SupplierRegisterValidator;

class SupplierAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:supplier_api', ['except' => ['login', 'register']]);
    }

    /**
     * Register new Supplier.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(SupplierRegisterValidator $request)
    {
        $validated = $request->validated();
        $supplier = Supplier::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);
        $token = auth('supplier_api')->login($supplier);

        return response()->json([
            'massage' => 'success',
            'token' => $token,
        ]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('supplier_api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('supplier_api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('supplier_api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('supplier_api')->factory()->getTTL() * 60
        ]);
    }





}
