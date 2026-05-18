<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('username', $request->username)
            ->where('role', $request->role)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Hapus token lama
        $user->tokens()->delete();

        // Generate token baru
        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER CLIENT
    |--------------------------------------------------------------------------
    */

    public function registerClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:20|unique:user,username',
            'email' => 'required|email|max:255|unique:user,email',
            'password' => 'required|string|min:8',
            'tinggi_badan' => 'required|numeric|min:100|max:300',
            'berat_badan' => 'required|numeric|min:40|max:500',
            'gender' => 'required|in:L,P',
            'umur' => 'required|integer|min:17|max:120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {

            DB::beginTransaction();

            // Create User
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 1,
            ]);

            // Create Client
            Client::create([
                'username' => $request->username,
                'tb' => round($request->tinggi_badan, 2),
                'bb' => round($request->berat_badan, 2),
                'gender' => $request->gender,
                'umur' => (int) $request->umur,
            ]);

            // Generate Token
            $token = $user->createToken('mobile-token')->plainTextToken;

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Client registered successfully',
                'data' => [
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $user
                ]
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER TRAINER
    |--------------------------------------------------------------------------
    */

    public function registerTrainer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:20|unique:user,username',
            'email' => 'required|email|max:255|unique:user,email',
            'password' => 'required|string|min:8',
            'nama' => 'required|string|max:100',
            'keahlian' => 'required|string|max:255',
            'sertifikasi' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {

            DB::beginTransaction();

            // Create User
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 2,
            ]);

            // Create Trainer
            Trainer::create([
                'username' => $request->username,
                'nama' => $request->nama,
                'keahlian' => $request->keahlian,
                'sertifikasi' => $request->sertifikasi,
            ]);

            // Generate Token
            $token = $user->createToken('mobile-token')->plainTextToken;

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trainer registered successfully',
                'data' => [
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $user
                ]
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }
}