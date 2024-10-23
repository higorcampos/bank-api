<?php

namespace Database\Seeders;

use App\Models\Accounts;
use Illuminate\Database\Seeder;

class AccountsSeeder extends Seeder
{
    /**
     * Seed the users table.
     */
    public function run(): void
    {
        Accounts::factory()->count(30)->create();
    }
}