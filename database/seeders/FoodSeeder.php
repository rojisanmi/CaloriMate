<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        // Data berdasarkan TKPI (Tabel Komposisi Pangan Indonesia) per 1 porsi umum.
        // Ganti array ini dengan data TKPI/BPOM yang lebih lengkap jika tersedia.
        // Format: name, grammage (g/porsi), calories_per_portion (kkal), total_protein (g), total_carbo (g), total_fat (g)

        $foods = [
            // ── Karbohidrat / Makanan Pokok ──────────────────────────────────
            ['Nasi Putih',           200, 260,  4.8, 57.2,  0.4],
            ['Nasi Merah',           200, 218,  4.6, 45.8,  1.6],
            ['Roti Tawar (2 lembar)', 70,  185,  6.2, 34.5,  2.5],
            ['Mie Goreng',           200, 338,  8.8, 52.0, 11.2],
            ['Kentang Rebus',        150, 127,  2.8, 29.4,  0.1],
            ['Jagung Rebus',         150, 155,  5.0, 34.2,  2.0],
            ['Ubi Rebus',            150, 131,  1.7, 31.4,  0.2],

            // ── Protein Hewani ────────────────────────────────────────────────
            ['Ayam Goreng (1 potong)', 100, 260, 26.0,  2.5, 14.3],
            ['Telur Goreng',           60,  148, 10.4,  0.6, 11.4],
            ['Telur Rebus',            60,  93,  7.9,  0.7,  6.3],
            ['Ikan Goreng (1 ekor)',   100, 195, 22.5,  0.0, 11.8],
            ['Ikan Bakar',            100, 153, 23.2,  0.0,  6.4],
            ['Daging Sapi Rendang',    80,  193, 17.6,  4.2, 11.7],
            ['Bakso (5 butir)',       100, 175, 13.8,  9.0,  8.2],

            // ── Protein Nabati ────────────────────────────────────────────────
            ['Tempe Goreng',           80,  196, 14.2, 13.8, 10.9],
            ['Tahu Goreng',            80,   97,  7.0,  3.7,  6.5],
            ['Tempe Bacem',            80,  160, 12.0, 14.0,  6.0],

            // ── Sayuran ───────────────────────────────────────────────────────
            ['Sayur Bayam',           100,   29,  2.9,  4.0,  0.3],
            ['Tumis Kangkung',        100,   57,  3.1,  5.5,  2.7],
            ['Sayur Lodeh',           200,   95,  3.5, 10.2,  4.5],
            ['Gado-gado',             250,  253, 13.3, 21.5, 13.8],

            // ── Buah-buahan ───────────────────────────────────────────────────
            ['Pisang Ambon',           100,   92,  1.0, 23.4,  0.2],
            ['Apel',                   150,   87,  0.5, 22.7,  0.3],
            ['Jeruk',                  150,   71,  1.4, 17.4,  0.3],
            ['Semangka',               200,   62,  1.4, 15.0,  0.4],
            ['Pepaya',                 150,   62,  0.8, 15.8,  0.2],

            // ── Minuman & Lainnya ─────────────────────────────────────────────
            ['Susu Sapi (250ml)',      250,  153,  8.1, 12.0,  8.1],
            ['Susu Kedelai (250ml)',   250,  103,  7.0, 12.5,  2.5],
            ['Teh Manis',             200,   68,  0.1, 17.5,  0.0],
            ['Jus Alpukat',           250,  212,  2.3, 14.5, 17.8],

            // ── Camilan ───────────────────────────────────────────────────────
            ['Keripik Singkong',       50,  244,  1.4, 36.9, 10.1],
            ['Biskuit Gandum (5 pcs)',  50,  222,  4.3, 34.2,  8.0],
            ['Kacang Rebus',           50,  178,  8.1,  9.4, 12.6],
        ];

        $rows = [];
        foreach ($foods as [$name, $grammage, $cal, $protein, $carbo, $fat]) {
            $rows[] = [
                'name'                 => $name,
                'grammage'             => $grammage,
                'calories_per_portion' => $cal,
                'total_protein'        => $protein,
                'total_carbo'          => $carbo,
                'total_fat'            => $fat,
            ];
        }

        DB::table('foods')->insert($rows);
    }
}
