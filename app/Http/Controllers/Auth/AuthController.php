<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Repositories\AuthRepositories;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authRepo;
    public function __construct(AuthRepositories $authRepo)
    {
        $this->authRepo = $authRepo;
    }
    public function login(AuthRequest $request)
    {
        return $this->authRepo->login($request);
    }
    public function logout(Request $request)
    {
        return $this->authRepo->logout($request);
    }
}
