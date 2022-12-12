<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $messages = [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',

            'password.required' => 'Mật khẩu không được trống',
        ];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => [
                'required',
            ],
        ], $messages);
        if ($validator->fails()) {
            return response([
                'status' => 'error',
                'message' => $validator->errors()->all(),
            ]);
        }
        // $credentials = request(['email', 'password']);
        // if (!$token = auth('api')->attempt($credentials)) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        // return response()->json(auth('api')->user())->header('Authorization', $token);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // return response()->json(Auth::user(),200);
            return response([
                'status' => 'success',
                'user' => Auth::user()
            ], 200);
        } else {
            return response([
                'status' => 'error',
                'message' => ['Đăng nhập không thành công']
            ]);
        }
    }
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        return response()->json(['message' => 'Successfully logged out'],200);
    }

    public function user()
    {
        return response()->json(auth('api')->user());
    }
}
