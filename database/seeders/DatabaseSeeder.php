<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Mostafa',
            'email' => 'mustafa@alhlebi.co',
            'password' => bcrypt('Mostafa_ameen00'),
        ]);
        User::factory()->create([
            'name' => 'Mohammed Alhalabi',
            'email' => 'mhq.gg93@gmail.com',
            'password' => bcrypt('123456789'),
        ]);

        $this->call([
            ProjectSeeder::class,
            EmployeeSeeder::class,
            ScheduleSeeder::class,
        ]);
    }
}
