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
    /**
     * Login User
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:20',
            'password' => 'required|string',
            'role'     => 'required|integer', // 1 untuk client, 2 untuk trainer
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('username', $request->username)
            ->where('role', $request->role)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $valid = false;
        try {
            $valid = Hash::check($request->password, $user->password);
        } catch (\Exception $e) {
            if ($request->password === $user->password) {
                $user->password = Hash::make($request->password);
                $user->save();
                $valid = true;
            }
        }

        if (!$valid) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 200);
    }

    /**
     * Register Client
     */
    public function registerClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'     => 'required|string|max:20|unique:user,username',
            'email'        => 'required|email|max:255|unique:user,email',
            'password'     => 'required|string|min:8',
            'tinggi_badan' => 'required|numeric|min:100|max:300',
            'berat_badan'  => 'required|numeric|min:40|max:500',
            'gender'       => 'required|in:L,P',
            'umur'         => 'required|integer|min:17|max:120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $result = DB::transaction(function () use ($request) {
                $user = User::create([
                    'username' => $request->username,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'role'     => 1, // 1 for client
                ]);

                Client::create([
                    'username' => $request->username,
                    'tb'       => round($request->tinggi_badan, 2),
                    'bb'       => round($request->berat_badan, 2),
                    'gender'   => $request->gender,
                    'umur'     => (int) $request->umur,
                ]);

                // Create token inside transaction to ensure rollback if it fails
                $token = $user->createToken('auth_token')->plainTextToken;

                return [
                    'message' => 'Client registered successfully',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $user
                ];
            });

            return response()->json($result, 201);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Registration failed', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Register Trainer
     */
    public function registerTrainer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'    => 'required|string|max:20|unique:user,username',
            'email'       => 'required|email|max:255|unique:user,email',
            'password'    => 'required|string|min:8',
            'nama'        => 'required|string|max:100',
            'keahlian'    => 'required|string|max:255',
            'sertifikasi' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $result = DB::transaction(function () use ($request) {
                $user = User::create([
                    'username' => $request->username,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'role'     => 2, // 2 for trainer
                ]);

                Trainer::create([
                    'username'    => $request->username,
                    'nama'        => $request->nama,
                    'keahlian'    => $request->keahlian,
                    'sertifikasi' => $request->sertifikasi,
                ]);

                // Create token inside transaction to ensure rollback if it fails
                $token = $user->createToken('auth_token')->plainTextToken;

                return [
                    'message' => 'Trainer registered successfully',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $user
                ];
            });

            return response()->json($result, 201);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Registration failed', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Logout User
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
