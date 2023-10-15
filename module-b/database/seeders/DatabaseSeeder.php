<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Quota;
use App\Models\Service;
use App\Models\Token;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'username' => 'demo1',
            'password' => 'skills2023d1',
        ]);

        User::create([
            'username' => 'demo2',
            'password' => 'skills2023d2',
        ]);

        Workspace::create([
            'title' => 'My App',
            'user_id' => 1,
        ]);

        Token::create([
            'name' => 'production',
            'token' => Str::random(40),
            'workspace_id' => 1,
        ]);

        Token::create([
            'name' => 'development',
            'token' => Str::random(40),
            'workspace_id' => 1,
        ]);


        Service::create([
            'name' => 'Service #1',
            'cost_per_ms' => 0.001500,
        ]);

        Service::create([
            'name' => 'Service #2',
            'cost_per_ms' => 0.005000,
        ]);

        Quota::create([
            'limit' => '5.00',
        ]);

        Quota::create([
            'limit' => '5.00',
        ]);

        Quota::create([
            'limit' => '10.00',
        ]);

        Quota::create([
            'limit' => '20.00',
        ]);
    }
}
