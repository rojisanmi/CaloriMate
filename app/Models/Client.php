<?php
// app/Models/Client.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public $timestamps = false;
    protected $table = 'client';
    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['username', 'tb', 'bb', 'gender', 'umur'];

    // relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
    // relasi dengan model History
    public function histories()
    {
        return $this->hasMany(History::class, 'username', 'username');
    }

    // Business Logic Methods
    // public function calculateBMI(): float
    // {
    //     if ($this->tb <= 0)
    //         return 0;
    //     $heightInMeters = $this->tb / 100;
    //     return round($this->bb / ($heightInMeters * $heightInMeters), 2);
    // }


    // public function getBMICategory(): string
    // {
    //     $bmi = $this->calculateBMI();

    //     if ($bmi < 18.5)
    //         return 'Underweight';
    //     if ($bmi < 25)
    //         return 'Normal';
    //     if ($bmi < 30)
    //         return 'Overweight';
    //     return 'Obese';
    // }

    // Hitung Basal Metabolic Rate (BMR)
    public function calculateBMR(): float
    {
        // Mifflin-St Jeor Equation
        if ($this->gender === 'L') {
            return (10 * $this->bb) + (6.25 * $this->tb) - (5 * $this->umur) + 5;
        } else {
            return (10 * $this->bb) + (6.25 * $this->tb) - (5 * $this->umur) - 161;
        }
    }

    // Hitung kebutuhan kalori harian berdasarkan level aktivitas
    public function calculateDailyCalories(string $activityLevel = 'sedentary'): float
    {
        $bmr = $this->calculateBMR();
        $multipliers = [
            'sedentary' => 1.2,
            'light' => 1.375,
            'moderate' => 1.55,
            'active' => 1.725,
            'very_active' => 1.9
        ];

        return round($bmr * ($multipliers[$activityLevel] ?? 1.2), 2);
    }

    // Update profile
    public function updateProfile(array $data): bool
    {
        return $this->update($data);
    }

    // Dapatkan history hari ini beserta program dan konsumsi makanannya
    public function getTodayHistory()
    {
        return $this->histories()
            ->whereDate('date', today())
            ->with(['program', 'foodConsumptions.food'])
            ->first();
    }

    public static function isValidAge(int $umur): bool
    {
    return $umur >= 17;
    }
}
