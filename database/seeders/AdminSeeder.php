<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('authorization')->updateOrInsert(
            ['username' => 'adminutama'],
            [
                'email' => 'adminutama@batukota.go.id',
                'password' => Hash::make('admin123'),
                'role' => 1,
                'user_publikid' => null,
                'ppid_pembantuid' => null,
            ]
        );

        DB::table('authorization')->updateOrInsert(
            ['username' => 'adminpembantu'],
            [
                'email' => 'adminpembantu@batukota.go.id',
                'password' => Hash::make('admin123'),
                'role' => 2,
                'user_publikid' => null,
                'ppid_pembantuid' => 1,
            ]
        );
    }
}
