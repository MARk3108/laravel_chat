<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = auth()->user()->createToken('MyApp')->accessToken;

        return response()->json(['token' => $token]);
    }


    public function updateProfile(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name' => 'string|max:255',
        'password' => 'string|min:8|nullable',
    ]);

    if ($request->has('name')) {
        $user->name = $request->name;
    }

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return response()->json($user);
}
}