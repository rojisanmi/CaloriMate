<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Artisan command to generate scheduled reminder notifications
 * for food intake and exercise based on each client's preferred reminder times.
 *
 * Designed to be run every minute via the Laravel scheduler.
 */
class GenerateNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'notifications:generate';

    /**
     * The console command description.
     */
    protected $description = 'Generate food and exercise reminder notifications for clients based on their configured reminder times';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = Carbon::now('Asia/Jakarta');
        $currentHour = $now->format('H');
        $currentMinute = $now->format('i');
        $today = $now->toDateString();

        $this->info("Generating notifications for {$now->toDateTimeString()} (Asia/Jakarta)");

        $foodCount = $this->generateFoodReminders($currentHour, $currentMinute, $today);
        $exerciseCount = $this->generateExerciseReminders($currentHour, $currentMinute, $today);

        $this->info("Created {$foodCount} food reminder(s) and {$exerciseCount} exercise reminder(s).");

        return self::SUCCESS;
    }

    /**
     * Generate food reminder notifications for clients whose
     * food_reminder_time matches the current hour and minute.
     */
    private function generateFoodReminders(string $hour, string $minute, string $today): int
    {
        $count = 0;

        $clients = Client::whereNotNull('food_reminder_time')->get();

        foreach ($clients as $client) {
            $reminderTime = Carbon::parse($client->food_reminder_time);

            // Compare only hour and minute (ignore seconds)
            if ($reminderTime->format('H') !== $hour || $reminderTime->format('i') !== $minute) {
                continue;
            }

            // Check if a food notification already exists for this user today
            $alreadyExists = Notification::where('username', $client->username)
                ->where('type', 'food')
                ->whereDate('notify_at', $today)
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            Notification::create([
                'username'  => $client->username,
                'title'     => 'Pengingat Input Makanan',
                'message'   => 'Waktunya mencatat makanan kamu! Jangan lupa input makanan hari ini ya 🍽️',
                'type'      => 'food',
                'icon'      => 'restaurant',
                'notify_at' => Carbon::now('Asia/Jakarta'),
                'is_read'   => false,
            ]);

            $count++;
        }

        return $count;
    }

    /**
     * Generate exercise reminder notifications for clients whose
     * exercise_reminder_time matches the current hour and minute.
     */
    private function generateExerciseReminders(string $hour, string $minute, string $today): int
    {
        $count = 0;

        $clients = Client::whereNotNull('exercise_reminder_time')->get();

        foreach ($clients as $client) {
            $reminderTime = Carbon::parse($client->exercise_reminder_time);

            // Compare only hour and minute (ignore seconds)
            if ($reminderTime->format('H') !== $hour || $reminderTime->format('i') !== $minute) {
                continue;
            }

            // Check if an exercise notification already exists for this user today
            $alreadyExists = Notification::where('username', $client->username)
                ->where('type', 'exercise')
                ->whereDate('notify_at', $today)
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            Notification::create([
                'username'  => $client->username,
                'title'     => 'Pengingat Jadwal Olahraga',
                'message'   => 'Saatnya berolahraga! Ayo jaga kebugaran tubuhmu hari ini 💪',
                'type'      => 'exercise',
                'icon'      => 'fitness_center',
                'notify_at' => Carbon::now('Asia/Jakarta'),
                'is_read'   => false,
            ]);

            $count++;
        }

        return $count;
    }
}
