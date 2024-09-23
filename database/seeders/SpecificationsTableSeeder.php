<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timestamp = Carbon::now();
        DB::table('specifications')->truncate();
        DB::table('specifications')->insert([
            [
                'name' => 'Operator',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Machine',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'PE',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Store',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            // Add more entries if needed
        ]);
    }
}
