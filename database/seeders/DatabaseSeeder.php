<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SubjectSeeder::class,
        ]);

        // 3 Teacher Accounts
        User::factory()->teacher()->create([
            'name' => 'Teacher One',
            'email' => 'teacher1@example.com',
        ]);
        User::factory()->teacher()->create([
            'name' => 'Teacher Two',
            'email' => 'teacher2@example.com',
        ]);
        User::factory()->teacher()->create([
            'name' => 'Teacher Three',
            'email' => 'teacher3@example.com',
        ]);

        // 20 Student Accounts
        User::factory(20)->student()->create();
    }
}
