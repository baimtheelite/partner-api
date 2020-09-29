<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'email'     => 'required|unique:users|email',
            'password'  => 'required|min:6'
        ]);

        $email          = $request->input('email');
        $password       = $request->input('password');
        $hashPassword   = Hash::make($password);

        $user = User::create([
            'email'     => $email,
            'password'  => $hashPassword
        ]);

        return response()->json(['message' => 'berhasil daftar'], 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'nik'     => 'required',
            'password'  => 'required|min:6'
        ]);

        $nik = $request->input('nik');
        $password = $request->input('password');

        $user = User::where('nik', $nik)->first();

        if (!$user) {
            return response()->json(['message' => 'login failed'], 401);
        }

        $isValidPassword = Hash::check($password, $user->password);

        if (!$isValidPassword) {
            return response()->json(["message" => "Password untuk nik $nik salah"], 401);
        }

        $generateToken = bin2hex(random_bytes(40));

        $user->update([
            'token' => $generateToken
        ]);

        return response()->json($user);
    }

    public function updatePassword(Request $request)
    {
        $user = User::where('nik', $request->input('nik'));
        $user->update(['password' => Hash::make('bfisyariah')]);
    }
}
