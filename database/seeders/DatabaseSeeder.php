<?php

namespace Database\Seeders;

use App\Models\Departement;
use App\Models\Employee;
use App\Models\Position;
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
        User::create([
            'name'                  => 'Rama Adhitya Setiadi',
            'email'                 => 'rama@gmail.com',
            'password'              => bcrypt('password'),
            'email_verified_at'     => now()
        ]);

        collect([
            ['name' => 'IT', 'active' => true],
            ['name' => 'HR', 'active' => true],
            ['name' => 'Finance', 'active' => true],
            ['name' => 'QA', 'active' => true],
        ])->each(fn ($item) => Departement::create($item));

        collect([
            ['name' => 'Staff'],
            ['name' => 'Programmer'],
            ['name' => 'Admin'],
            ['name' => 'SPV'],

        ])->each(fn ($item) => Position::create($item));

        Employee::factory(20)->create();
    }
}
