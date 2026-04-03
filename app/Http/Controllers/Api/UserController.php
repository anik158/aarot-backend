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

    public function profile(Request $request) {
        return $this->success(new UserResource($request->user()), 'Profile fetched');
    }

    public function updateProfile(Request $request) {
        $user = $request->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                $oldPath = public_path('storage/images/users/'.$user->profile_image);
                if (file_exists($oldPath)) { @unlink($oldPath); }
            }
            $imageName = time().'.'.$request->profile_image->extension();  
            $request->profile_image->move(public_path('storage/images/users/'), $imageName);
            $user->profile_image = $imageName;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->zip_code = $request->zip_code;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return $this->success(new UserResource($user), 'Profile updated successfully');
    }
}
