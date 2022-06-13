<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;

class AuthController extends Controller
{

    use ApiResponser;

    /**
     * Get a JWT via given credentials.
     * @param \App\Http\Requests\AuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = $this->validateLogin();
        if ($validator->fails()) {
            return $this->errorsResponse($validator->messages(), 422);
        }

        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return $this->errorResponse(__('Invalid user or password. Please try again or reset your password'), 401);
        }
        return $this->respondWithToken($token, __('Login successfully'));
    }


    /**
     * Get the authenticated User.
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            $user = User::with('roles', 'userDetails')->findOrFail(auth()->id());
            return $this->successResponse($user, __('Authenticated user'),'success_user', 201);
        } catch (ModelNotFoundException $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return $this->successResponse(null, __('Logout successfully'),'success_logut', 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            return $this->respondWithToken(auth()->refresh(), 'Refresh token');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

    }

    private function validateLogin()
    {
        return Validator::make(request()->all(), [
            'email' => 'required|string|email',
            'password' => 'required',
        ]);
    }
}
