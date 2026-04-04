<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ApiAuthTest extends TestCase
{
    /**
     * Test Trainer Registration
     */
    public function test_trainer_registration_and_food_creation(): void
    {
        $username = 'testtrainer_' . time();
        $response = $this->postJson('/api/register/trainer', [
            'username' => $username,
            'email' => $username . '@example.com',
            'password' => 'password123',
            'nama' => 'Test Trainer',
            'keahlian' => 'Weight Loss',
            'sertifikasi' => 'PT-1',
        ]);

        $response->dump();
        $response->assertStatus(200)->assertJsonStructure(['access_token', 'token_type']);

        $token = $response->json('access_token');

        // Test Profile
        $profileResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/trainer/profile');

        $profileResponse->assertStatus(200)->assertJsonPath('user.username', $username);

        // Test Food Creation
        $foodResponse = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/trainer/foods', [
            'name' => 'Nasi Goreng Test',
            'grammage' => 100,
            'calories_per_portion' => 200,
            'total_fat' => 10,
            'total_carbo' => 30,
            'total_protein' => 5,
        ]);

        $foodResponse->assertStatus(201)->assertJsonPath('food.name', 'Nasi Goreng Test');
    }
}
