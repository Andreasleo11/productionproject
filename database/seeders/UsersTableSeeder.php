<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timestamp = Carbon::now();
        DB::table('users')->truncate();
        DB::table('users')->insert([
            [
                'specification_id' => 1, // assuming a valid specification_id exists
                'name' => 'Administrator',
                'email' => 'admin@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('Administrator'), // Use bcrypt or another hashing method
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 3, // assuming a valid specification_id exists
                'name' => 'PE',
                'email' => 'pe@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('PE'), // Use bcrypt or another hashing method
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 4,
                'name' => 'Store',
                'email' => 'store@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('Store'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => 'VTC',
                'email' => 'VTC@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('VTC'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0050A',
                'email' => '0050A@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0050A'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0050B',
                'email' => '0050B@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0050B'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0110A',
                'email' => '0110A@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0110A'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0150A',
                'email' => '0150A@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0150A'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0150B',
                'email' => '0150B@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0150B'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0150C',
                'email' => '0150C@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0150C'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0150D',
                'email' => '0150D@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0150D'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0150E',
                'email' => '0150E@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0150E'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0150F',
                'email' => '0150F@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0150F'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0170A',
                'email' => '0170A@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0170A'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0170B',
                'email' => '0170B@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0170B'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0240A',
                'email' => '0240A@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0240A'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0290A',
                'email' => '0290A@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0290A'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0290C',
                'email' => '0290C@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0290C'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0290D',
                'email' => '0290D@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0290D'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0350A',
                'email' => '0350A@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0350A'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0350B',
                'email' => '0350B@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0350B'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0350C',
                'email' => '0350C@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0350C'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0350E',
                'email' => '0350E@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0350E'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0350F',
                'email' => '0350F@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0350F'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0450A',
                'email' => '0450A@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0450A'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0450B',
                'email' => '0450B@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0450B'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0450C',
                'email' => '0450C@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0450C'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0450E',
                'email' => '0450E@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0450E'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0450F',
                'email' => '0450F@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0450F'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0450G',
                'email' => '0450G@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0450G'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0450H',
                'email' => '0450H@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0450H'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0450I',
                'email' => '0450I@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0450I'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0450J',
                'email' => '0450J@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0450J'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0550B',
                'email' => '0550B@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0550B'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0650A',
                'email' => '0650A@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0650A'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0650C',
                'email' => '0650C@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0650C'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0650D',
                'email' => '0650D@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0650D'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0650E',
                'email' => '0650E@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0650E'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0700A',
                'email' => '0700A@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0700A'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0700B',
                'email' => '0700B@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0700B'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0850B',
                'email' => '0850B@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0850B'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0850D',
                'email' => '0850D@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0850D'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0900A',
                'email' => '0900A@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0900A'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '0900B',
                'email' => '0900B@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('0900B'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '1050A',
                'email' => '1050A@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('1050A'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '1050B',
                'email' => '1050B@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('1050B'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '1050C',
                'email' => '1050C@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('1050C'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '1050D',
                'email' => '1050D@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('1050D'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '1300A',
                'email' => '1300A@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('1300A'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '1300B',
                'email' => '1300B@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('1300B'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '1300C',
                'email' => '1300C@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('1300C'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '1400A',
                'email' => '1400A@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('1400A'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '1400B',
                'email' => '1400B@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('1400B'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 2,
                'name' => '1400C',
                'email' => '1400C@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('1400C'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'specification_id' => 5,
                'name' => 'budiman',
                'email' => 'budiman@daijo.co.id',
                'email_verified_at' => $timestamp,
                'password' => Hash::make('budiman1234'),
                'remember_token' => Str::random(10),
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ]);
    }
}
