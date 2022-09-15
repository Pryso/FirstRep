<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function registration(RegisterRequest $request) {
        $data = $request->validated();

        $user = User::create([
            'username' => $data['username'] = htmlspecialchars(strip_tags($data['username'])),
            'name' => htmlspecialchars(strip_tags($data['name'])),
            'password' => Hash::make($data['password']),
            'email' => $data['email'],
        ]);

        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
        ],200);
    }
    public function login(LoginRequest $request) {
        $data = $request->validated();
        if (!Auth::attempt($data)) {
            return response()->json([
            'fail' => 'Неверные данные'
            ], 401);
        }
            $user = User::where('email', $request['email'])->firstOrFail();
            $token = $user->createToken('token')->plainTextToken;
            return response()->json([
                       'access_token' => $token,
            ],200);
    }
    public function edit(EditRequest $request) {
        $user = User::where('id',$request->user()->id)->first();
        if($user->status === "BANNED") {
            return response()->json(['fail' => 'User is blocked'],403);
        }
        $data = $request->validated();
        User::where('id',$request->user()->id)->update([
            'name' => htmlspecialchars(strip_tags($data['name'])),
            'username' => htmlspecialchars(strip_tags($data['username']))
        ]);
        return response()->json([
            'success' => 'Данные отредактированы',
            'data' => [
                'name' => htmlspecialchars(strip_tags($data['name'])),
                'username' => htmlspecialchars(strip_tags($data['username'])),
            ]
            ]);
    }
    public function user(Request $request)
    {   
        if($request->header('User-Id') === 'ID') {
            return $request->user();
        }
        return response()->json(['fail' => 'Ошибка'],403);
    }
    public function destroy(Request $request) {
        $request->user()->currentAccessToken()->delete();

        User::where('id',$request->user()->id)->delete();

        return response()->json(['success' => 'Пользователь удалён'],200);
    }
}
