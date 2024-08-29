<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequert;
use App\Http\Requests\RegisterRequert;
use App\Models\User;
use App\Models\UsersMeta;
use App\Service\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Event\TestSuite\Loaded;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequert $request)
    {
        return $this->authService->register($request->all());
    }

    public function login(LoginRequert $request)
    {
        return $this->authService->login($request->only('email', 'password'));
    }

    public function logout(Request $request)
    {
        return $this->authService->logout();
    }

    public function user(Request $request)
    {
        $usersMeta = UsersMeta::with(['user', 'tickets','messages', 'ratings'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$usersMeta) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'data' => $usersMeta,
        ]);
    }
}
