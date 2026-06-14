<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
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
            // Hanya client (1) & trainer (2) yang boleh login lewat mobile.
            // Admin (0) hanya lewat web.
            'role'     => 'required|integer|in:1,2',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('username', $request->username)
            ->where('role', $request->role)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Username atau password salah.'], 401);
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
            return response()->json(['message' => 'Username atau password salah.'], 401);
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
            'photo'        => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'username.required'     => 'Username wajib diisi.',
            'username.unique'       => 'Username sudah digunakan, coba yang lain.',
            'username.max'          => 'Username maksimal 20 karakter.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'tinggi_badan.required' => 'Tinggi badan wajib diisi.',
            'tinggi_badan.min'      => 'Tinggi badan minimal 100 cm.',
            'tinggi_badan.max'      => 'Tinggi badan maksimal 300 cm.',
            'berat_badan.required'  => 'Berat badan wajib diisi.',
            'berat_badan.min'       => 'Berat badan minimal 40 kg.',
            'berat_badan.max'       => 'Berat badan maksimal 500 kg.',
            'gender.required'       => 'Jenis kelamin wajib dipilih.',
            'umur.required'         => 'Umur wajib diisi.',
            'umur.min'              => 'Umur minimal 17 tahun.',
            'umur.max'              => 'Umur maksimal 120 tahun.',
            'photo.required'        => 'Foto profil wajib diunggah.',
            'photo.image'           => 'File harus berupa gambar.',
            'photo.mimes'           => 'Format foto harus jpg, jpeg, png, atau webp.',
            'photo.max'             => 'Ukuran foto maksimal 2 MB.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $photoPath = $request->file('photo')->store('avatars', 'public');

        try {
            $result = DB::transaction(function () use ($request, $photoPath) {
                $user = User::create([
                    'username' => $request->username,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password),
                    'role'     => 1, // 1 for client
                ]);

                Client::create([
                    'username'   => $request->username,
                    'tb'         => round($request->tinggi_badan, 2),
                    'bb'         => round($request->berat_badan, 2),
                    'gender'     => $request->gender,
                    'umur'       => (int) $request->umur,
                    'photo_path' => $photoPath,
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
