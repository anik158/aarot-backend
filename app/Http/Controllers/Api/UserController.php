<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    use ResponseStatus;

    public function login(AuthLoginRequest $request){

    }

    public function store(UserRequest $request){
        if($request->validated()){
            $user = User::create($request->validated());

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

    public function authLogin(AuthLoginRequest $request){
        if(!isset($request->email) || !isset($request->password)){
            return $this->error('Email or Password required', 400);
        }

        if($request->validated()){
            $user = User::where('email', $request->email)->first();
            if(!empty($user) || !Hash::check($request->password, $user->password)){
                return $this->error('Credentials not matched', 401);
            }else{
                return response()->json([
                    'user' => UserResource::make($user),
                    'access_token' => $user->createToken('auth_token')->plainTextToken
                ]);
            }
        }

        return $this->error('Something went wrong', 500);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'User has been logged out');
    }
}
