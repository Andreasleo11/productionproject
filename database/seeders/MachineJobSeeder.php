<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class MachineJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users with specification_id = 2
        $users = User::where('specification_id', 2)->get();

        // Loop through the users and insert data into your table
        foreach ($users as $user) {
            DB::table('machine_jobs')->insert([
                'user_id' => $user->id
            ]);
        }
    }
}
