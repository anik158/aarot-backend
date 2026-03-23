<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    use ResponseStatus;

    public function login(AuthLoginRequest $request){
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token  = $user->createToken('ecommerce-frontend')->accessToken;

            $responseData  = [
                'isLoggedIn'  => true,
                'user' => new UserResource($user),
                'access_token'     => $token,
            ];

            return $this->success($responseData, 'User login successfully.');
        }

        return $this->error('Invalid Credentials', 401);
    }

    public function store(UserRequest $request)
    {
        if ($request->validated()) {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);

            return $this->success($user, 'User has been created');
        }

        return $this->error('User not created', 400);
    }

    public function update(UserRequest $request, User $user){
        if($request->validated()){
            $user->update($request->validated());
            return $this->success($user, 'User has been updated');
        }

        return $this->error('User is not updated', 400);
    }

    public function destroy(User $user){
        $user->delete();
    }


    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'User has been logged out');
    }
}
